<?php

namespace App\Controller;

use App\Http\Utils;

abstract class Controller
{
    use Utils;

    /**
     * Default values for pagination and sorting.
     */
    const DEFAULT_PER_PAGE = 10;
    const DEFAULT_PAGE = 1;
    const DEFAULT_SORT = [['column' => 'created_at', 'order' => 'DESC'], ['column' => 'updated_at', 'order' => 'DESC']];
}
