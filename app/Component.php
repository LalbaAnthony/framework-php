<?php

namespace App;

use App\Exceptions\FileException;
use App\Exceptions\ComponentException;
use App\Exceptions\NotFoundException;
use Exception;

/**
 * The component rendering system.
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
     * Associative array to track printed HTML tags for deduplication.
     */
    private static array $printedTags = [
        'style' => [],
        'script' => []
    ];

    /**
     * Associative array of loaded files.
     */
    private static array $includedTags =  [
        'style' => [],
        'script' => [],
        'div' => []
    ];

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
    private string $cssPath;

    /**
     * The full path to the component file.
     */
    private string $jsPath;

    /**
     * The full path to the component file.
     */
    private string $htmlPath;

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
        $this->cssPath = self::COMPONENTS_PATH . '/' . $name . '/style.css';
        $this->jsPath = self::COMPONENTS_PATH . '/' . $name . '/index.js';
        $this->htmlPath = self::COMPONENTS_PATH . '/' . $name . '/index.html';
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

        if (!(isset($this->params['deduplicate']) && $this->params['deduplicate'] === false)) {
            $this->deduplicateTags($content, 'style');
            $this->deduplicateTags($content, 'script');
        }

        return $content;
    }

    /**
     * Render the HTML tags required by the component.
     *
     * @return string The HTML tags for the component.
     */
    public function renderInclud(string $path, string $type): string
    {
        $output = '';

        $tag = match ($type) {
            'css' => 'style',
            'js' => 'script',
            'html' => 'div',
            default => throw new ComponentException("Invalid type for renderInclud: " . $type),
        };

        if ($type === 'css' && (isset($this->params['css']) && $this->params['css'] === false)) $path = null;
        if ($type === 'js' && (isset($this->params['js']) && $this->params['js'] === false)) $path = null;
        if ($type === 'html' && (isset($this->params['html']) && $this->params['html'] === false)) $path = null;

        if ($path && !in_array($path, self::$includedTags[$tag])) {
            if (!file_exists($path)) return '';
            if (!is_readable($path)) return '';

            $output .= '<' . $tag . '>';
            $output .= file_get_contents($path);
            $output .= '</' . $tag . '>';

            // Deduplicate only for css and js, print html every time
            if ($type !== 'html') self::$includedTags[$tag][] = $path;
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
        if (!$name || empty($name)) {
            throw new ComponentException("Component name is required for display.", 400);
        }

        $component = new self($name, $props, $params);

        try {
            $content = '';
            $content .= $component->renderPhp();
            $content .= $component->renderInclud($component->cssPath, 'css');
            $content .= $component->renderInclud($component->jsPath, 'js');
            $content .= $component->renderInclud($component->htmlPath, 'html');

            echo $content;
        } catch (Exception $e) {
            throw new ComponentException("Error rendering component '" . $name . "': " . $e->getMessage(), $e->getCode(), $e);
        }
    }
}
