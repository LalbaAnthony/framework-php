<?php

use App\Component;
use App\Helpers;

?>

<?php Component::display('header') ?>

<main>
    <section>
        <?php Component::display('section-title', ['title' => 'All posts', 'color' => '#eb4034']) ?>
        <?php Component::display('search-bar', [
            'action' => APP_URL,
            'method' => 'GET',
            'placeholder' => 'Search posts...',
            'search' => $search,
        ]);
        ?>
        <?php Component::display('table', [
            'sort' => $sort,
            'columns' => [
                [
                    'name' => 'ID',
                    'value' => 'id',
                    'sortable' => 'id',
                    'fn' => function ($row) {
                        $text = '#' . dataGet($row, 'id');
                        return '<strong>' . $text . '</strong>';
                    }
                ],
                [
                    'name' => 'Slug',
                    'value' => 'slug',
                    'sortable' => 'slug'
                ],
                [
                    'name' => 'Title',
                    'value' => 'title',
                    'sortable' => 'title'
                ],
                [
                    'name' => 'Content',
                    'value' => 'content',
                    'sortable' => 'content',
                    'fn' => function ($row) {
                        return Helpers::stringLimit(dataGet($row, 'content'), 15);
                    }
                ],
                [
                    'name' => 'Published',
                    'value' => 'published',
                    'sortable' => 'published',
                    'fn' => function ($row) {
                        $text = dataGet($row, 'published') ? 'Yes' : 'No';
                        $style = dataGet($row, 'published') ? 'color: black;' : 'font-weight: bold; color: orange;';
                        return "<span style='$style'>$text</span>";
                    }
                ],
                [
                    'name' => 'Date',
                    'value' => 'date',
                    'sortable' => 'date',
                    'fn' => function ($row) {
                        $text = 'On ' . Helpers::formatDate(dataGet($row, 'date'));
                        return $text;
                    }
                ],
            ],
            'rows' => $posts["data"],
            'actions' => [
                [
                    'name' => 'Éditer',
                    'url' => '#',
                    'icon' => 'pen',
                    'color' => 'dark',
                    'method' => 'GET',
                ],
                [
                    'name' => 'Supprimer',
                    'url' => '#',
                    'icon' => 'trash',
                    'color' => 'light',
                    'method' => 'DELETE',
                ]
            ],
        ]) ?>
        <?php Component::display('pagination', [
            'page' => $posts["page"],
            'last' => $posts["last"],
        ]);
        ?>
    </section>
</main>

<?php Component::display('footer') ?>