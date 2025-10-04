<?php
session_start();

// Настройки базы данных
$host = 'localhost';
$dbname = 'ais_autosalon';
$username = 'root';
$password = 'root';

// Подключение к БД
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Ошибка подключения к базе данных: " . $e->getMessage());
}

// Создание таблиц если они не существуют
function createTables($pdo) {
    $queries = [
        "CREATE TABLE IF NOT EXISTS cars (
            id INT AUTO_INCREMENT PRIMARY KEY,
            brand VARCHAR(100) NOT NULL,
            model VARCHAR(100) NOT NULL,
            year INT NOT NULL,
            color VARCHAR(50) NOT NULL,
            price DECIMAL(10, 2) NOT NULL,
            in_stock BOOLEAN DEFAULT TRUE,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )",
        
        "CREATE TABLE IF NOT EXISTS customers (
            id INT AUTO_INCREMENT PRIMARY KEY,
            full_name VARCHAR(200) NOT NULL,
            phone VARCHAR(20) NOT NULL,
            email VARCHAR(100),
            address TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )",
        
        "CREATE TABLE IF NOT EXISTS sales (
            id INT AUTO_INCREMENT PRIMARY KEY,
            car_id INT NOT NULL,
            customer_id INT NOT NULL,
            price DECIMAL(10, 2) NOT NULL,
            sale_date DATE NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )"
    ];
    
    foreach ($queries as $query) {
        $pdo->exec($query);
    }
    
    // Добавление тестовых данных если таблицы пустые
    addTestData($pdo);
}

// Добавление тестовых данных
function addTestData($pdo) {
    // Проверяем, есть ли уже данные
    $carsCount = $pdo->query("SELECT COUNT(*) FROM cars")->fetchColumn();
    
    if ($carsCount == 0) {
        // Тестовые автомобили
        $cars = [
            ['Toyota', 'Camry', 2022, 'Черный', 2500000.00, 1],
            ['Honda', 'Civic', 2021, 'Белый', 1800000.00, 1],
            ['BMW', 'X5', 2023, 'Синий', 5500000.00, 1],
            ['Mercedes-Benz', 'E-Class', 2022, 'Серый', 4800000.00, 1],
            ['Audi', 'A4', 2021, 'Красный', 3200000.00, 0],
            ['Hyundai', 'Solaris', 2023, 'Белый', 1200000.00, 1],
            ['Kia', 'Rio', 2022, 'Серебристый', 1100000.00, 0],
            ['Volkswagen', 'Tiguan', 2023, 'Черный', 2200000.00, 1]
        ];
        
        $stmt = $pdo->prepare("INSERT INTO cars (brand, model, year, color, price, in_stock) VALUES (?, ?, ?, ?, ?, ?)");
        foreach ($cars as $car) {
            $stmt->execute($car);
        }
        
        // Тестовые клиенты
        $customers = [
            ['Иванов Иван Иванович', '+7 (912) 345-67-89', 'ivanov@example.com', 'г. Москва, ул. Ленина, д. 10, кв. 5'],
            ['Петров Петр Петрович', '+7 (923) 456-78-90', 'petrov@example.com', 'г. Санкт-Петербург, ул. Пушкина, д. 25, кв. 12'],
            ['Сидорова Мария Сергеевна', '+7 (934) 567-89-01', 'sidorova@example.com', 'г. Екатеринбург, пр. Космонавтов, д. 15, кв. 8'],
            ['Кузнецов Алексей Викторович', '+7 (945) 678-90-12', 'kuznetsov@example.com', 'г. Новосибирск, ул. Мира, д. 30, кв. 3']
        ];
        
        $stmt = $pdo->prepare("INSERT INTO customers (full_name, phone, email, address) VALUES (?, ?, ?, ?)");
        foreach ($customers as $customer) {
            $stmt->execute($customer);
        }
        
        // Тестовые продажи
        $sales = [
            [5, 1, 3150000.00, '2023-07-10'],
            [7, 2, 1050000.00, '2023-08-15']
        ];
        
        $stmt = $pdo->prepare("INSERT INTO sales (car_id, customer_id, price, sale_date) VALUES (?, ?, ?, ?)");
        foreach ($sales as $sale) {
            $stmt->execute($sale);
        }
    }
}

