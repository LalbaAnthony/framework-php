<?php

use App\Helpers;

$id = isset($id) ? $id : Helpers::randomHex(8);
$class = isset($class) ? $class : '';
$error = isset($error) && strlen($error) > 1 ? $error : '';
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
            class="<?= $error ? 'error' : '' ?>" />
        <span class="checkbox-label">
            <?= e($label ?? '') ?>
            <?php if ($required) : ?>
                <span class="required">*</span>
            <?php endif; ?>
        </span>
    </label>
    <?php if ($error) : ?>
        <p class="error"><?= $error ?></p>
    <?php endif; ?>
</div>