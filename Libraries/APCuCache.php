<?php
/**
 * APCuCache.php
 * 
 * Simple APCu-based caching utility
 * Gracefully falls back to no caching if APCu is not available
 */

class APCuCache {
    private static $enabled = null;
    
    /**
     * Check if APCu is available and enabled
     */
    private static function isAvailable() {
        if (self::$enabled === null) {
            self::$enabled = extension_loaded('apcu') && ini_get('apc.enabled');
        }
        return self::$enabled;
    }
    
    /**
     * Get a cached value
     * @param string $key Cache key
     * @param mixed $default Value to return if not cached
     * @return mixed
     */
    public static function get($key, $default = null) {
        if (!self::isAvailable()) {
            return $default;
        }
        
        $value = apcu_fetch($key, $success);
        return $success ? $value : $default;
    }
    
    /**
     * Set a cached value with TTL
     * @param string $key Cache key
     * @param mixed $value Value to cache
     * @param int $ttl Time to live in seconds (default: 600 = 10 minutes)
     * @return bool Success
     */
    public static function set($key, $value, $ttl = 600) {
        if (!self::isAvailable()) {
            return false;
        }
        
        return apcu_store($key, $value, $ttl);
    }
    
    /**
     * Delete a cached value
     * @param string $key Cache key
     * @return bool Success
     */
    public static function delete($key) {
        if (!self::isAvailable()) {
            return false;
        }
        
        return apcu_delete($key);
    }
    
    /**
     * Clear all cache
     * @return bool Success
     */
    public static function clear() {
        if (!self::isAvailable()) {
            return false;
        }
        
        return apcu_clear_cache();
    }
}
