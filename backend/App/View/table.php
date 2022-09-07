<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<table class="table">
    <thead>
        <tr>
            <th scope="col">Номер места</th>
            <th scope="col">Имя пилота</th>
            <th scope="col">Город пилота</th>
            <th scope="col">Автомобиль</th>
            <th scope="col">Попытка #1</th>
            <th scope="col">Попытка #2</th>
            <th scope="col">Попытка #3</th>
            <th scope="col">Попытка #4</th>
            <th scope="col">Сумма очков</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($result as $place => $rowData): ?>
            <tr>
                <td><?= $place + 1 ?></td>
                <td><?= $rowData['pilotName'] ?></td>
                <td><?= $rowData['pilotCity'] ?></td>
                <td><?= $rowData['pilotCar'] ?></td>
                <td><?= $rowData['attempt1'] ?></td>
                <td><?= $rowData['attempt2'] ?></td>
                <td><?= $rowData['attempt3'] ?></td>
                <td><?= $rowData['attempt4'] ?></td>
                <td><?= $rowData['totalResult'] ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<form action="" method="post">
    <input type="submit" name="saveResult" value="Пересчитать и сохранить">
</form>