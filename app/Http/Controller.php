<?php

namespace App\Http;

use App\View\View;

abstract class Controller
{
    use Utils;
    use Response;
    use View;

    /**
     * Default values for pagination and sorting.
     */
    const DEFAULT_PER_PAGE = 10;
    const DEFAULT_PAGE = 1;
    const DEFAULT_SORT = [['column' => 'created_at', 'order' => 'DESC'], ['column' => 'updated_at', 'order' => 'DESC']];
}
