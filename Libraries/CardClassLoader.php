<?php

// Card classes load on first use. The class map is parsed from this server's card files; with validate_timestamps=0 it is
// pinned to the opcache generation after compiling every card file. If it is unusable or wrong, every card file is included.
// RegisterCardObjectFiles must run before Classes/Card.php: compiling a class whose parent exists also declares it.
// opcache_compile_file on an already-cached script marks it included without running it, so cached files are skipped.

function RegisterCardObjectFiles(array $files): void
{
  CardObjectFiles($files);
  static $registered = false;
  if ($registered) return;
  $registered = true;
  spl_autoload_register("CardObjectAutoload");
  CardClassMap();
}

function LoadCardObjectsUnlessMapped(): void
{
  if (CardClassMap() === false) LoadAllCardObjects();
}

function CardObjectFiles(?array $files = null): array
{
  static $list = [];
  if ($files !== null) $list = $files;
  return $list;
}

function CardObjectsDirectory(): string
{
  return __DIR__ . "/../Classes/CardObjects/";
}

function CardClassMapDirectory(): string
{
  return __DIR__ . "/../GeneratedCode/CardClassMaps/";
}

function MarkedCardObjectFiles(?array $files = null): array
{
  static $marked = [];
  if ($files !== null) $marked = $files;
  return $marked;
}

function LoadAllCardObjects(): void
{
  static $loaded = false;
  if ($loaded) return;
  $directory = CardObjectsDirectory();
  $marked = MarkedCardObjectFiles();
  foreach (CardObjectFiles() as $file) {
    if (isset($marked[$file])) include $directory . $file;
    else include_once $directory . $file;
  }
  $loaded = true;
}

function CardObjectAutoload(string $className): void
{
  $map = CardClassMap();
  if ($map === false) {
    LoadAllCardObjects();
    return;
  }
  $file = $map[strtolower($className)] ?? null;
  if ($file === null) return;
  include_once CardObjectsDirectory() . $file;
  if (!class_exists($className, false)) LoadAllCardObjects();
}

function CardClassMapApcuAvailable(): bool
{
  static $available = null;
  return $available ??= function_exists("apcu_fetch") && filter_var(ini_get(PHP_SAPI === "cli" ? "apc.enable_cli" : "apc.enabled"), FILTER_VALIDATE_BOOLEAN);
}

function OpcacheGeneration(): ?string
{
  if (!function_exists("opcache_get_status")) return null;
  $status = @opcache_get_status(false);
  if (!is_array($status) || empty($status["opcache_enabled"])) return null;
  return ($status["opcache_statistics"]["start_time"] ?? 0) . ":" . ($status["opcache_statistics"]["last_restart_time"] ?? 0);
}

function CardObjectDiskFingerprint(): ?string
{
  clearstatcache();
  $now = time();
  $directory = CardObjectsDirectory();
  $parts = [];
  foreach (CardObjectFiles() as $file) {
    $stat = @stat($directory . $file);
    if ($stat === false || $stat["mtime"] > $now - 3) return null;
    $parts[] = $file . ":" . $stat["size"] . ":" . $stat["mtime"] . ":" . $stat["ino"];
  }
  return md5(implode(";", $parts));
}

function CardClassMap()
{
  static $map = null;
  if ($map !== null) return $map;
  $map = false;
  try {
    $generation = OpcacheGeneration();
    $pinned = $generation !== null && !filter_var(ini_get("opcache.validate_timestamps"), FILTER_VALIDATE_BOOLEAN);
    if ($pinned && !CardClassMapApcuAvailable()) return $map;
    $generationKey = "card_class_generation_" . md5(__DIR__);
    if ($pinned) {
      $pin = @apcu_fetch($generationKey);
      if (is_array($pin) && $pin[0] === $generation) {
        $data = CardClassMapData($pin[1]);
        if ($data !== null && empty($data["eager"])) $map = $data["classes"];
        return $map;
      }
    }
    $fingerprint = CardObjectDiskFingerprint();
    if ($fingerprint === null) return $map;
    $data = CardClassMapData($fingerprint);
    if ($data === null) return $map;
    if ($pinned) {
      $marked = empty($data["eager"]) ? CompileCardObjects($data) : [];
      if ($marked === null || CardObjectDiskFingerprint() !== $fingerprint) return $map;
      @apcu_store($generationKey, [$generation, $fingerprint]);
      if ($marked !== []) return $map;
    }
    if (empty($data["eager"])) $map = $data["classes"];
  } catch (Throwable $e) {
    $map = false;
  }
  return $map;
}

function CompileCardObjects(array $data): ?array
{
  foreach ($data["parents"] as $parent) if (class_exists($parent, false)) return null;
  foreach ($data["classes"] as $name => $file) if (class_exists($name, false)) return null;
  $directory = realpath(CardObjectsDirectory());
  if ($directory === false) return null;
  $included = array_flip(get_included_files());
  $paths = [];
  foreach (CardObjectFiles() as $file) {
    $path = $directory . DIRECTORY_SEPARATOR . $file;
    $paths[$path] = $file;
    if (isset($included[$path]) || opcache_is_script_cached($path)) continue;
    if (!@opcache_compile_file($path)) return null;
  }
  $marked = [];
  foreach (get_included_files() as $path) {
    if (!isset($included[$path]) && isset($paths[$path])) $marked[$paths[$path]] = true;
  }
  MarkedCardObjectFiles($marked);
  return $marked;
}

