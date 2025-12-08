<?php

include __DIR__ . '/zzImageConverter.php';

// FAB Cube API endpoint for card data with all printing variations
$jsonUrl = "https://raw.githubusercontent.com/the-fab-cube/flesh-and-blood-cards/refs/heads/compendium-of-rathe/json/english/card.json";

echo "<h2>Starting Art Variations Download</h2>";
echo "Fetching card data from FAB Cube...<BR>";

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
  echo "Error fetching data! HTTP Code: " . $httpCode . "<BR>";
  echo "Curl Error: " . $curlError . "<BR>";
  echo "Received: " . substr($cardData, 0, 200) . "...<BR>";
  exit;
}

echo "Successfully fetched data (" . strlen($cardData) . " bytes)<BR>";

$cardArray = json_decode($cardData);

if (json_last_error() !== JSON_ERROR_NONE) {
  echo "JSON Parse Error: " . json_last_error_msg() . "<BR>";
  exit;
}

if (!is_array($cardArray) && !is_object($cardArray)) {
  echo "Invalid data structure received<BR>";
  var_dump($cardArray);
  exit;
}

echo "Decoded " . (is_array($cardArray) ? count($cardArray) : "unknown") . " cards<BR>";

if (!is_dir(__DIR__ . "/WebpImages/en/altarts")) {
  mkdir(__DIR__ . "/WebpImages/en/altarts", 0777, true);
}

$altArtsArray = [];
$downloadedCount = 0;
$skippedCount = 0;
$failedCount = 0;

// Track best art variation per card (AA > FA > EA)
$cardBestVariant = [];

echo "Processing cards...<BR>";

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
    "AA" => 3,
    "FA" => 2,
    "EA" => 1
  ];
  return $priorities[$artVar] ?? 0;
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
    
    // Debug: look for any art variations
    if (!empty($artVariations) && is_array($artVariations)) {
      if ($cardWithArtVar === null) {
        $cardWithArtVar = (object)['name' => $cardName, 'printing' => $printing];
      }
      
      $artVariationCount++;
      echo "[DEBUG] Found art variations for: " . $cardName . " - " . json_encode($artVariations) . "<BR>";
    }
    
    // Filter for EA, FA, AA
    if (empty($artVariations) || !is_array($artVariations)) {
      continue;
    }
    
    // Check if any of the art variations match EA, FA, AA
    $hasTargetVariation = false;
    $bestVariationForPrinting = null;
    $bestPriority = 0;
    
    foreach ($artVariations as $av) {
      if (in_array($av, ["EA", "FA", "AA"])) {
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

    // Get image URL
    $imageUrl = $printing->image_url ?? null;
    if (empty($imageUrl)) {
      $skippedCount++;
      echo "Skipped: " . $setID . " - No image URL<BR>";
      continue;
    }

    // Build filename: SET + number + "-T"
    $filename = $set . str_pad($number, 3, "0", STR_PAD_LEFT) . "-T";
    $filepath = __DIR__ . "/WebpImages/en/altarts/" . $filename . ".webp";

    // Track if this is the best variant for this card (only download the fanciest)
    if (!isset($cardBestVariant[$cardID]) || $bestPriority > $cardBestVariant[$cardID]['priority']) {
      // Remove old download entry if we're replacing with a fancier variant
      if (isset($cardBestVariant[$cardID])) {
        echo "Upgrading " . $cardID . " from " . $cardBestVariant[$cardID]['variant'] . " to " . $bestVariationForPrinting . "<BR>";
      }
      
      $cardBestVariant[$cardID] = [
        'priority' => $bestPriority,
        'variant' => $bestVariationForPrinting,
        'filename' => $filename,
        'filepath' => $filepath,
        'imageUrl' => $imageUrl
      ];
    } else {
      // Skip this variant, we already have a better one for this card
      echo "Skipped: " . $filename . " (" . $bestVariationForPrinting . ") - Already have " . $cardBestVariant[$cardID]['variant'] . "<BR>";
      $skippedCount++;
      continue;
    }

    // Download only the best variant
    if (!DownloadArtVariationImage($filepath, $imageUrl, $filename)) {
      $failedCount++;
      echo "Failed: " . $filename . " - Could not download from URL<BR>";
      continue;
    }

    $downloadedCount++;
    echo "Downloaded: " . $filename . " (" . $bestVariationForPrinting . ")<BR>";
  }
}

