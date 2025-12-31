<?php

namespace App\View;

trait Html
{
    /**
     * Open the HTML document
     * 
     * @return void
     */
    public static function openHtml(): void
    {
        echo '<!DOCTYPE html>';
        echo '<html lang="' . APP_LANG . '">';
        echo '<head>';
        echo '<meta charset="UTF-8">';
        echo '<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />';
        echo '<meta name="description" content="' . APP_DESCRIPTION . '" />';
        echo '<meta name="author" content="' . APP_AUTHOR . '" />';
        echo '<title>' . APP_NAME_LONG . '</title>';
        echo '<link rel="icon" type="image/x-icon" href="' . APP_URL . '/public/favicon.ico" />';

        foreach (HTML_STYLES as $style) echo '<link rel="stylesheet" href="' . $style['href'] . '" ' . ($style['rel'] ? 'rel="' . $style['rel'] . '"' : '') . '>';

        foreach (HTML_SCRIPTS as $script) echo '<script src="' . $script['src'] . '" ' . ($script['defer'] ?? 'defer') . ' ' . ($script['async'] ?? 'async') . '></script>';

        if (HTML_NOJS) echo '<noscript><h1>JavaScript is disabled</h1><p>Please enable JavaScript to use this website.</p></noscript>';

        echo '</head>';
        echo '<body>';
    }

    /**
     * Close the HTML document
     * 
     * @return void
     */
    public static function closeHtml(): void
    {
        echo '</body>';
        echo '</html>';
    }
}
