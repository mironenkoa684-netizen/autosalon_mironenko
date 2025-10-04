-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Окт 04 2025 г., 03:01
-- Версия сервера: 8.0.19
-- Версия PHP: 7.1.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `ais_autosalon`
--

-- --------------------------------------------------------

--
-- Структура таблицы `cars`
--

CREATE TABLE `cars` (
  `id` int NOT NULL,
  `brand` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `year` int NOT NULL,
  `color` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `in_stock` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `cars`
--

INSERT INTO `cars` (`id`, `brand`, `model`, `year`, `color`, `price`, `in_stock`, `created_at`) VALUES
(1, 'Toyota', 'Camry', 2022, 'Черный', '2500000.00', 0, '2025-10-02 22:32:44'),
(2, 'Honda', 'Civic', 2021, 'Белый', '1800000.00', 1, '2025-10-02 22:32:44'),
(3, 'BMW', 'X5', 2023, 'Синий', '5500000.00', 0, '2025-10-02 22:32:44'),
(4, 'Mercedes-Benz', 'E-Class', 2022, 'Серый', '4800000.00', 1, '2025-10-02 22:32:44'),
(5, 'Audi', 'A4', 2021, 'Красный', '3200000.00', 0, '2025-10-02 22:32:44'),
(6, 'Hyundai', 'Solaris', 2023, 'Белый', '1200000.00', 1, '2025-10-02 22:32:44'),
(7, 'Kia', 'Rio', 2022, 'Серебристый', '1100000.00', 1, '2025-10-02 22:32:44'),
(8, 'Volkswagen', 'Tiguan', 2023, 'Черный', '2200000.00', 1, '2025-10-02 22:32:44');

-- --------------------------------------------------------

--
-- Структура таблицы `customers`
--

CREATE TABLE `customers` (
  `id` int NOT NULL,
  `full_name` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `customers`
--

INSERT INTO `customers` (`id`, `full_name`, `phone`, `email`, `address`, `created_at`) VALUES
(1, 'Иванов Иван Иванович', '+7 (912) 345-67-89', 'ivanov@example.com', 'г. Москва, ул. Ленина, д. 10, кв. 5', '2025-10-02 22:32:44'),
(2, 'Петров Петр Петрович', '+7 (923) 456-78-90', 'petrov@example.com', 'г. Санкт-Петербург, ул. Пушкина, д. 25, кв. 12', '2025-10-02 22:32:44'),
(3, 'Сидорова Мария Сергеевна', '+7 (934) 567-89-01', 'sidorova@example.com', 'г. Екатеринбург, пр. Космонавтов, д. 15, кв. 8', '2025-10-02 22:32:44'),
(4, 'Кузнецов Алексей Викторович', '+7 (945) 678-90-12', 'kuznetsov@example.com', 'г. Новосибирск, ул. Мира, д. 30, кв. 3', '2025-10-02 22:32:44'),
(5, 'Смирнова Ольга Дмитриевна', '+7 (956) 789-01-23', 'smirnova@example.com', 'г. Казань, ул. Гагарина, д. 5, кв. 17', '2025-10-02 22:32:44');

-- --------------------------------------------------------

--
-- Структура таблицы `sales`
--

CREATE TABLE `sales` (
  `id` int NOT NULL,
  `car_id` int NOT NULL,
  `customer_id` int NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `sale_date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `sales`
--

INSERT INTO `sales` (`id`, `car_id`, `customer_id`, `price`, `sale_date`, `created_at`) VALUES
(1, 1, 1, '2450000.00', '2023-05-15', '2025-10-02 22:32:44'),
(2, 3, 2, '5400000.00', '2023-06-20', '2025-10-02 22:32:44'),
(3, 5, 3, '3150000.00', '2023-07-10', '2025-10-02 22:32:44');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `cars`
--
ALTER TABLE `cars`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`id`),
  ADD KEY `car_id` (`car_id`),
  ADD KEY `customer_id` (`customer_id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `cars`
--
ALTER TABLE `cars`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT для таблицы `customers`
--
ALTER TABLE `customers`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT для таблицы `sales`
--
ALTER TABLE `sales`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `sales`
--
ALTER TABLE `sales`
  ADD CONSTRAINT `sales_ibfk_1` FOREIGN KEY (`car_id`) REFERENCES `cars` (`id`) ON DELETE RESTRICT,
  ADD CONSTRAINT `sales_ibfk_2` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE RESTRICT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