// Создаем таблицы при запуске
createTables($pdo);

// Обработка POST запросов
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    switch ($action) {
        case 'add_car':
            $brand = $_POST['brand'];
            $model = $_POST['model'];
            $year = $_POST['year'];
            $color = $_POST['color'];
            $price = $_POST['price'];
            $in_stock = isset($_POST['in_stock']) ? 1 : 0;
            
            $stmt = $pdo->prepare("INSERT INTO cars (brand, model, year, color, price, in_stock) VALUES (?, ?, ?, ?, ?, ?)");
            if ($stmt->execute([$brand, $model, $year, $color, $price, $in_stock])) {
                $_SESSION['success'] = "Автомобиль успешно добавлен!";
            } else {
                $_SESSION['error'] = "Ошибка при добавлении автомобиля!";
            }
            header('Location: ' . $_SERVER['PHP_SELF'] . '?page=cars');
            exit;
            
        case 'add_customer':
            $full_name = $_POST['full_name'];
            $phone = $_POST['phone'];
            $email = $_POST['email'] ?? '';
            $address = $_POST['address'] ?? '';
            
            $stmt = $pdo->prepare("INSERT INTO customers (full_name, phone, email, address) VALUES (?, ?, ?, ?)");
            if ($stmt->execute([$full_name, $phone, $email, $address])) {
                $_SESSION['success'] = "Клиент успешно добавлен!";
            } else {
                $_SESSION['error'] = "Ошибка при добавлении клиента!";
            }
            header('Location: ' . $_SERVER['PHP_SELF'] . '?page=customers');
            exit;
            
        case 'add_sale':
            $car_id = $_POST['car_id'];
            $customer_id = $_POST['customer_id'];
            $price = $_POST['price'];
            $sale_date = $_POST['sale_date'];
            
            $pdo->beginTransaction();
            try {
                $stmt = $pdo->prepare("INSERT INTO sales (car_id, customer_id, price, sale_date) VALUES (?, ?, ?, ?)");
                $stmt->execute([$car_id, $customer_id, $price, $sale_date]);
                
                $update_stmt = $pdo->prepare("UPDATE cars SET in_stock = 0 WHERE id = ?");
                $update_stmt->execute([$car_id]);
                
                $pdo->commit();
                $_SESSION['success'] = "Продажа успешно оформлена!";
            } catch (Exception $e) {
                $pdo->rollBack();
                $_SESSION['error'] = "Ошибка при оформлении продажи: " . $e->getMessage();
            }
            header('Location: ' . $_SERVER['PHP_SELF'] . '?page=sales');
            exit;
    }
}

