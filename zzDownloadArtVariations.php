<?php

include __DIR__ . '/zzImageConverter.php';
include_once __DIR__ . '/Assets/AllAltArtVariations.php';

// FAB Cube API endpoint for card data with all printing variations
$jsonUrl = "https://raw.githubusercontent.com/the-fab-cube/flesh-and-blood-cards/refs/heads/develop/json/english/card.json";

$manualArtVariationOverrides = [
  // Format option 1 - Auto-find URL: "cardID" => "artVariation"
  //   "art_of_the_void_red" => "AA",
  // Format option 2 - Manual URL: "cardID" => ["artVariation" => "XX", "imageUrl" => "https://..."]
  //   "another_card_red" => ["artVariation" => "FA", "imageUrl" => "https://example.com/image.jpg"],
  // Format option 3 - Multiple manual entries (e.g. token reprints with new art
  // that FabCube does not flag as art variations). Each entry needs a setID so
  // the filename can be derived from the printing it represents:
  //   "gold" => [
  //     ["artVariation" => "AA", "setID" => "TCC098", "imageUrl" => "https://example.com/gold-tcc.jpg"],
  //     ["artVariation" => "AA", "setID" => "SEA221", "imageUrl" => "https://example.com/gold-sea.jpg"],
  //   ],

/*    "cheaters_charm_yellow" => [
   "artVariation" => "EA",
   "imageUrl" => "https://legendstory-production-s3-public.s3.amazonaws.com/media/cards/normal/LGS424-RF.webp"
  ],  */
];

echo "=== Starting Art Variations Download ===\n";

$curl = curl_init();
$headers = [ "Content-Type: application/json" ];
curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
curl_setopt($curl, CURLOPT_URL, $jsonUrl);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($curl, CURLOPT_TIMEOUT, 60);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
$cardData = curl_exec($curl);
$httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
$curlError = curl_error($curl);
curl_close($curl);

if ($httpCode !== 200) {
  echo "Error fetching data! HTTP Code: " . $httpCode . "\n";
  echo "Curl Error: " . $curlError . "\n";
  echo "Received: " . substr($cardData, 0, 200) . "...\n";
  exit;
}

echo "Successfully fetched data (" . strlen($cardData) . " bytes)\n";

$cardArray = json_decode($cardData);

if (json_last_error() !== JSON_ERROR_NONE) {
  echo "JSON Parse Error: " . json_last_error_msg() . "\n";
  exit;
}

if (!is_array($cardArray) && !is_object($cardArray)) {
  echo "Invalid data structure received\n";
  var_dump($cardArray);
  exit;
}

echo "Decoded " . (is_array($cardArray) ? count($cardArray) : "unknown") . " cards\n";


$altArtsArray = [];
$newDownloads = [];  // "cardID=filename" entries newly downloaded this run
$existingDownloads = [];  // "cardID=filename" entries already on disk
$downloadedCount = 0;
$skippedCount = 0;
$existingCount = 0;
$failedCount = 0;

// A card can have several alt arts across sets/printings (e.g. Runechant and
// Gold tokens); download every distinct printing, deduped by filename.
$processedFilenames = [];
$cardsWithVariants = [];
$overrideConsumed = [];

$cardCount = 0;
$printingCount = 0;
$artVariationCount = 0;
$samplePrinting = null;
$cardWithArtVar = null;

/**
 * Priority ranking: Higher number = fancier
 */
function GetArtVariationPriority($artVar) {
  $priorities = [
    "AA" => 4,
    "AB" => 3,
    "FA" => 2,
    "EA" => 1
  ];
  return $priorities[$artVar] ?? 0;
}

