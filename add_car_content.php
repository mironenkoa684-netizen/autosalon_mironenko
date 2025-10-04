<h2>Добавление нового автомобиля</h2>

<form method="post" class="form">
    <input type="hidden" name="action" value="add_car">
    
    <div class="form-group">
        <label for="brand">Марка:</label>
        <input type="text" id="brand" name="brand" required>
    </div>
    
    <div class="form-group">
        <label for="model">Модель:</label>
        <input type="text" id="model" name="model" required>
    </div>
    
    <div class="form-group">
        <label for="year">Год выпуска:</label>
        <input type="number" id="year" name="year" min="1990" max="2023" required>
    </div>
    
    <div class="form-group">
        <label for="color">Цвет:</label>
        <input type="text" id="color" name="color" required>
    </div>
    
    <div class="form-group">
        <label for="price">Цена (руб.):</label>
        <input type="number" id="price" name="price" min="0" step="1000" required>
    </div>
    
    <div class="form-group">
        <label for="in_stock">
            <input type="checkbox" id="in_stock" name="in_stock" checked>
            В наличии
        </label>
    </div>
    
    <div class="form-group">
        <button type="submit" class="btn">Добавить автомобиль</button>
        <a href="?page=cars" class="btn-cancel">Отмена</a>
    </div>
</form>