// Обработка GET запросов на удаление
if (isset($_GET['delete'])) {
    $type = $_GET['delete'];
    $id = $_GET['id'] ?? 0;
    
    switch ($type) {
        case 'car':
            $check_stmt = $pdo->prepare("SELECT COUNT(*) FROM sales WHERE car_id = ?");
            $check_stmt->execute([$id]);
            $sales_count = $check_stmt->fetchColumn();
            
            if ($sales_count > 0) {
                $_SESSION['error'] = "Невозможно удалить автомобиль, так как он связан с продажами.";
            } else {
                $stmt = $pdo->prepare("DELETE FROM cars WHERE id = ?");
                if ($stmt->execute([$id])) {
                    $_SESSION['success'] = "Автомобиль успешно удален!";
                } else {
                    $_SESSION['error'] = "Ошибка при удалении автомобиля!";
                }
            }
            header('Location: ' . $_SERVER['PHP_SELF'] . '?page=cars');
            exit;
            
        case 'customer':
            $check_stmt = $pdo->prepare("SELECT COUNT(*) FROM sales WHERE customer_id = ?");
            $check_stmt->execute([$id]);
            $sales_count = $check_stmt->fetchColumn();
            
            if ($sales_count > 0) {
                $_SESSION['error'] = "Невозможно удалить клиента, так как он связан с продажами.";
            } else {
                $stmt = $pdo->prepare("DELETE FROM customers WHERE id = ?");
                if ($stmt->execute([$id])) {
                    $_SESSION['success'] = "Клиент успешно удален!";
                } else {
                    $_SESSION['error'] = "Ошибка при удалении клиента!";
                }
            }
            header('Location: ' . $_SERVER['PHP_SELF'] . '?page=customers');
            exit;
            
        case 'sale':
            $pdo->beginTransaction();
            try {
                $sale_stmt = $pdo->prepare("SELECT car_id FROM sales WHERE id = ?");
                $sale_stmt->execute([$id]);
                $sale = $sale_stmt->fetch(PDO::FETCH_ASSOC);
                
                if ($sale) {
                    $delete_stmt = $pdo->prepare("DELETE FROM sales WHERE id = ?");
                    $delete_stmt->execute([$id]);
                    
                    $update_stmt = $pdo->prepare("UPDATE cars SET in_stock = 1 WHERE id = ?");
                    $update_stmt->execute([$sale['car_id']]);
                    
                    $pdo->commit();
                    $_SESSION['success'] = "Запись о продаже успешно удалена!";
                } else {
                    $_SESSION['error'] = "Запись о продаже не найдена!";
                }
            } catch (Exception $e) {
                $pdo->rollBack();
                $_SESSION['error'] = "Ошибка при удалении записи о продаже: " . $e->getMessage();
            }
            header('Location: ' . $_SERVER['PHP_SELF'] . '?page=sales');
            exit;
    }
}

// Определяем текущую страницу
$page = $_GET['page'] ?? 'home';

// Получаем данные для разных страниц
$cars = [];
$customers = [];
$sales = [];
$available_cars = [];