foreach ($manualArtVariationOverrides as $overrideCardID => $override) {
  if (!is_array($override) || !array_is_list($override)) continue;
  foreach ($override as $entry) {
    $setID = $entry['setID'] ?? "";
    $imageUrl = $entry['imageUrl'] ?? "";
    if (strlen($setID) < 4 || empty($imageUrl)) {
      echo "[MANUAL OVERRIDE] Skipping entry for " . $overrideCardID . ": setID and imageUrl are required\n";
      continue;
    }
    $filename = substr($setID, 0, 3) . str_pad(intval(substr($setID, 3)), 3, "0", STR_PAD_LEFT) . "-T";
    if (isset($processedFilenames[$filename])) continue;
    $processedFilenames[$filename] = true;
    $cardsWithVariants[$overrideCardID] = true;

    $filepath = __DIR__ . "/../CardImages/media/missing/cardimages/english/" . $filename . ".webp";
    echo "[MANUAL OVERRIDE] " . $overrideCardID . " (" . ($entry['artVariation'] ?? "AA") . ") from manual URL\n";
    $downloadResult = DownloadArtVariationImage($filepath, $imageUrl, $filename, true, BuildFallbackImageUrls($setID));
    if ($downloadResult === false) {
      $failedCount++;
      echo "Failed: " . $filename . " - Could not download from URL\n";
    } else if ($downloadResult === true) {
      $downloadedCount++;
      $newDownloads[] = $overrideCardID . "=" . $filename;
      echo "Downloaded: " . $filename . " (" . ($entry['artVariation'] ?? "AA") . ")\n";
    } else if ($downloadResult === "exists") {
      $existingCount++;
      $existingDownloads[] = $overrideCardID . "=" . $filename;
    }
  }
}

foreach ($cardArray as $card) {
  $cardName = $card->name ?? "";
  $cardID = GetCardIdentifier($cardName, $card->pitch ?? 0);
  $cardCount++;
  
  if (empty($card->printings)) {
    continue;
  }

  // Process each printing of the card
  foreach ($card->printings as $printing) {
    $printingCount++;
    
    // Save sample printing for debugging
    if ($samplePrinting === null) {
      $samplePrinting = $printing;
    }
    
    // Check for art variations: use the art_variations array
    $artVariations = $printing->art_variations ?? [];
    $manualImageUrl = null;  // For manual URL overrides
    
    // Check if this card has a manual override
    $isManualOverride = false;
    if (
      isset($manualArtVariationOverrides[$cardID]) &&
      !isset($overrideConsumed[$cardID]) &&
      !(is_array($manualArtVariationOverrides[$cardID]) && array_is_list($manualArtVariationOverrides[$cardID]))
    ) {
      $overrideConsumed[$cardID] = true;
      $isManualOverride = true;
      $override = $manualArtVariationOverrides[$cardID];
      if (is_array($override)) {
        // Format 2: Manual URL provided
        $artVariations = [$override['artVariation']];
        $manualImageUrl = $override['imageUrl'];
        echo "[MANUAL OVERRIDE] Using override for " . $cardID . " with art variation: " . $override['artVariation'] . " and manual URL\n";
      } else {
        // Format 1: Auto-find URL
        $artVariations = [$override];
        echo "[MANUAL OVERRIDE] Using override for " . $cardID . " with art variation: " . $override . "\n";
      }
    }
    
    if (!empty($artVariations) && is_array($artVariations)) {
      if ($cardWithArtVar === null) {
        $cardWithArtVar = (object)['name' => $cardName, 'printing' => $printing];
      }
      
      $artVariationCount++;
    }
    
    // Filter for EA, FA, AA, AB
    if (empty($artVariations) || !is_array($artVariations)) {
      continue;
    }
    
    // Check if any of the art variations match EA, FA, AA, AB
    $hasTargetVariation = false;
    $bestVariationForPrinting = null;
    $bestPriority = 0;
    
    foreach ($artVariations as $av) {
      if (in_array($av, ["EA", "FA", "AA", "AB"])) {
        $hasTargetVariation = true;
        $priority = GetArtVariationPriority($av);
        if ($priority > $bestPriority) {
          $bestPriority = $priority;
          $bestVariationForPrinting = $av;
        }
      }
    }
    
    if (!$hasTargetVariation) {
      continue;
    }

    // Skip golden foil cards (foiling: "G")
    $foiling = $printing->foiling ?? null;
    if ($foiling === "G") {
      $skippedCount++;
      continue;
    }

    // Get set and card number
    $setID = $printing->id ?? "";
    if (empty($setID)) {
      continue;
    }

    $set = substr($setID, 0, 3);
    $number = intval(substr($setID, 3));

    // Check if double-sided card back (is_front: false adds +400)
    $isBack = false;
    if (isset($printing->double_sided_card_info) && is_array($printing->double_sided_card_info)) {
      foreach ($printing->double_sided_card_info as $dfc_info) {
        // Only add 400 if this is the back face (is_front: false)
        if (isset($dfc_info->is_front) && $dfc_info->is_front === false) {
          $isBack = true;
          break;
        }
      }
    }

    if ($isBack) {
      $number += 400;
    }

    // Get image URL - use manual override if provided, otherwise use API URL.
    // FabCube frequently ships no image_url for LSS promo/foil printings even
    // though the image exists on the S3 bucket under a foiling suffix. In that
    // case leave $imageUrl empty and rely entirely on the derived fallback URLs.
    $imageUrl = $manualImageUrl ?? ($printing->image_url ?? "");

    // Build filename: SET + number + "-T"
    $filename = $set . str_pad($number, 3, "0", STR_PAD_LEFT) . "-T";
    $filepath = __DIR__ . "/../CardImages/media/missing/cardimages/english/" . $filename . ".webp";

    if (isset($processedFilenames[$filename])) {
      $skippedCount++;
      continue;
    }
    $processedFilenames[$filename] = true;
    $cardsWithVariants[$cardID] = true;

    $downloadResult = DownloadArtVariationImage($filepath, $imageUrl, $filename, $isManualOverride, BuildFallbackImageUrls($setID));
    if ($downloadResult === false) {
      $failedCount++;
      echo "Failed: " . $filename . " - Could not download from URL\n";
      continue;
    } else if ($downloadResult === true) {
      // Newly downloaded
      $downloadedCount++;
      // Track this as a new download for adding to altArts
      $newDownloads[] = $cardID . "=" . $filename;
      echo "Downloaded: " . $filename . " (" . $bestVariationForPrinting . ")\n";
    } else if ($downloadResult === "exists") {
      $existingCount++;
      $existingDownloads[] = $cardID . "=" . $filename;
    }
  }
}

