<?php

use App\Helpers;

?>

<table class="table">
    <thead>
        <tr>
            <?php foreach ($columns as $column): ?>
                <th scope="col">
                    <?php
                    $col = isset($sort[0]['column']) ? $sort[0]['column'] : '';
                    $order = isset($sort[0]['order']) ? $sort[0]['order'] : 'ASC';

                    $params = [
                        'sort' => [
                            [
                                'column' => $column['sortable'] ?? '',
                                'order' => $order === 'DESC' ? 'ASC' : 'DESC',
                            ],
                        ],
                    ];

                    ?>
                    <a href="<?= Helpers::buildUrl(null, $params, true) ?>">
                        <span>
                            <?= e($column['name'] ?? '') ?>
                        </span>
                        <?php if ($col === ($column['sortable'] ?? null)): ?>
                            <?php if ($order === 'DESC'): ?>
                                <?php icon('chevron-up', '#aaa') ?>
                            <?php else: ?>
                                <?php icon('chevron-down', '#aaa') ?>
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
                <?php $id = dataGet($row, 'id'); ?>
                <tr>
                    <?php foreach ($columns as $key => $column): ?>
                        <td scope="row">
                            <?php if (isset($column['value'])): ?>
                                <?php if (is_callable($column['value'])): ?>
                                    <?= $column['value']($row) ?>
                                <?php else: ?>
                                    <?php
                                    $value = dataGet($row, $column['value']);
                                    if ($value) {
                                        echo e($value);
                                    } else if (isset($column['default'])) {
                                        echo e($column['default']);
                                    }
                                    ?>
                                <?php endif; ?>
                            <?php elseif (isset($column['default'])): ?>
                                <?= e($column['default']) ?>
                            <?php endif; ?>
                        </td>
                    <?php endforeach; ?>
                    <td class="actions">
                        <?php foreach ($actions as $key => $action): ?>
                            <?php
                            $url = is_callable($action['url']) ? $action['url']($row) : ($action['url'] ?? Helpers::currentUrl(false));
                            $method = $action['method'] ?? 'GET';
                            ?>
                            <form action="<?= $url ?>" method="<?= $method ?>">
                                <?php component('button', [
                                    'type' => 'submit',
                                    'label' => $action['name'] ?? '',
                                    'icon' => $action['icon'] ?? null,
                                    'color' => $action['color'] ?? '',
                                    'outline' => $action['outline'] ?? '',
                                ]); ?>
                            </form>
                        <?php endforeach; ?>
                    </td>
                </tr>

            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <!-- +1 column for actions -->
                <td colspan="<?= count($columns) + 1 ?>">
                    <div class="no-data">
                        No items found
                    </div>
                </td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>