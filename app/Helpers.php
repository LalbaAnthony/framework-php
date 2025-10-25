<?php

namespace App;

/**
 * Class Helpers
 *
 * This class is used to provide helper public functions.
 */
class Helpers
{
    public const ACCENTS_TO_LETTERS = ['à' => 'a', 'á' => 'a', 'â' => 'a', 'ã' => 'a', 'ä' => 'a', 'å' => 'a', 'ò' => 'o', 'ó' => 'o', 'ô' => 'o', 'õ' => 'o', 'ö' => 'o', 'ø' => 'o', 'è' => 'e', 'é' => 'e', 'ê' => 'e', 'ë' => 'e', 'ç' => 'c', 'ì' => 'i', 'í' => 'i', 'î' => 'i', 'ï' => 'i', 'ù' => 'u', 'ú' => 'u', 'û' => 'u', 'ü' => 'u', 'ÿ' => 'y', 'ñ' => 'n', 'ñ' => 'n'];

    /**
     * Get current date in Y-m-d format.
     *
     * @return string
     */
    public static function currentDate(): string
    {
        return date('Y-m-d');
    }

    /**
     * Get current date and time in Y-m-d H:i:s format.
     *
     * @return string
     */
    public static function currentDateTime(): string
    {
        return date('Y-m-d H:i:s');
    }

    /**
     * Get current date and time in Y-m-d H:i:s format.
     *
     * @return string
     */
    public static function dd(mixed $var): void
    {
        $args = func_get_args();
        $bt = debug_backtrace();
        $caller = array_shift($bt);

        echo "<pre style='background-color: #ccc; padding: 10px; border-radius: 5px; border: 1px solid #ccc;'>";
        echo "<p><strong>" . $caller['file'] . ":" . $caller['line'] . "</strong></p>";
        foreach ($args as $arg) {
            var_dump($arg);
        }
        echo "</pre>";
    }

    /**
     * Slugify a string.
     *
     * @param string $string
     * @return string
     */
    public static function slugify(string $text, string $divider = '-', string $default = 'n-a'): string
    {
        if (!$text || empty($text)) return $default;

        $text = strtolower($text); // convert to lowercase
        $text = strtr($text, self::ACCENTS_TO_LETTERS); // replace accents
        $text = preg_replace('/[^a-z0-9]/', $divider, $text); // replace non letter or digits by divider
        $text = preg_replace('~-+~', $divider, $text); // remove duplicate divider
        $text = trim($text, $divider); // trim divider from start and end of string

        if (empty($text)) return $default;

        return $text;
    }

    /**
     * Return a string with a limit of n characters plus a suffix.
     *
     * @param string $text
     * @param int $limit
     * @param string $suffix
     * @return string
     */
    public static function stringLimit(string $text, int $limit = 100, string $suffix = ' ...'): string
    {
        if (!$text || empty($text)) return '';
        if ($limit < 0) return $text;

        if (strlen($text) > $limit) {
            return substr($text, 0, $limit) . $suffix;
        }

        return $text;
    }

    /**
     * Get the current URL.
     *
     * @return string
     */
    public static function currentUrl(): string
    {
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https://" : "http://";
        return $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    }

    /**
     * Helper method to extract a single key segment from array or object.
     */
    private static function getSegment(array|object $target, string $segment, mixed $default): mixed
    {
        if (is_array($target) && array_key_exists($segment, $target)) {
            return $target[$segment];
        }

        if (is_object($target) && isset($target->{$segment})) {
            return $target->{$segment};
        }

        return $default;
    }

    /**
     * Recursively get a value from an array or object using dot notation.
     *
     * @param array|object $target
     * @param string|null $key
     * @param mixed $default
     * @return mixed
     */
    public static function dataGet(array|object $target, ?string $key, mixed $default = null): mixed
    {
        if (!$key || $key === '') return $target;

        // If no dot notation, return the single segment
        if (!str_contains($key, '.')) return self::getSegment($target, $key, $default);

        // Split and process each segment
        [$segment, $remaining] = explode('.', $key, 2);

        $next = self::getSegment($target, $segment, null);

        if (!$next) return $default;

        return self::dataGet($next, $remaining, $default);
    }

    /**
     * Generate lorem ipsum text of a given length.
     * @param int $length
     * @return string
     */
    public static function lorem(int $length = 2048): string
    {
        $lorem = "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.";

        $repeated = str_repeat($lorem . ' ', ceil($length / strlen($lorem)));
        return substr($repeated, 0, $length);
    }

    /**
     * Take a date as 2025-09-04 23:00:17 and format it
     * @param string $date
     * @return string
     */
    public static function formatDate(string $date, bool $hours = false): string
    {
        return date('d/m/Y' . ($hours ? ' H:i' : ''), strtotime($date));
    }

    /**
     * Escape HTML special characters in a string.
     * @param string $string
     * @return string
     */
    public static function e(string|null $string): string
    {
        if (!$string || empty($string)) return '';
        return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
    }
}