// Generate PatreonDictionary.php compatible entries for every downloaded variant
echo "\n=== Summary ===\n";
echo "Total cards processed: " . $cardCount . "\n";
echo "Total printings processed: " . $printingCount . "\n";
echo "Total art variations found: " . $artVariationCount . "\n";
echo "Total downloaded: " . $downloadedCount . "\n";
echo "Total already existed: " . $existingCount . "\n";
echo "Total skipped: " . $skippedCount . "\n";
echo "Total failed: " . $failedCount . "\n";
echo "Total unique cards with art variations: " . count($cardsWithVariants) . "\n";
echo "\n";

// Build final altArts array with newly downloaded images
$altArtsArray = $newDownloads;

// Which sets to highlight as "new" in the missing report
$newSets = ["FAB"];

if (count($altArtsArray) > 0) {
  echo "=== New entries to add to \$altArts ===\n";
  foreach ($altArtsArray as $entry) {
    echo "\"" . $entry . "\",\n";
  }
  echo "\n";
} else {
  echo "No new art variations to add.\n";
}

// Check for previously downloaded images that are missing from the $altArts array
$altArtsLookup = array_flip(GetAllAltArtVariations());

$missingFromAltArts = [];
foreach ($existingDownloads as $entry) {
  if (!isset($altArtsLookup[$entry]) && !in_array($entry, $newDownloads)) {
    list($cardID, $filename) = explode("=", $entry, 2);
    $missingFromAltArts[$cardID . "=" . $filename] = $filename;
  }
}

if (count($missingFromAltArts) > 0) {
  // Group missing entries by set
  $missingBySet = [];
  foreach ($missingFromAltArts as $cardID => $filename) {
    $set = substr($filename, 0, 3);
    $missingBySet[$set][$cardID] = $filename;
  }
  ksort($missingBySet);

  // Show new sets first (the ones you care about)
  $newSetMissing = [];
  $otherSetMissing = [];
  foreach ($missingBySet as $set => $entries) {
    if (in_array($set, $newSets)) {
      $newSetMissing[$set] = $entries;
    } else {
      $otherSetMissing[$set] = $entries;
    }
  }

  $newSetCount = 0;
  foreach ($newSetMissing as $entries) $newSetCount += count($entries);

  if ($newSetCount > 0) {
    echo "=== NEW SET ENTRIES missing from \$altArts (" . $newSetCount . " from " . implode(", ", array_keys($newSetMissing)) . ") ===\n";
    foreach ($newSetMissing as $set => $entries) {
      echo "// --- $set (" . count($entries) . " cards) ---\n";
      foreach ($entries as $entry => $altArtID) {
        echo "\"" . $entry . "\",\n";
      }
      echo "\n";
    }
  }

  $otherCount = 0;
  foreach ($otherSetMissing as $entries) $otherCount += count($entries);

  if ($otherCount > 0) {
    echo "(" . $otherCount . " other entries from older sets omitted: " . implode(", ", array_keys($otherSetMissing)) . ")\n";
  }
}

