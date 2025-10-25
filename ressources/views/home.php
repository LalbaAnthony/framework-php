<?php

use App\Component;
use App\Helpers;

?>

<?php Component::display('header') ?>

<main>
    <section>
        <?php Component::display('section-title', ['title' => 'List', 'color' => '#eb4034']) ?>
        <?php Component::display('table', [
            'search' => $search,
            'perPage' => $perPage,
            'page' => $page,
            'sort' => $sort,
            'columns' => [
                [
                    'name' => 'ID',
                    'value' => 'id',
                    'sortable' => 'id',
                    'fn' => function ($row) {
                        return '#' . Helpers::dataGet($row, 'id');
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
                        return Helpers::stringLimit(Helpers::dataGet($row, 'content'), 15);
                    }
                ],
                [
                    'name' => 'Date',
                    'value' => 'date',
                    'sortable' => 'date',
                    'fn' => function ($row) {
                        return 'On ' . Helpers::formatDate(Helpers::dataGet($row, 'date'));
                    }
                ],
            ],
            'rows' => $posts["data"],
            'actions' => [
                [
                    'name' => 'Éditer',
                    'route' => 'post.edit',
                ],
                [
                    'name' => 'Supprimer',
                    'route' => 'post.delete',
                ]
            ],
        ]) ?>
    </section>
</main>

<?php Component::display('footer') ?>