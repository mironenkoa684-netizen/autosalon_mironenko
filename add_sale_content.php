<h2>Оформление продажи автомобиля</h2>

<form method="post" class="form">
    <input type="hidden" name="action" value="add_sale">
    
    <div class="form-group">
        <label for="car_id">Автомобиль:</label>
        <select id="car_id" name="car_id" required>
            <option value="">Выберите автомобиль</option>
            <?php foreach ($available_cars as $car): ?>
            <option value="<?= $car['id'] ?>" data-price="<?= $car['price'] ?>">
                <?= htmlspecialchars($car['brand']) ?> <?= htmlspecialchars($car['model']) ?> (<?= $car['year'] ?> год) - <?= number_format($car['price'], 0, ',', ' ') ?> руб.
            </option>
            <?php endforeach; ?>
        </select>
    </div>
    
    <div class="form-group">
        <label for="customer_id">Клиент:</label>
        <select id="customer_id" name="customer_id" required>
            <option value="">Выберите клиента</option>
            <?php foreach ($customers as $customer): ?>
            <option value="<?= $customer['id'] ?>"><?= htmlspecialchars($customer['full_name']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    
    <div class="form-group">
        <label for="price">Цена продажи (руб.):</label>
        <input type="number" id="price" name="price" min="0" step="1000" required>
    </div>
    
    <div class="form-group">
        <label for="sale_date">Дата продажи:</label>
        <input type="date" id="sale_date" name="sale_date" value="<?= date('Y-m-d') ?>" required>
    </div>
    
    <div class="form-group">
        <button type="submit" class="btn">Оформить продажу</button>
        <a href="?page=sales" class="btn-cancel">Отмена</a>
    </div>
</form>