/**
 * Regenerate concat and crop images from an existing main image
 */
function RegenerateImageVariations($filepath, $filename)
{
  $cardSquaresMissingFolder = __DIR__ . "/../CardImages/media/missing/cardsquares/english/" . $filename . ".webp";
  $cardCropsMissingFolder = __DIR__ . "/../CardImages/media/missing/crops/" . $filename . "_cropped.webp";
  
  // Load the main image
  $image = null;
  if (function_exists('imagecreatefromwebp')) {
    $image = @imagecreatefromwebp($filepath);
  }
  
  if ($image === false) {
    return false;
  }
  
  try {
    // Create and save concat image
    $imageTop = @imagecrop($image, ['x' => 0, 'y' => 0, 'width' => 450, 'height' => 372]);
    $imageBottom = @imagecrop($image, ['x' => 0, 'y' => 550, 'width' => 450, 'height' => 78]);
    
    if ($imageTop !== false && $imageBottom !== false) {
      $dest = @imagecreatetruecolor(450, 450);
      if ($dest !== false) {
        @imagecopy($dest, $imageTop, 0, 0, 0, 0, 450, 372);
        @imagecopy($dest, $imageBottom, 0, 373, 0, 0, 450, 78);
        
        CreateDirIfNotExists(dirname($cardSquaresMissingFolder));
        @imagewebp($dest, $cardSquaresMissingFolder, 90);
        
        @imagedestroy($dest);
      }
    }
    
    if ($imageTop !== false) @imagedestroy($imageTop);
    if ($imageBottom !== false) @imagedestroy($imageBottom);
    
    // Create and save crop image
    $cropImage = @imagecrop($image, ['x' => 50, 'y' => 100, 'width' => 350, 'height' => 270]);
    if ($cropImage !== false) {
      CreateDirIfNotExists(dirname($cardCropsMissingFolder));
      @imagepng($cropImage, $cardCropsMissingFolder);
      
      @imagedestroy($cropImage);
    }
    
    @imagedestroy($image);
    return true;
  } catch (Exception $e) {
    @imagedestroy($image);
    return false;
  }
}

function BuildFallbackImageUrls($setID)
{
  $urls = [];
  $sizes = ["large", "normal", "small"];
  $suffixes = ["", "-RF", "-CF", "-MV", "-EA", "-FA"];
  foreach ($sizes as $size) {
    foreach ($suffixes as $suffix) {
      $urls[] = "https://legendstory-production-s3-public.s3.amazonaws.com/media/cards/" . $size . "/" . $setID . $suffix . ".webp";
    }
  }
  return $urls;
}

function FetchImageData($url, $filename, $quiet = false)
{
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_TIMEOUT, 30);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36');
  curl_setopt($ch, CURLOPT_HTTPHEADER, ['Accept: image/*,*/*']);

  $imageData = curl_exec($ch);
  $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
  $curlError = curl_error($ch);
  curl_close($ch);

  if ($httpCode !== 200 || empty($imageData)) {
    // Suppress per-URL noise while probing fallbacks; the caller reports once
    // if every candidate URL failed.
    if (!$quiet) {
      if (!empty($curlError)) {
        echo "Download error (" . $httpCode . "): " . $curlError . " - " . $filename . " [" . $url . "]<BR>";
      } else {
        echo "Download failed (HTTP " . $httpCode . "): " . $filename . " [" . $url . "]<BR>";
      }
    }
    return null;
  }

  if (strlen($imageData) < 5000) {
    if (!$quiet) {
      echo "Response too small (" . strlen($imageData) . " bytes): " . $filename . " [" . $url . "]<BR>";
    }
    return null;
  }

  return $imageData;
}

