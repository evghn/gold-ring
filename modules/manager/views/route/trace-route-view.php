<?php
    $row = 1;
    date_default_timezone_set('UTC');
?>
<table class="table table-striped table-hover">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">Населенный пункт</th>
      <th scope="col" class="text-center">Время прибытия</th>
      <th scope="col" class="text-center">Время отправления</th>
      <th scope="col" class="text-center">Стоянка</th>
    </tr>
  </thead>
  <tbody>
    <tr>
        <th scope="row"><?= $row++ ?></th>
        <td><?= $model->pointStart->title ?></td>
        <td class="text-center"></td>
        <td class="text-center"><?= $model->time_start ?></td>
        <td class="text-center"></td>
    </tr>
    <?php foreach ($modelItem as $item): ?>
        <tr>
            <th scope="row"><?= $row++ ?></th>
            <td><?= $item->point->title ?></td>
            <td class="text-center"><?= date('H:i:s', $item->time_visit) ?></td>
            <td class="text-center"><?= date('H:i:s', $item->time_out) ?></td>
            <td class="text-center"><?= $item->time_pause ?></td>
        </tr>
    <?php endforeach ?>    
    <tr>
        <th scope="row"><?= $row++ ?></th>
        <td><?= $model->pointEnd->title ?></td>
        <td class="text-center"><?= date('H:i:s', $model->time_end) ?></td>
        <td class="text-center"></td>
        <td class="text-center"></td>
    </tr>

    
    
  </tbody>
</table>