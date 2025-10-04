<div class="actions">
    <a href="?page=add_sale" class="btn">Оформить продажу</a>
</div>

<h2>Список продаж</h2>

<?php if (empty($sales)): ?>
    <p>Продажи отсутствуют в базе данных.</p>
<?php else: ?>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Дата продажи</th>
                <th>Клиент</th>
                <th>Автомобиль</th>
                <th>Год выпуска</th>
                <th>Цена продажи (руб.)</th>
                <th>Действия</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($sales as $sale): ?>
            <tr>
                <td><?= htmlspecialchars($sale['id']) ?></td>
                <td><?= htmlspecialchars($sale['sale_date']) ?></td>
                <td><?= htmlspecialchars($sale['customer_name']) ?></td>
                <td><?= htmlspecialchars($sale['brand']) ?> <?= htmlspecialchars($sale['model']) ?></td>
                <td><?= htmlspecialchars($sale['year']) ?></td>
                <td><?= number_format($sale['price'], 0, ',', ' ') ?></td>
                <td>
                    <a href="?delete=sale&id=<?= $sale['id'] ?>" class="btn-delete" onclick="return confirm('Вы уверены, что хотите удалить эту запись о продаже?')">Удалить</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>