function DownloadArtVariationImage($filepath, $imageUrl, $filename, $isManualOverride = false, $fallbackUrls = [])
{
  // Only use CardImages paths, not backend folders
  $cardImagesUploadedFolder = __DIR__ . "/../CardImages/media/uploaded/public/cardimages/english/" . $filename . ".webp";
  $cardImagesMissingFolder = __DIR__ . "/../CardImages/media/missing/cardimages/english/" . $filename . ".webp";
  $cardSquaresUploadedFolder = __DIR__ . "/../CardImages/media/uploaded/public/cardsquares/english/" . $filename . ".webp";
  $cardSquaresMissingFolder = __DIR__ . "/../CardImages/media/missing/cardsquares/english/" . $filename . ".webp";
  $cardCropsUploadedFolder = __DIR__ . "/../CardImages/media/uploaded/public/crops/" . $filename . "_cropped.webp";
  $cardCropsMissingFolder = __DIR__ . "/../CardImages/media/missing/crops/" . $filename . "_cropped.webp";
  
  // Check if file already exists in CardImages location
  if (file_exists($filepath) || file_exists($cardImagesUploadedFolder)) {
    // If manual override, still regenerate concat/crops
    if ($isManualOverride) {
      echo "Regenerating concat/crops for manual override: " . $filename . "<BR>";
      $sourceFile = file_exists($cardImagesUploadedFolder) ? $cardImagesUploadedFolder : $filepath;
      RegenerateImageVariations($sourceFile, $filename);
    }
    return "exists";  // File already exists, return "exists" instead of true
  }

  // Create temp file for download
  $tempFile = $filepath . ".tmp";

  // Try the primary URL first, then any fallback URL variations. De-dupe and
  // drop empties so we never probe the same URL twice or curl an empty string
  // (the primary is empty when FabCube shipped no image_url for the printing).
  $urlsToTry = array_values(array_filter(array_unique(array_merge([$imageUrl], $fallbackUrls))));
  $imageData = null;
  $primaryUrl = $imageUrl;
  foreach ($urlsToTry as $url) {
    // Probe every candidate quietly; report only once below if all fail.
    $imageData = FetchImageData($url, $filename, true);
    if ($imageData !== null) {
      if ($url !== $primaryUrl) {
        echo "Fallback URL worked for " . $filename . ": " . $url . "<BR>";
      }
      break;
    }
  }

  if ($imageData === null) {
    echo "Download failed (all " . count($urlsToTry) . " URL variations tried): " . $filename . "<BR>";
    return false;
  }

  // Write to temp file first
  $handler = fopen($tempFile, "w");
  if ($handler === false) {
    echo "Failed to create file: " . $tempFile . "<BR>";
    return false;
  }

  fwrite($handler, $imageData);
  fclose($handler);

  // Try to process image and convert to webp
  $success = false;
  $processedImage = null;
  
  try {
    // Detect image type and load accordingly
    $image = null;
    
    // Try PNG first
    if (function_exists('imagecreatefrompng')) {
      $image = @imagecreatefrompng($tempFile);
    }
    
    // Try JPEG
    if ($image === false && function_exists('imagecreatefromjpeg')) {
      $image = @imagecreatefromjpeg($tempFile);
    }
    
    // Try webp
    if ($image === false && function_exists('imagecreatefromwebp')) {
      $image = @imagecreatefromwebp($tempFile);
    }
    
    if ($image !== false) {
      try {
        // Scale to standard size (450x628) - use error suppression
        $scaled = @imagescale($image, 450, 628);
        if ($scaled !== false) {
          $processedImage = $scaled;
          $success = true;
        } else {
          // If scaling fails, use original
          $processedImage = $image;
          $success = true;
        }
      } catch (Exception $e) {
        // If scaling fails, use original
        $processedImage = $image;
        $success = true;
      }
    }
  } catch (Exception $e) {
    // Silently continue to fallback
  }

  // Save processed image to all locations
  if ($success && $processedImage !== false) {
    try {
      // Create directories if they don't exist (CardImages only)
      CreateDirIfNotExists(dirname($filepath));
      CreateDirIfNotExists(dirname($cardImagesUploadedFolder));
      CreateDirIfNotExists(dirname($cardImagesMissingFolder));
      CreateDirIfNotExists(dirname($cardSquaresUploadedFolder));
      CreateDirIfNotExists(dirname($cardSquaresMissingFolder));
      CreateDirIfNotExists(dirname($cardCropsUploadedFolder));
      CreateDirIfNotExists(dirname($cardCropsMissingFolder));
      
      // Save main card image to CardImages missing folder
      $webpResult = @imagewebp($processedImage, $filepath, 90);
      if (!$webpResult) {
        error_log("Warning: Failed to save webp for $filename to $filepath");
      }
      
      // Create and save concat image (like zzImageConverter.php)
      // Always regenerate for new downloads to ensure it exists
      try {
        $imageTop = @imagecrop($processedImage, ['x' => 0, 'y' => 0, 'width' => 450, 'height' => 372]);
        $imageBottom = @imagecrop($processedImage, ['x' => 0, 'y' => 550, 'width' => 450, 'height' => 78]);
        
        if ($imageTop !== false && $imageBottom !== false) {
          $dest = @imagecreatetruecolor(450, 450);
          if ($dest !== false) {
            @imagecopy($dest, $imageTop, 0, 0, 0, 0, 450, 372);
            @imagecopy($dest, $imageBottom, 0, 373, 0, 0, 450, 78);
            
            @imagewebp($dest, $cardSquaresMissingFolder, 90);
            
            @imagedestroy($dest);
          }
        } else {
          error_log("Failed to crop for concat: " . $filename);
        }
        
        if ($imageTop !== false) @imagedestroy($imageTop);
        if ($imageBottom !== false) @imagedestroy($imageBottom);
      } catch (Exception $e) {
        error_log("Exception creating concat for $filename: " . $e->getMessage());
      }
      
      // Create and save crop image (like zzImageConverter.php)
      // Always regenerate for new downloads to ensure it exists
      try {
        $cropImage = @imagecrop($processedImage, ['x' => 50, 'y' => 100, 'width' => 350, 'height' => 270]);
        if ($cropImage !== false) {
          @imagepng($cropImage, $cardCropsMissingFolder);
          @imagedestroy($cropImage);
        } else {
          error_log("Failed to create crop image for: " . $filename);
        }
      } catch (Exception $e) {
        error_log("Exception creating crop for $filename: " . $e->getMessage());
      }
      
      @imagedestroy($processedImage);
      @unlink($tempFile);
      
      return true;
    } catch (Exception $e) {
      @imagedestroy($processedImage);
      @unlink($tempFile);
      return false;
    }
  }

  if (file_exists($tempFile)) {
    try {
      CreateDirIfNotExists(dirname($filepath));
      rename($tempFile, $filepath);
      return true;
    } catch (Exception $e) {
      @unlink($tempFile);
      return false;
    }
  }

  return false;
}

