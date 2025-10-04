<h2>Добавление нового клиента</h2>

<form method="post" class="form">
    <input type="hidden" name="action" value="add_customer">
    
    <div class="form-group">
        <label for="full_name">ФИО:</label>
        <input type="text" id="full_name" name="full_name" required>
    </div>
    
    <div class="form-group">
        <label for="phone">Телефон:</label>
        <input type="text" id="phone" name="phone" required>
    </div>
    
    <div class="form-group">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email">
    </div>
    
    <div class="form-group">
        <label for="address">Адрес:</label>
        <textarea id="address" name="address"></textarea>
    </div>
    
    <div class="form-group">
        <button type="submit" class="btn">Добавить клиента</button>
        <a href="?page=customers" class="btn-cancel">Отмена</a>
    </div>
</form>