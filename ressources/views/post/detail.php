<?php

use App\Helpers;

?>

<?php component('header') ?>

<main>
    <section>
        <?php component('section-title', ['title' => $post->title, 'color' => '#32a852']) ?>

        <form action="<?= Helpers::currentUrl(false) ?>" method="POST">
            <div class="fields-grid">
                <?php component('field', [
                    'placeholder' => 'Enter the title',
                    'label' => 'Title',
                    'name' => 'title',
                    'class' => 'field-title',
                    'type' => 'text',
                    'required' => true,
                    'error' => $errors['title'] ?? null,
                    'value' => $post->title,
                ]) ?>
                <?php component('field', [
                    'placeholder' => 'Enter the slug',
                    'label' => 'Slug',
                    'name' => 'slug',
                    'class' => 'field-slug',
                    'type' => 'text',
                    'required' => true,
                    'error' => $errors['slug'] ?? null,
                    'value' => $post->slug,
                ]) ?>
                <?php component('field', [
                    'placeholder' => 'Enter the date',
                    'label' => 'Date',
                    'name' => 'date',
                    'class' => 'field-date',
                    'type' => 'date',
                    'required' => true,
                    'error' => $errors['date'] ?? null,
                    'value' => $post->date,
                    ]) ?>
                <?php component('checkbox', [
                    'label' => 'Published',
                    'name' => 'published',
                    'class' => 'field-published',
                    'required' => false,
                    'error' => $errors['published'] ?? null,
                    'checked' => $post->published,
                ]) ?>
                <?php component('field', [
                    'placeholder' => 'Content of the post',
                    'label' => 'Content',
                    'name' => 'content',
                    'class' => 'field-content',
                    'type' => 'textarea',
                    'required' => true,
                    'error' => $errors['content'] ?? null,
                    'value' => $post->content,
                ]) ?>
            </div>
            <div class="actions-grid">
                <?php component('button', [
                    'type' => 'submit',
                    'label' => 'Update',
                    'color' => 'dark'
                ]) ?>
            </div>
        </form>
    </section>
</main>

<?php component('footer') ?>

<style>
    .fields-grid {
        display: grid;
        grid-template-rows: auto;
    }

    .field-title {
        grid-area: title;
    }

    .field-date {
        grid-area: date;
    }

    .field-published {
        grid-area: published;
    }

    .field-slug {
        grid-area: slug;
    }

    .field-content {
        grid-area: content;
    }

    @media (min-width: 640px)  {
        .fields-grid {
            grid-template-areas:
                "title slug"
                "date ."
                "published ."
                "content content";
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
        }
    }

    @media (max-width: 639px) {
        .fields-grid {
            grid-template-areas:
                "title"
                "slug"
                "date"
                "published"
                "content";
            grid-template-columns: repeat(1, 1fr);
            gap: 1rem;
        }
    }

    .actions-grid {
        display: flex;
        justify-content: flex-end;
        margin-top: 1.5rem;
    }
</style>