function CreateDirIfNotExists($path)
{
  if (!is_dir($path)) {
    @mkdir($path, 0777, true);
  }
}

function GetCardIdentifier($name, $pitch, $delimiter = "_")
{
  if ($name == "Goldfin Harpoon") return "goldfin_harpoon_yellow";
  
  $cardID = strtolower($name);
  $cardID = str_replace("//", $delimiter, $cardID);
  $cardID = str_replace(["ā", "ä", "ö", "ü", "ß", "ṣ"], ["a", "a", "o", "u", "s", "s"], $cardID);
  
  if (function_exists('iconv')) {
    $cardID = iconv('UTF-8', 'US-ASCII//TRANSLIT', $cardID);
  }
  
  $cardID = str_replace(" ", $delimiter, $cardID);
  $cardID = str_replace("-", $delimiter, $cardID);
  $cardID = preg_replace("/[^a-z0-9 $delimiter]/", "", $cardID);
  $cardID = preg_replace("/$delimiter$delimiter/", $delimiter, $cardID);
  
  $suffix = match($pitch) {
    1, "1" => "_red",
    2, "2" => "_yellow",
    3, "3" => "_blue",
    default => ""
  };
  
  return $cardID . $suffix;
}

echo "<BR><h2>Process Complete!</h2>";