if ($page === 'cars' || $page === 'add_sale') {
    $stmt = $pdo->query("SELECT * FROM cars ORDER BY brand, model");
    $cars = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

if ($page === 'customers' || $page === 'add_sale') {
    $stmt = $pdo->query("SELECT * FROM customers ORDER BY full_name");
    $customers = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

if ($page === 'sales') {
    $stmt = $pdo->query("
        SELECT s.id, s.sale_date, s.price, 
               c.full_name as customer_name, 
               car.brand, car.model, car.year
        FROM sales s
        JOIN customers c ON s.customer_id = c.id
        JOIN cars car ON s.car_id = car.id
        ORDER BY s.sale_date DESC
    ");
    $sales = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

if ($page === 'add_sale') {
    $stmt = $pdo->query("SELECT id, brand, model, year, price FROM cars WHERE in_stock = 1");
    $available_cars = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>АИС Автосалон</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f4f4f4;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        header {
            background-color: #2c3e50;
            color: white;
            padding: 1rem 0;
            margin-bottom: 2rem;
        }

        header h1 {
            text-align: center;
            margin-bottom: 1rem;
        }

        nav ul {
            display: flex;
            justify-content: center;
            list-style: none;
            flex-wrap: wrap;
        }

        nav ul li {
            margin: 5px 15px;
        }

        nav ul li a {
            color: white;
            text-decoration: none;
            font-weight: bold;
            padding: 5px 10px;
            border-radius: 3px;
            transition: background-color 0.3s;
            display: block;
        }

        nav ul li a:hover, nav ul li a.active {
            background-color: #34495e;
        }

        main {
            min-height: 70vh;
            margin-bottom: 2rem;
        }

        .welcome {
            text-align: center;
            margin-bottom: 2rem;
        }

        .welcome h2 {
            margin-bottom: 1rem;
            color: #2c3e50;
        }

        .dashboard {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-top: 2rem;
        }

        .card {
            background: white;
            padding: 1.5rem;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            text-align: center;
        }

        .card h3 {
            margin-bottom: 1rem;
            color: #2c3e50;
        }

        .actions {
            margin-bottom: 1.5rem;
        }

        .btn {
            display: inline-block;
            background-color: #3498db;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s;
            font-size: 14px;
        }

        .btn:hover {
            background-color: #2980b9;
        }

        .btn-delete {
            display: inline-block;
            background-color: #e74c3c;
            color: white;
            padding: 5px 10px;
            text-decoration: none;
            border-radius: 3px;
            font-size: 0.9rem;
        }

        .btn-delete:hover {
            background-color: #c0392b;
        }

        .btn-cancel {
            display: inline-block;
            background-color: #95a5a6;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            margin-left: 10px;
        }

        .btn-cancel:hover {
            background-color: #7f8c8d;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            border-radius: 5px;
            overflow: hidden;
            margin-bottom: 1rem;
        }

        table th, table td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        table th {
            background-color: #2c3e50;
            color: white;
        }

        table tr:hover {
            background-color: #f5f5f5;
        }

        .form {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            padding: 2rem;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: bold;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1rem;
        }

        .form-group textarea {
            min-height: 100px;
            resize: vertical;
        }

        .error {
            background-color: #e74c3c;
            color: white;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 1rem;
        }

        .success {
            background-color: #2ecc71;
            color: white;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 1rem;
        }

        footer {
            background-color: #2c3e50;
            color: white;
            text-align: center;
            padding: 1rem 0;
            margin-top: 2rem;
        }

        @media (max-width: 768px) {
            .dashboard {
                grid-template-columns: 1fr;
            }
            
            nav ul {
                flex-direction: column;
                align-items: center;
            }
            
            table {
                display: block;
                overflow-x: auto;
            }
            
            .btn, .btn-cancel {
                display: block;
                margin: 5px 0;
                text-align: center;
            }
            
            .btn-cancel {
                margin-left: 0;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>АИС Автосалон</h1>
            <nav>
                <ul>
                    <li><a href="?page=home" class="<?= $page === 'home' ? 'active' : '' ?>">Главная</a></li>
                    <li><a href="?page=cars" class="<?= $page === 'cars' ? 'active' : '' ?>">Автомобили</a></li>
                    <li><a href="?page=customers" class="<?= $page === 'customers' ? 'active' : '' ?>">Клиенты</a></li>
                    <li><a href="?page=sales" class="<?= $page === 'sales' ? 'active' : '' ?>">Продажи</a></li>
                </ul>
            </nav>
        </header>
        
        <main>
            <?php if (isset($_SESSION['success'])): ?>
                <div class="success"><?= $_SESSION['success'] ?></div>
                <?php unset($_SESSION['success']); ?>
            <?php endif; ?>
            
            <?php if (isset($_SESSION['error'])): ?>
                <div class="error"><?= $_SESSION['error'] ?></div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>
            
            <?php
            switch ($page) {
                case 'home':
                    include 'home_content.php';
                    break;
                case 'cars':
                    include 'cars_content.php';
                    break;
                case 'customers':
                    include 'customers_content.php';
                    break;
                case 'sales':
                    include 'sales_content.php';
                    break;
                case 'add_car':
                    include 'add_car_content.php';
                    break;
                case 'add_customer':
                    include 'add_customer_content.php';
                    break;
                case 'add_sale':
                    include 'add_sale_content.php';
                    break;
                default:
                    include 'home_content.php';
            }
            ?>
        </main>
        
        <footer>
            <p>&copy; 2025 АИС Автосалон. Все права защищены.</p>
        </footer>
    </div>

    <script>
        // Автоматическое заполнение цены при выборе автомобиля в форме продажи
        document.addEventListener('DOMContentLoaded', function() {
            var carSelect = document.getElementById('car_id');
            var priceInput = document.getElementById('price');
            
            if (carSelect && priceInput) {
                carSelect.addEventListener('change', function() {
                    var selectedOption = this.options[this.selectedIndex];
                    var price = selectedOption.getAttribute('data-price');
                    if (price) {
                        priceInput.value = price;
                    }
                });
            }
        });
    </script>
</body>
</html>