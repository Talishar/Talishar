<?php

/**
 * MatchupHelpers.php
 * Helper functions for processing and transforming matchup data from Fabrary API
 * Handles extraction and standardization of turn order preferences
 */

/**
 * Extract turn order preference from a single matchup
 * Fabrary sends preferredTurnOrder as: "First", "Second", "NoPreference", or undefined
 * 
 * @param mixed $matchup The matchup data from Fabrary (array or object)
 * @return object The matchup with standardized preferredTurnOrder field
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
    
    // Initialize the preferredTurnOrder field if not present
    if (!isset($matchup->preferredTurnOrder)) {
        $matchup->preferredTurnOrder = null;
    } else {
        // Normalize the value - Fabrary sends "First", "Second", "NoPreference", or undefined
        $turnOrder = $matchup->preferredTurnOrder;
        
        // Handle undefined/null cases
        if ($turnOrder === 'undefined' || $turnOrder === null || $turnOrder === '' || $turnOrder === 'NoPreference') {
            $matchup->preferredTurnOrder = null;
        } else if ($turnOrder === 'First' || $turnOrder === 'first' || $turnOrder === '1st') {
            $matchup->preferredTurnOrder = '1st';
        } else if ($turnOrder === 'Second' || $turnOrder === 'second' || $turnOrder === '2nd') {
            $matchup->preferredTurnOrder = '2nd';
        } else {
            // Fallback for any other value - treat as no preference
            $matchup->preferredTurnOrder = null;
        }
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
    
    $transformed = array();
    foreach ($matchups as $matchup) {
        $transformed[] = ExtractTurnOrderPreference($matchup);
    }
    
    return $transformed;
}

?>
