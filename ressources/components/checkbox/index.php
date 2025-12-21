<?php

use App\Helpers;

$id = isset($id) ? $id : Helpers::randomHex(8);
$class = isset($class) ? $class : '';
$errors = isset($errors) && count($errors) > 0 ? $errors : [];
$required = isset($required) ? $required : false;
$checked = isset($checked) ? (bool) $checked : false;

if (!isset($name)) {
    throw new Exception("The 'name' prop is required for the Checkbox component.");
}

?>

<div class="checkbox-field <?= $class ?>">
    <label for="<?= e($id) ?>" class="checkbox-wrapper">
        <input
            type="checkbox"
            id="<?= e($id) ?>"
            name="<?= e($name) ?>"
            value="1"
            <?php if ($checked) : ?> checked <?php endif; ?>
            <?php if ($required) : ?> required <?php endif; ?>
            class="<?= $errors ? 'error' : '' ?>" />
        <span class="checkbox-label">
            <?= e($label ?? '') ?>
            <?php if ($required) : ?>
                <span class="required">*</span>
            <?php endif; ?>
        </span>
    </label>
    <?php if ($errors && count($errors) > 0) : ?>
        <p class="error"><?= implode(', ', $errors) ?></p>
    <?php endif; ?>
</div>