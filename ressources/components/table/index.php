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
                    $params = [
                        'sort' => [
                            [
                                'column' => $column['sortable'] ?? '',
                                'order' => (isset($sort[0]['order']) && $sort[0]['order'] === 'DESC') ? 'ASC' : 'DESC',
                            ],
                        ],
                    ];

                    $isSorted = (
                        isset($sort[0]['column']) &&
                        $sort[0]['column'] === ($column['sortable'] ?? null)
                    );

                    $url = Helpers::buildUrl(null, $params);
                    ?>
                    <a href="<?= $url ?>">
                        <span>
                            <?= Helpers::e($column['name']) ?>
                        </span>
                        <span>
                            <?php if ($isSorted): ?>
                                <?php if (isset($sort[0]['order']) && $sort[0]['order'] === 'DESC'): ?>
                                    <?php Icon::display('chevron-up') ?>
                                <?php else: ?>
                                    <?php Icon::display('chevron-down') ?>
                                <?php endif; ?>
                            <?php endif; ?>
                        </span>
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
                            <?php if (isset($column['fn'])): ?>
                                <?= $column['fn']($row) ?>
                            <?php elseif (isset($column['value'])): ?>
                                <?php
                                $value = dataGet($row, $column['value']);
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