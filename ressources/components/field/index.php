<?php

use App\Helpers;

$types = ['text', 'password', 'email', 'number', 'url', 'tel', 'search', 'date', 'datetime-local', 'month', 'week', 'time', 'color', 'textarea'];
$autocompletes = ['text' => 'on', 'password' => 'current-password', 'email' => 'email', 'tel' => 'tel', 'url' => 'url', 'search' => 'search', 'name' => 'name'];

$id = isset($id) ? $id : Helpers::randomHex(8);
$type = isset($type) ? $type : 'text';
$placeholder = isset($placeholder) ? $placeholder : '';
$class = isset($class) ? $class : '';
$error = isset($error) && sizeof($error) > 1 ? $error : '';
$required = isset($required) ? $required : false;

$tag  =  match ($type) {
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
            <?php if ($required) : ?> required <?php endif; ?>
            class="<?= $class ?> <?= $error ? 'error' : '' ?>" />
    <?php elseif ($tag === 'textarea') : ?>
        <textarea
            id="<?= e($id) ?>"
            name="<?= e($name) ?>"
            placeholder="<?= e($placeholder) ?>"
            <?php if ($required) : ?> required <?php endif; ?>
            class="<?= $error ? 'error' : '' ?>"><?= e($value) ?></textarea>
    <?php endif; ?>
    <?php if ($error) : ?>
        <p class="error"><?= $error  ?></p>
    <?php endif; ?>
    </p>
</div>