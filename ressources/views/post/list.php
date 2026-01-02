<?php

use App\Util\Helpers;
use App\View\Component;

?>

<?php component('header') ?>

<main>
    <section>
        <?php component('section-title', ['title' => 'All posts']) ?>

        <?php if (isset($success) && $success) : ?>
            <?php component('alert', [
                'type' => 'success',
                'message' => $success
            ]) ?>
        <?php endif; ?>

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
                    'name' => 'Title',
                    'value' => 'title',
                    'sortable' => 'title'
                ],
                [
                    'name' => 'Slug',
                    'value' => 'slug',
                    'sortable' => 'slug'
                ],
                [
                    'name' => 'Content',
                    'sortable' => 'content',
                    'value' => function ($row) {
                        return Helpers::stringLimit(dataGet($row, 'content'), 15);
                    }
                ],
                [
                    'name' => 'Categories',
                    'value' => function ($row) {
                        $categories = $row->categories;
                        if (is_array($categories) && count($categories) > 0) {
                            $content = '';
                            $content .= '<div style="display: flex; gap: 4px; flex-wrap: wrap;">';
                            foreach ($categories as $category) {
                                $content .= Component::get('pill', [
                                    'label' => $category->label,
                                    'color' => $category->color,
                                ]);
                            }
                            $content .= '</div>';
                            return $content;
                        } else {
                            return '<div style="color: var(--gray);">No categories</div>';
                        }
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
                    'color' => 'info',
                    'method' => 'GET',
                ],
                [
                    'name' => 'Delete',
                    'url' => function ($row) {
                        return APP_URL . '/posts/' . dataGet($row, 'id');
                    },
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