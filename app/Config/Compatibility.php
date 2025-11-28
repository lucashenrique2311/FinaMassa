<?php

/**
 * Compatibility functions for PHP < 8.0
 * 
 * CodeIgniter 4 requires PHP 8.1+, but this file provides
 * compatibility functions for older PHP versions during development.
 * 
 * ⚠️ IMPORTANT: Update to PHP 8.1+ for production!
 */

if (!function_exists('str_starts_with')) {
    function str_starts_with($haystack, $needle) {
        return (string)$needle !== '' && strncmp($haystack, $needle, strlen($needle)) === 0;
    }
}

if (!function_exists('str_contains')) {
    function str_contains($haystack, $needle) {
        return $needle !== '' && mb_strpos($haystack, $needle) !== false;
    }
}

if (!function_exists('str_ends_with')) {
    function str_ends_with($haystack, $needle) {
        return $needle !== '' && substr($haystack, -strlen($needle)) === (string)$needle;
    }
}

