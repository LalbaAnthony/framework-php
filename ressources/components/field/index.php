<?php

use App\Util\Helpers;

$types = ['text', 'password', 'email', 'number', 'url', 'tel', 'search', 'date', 'datetime-local', 'month', 'week', 'time', 'color', 'textarea'];
$autocompletes = ['text' => 'on', 'password' => 'current-password', 'email' => 'email', 'tel' => 'tel', 'url' => 'url', 'search' => 'search', 'name' => 'name'];

$id = isset($id) ? $id : Helpers::randomHex(8);
$type = isset($type) ? $type : 'text';
$placeholder = isset($placeholder) ? $placeholder : '';
$class = isset($class) ? $class : '';
$errors = isset($errors) && count($errors) > 0 ? $errors : [];
$required = isset($required) ? $required : false;

$tag = match ($type) {
    'textarea' => 'textarea',
    default => 'input',
};

if (!isset($name)) {
    throw new Exception("The 'name' prop is required for the Field component.");
}

if (!in_array($type, $types)) {
    throw new Exception("The 'type' prop must be one of the following: " . implode(', ', $types) . ".");
}

?>

<div class="field <?= $class ?>">
    <?php if ($label) : ?>
        <label for="<?= e($id) ?>">
            <?= e($label) ?>
            <?php if ($required) : ?>
                <span class="required">*</span>
            <?php endif; ?>
        </label>
    <?php endif; ?>
    <?php if ($tag === 'input') : ?>
        <input
            id="<?= e($id) ?>"
            type="<?= e($type) ?>"
            name="<?= e($name) ?>"
            value="<?= e($value) ?>"
            autocomplete="<?= e($autocompletes[$type] ?? 'on') ?>"
            placeholder="<?= e($placeholder) ?>"
            class="<?= $class ?> <?= $errors ? 'error' : '' ?>" />
    <?php elseif ($tag === 'textarea') : ?>
        <textarea
            id="<?= e($id) ?>"
            name="<?= e($name) ?>"
            placeholder="<?= e($placeholder) ?>"
            class="<?= $errors ? 'error' : '' ?>"><?= e($value) ?></textarea>
    <?php endif; ?>
    <?php if ($errors && count($errors) > 0) : ?>
        <p class="error"><?= implode(', ', $errors) ?></p>
    <?php endif; ?>
    </p>
</div>