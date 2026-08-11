<?php

/**
 * MatchupHelpers.php
 * Helper functions for processing and transforming matchup data from Fabrary API
 * Handles extraction and standardization of turn order preferences
 */

/**
 * Extract turn order preference from a single matchup
 * Fabrary sends preferredTurnOrder as: "First", "Second", "NoPreference", or undefined
 * Also extracts and preserves notes field
 * 
 * @param mixed $matchup The matchup data from Fabrary (array or object)
 * @return object The matchup with standardized preferredTurnOrder field and notes
 */
function ExtractTurnOrderPreference($matchup) {
    // Convert to object if it's an array
    if (is_array($matchup)) {
        $matchup = (object)$matchup;
    }
    
    // Ensure we have an object
    if (!is_object($matchup)) {
        return $matchup;
    }
    
    // Normalize preferredTurnOrder - Fabrary sends "First", "Second", "NoPreference", or undefined
    $matchup->preferredTurnOrder = match($matchup->preferredTurnOrder ?? null) {
        'First', 'first', '1st' => '1st',
        'Second', 'second', '2nd' => '2nd',
        default => null,
    };
    
    // Ensure notes field exists (preserve from Fabrary if present)
    if (!isset($matchup->notes)) {
        $matchup->notes = null;
    }
    
    return $matchup;
}

/**
 * Transform an array of matchups to include standardized turn order preferences
 * 
 * @param array $matchups Array of matchup objects from Fabrary API
 * @return array Transformed matchups array with standardized preferredTurnOrder fields
 */
function TransformMatchupsWithTurnOrder($matchups) {
    if (!is_array($matchups)) {
        return $matchups;
    }
    
    $transformed = [];
    foreach ($matchups as $matchup) {
        $transformed[] = ExtractTurnOrderPreference($matchup);
    }
    
    return $transformed;
}

