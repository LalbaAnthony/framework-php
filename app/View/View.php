<?php

namespace App\View;

use App\Exceptions\NotFoundException;
use App\Exceptions\FileException;
use App\Http\Utils;

trait View
{
    use Html;
    use Utils;

    const VIEWS_PATH = __DIR__ . '/../../ressources/views/';

    /**
     * Render a view
     * 
     * @param string $name
     * @param mixed $data
     * @return void
     */
    public function view(string $name, mixed $data = [], int $code = 200): void
    {
        $this->prepare($code);

        $path = self::VIEWS_PATH . $name . '.php';

        if (!file_exists($path)) throw new NotFoundException("The view $name does not exist.");
        if (!is_readable($path)) throw new FileException("The file $path is not readable.");

        self::openHtml();

        if ($data) {
            extract($data, EXTR_OVERWRITE);
        }

        require $path;

        if ($data) {
            foreach (array_keys($data) as $key) {
                unset($$key);
            }
        }

        self::closeHtml();

        exit;
    }
}