function CardClassMapData(string $fingerprint): ?array
{
  $path = CardClassMapDirectory() . "map_" . $fingerprint . ".php";
  $apcuKey = "card_class_map_" . md5(__DIR__) . "_" . $fingerprint;
  $data = @include $path;
  if (!CardClassMapValid($data) && CardClassMapApcuAvailable()) $data = @apcu_fetch($apcuKey);
  if (!CardClassMapValid($data)) $data = BuildAndStoreCardClassMap($path, $apcuKey, $fingerprint);
  return CardClassMapValid($data) ? $data : null;
}

function CardClassMapValid($data): bool
{
  return is_array($data) && (!empty($data["eager"]) || (is_array($data["classes"] ?? null) && is_array($data["parents"] ?? null)));
}

function BuildAndStoreCardClassMap(string $path, string $apcuKey, string $fingerprint): ?array
{
  $directory = dirname($path);
  if (!is_dir($directory)) @mkdir($directory, 0775, true);
  $lock = is_dir($directory) ? @fopen($directory . "/build.lock", "c") : false;
  if ($lock === false && !CardClassMapApcuAvailable()) return null;
  if ($lock !== false && !flock($lock, LOCK_EX | LOCK_NB)) {
    fclose($lock);
    return null;
  }
  try {
    $data = BuildCardClassMapFromSource() ?? ["eager" => true];
    if (CardObjectDiskFingerprint() !== $fingerprint) return null;
    $written = false;
    if ($lock !== false) {
      $temp = $path . "." . getmypid() . "." . uniqid("", true) . ".tmp";
      if (@file_put_contents($temp, "<?php\nreturn " . var_export($data, true) . ";\n") !== false) {
        $written = @rename($temp, $path);
        if (!$written) @unlink($temp);
      }
    }
    if ($written) {
      foreach (glob($directory . "/map_*.php") ?: [] as $old) {
        if ($old !== $path && @filemtime($old) < time() - 3600) @unlink($old);
      }
    } elseif (CardClassMapApcuAvailable()) {
      @apcu_store($apcuKey, $data);
    }
    return $data;
  } finally {
    if ($lock !== false) {
      flock($lock, LOCK_UN);
      fclose($lock);
    }
  }
}

function BuildCardClassMapFromSource(): ?array
{
  $directory = CardObjectsDirectory();
  $files = CardObjectFiles();
  $roots = array_flip($files);
  $classes = [];
  $parents = [];
  foreach ($files as $file) {
    $source = @file_get_contents($directory . $file);
    if ($source === false) return null;
    $declared = CardObjectFileDeclarations($source, $roots);
    if ($declared === null) return null;
    foreach ($declared as [$name, $parent]) {
      $key = strtolower($name);
      if (isset($classes[$key])) return null;
      $classes[$key] = $file;
      if ($parent !== null) $parents[strtolower($parent)] = true;
    }
  }
  return ["eager" => false, "classes" => $classes, "parents" => array_keys(array_diff_key($parents, $classes))];
}

function CardObjectFileDeclarations(string $source, array $roots): ?array
{
  try {
    $tokens = token_get_all($source, TOKEN_PARSE);
  } catch (Throwable $e) {
    return null;
  }
  static $ignored = [T_OPEN_TAG => true, T_CLOSE_TAG => true, T_WHITESPACE => true, T_COMMENT => true, T_DOC_COMMENT => true];
  $declarations = [];
  $count = count($tokens);
  $next = static function (int $i) use ($tokens, $count, $ignored): int {
    for (++$i; $i < $count && is_array($tokens[$i]) && isset($ignored[$tokens[$i][0]]); ++$i);
    return $i;
  };
  for ($i = 0; $i < $count; ++$i) {
    $token = $tokens[$i];
    $id = is_array($token) ? $token[0] : null;
    if (isset($ignored[$id])) continue;
    if ($id === T_INLINE_HTML) {
      if ($token[1] !== "") return null;
      continue;
    }
    if ($id === T_INCLUDE_ONCE || $id === T_REQUIRE_ONCE) {
      $statement = "";
      for (++$i; $i < $count && $tokens[$i] !== ";"; ++$i) {
        if (is_array($tokens[$i]) && isset($ignored[$tokens[$i][0]])) continue;
        $statement .= is_array($tokens[$i]) ? $tokens[$i][1] : $tokens[$i];
      }
      if (!preg_match('/^\(?__DIR__\.(["\'])\/([A-Za-z0-9_]+\.php)\1\)?$/', $statement, $match) || !isset($roots[$match[2]])) return null;
      continue;
    }
    if ($id !== T_CLASS) return null;
    $i = $next($i);
    if ($i >= $count || !is_array($tokens[$i]) || $tokens[$i][0] !== T_STRING) return null;
    $name = $tokens[$i][1];
    $parent = null;
    $i = $next($i);
    if ($i < $count && is_array($tokens[$i]) && $tokens[$i][0] === T_EXTENDS) {
      $i = $next($i);
      if ($i >= $count || !is_array($tokens[$i]) || $tokens[$i][0] !== T_STRING) return null;
      $parent = $tokens[$i][1];
      $i = $next($i);
    }
    if ($i >= $count || $tokens[$i] !== "{") return null;
    $depth = 0;
    for (; $i < $count; ++$i) {
      $text = is_array($tokens[$i]) ? $tokens[$i][1] : $tokens[$i];
      $innerID = is_array($tokens[$i]) ? $tokens[$i][0] : null;
      if ($text === "{" || $innerID === T_CURLY_OPEN || $innerID === T_DOLLAR_OPEN_CURLY_BRACES) ++$depth;
      elseif ($text === "}" && --$depth === 0) break;
    }
    if ($depth !== 0) return null;
    $declarations[] = [$name, $parent];
  }
  return $declarations;
}
