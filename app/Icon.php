<?php

namespace App;

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
     * The full path to the icon file.
     */
    private string $iconPath;

    /**
     * Icon constructor.
     *
     * @param string $name  The name of the icon.
     */
    public function __construct(string $name)
    {
        $this->name = $name;
        $this->iconPath = self::ICONS_PATH . '/' . $name . '.svg';
    }

    /**
     * Static helper method to quickly display an icon.
     *
     * @param string $name  The icon name.
     */
    public static function display(string $name): void
    {
        include (new self($name))->iconPath;
    }
}
