<?php

use App\Helpers;
use App\Icon;

?>

<table class="table">
    <thead>
        <tr>
            <?php foreach ($columns as $column): ?>
                <th scope="col">
                    <?php
                    $col = isset($sort[0]) && isset($sort[0]['column']) && $sort[0]['column'] ? $sort[0]['column'] : '';
                    $order = isset($sort[0]) && isset($sort[0]['order']) && $sort[0]['order'] ? $sort[0]['order'] : 'ASC';

                    $params = [
                        'sort' => [
                            [
                                'column' => $column['sortable'] ?? '',
                                'order' => $order === 'DESC' ? 'ASC' : 'DESC',
                            ],
                        ],
                    ];

                    ?>
                    <a href="<?= Helpers::buildUrl(null, $params) ?>">
                        <span>
                            <?= Helpers::e($column['name'] ?? '') ?>
                        </span>
                        <?php if ($col === ($column['sortable'] ?? null)): ?>
                            <?php if ($order === 'DESC'): ?>
                                <?php Icon::display('chevron-up', '#aaa') ?>
                            <?php else: ?>
                                <?php Icon::display('chevron-down', '#aaa') ?>
                            <?php endif; ?>
                        <?php endif; ?>
                    </a>
                </th>
            <?php endforeach; ?>

            <?php if (isset($actions) && count($actions) > 0): ?>
                <th scope="col">&nbsp;</th>
            <?php endif; ?>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($rows)): ?>
            <?php foreach ($rows as $row): ?>
                <?php $id = Helpers::dataGet($row, 'id'); ?>
                <tr>
                    <?php foreach ($columns as $key => $column): ?>
                        <td scope="row">
                            <?php if (isset($column['fn'])): ?>
                                <?= $column['fn']($row) ?>
                            <?php elseif (isset($column['value'])): ?>
                                <?php
                                $value = Helpers::dataGet($row, $column['value']);
                                if ($value) {
                                    echo Helpers::e($value);
                                } else  if (isset($column['default'])) {
                                    echo Helpers::e($column['default']);
                                }
                                ?>
                            <?php elseif (isset($column['default'])): ?>
                                <?= Helpers::e($column['default']) ?>
                            <?php endif; ?>
                        </td>
                    <?php endforeach; ?>
                    <td>
                        <?php foreach ($actions as $key => $action): ?>
                            <?php if (isset($action['route'])): ?>
                                <form
                                    action="#"
                                    method="POST"
                                    data-action-type="single">
                                    <button type="button">
                                        <?php if (isset($action['icon']) && $action['icon']): ?>
                                            <?php Icon::display($action['icon']) ?>
                                        <?php endif; ?>
                                        <?php if (isset($action['name']) && $action['name']): ?>
                                            <span>
                                                <?= Helpers::e($action['name']) ?>
                                            </span>
                                        <?php endif; ?>
                                    </button>
                                </form>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </td>
                </tr>

            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="<?= count($columns) + 2 ?>">
                    <p>
                        Nothing to display.
                    </p>
                </td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>