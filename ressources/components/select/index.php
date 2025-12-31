<?php

use App\Util\Helpers;

$id = isset($id) ? $id : Helpers::randomHex(8);
$class = isset($class) ? $class : '';
$options = isset($options) && is_array($options) ? $options : [];
$multiple = isset($multiple) ? (bool)$multiple : false;
$errors = isset($errors) && count($errors) > 0 ? $errors : [];
$required = isset($required) ? $required : false;

if (!isset($name)) {
    throw new Exception("The 'name' prop is required for the Field component.");
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
    <select id="<?= e($id) ?>"
        name="<?= e($name) ?><?= $multiple ? '[]' : '' ?>"
        class="<?= $errors ? 'error' : '' ?>"
        <?= $multiple ? 'multiple' : '' ?>>
        <?php foreach ($options as $option) : ?>
            <option value="<?= e($option['value']) ?>" <?= !empty($option['selected']) ? 'selected' : '' ?>><?= e($option['label']) ?></option>
        <?php endforeach; ?>
    </select>
    <?php if ($errors && count($errors) > 0) : ?>
        <p class="error"><?= implode(', ', $errors) ?></p>
    <?php endif; ?>
    </p>
</div>