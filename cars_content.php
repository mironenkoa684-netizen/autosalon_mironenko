<div class="actions">
    <a href="?page=add_car" class="btn">Добавить автомобиль</a>
</div>

<h2>Список автомобилей</h2>

<?php if (empty($cars)): ?>
    <p>Автомобили отсутствуют в базе данных.</p>
<?php else: ?>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Марка</th>
                <th>Модель</th>
                <th>Год выпуска</th>
                <th>Цвет</th>
                <th>Цена (руб.)</th>
                <th>В наличии</th>
                <th>Действия</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($cars as $car): ?>
            <tr>
                <td><?= htmlspecialchars($car['id']) ?></td>
                <td><?= htmlspecialchars($car['brand']) ?></td>
                <td><?= htmlspecialchars($car['model']) ?></td>
                <td><?= htmlspecialchars($car['year']) ?></td>
                <td><?= htmlspecialchars($car['color']) ?></td>
                <td><?= number_format($car['price'], 0, ',', ' ') ?></td>
                <td><?= $car['in_stock'] ? 'Да' : 'Нет' ?></td>
                <td>
                    <a href="?delete=car&id=<?= $car['id'] ?>" class="btn-delete" onclick="return confirm('Вы уверены, что хотите удалить этот автомобиль?')">Удалить</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>