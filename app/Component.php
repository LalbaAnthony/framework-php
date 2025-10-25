<?php

namespace App;

use App\Exceptions\FileException;
use App\Exceptions\ComponentException;
use Exception;
use App\Exceptions\NotFoundException;

/**
 * A simple component rendering system.
 *
 * This class loads a PHP component file from a predefined components directory,
 * extracts the provided properties into the local symbol table, and returns the
 * component's rendered output.
 * 
 * Example usage:
 * Component::display('example', ['text' => 'Hello World'], ['css' => true, 'js' => true]);
 */
class Component
{
    /**
     * Base directory for components.
     */
    private const COMPONENTS_PATH = __DIR__ . '/../ressources/components';

    /**
     * Base directory for components.
     */
    private const COMPONENTS_URL = APP_URL . '/ressources/components';

    /**
     * Associative array to track printed HTML tags for deduplication.
     */
    private static array $printedTags = [
        'style' => [],
        'script' => []
    ];

    /**
     * Associative array of loaded CSS files.
     */
    private static array $loadedCss = [];

    /**
     * Associative array of loaded JS files.
     */
    private static array $loadedJs = [];

    /**
     * The component name (which corresponds to the file name without extension).
     */
    private string $name;

    /**
     * Associative array of properties to pass to the component.
     */
    private array $props;

    /**
     * Associative array of parameters to pass to the component.
     */
    private array $params;

    /**
     * The full path to the component file.
     */
    private string $phpPath;

    /**
     * The full path to the component file.
     */
    private string $cssUrl;

    /**
     * The full path to the component file.
     */
    private string $jsUrl;

    /**
     * Component constructor.
     *
     * @param string $name  The name of the component.
     * @param array  $props Associative array of properties.
     * @param array  $params Associative array of component parameters.
     */
    public function __construct(string $name, array $props = [], array $params = [])
    {
        $this->name = $name;
        $this->props = $props;
        $this->params = $params;
        $this->phpPath = self::COMPONENTS_PATH . '/' . $name . '/index.php';
        $this->cssUrl = self::COMPONENTS_URL . '/' . $name . '/style.css';
        $this->jsUrl = self::COMPONENTS_URL . '/' . $name . '/index.js';
    }

    /**
     * Deduplicates HTML tags (like style or script).
     */
    public function deduplicateTags(string &$content, string $tag): void
    {
        if (!$content) return;
        if (!$tag) return;

        if (!isset(self::$printedTags[$tag])) {
            self::$printedTags[$tag] = [];
        }

        if (preg_match_all('/<' . $tag . '\b[^>]*>(.*?)<\/' . $tag . '>/is', $content, $matches)) {
            foreach ($matches[0] as $key => $match) {
                $md5 = md5($matches[1][$key]);

                if (!$md5) continue;

                if (!in_array($md5, self::$printedTags[$tag])) {
                    self::$printedTags[$tag][] = $md5;
                } else {
                    // Remove the tag from content if already loaded
                    $content = str_replace($match, '', $content);
                }
            }
        }
    }

    /**
     * Renders the component and returns its output as a string.
     *
     * @return string The rendered component output.
     *
     * @throws NotFoundException If the component file is not found or is not readable.
     */
    public function renderPhp(): string
    {
        if (!file_exists($this->phpPath)) throw new NotFoundException("Component not found: " . $this->name, 404);
        if (!is_readable($this->phpPath)) throw new FileException("Component not readable: " . $this->name, 403);

        ob_start();

        // Extract data to local variables.
        if ($this->props) {
            extract($this->props, EXTR_OVERWRITE);
        }

        include $this->phpPath;

        // Unset extracted variables to avoid polluting the scope.
        if ($this->props) {
            foreach (array_keys($this->props) as $key) {
                unset($$key);
            }
        }

        $content = ob_get_clean();
        $this->deduplicateTags($content, 'style');
        $this->deduplicateTags($content, 'script');

        return $content;
    }

    /**
     * Render the HTML tags required by the component (CSS and JS).
     *
     * @return string The HTML tags for the component's CSS and JS.
     */
    public function renderIncludeds(): string
    {
        $output = '';

        // Include CSS only if it hasn't been printed before
        if (
            $this->cssUrl &&
            !in_array($this->cssUrl, self::$loadedCss) &&
            !(isset($this->params['css']) && $this->params['css'] === false)
        ) {
            $output .= PHP_EOL . '<link rel="stylesheet" href="' . $this->cssUrl . '">' . PHP_EOL;
            self::$loadedCss[] = $this->cssUrl;
        }

        // Include JS only if it hasn't been printed before
        if (
            $this->jsUrl &&
            !in_array($this->jsUrl, self::$loadedJs) &&
            !(isset($this->params['js']) && $this->params['js'] === false)
        ) {
            $output .= PHP_EOL . '<script src="' . $this->jsUrl . '"></script>' . PHP_EOL;
            self::$loadedJs[] = $this->jsUrl;
        }

        return $output;
    }

    /**
     * Static helper method to quickly render a component.
     *
     * @param string $name  The component name.
     * @param array  $props Associative array of properties.
     * @param array  $params Associative array of component parameters.
     *
     * @throws NotFoundException If the component file is not found or is not readable.
     */
    public static function display(string $name, array $props = [], array $params = []): void
    {
        $component = new self($name, $props, $params);

        try {
            echo $component->renderPhp();
            echo $component->renderIncludeds();
        } catch (Exception $e) {
            throw new ComponentException("Error rendering component '" . $name . "': " . $e->getMessage(), $e->getCode(), $e);
        }
    }
}
