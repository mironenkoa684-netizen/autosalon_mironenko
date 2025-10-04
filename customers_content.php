<div class="actions">
    <a href="?page=add_customer" class="btn">Добавить клиента</a>
</div>

<h2>Список клиентов</h2>

<?php if (empty($customers)): ?>
    <p>Клиенты отсутствуют в базе данных.</p>
<?php else: ?>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>ФИО</th>
                <th>Телефон</th>
                <th>Email</th>
                <th>Адрес</th>
                <th>Действия</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($customers as $customer): ?>
            <tr>
                <td><?= htmlspecialchars($customer['id']) ?></td>
                <td><?= htmlspecialchars($customer['full_name']) ?></td>
                <td><?= htmlspecialchars($customer['phone']) ?></td>
                <td><?= htmlspecialchars($customer['email']) ?></td>
                <td><?= htmlspecialchars($customer['address']) ?></td>
                <td>
                    <a href="?delete=customer&id=<?= $customer['id'] ?>" class="btn-delete" onclick="return confirm('Вы уверены, что хотите удалить этого клиента?')">Удалить</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>