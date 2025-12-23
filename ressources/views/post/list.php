<?php

use App\Helpers;

?>

<?php component('header') ?>

<main>
    <section>
        <?php component('section-title', ['title' => 'All posts']) ?>

        <?php component('search-bar', [
            'method' => 'GET',
            'placeholder' => 'Search posts...',
            'search' => $search,
        ]);
        ?>
        <?php component('table', [
            'sort' => $sort,
            'columns' => [
                [
                    'name' => 'ID',
                    'sortable' => 'id',
                    'value' => function ($row) {
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
                    'sortable' => 'content',
                    'value' => function ($row) {
                        return Helpers::stringLimit(dataGet($row, 'content'), 15);
                    }
                ],
                [
                    'name' => 'Published',
                    'sortable' => 'published',
                    'value' => function ($row) {
                        $text = dataGet($row, 'published') ? 'Yes' : 'No';
                        return $text;
                    }
                ],
                [
                    'name' => 'Date',
                    'sortable' => 'date',
                    'value' => function ($row) {
                        $text = 'On ' . Helpers::formatDate(dataGet($row, 'date'));
                        return $text;
                    }
                ],
            ],
            'rows' => $posts,
            'actions' => [
                [
                    'name' => 'Edit',
                    'url' => function ($row) {
                        return APP_URL . '/posts/' . dataGet($row, 'id');
                    },
                    'icon' => 'pen',
                    'color' => 'dark',
                    'method' => 'GET',
                ],
                [
                    'name' => 'Delete',
                    'url' => '#',
                    'icon' => 'trash',
                    'color' => 'danger',
                    'method' => 'DELETE',
                ]
            ],
        ]) ?>
        <?php component('pagination', [
            'page' => $meta["page"],
            'lastPage' => $meta["lastPage"],
        ]);
        ?>
    </section>
</main>

<?php component('footer') ?>