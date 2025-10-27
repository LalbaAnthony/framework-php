<?php

namespace App;

use Exception;
use App\Exceptions\IconException;

/**
 * A simple icon rendering system.
 *
 * This class loads a PHP icon file from a predefined icons directory,
 * extracts the provided properties into the local symbol table, and returns the
 * icon's rendered output.
 * 
 * Example usage:
 * Icon::display('example', ['text' => 'Hello World'], ['css' => true, 'js' => true]);
 */
class Icon
{
    /**
     * Base directory for icons.
     */
    private const ICONS_PATH = __DIR__ . '/../ressources/icons';

    /**
     * The name of the icon.
     */
    private string $name;

    /**
     * The color of the icon.
     */
    private string $color;

    /**
     * The size of the icon.
     */
    private string $size;

    /**
     * The full path to the icon file.
     */
    private string $path;

    /**
     * Icon constructor.
     *
     * @param string $name  The name of the icon.
     */
    public function __construct(string $name, string $color = 'currentColor', string $size = '24px')
    {
        $this->name = $name;
        $this->color = $color;
        $this->size = $size;
        $this->path = self::ICONS_PATH . '/' . $name . '.svg';
    }

    /**
     * Replace the color attribute in the SVG content.
     */
    public function replaceColorAttribute(string &$content, string $color): void
    {
        if (!$content) return;
        if (!$color) return;

        // Find 'stroke' attributes and replace their values with the specified color
        $content = preg_replace('/(\sstroke=["\'])([^"\']*)(["\'])/i', '${1}' . $color . '${3}', $content);
    }

    /**
     * Replace the size attributes in the SVG content.
     */
    public function replaceSizeAttribute(string &$content, string $size): void
    {
        if (!$content) return;
        if (!$size) return;

        // Find 'width' attributes and replace their values with the specified size
        $content = preg_replace('/(\swidth=["\'])([^"\']*)(["\'])/i', '${1}' . $size . '${3}', $content);

        // Find 'height' attributes and replace their values with the specified size
        $content = preg_replace('/(\sheight=["\'])([^"\']*)(["\'])/i', '${1}' . $size . '${3}', $content);
    }

    /**
     * Render the icon.
     */
    public function render(): string
    {
        if (!file_exists($this->path)) throw new \Exception("Icon file not found: " . $this->path);
        if (!is_readable($this->path)) throw new \Exception("Icon file is not readable: " . $this->path);

        ob_start();

        include $this->path;

        $content = ob_get_clean();

        if (isset($this->color) && !empty($this->color)) $this->replaceColorAttribute($content, $this->color);
        if (isset($this->size) && !empty($this->size)) $this->replaceSizeAttribute($content, $this->size);

        return $content;
    }

    /**
     * Static helper method to quickly display an icon.
     */
    public static function display(string $name, string $color = 'currentColor', string $size = '24px'): void
    {
        if (!$name || empty($name)) return;

        $icon = new self($name, $color, $size);

        try {
            echo $icon->render();
        } catch (Exception $e) {
            throw new IconException("Error rendering icon '" . $name . "': " . $e->getMessage(), $e->getCode(), $e);
        }
    }
}