// Generate PatreonDictionary.php compatible array using only BEST variants
echo "<h2>Generating altArts Array (Using Best Variants Only)</h2>";
echo "Total cards processed: " . $cardCount . "<BR>";
echo "Total printings processed: " . $printingCount . "<BR>";
echo "Total art variations found: " . $artVariationCount . "<BR>";
echo "Total downloaded: " . $downloadedCount . "<BR>";
echo "Total skipped: " . $skippedCount . "<BR>";
echo "Total failed: " . $failedCount . "<BR>";
echo "Total unique cards with art variations: " . count($cardBestVariant) . "<BR>";
echo "<BR>";

// Build final altArts array with only the best variant per card
foreach ($cardBestVariant as $cardID => $variantInfo) {
  $altArtsArray[$cardID] = $variantInfo['filename'];
}

// Debug: Show sample printing structure
if ($samplePrinting !== null) {
  echo "<h3>Sample Printing Structure:</h3>";
  echo "<pre>";
  echo htmlspecialchars(json_encode($samplePrinting, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
  echo "</pre>";
}

// Debug: Show sample card with art variations
if ($cardWithArtVar !== null) {
  echo "<h3>Sample Card With Art Variations:</h3>";
  echo "Card: " . $cardWithArtVar->name . "<BR>";
  echo "<pre>";
  echo htmlspecialchars(json_encode($cardWithArtVar->printing, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
  echo "</pre>";
}

if (count($altArtsArray) > 0) {
  echo "<h3>Add this to PatreonDictionary.php:</h3>";
  echo "<pre>";
  echo "\$altArts = [<BR>";
  
  foreach ($altArtsArray as $cardID => $altArtID) {
    echo "  \"" . htmlspecialchars($cardID) . "\" => \"" . htmlspecialchars($altArtID) . "\",<BR>";
  }
  
  echo "];<BR>";
  echo "</pre>";
  
  // Also save to file
  $outputFile = __DIR__ . "/GeneratedCode/AltArtsArray.php";
  if (!is_dir(__DIR__ . "/GeneratedCode")) {
    mkdir(__DIR__ . "/GeneratedCode", 0777, true);
  }
  
  $fileContent = "<?php\n\n";
  $fileContent .= "// Auto-generated altArts array\n";
  $fileContent .= "// Maps card IDs to alternate art variant IDs\n\n";
  $fileContent .= "\$altArts = [\n";
  
  foreach ($altArtsArray as $cardID => $altArtID) {
    $fileContent .= "  \"" . $cardID . "\" => \"" . $altArtID . "\",\n";
  }
  
  $fileContent .= "];\n\n";
  $fileContent .= "?>";
  
  file_put_contents($outputFile, $fileContent);
  echo "<BR><strong>Array also saved to: /GeneratedCode/AltArtsArray.php</strong><BR>";
  
  // Automatically update PatreonDictionary.php
  echo "<BR><h3>Updating PatreonDictionary.php...</h3>";
  UpdatePatreonDictionary($altArtsArray);
} else {
  echo "No art variations found.<BR>";
}

/**
 * Automatically update PatreonDictionary.php with new altArts
 */
function UpdatePatreonDictionary($altArtsArray)
{
  $patDictFile = __DIR__ . "/Assets/patreon-php-master/src/PatreonDictionary.php";
  
  if (!file_exists($patDictFile)) {
    echo "Error: PatreonDictionary.php not found at " . $patDictFile . "<BR>";
    return;
  }
  
  // Read the file
  $content = file_get_contents($patDictFile);
  
  // Build new altArts entries in the same format as existing ones
  $newEntries = [];
  foreach ($altArtsArray as $cardID => $filename) {
    $newEntries[] = "          \"" . $cardID . "=" . $filename . "\"";
  }
  
  // Find the Talishar/PvtVoid case and the closing ];
  // Pattern: case "7198186": // Talishar
  //          case "9408649": // PvtVoid
  //            $altArts = [
  //              ... entries ...
  //            ];
  
  $pattern = '/(\s+case "7198186":\s*\/\/\s*Talishar\s+case "9408649":\s*\/\/\s*PvtVoid\s+\$altArts\s*=\s*\[\s+)(.*?)(\s+\];)/s';
  
  if (!preg_match($pattern, $content, $matches)) {
    echo "Warning: Could not find Talishar/PvtVoid case in PatreonDictionary.php<BR>";
    return;
  }
  
  // Get the existing entries
  $existingEntries = trim($matches[2]);
  
  // Remove trailing comma from existing entries
  $existingEntries = rtrim($existingEntries, ",\n");
  
  // Combine: existing entries, comma, new entries
  $combinedEntries = $existingEntries;
  if (!empty($existingEntries)) {
    $combinedEntries .= ",\n";
  }
  $combinedEntries .= implode(", ", $newEntries);
  
  // Create the replacement
  $replacement = $matches[1] . $combinedEntries . $matches[3];
  
  // Replace in content
  $newContent = preg_replace($pattern, $replacement, $content);
  
  // Write back to file
  if ($newContent !== $content) {
    file_put_contents($patDictFile, $newContent);
    echo "✓ Successfully updated PatreonDictionary.php with " . count($altArtsArray) . " new alt arts<BR>";
  } else {
    echo "Warning: No changes made to PatreonDictionary.php (pattern may not have matched)<BR>";
  }
}

/**
 * Download art variation image with error handling
 * Saves to both local WebpImages and CardImages folders (like zzImageConverter.php does)
 */
function DownloadArtVariationImage($filepath, $imageUrl, $filename)
{
  // Define paths like zzImageConverter.php
  $cardImagesUploadedFolder = __DIR__ . "/../CardImages/media/uploaded/public/cardimages/english/" . $filename . ".webp";
  $cardImagesMissingFolder = __DIR__ . "/../CardImages/media/missing/cardimages/english/" . $filename . ".webp";
  $concatFilename = __DIR__ . "/concat/en/" . $filename . ".webp";
  $cardSquaresUploadedFolder = __DIR__ . "/../CardImages/media/uploaded/public/cardsquares/english/" . $filename . ".webp";
  $cardSquaresMissingFolder = __DIR__ . "/../CardImages/media/missing/cardsquares/english/" . $filename . ".webp";
  $cropFilename = __DIR__ . "/crops/" . $filename . "_cropped.png";
  $cardCropsUploadedFolder = __DIR__ . "/../CardImages/media/uploaded/public/crops/" . $filename . "_cropped.png";
  $cardCropsMissingFolder = __DIR__ . "/../CardImages/media/missing/crops/" . $filename . "_cropped.png";
  
  // Check if file already exists in any location
  if (file_exists($filepath) && file_exists($cardImagesUploadedFolder)) {
    echo "Already exists: " . $filename . "<BR>";
    return true;
  }

  // Create temp file for download
  $tempFile = $filepath . ".tmp";
  
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $imageUrl);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_TIMEOUT, 30);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  
  $imageData = curl_exec($ch);
  $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
  $curlError = curl_error($ch);
  curl_close($ch);

  if ($httpCode !== 200 || empty($imageData)) {
    if (!empty($curlError)) {
      echo "Download error (" . $httpCode . "): " . $curlError . " - " . $filename . "<BR>";
    } else {
      echo "Download failed (HTTP " . $httpCode . "): " . $filename . "<BR>";
    }
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

  // Validate file size
  $fileSize = filesize($tempFile);
  if ($fileSize < 5000) {
    unlink($tempFile);
    echo "File too small (" . $fileSize . " bytes): " . $filename . "<BR>";
    return false;
  }

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
      // Create directories if they don't exist
      CreateDirIfNotExists(dirname($filepath));
      CreateDirIfNotExists(dirname($cardImagesUploadedFolder));
      CreateDirIfNotExists(dirname($cardImagesMissingFolder));
      CreateDirIfNotExists(dirname($concatFilename));
      CreateDirIfNotExists(dirname($cardSquaresUploadedFolder));
      CreateDirIfNotExists(dirname($cardSquaresMissingFolder));
      CreateDirIfNotExists(dirname($cropFilename));
      CreateDirIfNotExists(dirname($cardCropsUploadedFolder));
      CreateDirIfNotExists(dirname($cardCropsMissingFolder));
      
      // Save main card image
      $webpResult = @imagewebp($processedImage, $filepath, 80);
      if (!$webpResult) {
        error_log("Warning: Failed to save webp for $filename to $filepath");
      }
      if (!file_exists($cardImagesUploadedFolder)) {
        @imagewebp($processedImage, $cardImagesMissingFolder, 80);
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
            
            $concatResult = @imagewebp($dest, $concatFilename, 80);
            if (!$concatResult) {
              error_log("Failed to save concat to $concatFilename");
            }
            
            if (!file_exists($cardSquaresUploadedFolder)) {
              @imagewebp($dest, $cardSquaresMissingFolder, 80);
            }
            
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
          $cropResult = @imagepng($cropImage, $cropFilename);
          if (!$cropResult) {
            error_log("Failed to save crop to $cropFilename");
          }
          
          if (!file_exists($cardCropsUploadedFolder)) {
            @imagepng($cropImage, $cardCropsMissingFolder);
          }
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

  // If conversion failed, just move the file as-is
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

/**
 * Helper function to create directory if it doesn't exist
 */
function CreateDirIfNotExists($path)
{
  if (!is_dir($path)) {
    @mkdir($path, 0777, true);
  }
}

/**
 * Get card identifier (from zzCardCodeGenerator.php)
 * Normalizes card name to lowercase with color suffix
 */
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