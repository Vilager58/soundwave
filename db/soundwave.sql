-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Хост: localhost:3306
-- Время создания: Сен 14 2026 г., 17:17
-- Версия сервера: 8.4.3
-- Версия PHP: 8.3.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `soundwave`
--

-- --------------------------------------------------------

--
-- Структура таблицы `attributes`
--

CREATE TABLE `attributes` (
  `id` int NOT NULL,
  `code` varchar(50) NOT NULL COMMENT 'Системный код характеристики (напр. frequency_range, impedance)',
  `title` varchar(100) NOT NULL COMMENT 'Отображаемое название (напр. Диапазон частот, Импеданс)',
  `unit` varchar(30) DEFAULT NULL COMMENT 'Единица измерения (Ом, Гц, ч, дБ, мм, г)',
  `data_type` enum('string','number','boolean') NOT NULL DEFAULT 'string' COMMENT 'Тип хранимых данных',
  `is_filterable` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'Использовать ли в фильтрах каталога'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='Справочник характеристик товаров';

--
-- Дамп данных таблицы `attributes`
--

INSERT INTO `attributes` (`id`, `code`, `title`, `unit`, `data_type`, `is_filterable`) VALUES
(1, 'frequency_response', 'Частотный диапазон', 'Гц', 'string', 1),
(2, 'impedance', 'Импеданс (Сопротивление)', 'Ом', 'number', 1),
(3, 'anc', 'Активное шумоподавление (ANC)', NULL, 'boolean', 1),
(4, 'battery_life', 'Время работы от аккумулятора', 'ч', 'number', 1),
(5, 'connection_type', 'Тип подключения', NULL, 'string', 1),
(6, 'weight', 'Вес', 'г', 'number', 1),
(7, 'microphone', 'Наличие микрофона', NULL, 'boolean', 1),
(8, 'bluetooth_codecs', 'Поддерживаемые аудиокодеки', NULL, 'string', 1);

-- --------------------------------------------------------

--
-- Структура таблицы `brands`
--

CREATE TABLE `brands` (
  `id` int NOT NULL,
  `title` varchar(100) NOT NULL COMMENT 'Название бренда',
  `slug` varchar(100) NOT NULL COMMENT 'ЧПУ для бренда',
  `country` varchar(60) DEFAULT NULL COMMENT 'Страна происхождения бренда',
  `logo_url` varchar(255) DEFAULT NULL COMMENT 'Ссылка на логотип',
  `description` text,
  `is_active` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='Бренды аудиотехники';

--
-- Дамп данных таблицы `brands`
--

INSERT INTO `brands` (`id`, `title`, `slug`, `country`, `logo_url`, `description`, `is_active`) VALUES
(1, 'Sennheiser', 'sennheiser', 'Германия', '/assets/brands/sennheiser.svg', 'Легендарный немецкий производитель премиальной аудиоаппаратуры и микрофонов с 1945 года.', 1),
(2, 'Sony', 'sony', 'Япония', '/assets/brands/sony.svg', 'Мировой технологический гигант, признанный лидер в технологиях активного шумоподавления и аудио высокой четкости Hi-Res.', 1),
(3, 'Marshall', 'marshall', 'Великобритания', '/assets/brands/marshall.svg', 'Культовый британский рок-н-ролльный бренд с фирменным винтажным гитарным дизайном и мощным драйвовым звуком.', 1),
(4, 'Audio-Technica', 'audio-technica', 'Япония', '/assets/brands/audio-technica.svg', 'Один из главных мировых производителей картриджей, проигрывателей винила и легендарных мониторных наушников.', 1),
(5, 'Beyerdynamic', 'beyerdynamic', 'Германия', '/assets/brands/beyerdynamic.svg', 'Изобретатели динамических наушников, эталон студийного звука ручной сборки в Хайльбронне.', 1),
(6, 'Shure', 'shure', 'США', '/assets/brands/shure.svg', 'Мировой золотой стандарт вокальных и вещательных микрофонов.', 1);

-- --------------------------------------------------------

--
-- Структура таблицы `categories`
--

CREATE TABLE `categories` (
  `id` int NOT NULL,
  `parent_id` int DEFAULT NULL COMMENT 'Родительская категория для создания подкатегорий',
  `title` varchar(100) NOT NULL COMMENT 'Название категории',
  `slug` varchar(100) NOT NULL COMMENT 'ЧПУ для URL каталога',
  `description` text,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `sort_order` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='Категории товаров';

--
-- Дамп данных таблицы `categories`
--

INSERT INTO `categories` (`id`, `parent_id`, `title`, `slug`, `description`, `is_active`, `sort_order`) VALUES
(1, NULL, 'Наушники', 'headphones', 'Полноразмерные, внутриканальные, беспроводные и аудиофильские наушники', 1, 10),
(2, 1, 'Полноразмерные наушники', 'over-ear-headphones', 'Охватывающие наушники для дома, улицы и звукозаписывающих студий', 1, 11),
(3, 1, 'TWS и беспроводные наушники', 'tws-wireless-headphones', 'Компактные Bluetooth-наушники с шумоподавлением и защитой от влаги', 1, 12),
(4, NULL, 'Акустические системы', 'speakers', 'Беспроводные Bluetooth-колонки, полочная и напольная Hi-Fi акустика', 1, 20),
(5, NULL, 'Виниловые проигрыватели', 'turntables', 'Классические и современные проигрыватели винила, фонокорректоры, аксессуары', 1, 30),
(6, NULL, 'Студийное оборудование', 'studio-gear', 'Микрофоны, аудиоинтерфейсы, студийные мониторы и звуковые карты', 1, 40);

-- --------------------------------------------------------

--
-- Структура таблицы `delivery_methods`
--

CREATE TABLE `delivery_methods` (
  `id` int NOT NULL,
  `code` varchar(50) NOT NULL COMMENT 'Код метода: courier, express, pickup_store, cdek, russian_post',
  `title` varchar(100) NOT NULL COMMENT 'Название способа доставки',
  `description` text COMMENT 'Подробные условия и описание доставки',
  `base_cost` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT 'Базовая стоимость доставки в рублях',
  `free_shipping_threshold` decimal(10,2) DEFAULT NULL COMMENT 'Сумма заказа для бесплатной доставки (NULL если нет)',
  `estimated_days_min` int DEFAULT '1' COMMENT 'Минимальный срок доставки (дни)',
  `estimated_days_max` int DEFAULT '3' COMMENT 'Максимальный срок доставки (дни)',
  `requires_address` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1 - требуется домашний адрес, 0 - самовывоз',
  `is_active` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1 - доступен для выбора, 0 - отключен',
  `sort_order` int NOT NULL DEFAULT '0' COMMENT 'Порядок вывода в корзине'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='Способы доставки заказов';

--
-- Дамп данных таблицы `delivery_methods`
--

INSERT INTO `delivery_methods` (`id`, `code`, `title`, `description`, `base_cost`, `free_shipping_threshold`, `estimated_days_min`, `estimated_days_max`, `requires_address`, `is_active`, `sort_order`) VALUES
(1, 'pickup_store', 'Самовывоз из шоурума Soundwave', 'Бесплатно заберите заказ из нашего флагманского салона с предварительным прослушиванием в комнате акустики.', 0.00, NULL, 0, 1, 0, 1, 10),
(2, 'courier_standard', 'Курьерская доставка Soundwave', 'Бережная доставка собственной курьерской службой с возможностью проверки оборудования при получении.', 450.00, 15000.00, 1, 2, 1, 1, 20),
(3, 'courier_express', 'Экспресс-доставка за 2-3 часа', 'Срочная доставка день в день по городу на такси/срочным курьером для срочных подарков и студийных сессий.', 990.00, NULL, 0, 0, 1, 1, 30),
(4, 'cdek_pvz', 'Пункты выдачи СДЭК', 'Доставка в любой из 5000+ удобных пунктов выдачи и постаматов СДЭК по всей России.', 350.00, 10000.00, 2, 5, 1, 1, 40),
(5, 'russian_post', 'Почта России (1-й класс)', 'Доставка в отдаленные населенные пункты и регионы РФ ценной посылкой с трек-номером.', 500.00, 20000.00, 4, 10, 1, 1, 50);

-- --------------------------------------------------------

--
-- Структура таблицы `orders`
--

CREATE TABLE `orders` (
  `id` int NOT NULL,
  `order_number` varchar(32) NOT NULL COMMENT 'Клиентский номер заказа (напр. SW-2026-0001)',
  `user_id` int NOT NULL COMMENT 'Покупатель',
  `status_id` int NOT NULL DEFAULT '1' COMMENT 'Текущий статус заказа (FK -> order_statuses)',
  `delivery_method_id` int NOT NULL COMMENT 'Выбранный способ доставки (FK -> delivery_methods)',
  `pickup_point_id` int DEFAULT NULL COMMENT 'Выбранный пункт самовывоза (если способ доставки - самовывоз)',
  `address_id` int DEFAULT NULL COMMENT 'Выбранный адрес доставки из профиля пользователя (NULL при самовывозе)',
  `delivery_address_snapshot` text COMMENT 'Снимок адреса на момент оформления (для защиты от изменений в профиле)',
  `delivery_cost` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT 'Зафиксированная стоимость доставки для этого заказа',
  `tracking_number` varchar(100) DEFAULT NULL COMMENT 'Трек-номер курьерской службы / СДЭК / Почты',
  `payment_method_id` int NOT NULL COMMENT 'Выбранный способ оплаты',
  `payment_status_id` int NOT NULL DEFAULT '1' COMMENT 'Статус оплаты (FK -> payment_statuses)',
  `products_total` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT 'Сумма товаров в заказе',
  `discount_total` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT 'Примененная скидка по купону/акции',
  `final_total` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT 'Итоговая сумма к оплате (товары - скидка + доставка)',
  `customer_notes` text COMMENT 'Пожелания клиента к доставке',
  `manager_notes` text COMMENT 'Внутренние пометки менеджеров Soundwave',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='Заказы интернет-магазина';

--
-- Дамп данных таблицы `orders`
--

INSERT INTO `orders` (`id`, `order_number`, `user_id`, `status_id`, `delivery_method_id`, `pickup_point_id`, `address_id`, `delivery_address_snapshot`, `delivery_cost`, `tracking_number`, `payment_method_id`, `payment_status_id`, `products_total`, `discount_total`, `final_total`, `customer_notes`, `manager_notes`, `created_at`, `updated_at`) VALUES
(1, 'SW-2026-0001', 5, 7, 2, NULL, 1, 'г. Москва, ул. Тверская, д. 12, корп. 2, кв. 45, этаж 5, код 45К', 0.00, 'SW-EXP-770192', 1, 2, 37990.00, 0.00, 37990.00, 'Пожалуйста, позвоните за час до приезда.', 'Заказ вручен клиенту, оплачен онлайн картой', '2026-09-01 14:20:00', '2026-09-12 19:02:56'),
(2, 'SW-2026-0002', 6, 5, 1, 3, NULL, 'г. Санкт-Петербург, Невский проспект, д. 54 (Шоурум Soundwave СПб)', 0.00, NULL, 3, 1, 41990.00, 0.00, 41990.00, 'Заберу в пятницу вечером после работы.', 'Товар зарезервирован на складе в СПб', '2026-09-10 11:45:00', '2026-09-12 19:02:56'),
(3, 'SW-2026-0003', 5, 3, 4, NULL, NULL, 'г. Москва, ПВЗ СДЭК ул. Тверская, д. 9', 350.00, 'CDEK-882910411', 2, 2, 18990.00, 0.00, 19340.00, 'Срочно нужно для работы на студии.', 'Оплачено по СБП, сформирована накладная СДЭК', '2026-09-12 18:30:00', '2026-09-12 19:02:56');

-- --------------------------------------------------------

--
-- Структура таблицы `order_items`
--

CREATE TABLE `order_items` (
  `id` int NOT NULL,
  `order_id` int NOT NULL,
  `variant_id` int NOT NULL,
  `amount` int NOT NULL COMMENT 'Количество заказанных штук',
  `unit_price` decimal(10,2) NOT NULL COMMENT 'Зафиксированная цена за 1 шт. на момент оформления заказа',
  `total_price` decimal(10,2) NOT NULL COMMENT 'Итого за позицию (amount * unit_price)'
) ;

--
-- Дамп данных таблицы `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `variant_id`, `amount`, `unit_price`, `total_price`) VALUES
(1, 1, 1, 1, 37990.00, 37990.00),
(2, 3, 4, 1, 41990.00, 41990.00),
(3, 3, 9, 1, 18990.00, 18990.00);

-- --------------------------------------------------------

--
-- Структура таблицы `order_statuses`
--

CREATE TABLE `order_statuses` (
  `id` int NOT NULL,
  `code` varchar(50) NOT NULL COMMENT 'new, awaiting_payment, paid, processing, shipped, ready_pickup, delivered, cancelled',
  `title` varchar(50) NOT NULL COMMENT 'Название для интерфейса',
  `color_hex` varchar(10) NOT NULL DEFAULT '#6B7280' COMMENT 'Цветовой бейдж для панели управления',
  `is_final` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Является ли статус финальным (доставлен / отменен)',
  `sort_order` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='Статусы заказов';

--
-- Дамп данных таблицы `order_statuses`
--

INSERT INTO `order_statuses` (`id`, `code`, `title`, `color_hex`, `is_final`, `sort_order`) VALUES
(1, 'new', 'Новый', '#3B82F6', 0, 10),
(2, 'awaiting_payment', 'Ожидает онлайн-оплаты', '#F59E0B', 0, 20),
(3, 'paid', 'Оплачен, ожидает сборки', '#10B981', 0, 30),
(4, 'processing', 'В сборке на складе', '#8B5CF6', 0, 40),
(5, 'shipped', 'Передан курьеру / В пути', '#EC4899', 0, 50),
(6, 'ready_pickup', 'Готов к выдаче в пункте', '#06B6D4', 0, 60),
(7, 'delivered', 'Успешно доставлен', '#059669', 1, 70),
(8, 'cancelled', 'Отменен', '#EF4444', 1, 80);

-- --------------------------------------------------------

--
-- Структура таблицы `order_status_history`
--

CREATE TABLE `order_status_history` (
  `id` int NOT NULL,
  `order_id` int NOT NULL,
  `status_id` int NOT NULL,
  `changed_by_user_id` int DEFAULT NULL COMMENT 'Сотрудник или курьер, сменивший статус',
  `comment` varchar(255) DEFAULT NULL COMMENT 'Причина смены статуса или комментарий курьера',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='Журнал перемещений и смены статусов заказов';

--
-- Дамп данных таблицы `order_status_history`
--

INSERT INTO `order_status_history` (`id`, `order_id`, `status_id`, `changed_by_user_id`, `comment`, `created_at`) VALUES
(1, 1, 1, 5, 'Клиент оформил заказ на сайте', '2026-09-01 14:20:00'),
(2, 1, 3, NULL, 'Платежная система подтвердила оплату картой', '2026-09-01 14:22:15'),
(3, 1, 4, 2, 'Заказ собран на складе и упакован', '2026-09-01 16:10:00'),
(4, 1, 5, 4, 'Курьер Быстров И.Н. принял заказ на доставку', '2026-09-02 10:00:00'),
(5, 1, 7, 4, 'Заказ успешно вручен клиенту', '2026-09-02 12:40:00'),
(6, 2, 1, 6, 'Клиент оформил самовывоз из шоурума', '2026-09-10 11:45:00'),
(7, 2, 6, 2, 'Товар перемещен в зал выдачи шоурума', '2026-09-10 13:00:00'),
(8, 3, 1, 5, 'Заказ создан', '2026-09-12 18:30:00'),
(9, 3, 3, NULL, 'Оплата по СБП успешно проведена', '2026-09-12 18:31:40');

-- --------------------------------------------------------

--
-- Структура таблицы `payments`
--

CREATE TABLE `payments` (
  `id` int NOT NULL,
  `order_id` int NOT NULL,
  `payment_method_id` int NOT NULL,
  `status_id` int NOT NULL,
  `amount` decimal(10,2) NOT NULL COMMENT 'Сумма транзакции',
  `transaction_id` varchar(100) DEFAULT NULL COMMENT 'Идентификатор платежа в эквайринге (Тинькофф/ЮКасса/Сбер)',
  `payment_payload` text COMMENT 'Ответ платежного шлюза / вебхука',
  `paid_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='Транзакции оплат по заказам';

--
-- Дамп данных таблицы `payments`
--

INSERT INTO `payments` (`id`, `order_id`, `payment_method_id`, `status_id`, `amount`, `transaction_id`, `payment_payload`, `paid_at`, `created_at`) VALUES
(1, 1, 1, 2, 37990.00, 'TINKOFF-PAY-992019482', NULL, '2026-09-01 14:22:15', '2026-09-01 14:20:05'),
(2, 3, 2, 2, 19340.00, 'SBP-QR-778201940129', NULL, '2026-09-12 18:31:40', '2026-09-12 18:30:10');

-- --------------------------------------------------------

--
-- Структура таблицы `payment_methods`
--

CREATE TABLE `payment_methods` (
  `id` int NOT NULL,
  `code` varchar(50) NOT NULL COMMENT 'Код метода: card_online, sbp, cash_on_delivery, installments',
  `title` varchar(100) NOT NULL COMMENT 'Название способа оплаты',
  `description` text COMMENT 'Пояснение для покупателя',
  `is_online` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1 - предоплата на сайте, 0 - при получении',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `sort_order` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='Способы оплаты заказов';

--
-- Дамп данных таблицы `payment_methods`
--

INSERT INTO `payment_methods` (`id`, `code`, `title`, `description`, `is_online`, `is_active`, `sort_order`) VALUES
(1, 'card_online', 'Банковская карта онлайн', 'Оплата картами МИР, Visa, MasterCard через защищенный платежный шлюз (без комиссии).', 1, 1, 10),
(2, 'sbp', 'СБП (Система быстрых платежей)', 'Мгновенная оплата по QR-коду через мобильное приложение любого банка.', 1, 1, 20),
(3, 'cash_on_delivery', 'При получении (наличными или картой)', 'Оплата курьеру или в пункте выдачи после визуального осмотра товара.', 0, 1, 30),
(4, 'installments', 'Оплата долями / Рассрочка 0-0-12', 'Разбивка платежа на 4 равные части без переплат или рассрочка от банков-партнеров.', 1, 1, 40);

-- --------------------------------------------------------

--
-- Структура таблицы `payment_statuses`
--

CREATE TABLE `payment_statuses` (
  `id` int NOT NULL,
  `code` varchar(50) NOT NULL COMMENT 'pending, paid, failed, refunded',
  `title` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='Статусы оплаты';

--
-- Дамп данных таблицы `payment_statuses`
--

INSERT INTO `payment_statuses` (`id`, `code`, `title`) VALUES
(1, 'pending', 'Ожидает оплаты'),
(2, 'paid', 'Оплачен'),
(3, 'failed', 'Ошибка транзакции'),
(4, 'refunded', 'Средства возвращены');

-- --------------------------------------------------------

--
-- Структура таблицы `permissions`
--

CREATE TABLE `permissions` (
  `id` int NOT NULL,
  `code` varchar(100) NOT NULL COMMENT 'Системный код права (напр. products.create, orders.edit_status)',
  `name` varchar(150) NOT NULL COMMENT 'Понятное название права',
  `module` varchar(50) NOT NULL COMMENT 'Раздел системы (Каталог, Заказы, Пользователи, Доставка, Склад)',
  `description` varchar(255) DEFAULT NULL COMMENT 'Подробное описание назначения права'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='Справочник гранулярных системных привилегий';

--
-- Дамп данных таблицы `permissions`
--

INSERT INTO `permissions` (`id`, `code`, `name`, `module`, `description`) VALUES
(1, 'catalog.view', 'Просмотр каталога в админке', 'Каталог', 'Доступ к реестру товаров и остатков'),
(2, 'catalog.create', 'Создание товаров', 'Каталог', 'Добавление новых товаров, категорий и брендов'),
(3, 'catalog.edit', 'Редактирование товаров', 'Каталог', 'Изменение описаний, цен, вариантов и характеристик'),
(4, 'catalog.delete', 'Удаление товаров', 'Каталог', 'Удаление или архивация товаров и вариантов'),
(5, 'orders.view_all', 'Просмотр всех заказов', 'Заказы', 'Доступ ко всем заказам клиентов'),
(6, 'orders.edit_status', 'Изменение статуса заказов', 'Заказы', 'Перевод заказов между этапами обработки'),
(7, 'orders.edit_items', 'Корректировка позиций заказа', 'Заказы', 'Добавление или удаление товаров из заказа клиента'),
(8, 'orders.cancel', 'Отмена заказов', 'Заказы', 'Право аннулировать заказ'),
(9, 'delivery.view_routes', 'Просмотр доставок', 'Доставка', 'Доступ к спискам доставки и адресам клиентов'),
(10, 'delivery.complete', 'Подтверждение вручения', 'Доставка', 'Фиксация вручения заказа курьером клиенту'),
(11, 'delivery.manage_methods', 'Настройка способов доставки', 'Доставка', 'Управление тарифами и пунктами выдачи'),
(12, 'users.view', 'Просмотр профилей клиентов', 'Пользователи', 'Доступ к списку покупателей и контактам'),
(13, 'users.manage_staff', 'Управление сотрудниками', 'Пользователи', 'Назначение ролей администраторов, менеджеров и курьеров'),
(14, 'reviews.moderate', 'Модерация отзывов', 'Отзывы', 'Одобрение и отклонение отзывов пользователей'),
(15, 'promos.manage', 'Управление промокодами', 'Маркетинг', 'Создание акций и скидочных купонов'),
(16, 'analytics.view', 'Просмотр финансовой аналитики', 'Аналитика', 'Доступ к отчетам о продажах, выручке и чекам');

-- --------------------------------------------------------

--
-- Структура таблицы `pickup_points`
--

CREATE TABLE `pickup_points` (
  `id` int NOT NULL,
  `title` varchar(150) NOT NULL COMMENT 'Название пункта (напр. Шоурум Soundwave на Арбате)',
  `city` varchar(100) NOT NULL COMMENT 'Город',
  `address` varchar(255) NOT NULL COMMENT 'Точный адрес',
  `phone` varchar(25) DEFAULT NULL COMMENT 'Контактный телефон точки',
  `work_hours` varchar(100) NOT NULL COMMENT 'Режим работы (напр. Ежедневно 10:00 - 22:00)',
  `metro_station` varchar(100) DEFAULT NULL COMMENT 'Ближайшая станция метро',
  `description` text COMMENT 'Как пройти от метро, наличие парковки, комната прослушивания',
  `is_active` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1 - открыт, 0 - временно закрыт'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='Пункты выдачи заказов и шоурумы';

--
-- Дамп данных таблицы `pickup_points`
--

INSERT INTO `pickup_points` (`id`, `title`, `city`, `address`, `phone`, `work_hours`, `metro_station`, `description`, `is_active`) VALUES
(1, 'Флагманский салон Soundwave Arbat', 'Москва', 'ул. Новый Арбат, д. 21, стр. 1', '+7 (495) 101-00-11', 'Пн-Вс: 10:00 - 22:00', 'Смоленская / Арбатская', 'Главный салон: 3 Hi-Fi комнаты прослушивания, виниловый лаундж, бесплатный кофе.', 1),
(2, 'Шоурум Soundwave Flacon', 'Москва', 'ул. Большая Новодмитровская, д. 36, Дизайн-завод Флакон', '+7 (495) 101-00-22', 'Пн-Вс: 11:00 - 21:00', 'Дмитровская', 'Студийный отдел: синтезаторы, студийные мониторы, студийные микрофоны и подкаст-станции.', 1),
(3, 'Шоурум Soundwave СПб Невский', 'Санкт-Петербург', 'Невский проспект, д. 54', '+7 (812) 202-00-33', 'Пн-Вс: 10:00 - 21:00', 'Гостиный двор', 'Большой выбор винила, наушников и ламповых усилителей.', 1);

-- --------------------------------------------------------

--
-- Структура таблицы `price_history`
--

CREATE TABLE `price_history` (
  `id` int NOT NULL,
  `variant_id` int NOT NULL,
  `old_price` decimal(10,2) NOT NULL COMMENT 'Предыдущая цена',
  `new_price` decimal(10,2) NOT NULL COMMENT 'Новая установленная цена',
  `changed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='Журнал аудита изменения цен товаров';

--
-- Дамп данных таблицы `price_history`
--

INSERT INTO `price_history` (`id`, `variant_id`, `old_price`, `new_price`, `changed_at`) VALUES
(1, 1, 42990.00, 37990.00, '2026-09-01 00:00:00'),
(2, 3, 59990.00, 54990.00, '2026-09-01 00:00:00'),
(3, 9, 21990.00, 18990.00, '2026-09-05 00:00:00');

-- --------------------------------------------------------

--
-- Структура таблицы `products`
--

CREATE TABLE `products` (
  `id` int NOT NULL,
  `category_id` int NOT NULL COMMENT 'Категория товара',
  `brand_id` int NOT NULL COMMENT 'Бренд производителя',
  `title` varchar(255) NOT NULL COMMENT 'Полное название товара',
  `slug` varchar(255) NOT NULL COMMENT 'Уникальный ЧПУ-алиас для веб-страницы',
  `model_code` varchar(50) DEFAULT NULL COMMENT 'Базовый модельный код производителя',
  `short_description` varchar(500) DEFAULT NULL COMMENT 'Краткое резюме для сниппетов и карточек каталога',
  `description` text NOT NULL COMMENT 'Полное описание товара с разметкой',
  `is_active` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1 - отображается на сайте, 0 - скрыт',
  `is_featured` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1 - хит продаж / рекомендованный товар',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='Карточки товаров';

--
-- Дамп данных таблицы `products`
--

INSERT INTO `products` (`id`, `category_id`, `brand_id`, `title`, `slug`, `model_code`, `short_description`, `description`, `is_active`, `is_featured`, `created_at`, `updated_at`) VALUES
(1, 2, 2, 'Беспроводные наушники Sony WH-1000XM5', 'sony-wh-1000xm5', 'WH1000XM5', 'Флагманские полноразмерные наушники с лучшим на рынке шумоподавлением и поддержкой LDAC.', 'Наушники Sony WH-1000XM5 переписывают правила персонального прослушивания без отвлекающих факторов. 2 процессора управляют 8 микрофонами для беспрецедентного шумоподавления и безупречного качества звонков.', 1, 1, '2026-09-12 19:02:56', '2026-09-12 19:02:56'),
(2, 2, 1, 'Открытые аудиофильские наушники Sennheiser HD 660S2', 'sennheiser-hd-660s2', 'HD660S2', 'Легендарные открытые динамические наушники с непревзойденной глубиной сцены и теплыми басами.', 'Sennheiser HD 660S2 открывают мельчайшие нюансы музыкальных композиций. Переработанная катушка индуктивности и оптимизированное движение воздуха обеспечивают невероятно артикулированный суббас.', 1, 1, '2026-09-12 19:02:56', '2026-09-12 19:02:56'),
(3, 4, 3, 'Беспроводная колонка Marshall Stanmore III', 'marshall-stanmore-iii', 'STANMORE-III', 'Мощная домашняя акустическая система мощностью 80 Вт в культовом дизайне гитарного усилителя.', 'Stanmore III занимает центральное место в линейке домашних колонок Marshall. Она выдает объемное звучание, заполняющее любую комнату, и поддерживает современный Bluetooth 5.2.', 1, 1, '2026-09-12 19:02:56', '2026-09-12 19:02:56'),
(4, 5, 4, 'Проигрыватель винила Audio-Technica AT-LP120XUSB', 'audio-technica-at-lp120xusb', 'ATLP120XUSB', 'Профессиональный виниловый проигрыватель с прямым приводом, встроенным фонокорректором и USB-выходом.', 'Новая версия популярнейшего проигрывателя оснащена сервомотором постоянного тока с прямым приводом, регулируемым антискейтингом и картриджем AT-VM95E Dual Magnet.', 1, 1, '2026-09-12 19:02:56', '2026-09-12 19:02:56'),
(5, 6, 6, 'Динамический вещательный микрофон Shure SM7B', 'shure-sm7b', 'SM7B', 'Культовый студийный микрофон для записи подкастов, стриминга, радиовещания и вокала.', 'Shure SM7B — это кардиоидный динамический микрофон с гладким, ровным и широкополосным частотным откликом, создающий бархатный «радийный» тембр голоса.', 1, 1, '2026-09-12 19:02:56', '2026-09-12 19:02:56'),
(6, 2, 5, 'Студийные наушники Beyerdynamic DT 770 PRO', 'beyerdynamic-dt-770-pro', 'DT770PRO', 'Эталонные закрытые студийные наушники для мониторинга, сведения и звукозаписи.', 'Сделанные в Германии DT 770 PRO завоевали доверие профессионалов по всему миру благодаря феноменальной детализации, глубокому басу и комфортным велюровым амбушюрам.', 1, 0, '2026-09-12 19:02:56', '2026-09-12 19:02:56');

-- --------------------------------------------------------

--
-- Структура таблицы `products_cart`
--

CREATE TABLE `products_cart` (
  `user_id` int NOT NULL,
  `variant_id` int NOT NULL,
  `amount` int NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ;

--
-- Дамп данных таблицы `products_cart`
--

INSERT INTO `products_cart` (`user_id`, `variant_id`, `amount`, `created_at`, `updated_at`) VALUES
(5, 3, 1, '2026-09-12 20:10:00', '2026-09-12 19:02:56'),
(6, 1, 1, '2026-09-12 19:40:00', '2026-09-12 19:02:56');

-- --------------------------------------------------------

--
-- Структура таблицы `products_favorites`
--

CREATE TABLE `products_favorites` (
  `user_id` int NOT NULL,
  `variant_id` int NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='Избранные товары пользователей';

--
-- Дамп данных таблицы `products_favorites`
--

INSERT INTO `products_favorites` (`user_id`, `variant_id`, `created_at`) VALUES
(5, 5, '2026-09-05 16:30:00'),
(5, 8, '2026-09-08 19:20:00'),
(6, 3, '2026-09-11 22:15:00');

-- --------------------------------------------------------

--
-- Структура таблицы `products_variants`
--

CREATE TABLE `products_variants` (
  `id` int NOT NULL,
  `product_id` int NOT NULL COMMENT 'Родительский товар',
  `title` varchar(100) NOT NULL COMMENT 'Название модификации (напр. Черный / 80 Ом, Серебристый)',
  `article` varchar(50) NOT NULL COMMENT 'Уникальный артикул (SKU)',
  `barcode` varchar(50) DEFAULT NULL COMMENT 'Штрих-код (EAN-13 / UPC)',
  `price` decimal(10,2) NOT NULL COMMENT 'Актуальная цена продажи в рублях',
  `old_price` decimal(10,2) DEFAULT NULL COMMENT 'Старая зачеркнутая цена (до скидки)',
  `amount` int NOT NULL DEFAULT '0' COMMENT 'Фактический складской остаток',
  `is_active` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1 - доступен для заказа, 0 - снят',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ;

--
-- Дамп данных таблицы `products_variants`
--

INSERT INTO `products_variants` (`id`, `product_id`, `title`, `article`, `barcode`, `price`, `old_price`, `amount`, `is_active`, `created_at`) VALUES
(1, 1, 'Черный матовый (Black)', 'SNY-WHXM5-BLK', '4548736132566', 37990.00, 42990.00, 15, 1, '2026-09-12 19:02:56'),
(2, 1, 'Серебристый платиновый (Silver)', 'SNY-WHXM5-SLV', '4548736132573', 37990.00, 42990.00, 8, 1, '2026-09-12 19:02:56'),
(3, 2, 'Матовый черный / 300 Ом', 'SNN-HD660S2-BLK', '4044155272895', 54990.00, 59990.00, 5, 1, '2026-09-12 19:02:56'),
(4, 3, 'Классический черный (Black)', 'MSH-STAN3-BLK', '7340055385671', 41990.00, NULL, 12, 1, '2026-09-12 19:02:56'),
(5, 3, 'Винтажный кремовый (Cream)', 'MSH-STAN3-CRM', '7340055385688', 43990.00, 46990.00, 4, 1, '2026-09-12 19:02:56'),
(6, 4, 'Черный металлик (Black)', 'AT-LP120X-BK', '4961310148782', 39990.00, 44990.00, 7, 1, '2026-09-12 19:02:56'),
(7, 4, 'Серебристый хром (Silver)', 'AT-LP120X-SV', '4961310148799', 39990.00, 44990.00, 3, 1, '2026-09-12 19:02:56'),
(8, 5, 'Черный классический', 'SHR-SM7B-STD', '042406088862', 46990.00, 49990.00, 20, 1, '2026-09-12 19:02:56'),
(9, 6, '80 Ом (Универсальные)', 'BYR-DT770-80', '4010118459048', 18990.00, 21990.00, 25, 1, '2026-09-12 19:02:56'),
(10, 6, '250 Ом (Для студийных ЦАП)', 'BYR-DT770-250', '4010118459055', 18990.00, 21990.00, 18, 1, '2026-09-12 19:02:56');

--
-- Триггеры `products_variants`
--
DELIMITER $$
CREATE TRIGGER `trg_product_variant_price_history` AFTER UPDATE ON `products_variants` FOR EACH ROW BEGIN
  IF OLD.`price` <> NEW.`price` THEN
    INSERT INTO `price_history` (`variant_id`, `old_price`, `new_price`, `changed_at`)
    VALUES (NEW.`id`, OLD.`price`, NEW.`price`, CURRENT_TIMESTAMP);
  END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Структура таблицы `product_attributes`
--

CREATE TABLE `product_attributes` (
  `variant_id` int NOT NULL,
  `attribute_id` int NOT NULL,
  `value_string` varchar(255) DEFAULT NULL COMMENT 'Строковое значение (напр. "Bluetooth 5.3")',
  `value_number` decimal(10,2) DEFAULT NULL COMMENT 'Числовое значение (напр. 80.00)',
  `value_bool` tinyint(1) DEFAULT NULL COMMENT 'Логическое значение (1 / 0)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='Значения характеристик для модификаций';

--
-- Дамп данных таблицы `product_attributes`
--

INSERT INTO `product_attributes` (`variant_id`, `attribute_id`, `value_string`, `value_number`, `value_bool`) VALUES
(1, 1, '4 - 40 000 Гц', NULL, NULL),
(1, 2, NULL, 48.00, NULL),
(1, 3, NULL, NULL, 1),
(1, 4, NULL, 30.00, NULL),
(1, 5, 'Bluetooth 5.2 / Mini-Jack 3.5 mm', NULL, NULL),
(1, 6, NULL, 250.00, NULL),
(1, 7, NULL, NULL, 1),
(1, 8, 'LDAC, AAC, SBC', NULL, NULL),
(3, 1, '8 - 41 500 Гц', NULL, NULL),
(3, 2, NULL, 300.00, NULL),
(3, 3, NULL, NULL, 0),
(3, 5, 'Проводное (Jack 6.3 mm / Pentaconn 4.4 mm)', NULL, NULL),
(3, 6, NULL, 260.00, NULL),
(3, 7, NULL, NULL, 0),
(4, 1, '45 - 20 000 Гц', NULL, NULL),
(4, 5, 'Bluetooth 5.2 / RCA / AUX 3.5 mm', NULL, NULL),
(4, 6, NULL, 4250.00, NULL),
(8, 1, '50 - 20 000 Гц', NULL, NULL),
(8, 2, NULL, 150.00, NULL),
(8, 5, 'Проводное (XLR 3-pin)', NULL, NULL),
(8, 6, NULL, 765.00, NULL),
(9, 1, '5 - 35 000 Гц', NULL, NULL),
(9, 2, NULL, 80.00, NULL),
(9, 3, NULL, NULL, 0),
(9, 5, 'Проводное (Mini-Jack 3.5 mm + переходник 6.3 mm)', NULL, NULL),
(9, 6, NULL, 270.00, NULL),
(9, 7, NULL, NULL, 0);

-- --------------------------------------------------------

--
-- Структура таблицы `product_images`
--

CREATE TABLE `product_images` (
  `id` int NOT NULL,
  `product_id` int NOT NULL COMMENT 'Товар, к которому относится фото',
  `variant_id` int DEFAULT NULL COMMENT 'Опциональная привязка к конкретному цвету/варианту',
  `image_url` varchar(500) NOT NULL COMMENT 'Путь к файлу или URL изображения',
  `alt_text` varchar(255) DEFAULT NULL COMMENT 'SEO alt-текст для изображения',
  `is_main` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1 - главное фото товара для витрины',
  `sort_order` int NOT NULL DEFAULT '0' COMMENT 'Порядок показа в галерее'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='Медиафайлы и галерея товаров';

--
-- Дамп данных таблицы `product_images`
--

INSERT INTO `product_images` (`id`, `product_id`, `variant_id`, `image_url`, `alt_text`, `is_main`, `sort_order`) VALUES
(1, 1, 1, '/uploads/products/sony-wh1000xm5-black-main.webp', 'Sony WH-1000XM5 Black вид спереди', 1, 1),
(2, 1, 1, '/uploads/products/sony-wh1000xm5-black-side.webp', 'Sony WH-1000XM5 Black вид сбоку', 0, 2),
(3, 1, 2, '/uploads/products/sony-wh1000xm5-silver-main.webp', 'Sony WH-1000XM5 Silver вид спереди', 0, 3),
(4, 2, 3, '/uploads/products/sennheiser-hd660s2-main.webp', 'Sennheiser HD 660S2 в студии', 1, 1),
(5, 3, 4, '/uploads/products/marshall-stanmore3-black-main.webp', 'Колонка Marshall Stanmore III Black', 1, 1),
(6, 3, 5, '/uploads/products/marshall-stanmore3-cream-main.webp', 'Колонка Marshall Stanmore III Cream', 0, 2),
(7, 4, 6, '/uploads/products/audiotechnica-lp120x-black.webp', 'Audio-Technica AT-LP120XUSB черный', 1, 1),
(8, 5, 8, '/uploads/products/shure-sm7b-main.webp', 'Микрофон Shure SM7B на пантографе', 1, 1),
(9, 6, 9, '/uploads/products/beyerdynamic-dt770-main.webp', 'Наушники Beyerdynamic DT 770 PRO 80 Ом', 1, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `promo_codes`
--

CREATE TABLE `promo_codes` (
  `id` int NOT NULL,
  `code` varchar(50) NOT NULL COMMENT 'Промокод (напр. SOUND2026, WELCOME10)',
  `discount_type` enum('percentage','fixed') NOT NULL DEFAULT 'percentage' COMMENT 'Тип: процент или фиксированная сумма',
  `discount_value` decimal(10,2) NOT NULL COMMENT 'Размер скидки (% или рубли)',
  `min_order_amount` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT 'Минимальная сумма заказа для применения',
  `max_uses` int DEFAULT NULL COMMENT 'Максимальное кол-во применений (NULL = безлимитно)',
  `used_count` int NOT NULL DEFAULT '0' COMMENT 'Количество фактических применений',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `expires_at` timestamp NULL DEFAULT NULL COMMENT 'Срок действия купона'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='Справочник промокодов';

--
-- Дамп данных таблицы `promo_codes`
--

INSERT INTO `promo_codes` (`id`, `code`, `discount_type`, `discount_value`, `min_order_amount`, `max_uses`, `used_count`, `is_active`, `expires_at`) VALUES
(1, 'WELCOME10', 'percentage', 10.00, 10000.00, 500, 42, 1, '2026-12-31 23:59:59'),
(2, 'SOUND3000', 'fixed', 3000.00, 40000.00, 100, 15, 1, '2026-12-31 23:59:59');

-- --------------------------------------------------------

--
-- Структура таблицы `reviews`
--

CREATE TABLE `reviews` (
  `id` int NOT NULL,
  `product_id` int NOT NULL COMMENT 'Оцениваемый товар',
  `user_id` int NOT NULL COMMENT 'Автор отзыва',
  `rating` tinyint NOT NULL COMMENT 'Оценка от 1 до 5 звезд',
  `pros` varchar(500) DEFAULT NULL COMMENT 'Достоинства аудиооборудования',
  `cons` varchar(500) DEFAULT NULL COMMENT 'Недостатки',
  `text` text NOT NULL COMMENT 'Подробный отзыв о звучании и качестве',
  `status` enum('pending','approved','rejected') NOT NULL DEFAULT 'approved' COMMENT 'Статус модерации',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ;

--
-- Дамп данных таблицы `reviews`
--

INSERT INTO `reviews` (`id`, `product_id`, `user_id`, `rating`, `pros`, `cons`, `text`, `status`, `created_at`) VALUES
(1, 1, 5, 5, 'Невероятный шумодав, очень легкие и удобные, автономность до 30 часов, шикарные микрофоны для созвонов.', 'Кейс стал больше по сравнению с XM4, не складываются внутрь оголовья.', 'Перешел с четвертого поколения XM4. Звук стал чище и прозрачнее в средних и высоких частотах. В метро тишина полная, даже в старых вагонах. Однозначно стоят каждого рубля!', 'approved', '2026-09-04 15:30:00'),
(2, 3, 6, 5, 'Потрясающий глубокий плотный звук, винтажная эстетика, удобные аналоговые регуляторы тембра.', 'Нет встроенного аккумулятора (работает только от розетки).', 'Колонка украсила гостиную! Звук объемный и бархатный, рок и блюз звучат как живые на концерте. Очень довольна покупкой в Soundwave.', 'approved', '2026-09-11 20:10:00');

-- --------------------------------------------------------

--
-- Структура таблицы `roles`
--

CREATE TABLE `roles` (
  `id` int NOT NULL,
  `code` varchar(50) NOT NULL COMMENT 'Уникальный код роли (superadmin, manager, courier, etc.)',
  `name` varchar(100) NOT NULL COMMENT 'Отображаемое название роли',
  `description` varchar(255) DEFAULT NULL COMMENT 'Описание обязанностей и зоны ответственности роли',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='Роли пользователей в системе';

--
-- Дамп данных таблицы `roles`
--

INSERT INTO `roles` (`id`, `code`, `name`, `description`, `created_at`) VALUES
(1, 'superadmin', 'Главный администратор', 'Полный неограниченный доступ ко всем системам и настройкам магазина', '2026-09-12 19:02:56'),
(2, 'admin', 'Администратор системы', 'Управление сотрудниками, аналитика и конфигурация сервисов', '2026-09-12 19:02:56'),
(3, 'order_manager', 'Менеджер по обработке заказов', 'Прием, изменение статусов заказов, связь с клиентами и координация логистики', '2026-09-12 19:02:56'),
(4, 'content_manager', 'Контент-менеджер каталога', 'Добавление товаров, брендов, характеристик, медиафайлов и модерация отзывов', '2026-09-12 19:02:56'),
(5, 'courier', 'Служба доставки / Курьер', 'Просмотр маршрутных листов, смена статусов доставки на \"В пути\" и \"Доставлен\"', '2026-09-12 19:02:56'),
(6, 'customer', 'Покупатель', 'Клиент интернет-магазина (личный кабинет, история покупок, корзина, избранное)', '2026-09-12 19:02:56');

-- --------------------------------------------------------

--
-- Структура таблицы `role_permissions`
--

CREATE TABLE `role_permissions` (
  `role_id` int NOT NULL,
  `permission_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='Назначение прав ролям';

--
-- Дамп данных таблицы `role_permissions`
--

INSERT INTO `role_permissions` (`role_id`, `permission_id`) VALUES
(1, 1),
(2, 1),
(3, 1),
(4, 1),
(1, 2),
(2, 2),
(4, 2),
(1, 3),
(2, 3),
(4, 3),
(1, 4),
(2, 4),
(4, 4),
(1, 5),
(2, 5),
(3, 5),
(1, 6),
(2, 6),
(3, 6),
(1, 7),
(2, 7),
(3, 7),
(1, 8),
(2, 8),
(3, 8),
(1, 9),
(2, 9),
(3, 9),
(5, 9),
(1, 10),
(5, 10),
(1, 11),
(2, 11),
(1, 12),
(2, 12),
(3, 12),
(1, 13),
(1, 14),
(2, 14),
(4, 14),
(1, 15),
(2, 15),
(1, 16),
(2, 16);

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `role_id` int NOT NULL DEFAULT '6' COMMENT 'Внешний ключ на роль (по умолчанию 6 - Customer)',
  `firstname` varchar(50) NOT NULL COMMENT 'Имя',
  `lastname` varchar(50) NOT NULL COMMENT 'Фамилия',
  `patronymic` varchar(50) DEFAULT NULL COMMENT 'Отчество (вместо некорректного поля surname)',
  `email` varchar(100) NOT NULL COMMENT 'E-mail для входа и уведомлений',
  `phone` varchar(20) NOT NULL COMMENT 'Номер телефона в международном формате',
  `password_hash` varchar(255) NOT NULL COMMENT 'Хеш пароля (bcrypt / argon2)',
  `birthdate` date DEFAULT NULL COMMENT 'Дата рождения (для персонализации и скидок к ДР)',
  `instrument` varchar(50) NOT NULL,
  `city` varchar(50) NOT NULL,
  `adress` varchar(255) NOT NULL,
  `registration_source` tinyint NOT NULL DEFAULT '1' COMMENT 'Источник регистрации: 1-Сайт, 2-Приложение, 3-Партнер',
  `is_active` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1 - активен, 0 - заблокирован',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `bonus_balance` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='Учетные записи клиентов и сотрудников';

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `role_id`, `firstname`, `lastname`, `patronymic`, `email`, `phone`, `password_hash`, `birthdate`, `instrument`, `city`, `adress`, `registration_source`, `is_active`, `email_verified_at`, `created_at`, `updated_at`, `bonus_balance`) VALUES
(1, 1, 'Алексей', 'Громов', 'Викторович', 'admin@soundwave.ru', '+79991112233', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '1988-04-12', '', '', '', 1, 1, NULL, '2026-09-12 19:02:56', '2026-09-12 19:02:56', 0),
(2, 3, 'Дмитрий', 'Смирнов', 'Андреевич', 'manager@soundwave.ru', '+79992223344', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '1993-08-25', '', '', '', 1, 1, NULL, '2026-09-12 19:02:56', '2026-09-12 19:02:56', 0),
(3, 4, 'Екатерина', 'Ковалева', 'Сергеевна', 'content@soundwave.ru', '+79993334455', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '1995-11-03', '', '', '', 1, 1, NULL, '2026-09-12 19:02:56', '2026-09-12 19:02:56', 0),
(4, 5, 'Иван', 'Быстров', 'Николаевич', 'courier@soundwave.ru', '+79994445566', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '1997-02-17', '', '', '', 1, 1, NULL, '2026-09-12 19:02:56', '2026-09-12 19:02:56', 0),
(5, 6, 'Михаил', 'Аудиофилов', 'Олегович', 'customer@gmail.com', '+79995556677', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '1991-07-19', '', '', '', 1, 1, NULL, '2026-09-12 19:02:56', '2026-09-12 19:02:56', 0),
(6, 6, 'Анна', 'Меломанова', 'Игоревна', 'anna.music@yandex.ru', '+79996667788', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '1999-05-14', '', '', '', 1, 1, NULL, '2026-09-12 19:02:56', '2026-09-12 19:02:56', 0),
(15, 6, 'tgbf', 'dsfgfh', 'grbf', 'mihael36536@gmail.com', '+79999999999', '$2y$10$r2OHdp.NRqYW0KrP5QAec.kQIO11KUICNKP4wvglOi50lXRGTNzbu', NULL, '', '', '', 1, 1, NULL, '2026-09-13 13:10:43', '2026-09-13 13:10:43', 0),
(24, 6, 'Михаил', 'Сидоров', 'Юрьевич', 'mihael36536@gmail.con', '+79999999900', '$2y$10$YDjqz.8WOZ1fcpavFz.S1eElzJnN4d1KLZ5pPrn2.EXZNQj8TSuma', '2008-07-10', 'Гитара', 'Сыктывкар', 'улица хуй соси, дом 653', 1, 1, NULL, '2026-09-13 14:15:24', '2026-09-14 09:15:12', 0);

-- --------------------------------------------------------

--
-- Структура таблицы `user_addresses`
--

CREATE TABLE `user_addresses` (
  `id` int NOT NULL,
  `user_id` int NOT NULL COMMENT 'Владелец адреса',
  `title` varchar(50) DEFAULT 'Основной' COMMENT 'Метка адреса: Дом, Офис, Родители',
  `city` varchar(100) NOT NULL COMMENT 'Город / населенный пункт',
  `street` varchar(150) NOT NULL COMMENT 'Улица / проспект / переулок',
  `house` varchar(20) NOT NULL COMMENT 'Номер дома / владения',
  `building` varchar(20) DEFAULT NULL COMMENT 'Корпус / строение',
  `apartment` varchar(20) DEFAULT NULL COMMENT 'Квартира / офис',
  `entrance` varchar(10) DEFAULT NULL COMMENT 'Подъезд',
  `floor` varchar(10) DEFAULT NULL COMMENT 'Этаж',
  `intercom` varchar(20) DEFAULT NULL COMMENT 'Код домофона',
  `postal_code` varchar(20) DEFAULT NULL COMMENT 'Почтовый индекс',
  `comment` varchar(255) DEFAULT NULL COMMENT 'Инструкция для курьера (шлагбаум, охрана)',
  `is_default` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Адрес по умолчанию',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='Адреса доставки пользователей';

--
-- Дамп данных таблицы `user_addresses`
--

INSERT INTO `user_addresses` (`id`, `user_id`, `title`, `city`, `street`, `house`, `building`, `apartment`, `entrance`, `floor`, `intercom`, `postal_code`, `comment`, `is_default`, `created_at`) VALUES
(1, 5, 'Домашний', 'Москва', 'ул. Тверская', '12', '2', '45', '1', '5', '45К', '125009', 'Охране сказать в 45 квартиру', 1, '2026-09-12 19:02:56'),
(2, 5, 'Офис', 'Москва', 'Ленинградский проспект', '39', 'стр. 79', 'Бизнес-центр SkyLigh', NULL, '12', NULL, '125167', 'Встречу на ресепшн', 0, '2026-09-12 19:02:56'),
(3, 6, 'Квартира', 'Санкт-Петербург', 'Казанская ул.', '8', NULL, '14', '2', '3', '14', '191186', 'Код домофона #1414', 1, '2026-09-12 19:02:56');

-- --------------------------------------------------------

--
-- Дублирующая структура для представления `v_catalog_products`
-- (См. Ниже фактическое представление)
--
CREATE TABLE `v_catalog_products` (
`avg_rating` decimal(5,1)
,`brand_id` int
,`brand_slug` varchar(100)
,`brand_title` varchar(100)
,`category_id` int
,`category_slug` varchar(100)
,`category_title` varchar(100)
,`is_active` tinyint(1)
,`is_featured` tinyint(1)
,`main_image_url` varchar(500)
,`max_price` decimal(10,2)
,`min_price` decimal(10,2)
,`model_code` varchar(50)
,`product_id` int
,`product_slug` varchar(255)
,`product_title` varchar(255)
,`reviews_count` bigint
,`short_description` varchar(500)
,`total_stock` decimal(32,0)
,`variants_count` bigint
);

-- --------------------------------------------------------

--
-- Дублирующая структура для представления `v_order_details`
-- (См. Ниже фактическое представление)
--
CREATE TABLE `v_order_details` (
`customer_email` varchar(100)
,`customer_name` varchar(152)
,`customer_phone` varchar(20)
,`delivery_code` varchar(50)
,`delivery_cost` decimal(10,2)
,`delivery_method_name` varchar(100)
,`discount_total` decimal(10,2)
,`final_destination_address` mediumtext
,`final_total` decimal(10,2)
,`is_home_delivery` tinyint(1)
,`order_date` timestamp
,`order_id` int
,`order_number` varchar(32)
,`order_status_code` varchar(50)
,`order_status_title` varchar(50)
,`payment_method_name` varchar(100)
,`payment_status_code` varchar(50)
,`payment_status_title` varchar(50)
,`pickup_point_title` varchar(150)
,`products_total` decimal(10,2)
,`status_color` varchar(10)
,`total_items_positions` bigint
,`total_units_count` decimal(32,0)
,`tracking_number` varchar(100)
,`user_id` int
);

-- --------------------------------------------------------

--
-- Дублирующая структура для представления `v_user_cart`
-- (См. Ниже фактическое представление)
--
CREATE TABLE `v_user_cart` (
`amount` int
,`in_stock_amount` int
,`old_price` decimal(10,2)
,`product_id` int
,`product_slug` varchar(255)
,`product_title` varchar(255)
,`subtotal_price` decimal(20,2)
,`unit_price` decimal(10,2)
,`user_id` int
,`variant_article` varchar(50)
,`variant_id` int
,`variant_image_url` varchar(500)
,`variant_title` varchar(100)
);

-- --------------------------------------------------------

--
-- Дублирующая структура для представления `v_user_permissions`
-- (См. Ниже фактическое представление)
--
CREATE TABLE `v_user_permissions` (
`email` varchar(100)
,`permission_code` varchar(100)
,`permission_id` int
,`permission_module` varchar(50)
,`role_code` varchar(50)
,`role_id` int
,`role_name` varchar(100)
,`user_id` int
,`user_is_active` tinyint(1)
);

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `attributes`
--
ALTER TABLE `attributes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_attributes_code` (`code`);

--
-- Индексы таблицы `brands`
--
ALTER TABLE `brands`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_brands_slug` (`slug`),
  ADD KEY `idx_brands_title` (`title`);

--
-- Индексы таблицы `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_categories_slug` (`slug`),
  ADD KEY `idx_categories_parent` (`parent_id`);

--
-- Индексы таблицы `delivery_methods`
--
ALTER TABLE `delivery_methods`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_delivery_methods_code` (`code`),
  ADD KEY `idx_delivery_methods_active` (`is_active`,`sort_order`);

--
-- Индексы таблицы `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_orders_number` (`order_number`),
  ADD KEY `idx_orders_user` (`user_id`),
  ADD KEY `idx_orders_status` (`status_id`),
  ADD KEY `idx_orders_delivery` (`delivery_method_id`),
  ADD KEY `idx_orders_payment` (`payment_method_id`,`payment_status_id`),
  ADD KEY `idx_orders_created` (`created_at`),
  ADD KEY `fk_orders_pickup_point` (`pickup_point_id`),
  ADD KEY `fk_orders_address` (`address_id`),
  ADD KEY `fk_orders_payment_status` (`payment_status_id`);

--
-- Индексы таблицы `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_order_items_variant` (`order_id`,`variant_id`),
  ADD KEY `idx_order_items_variant` (`variant_id`);

--
-- Индексы таблицы `order_statuses`
--
ALTER TABLE `order_statuses`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_order_statuses_code` (`code`);

--
-- Индексы таблицы `order_status_history`
--
ALTER TABLE `order_status_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_order_status_history_order` (`order_id`,`created_at`),
  ADD KEY `fk_order_status_history_status` (`status_id`),
  ADD KEY `fk_order_status_history_user` (`changed_by_user_id`);

--
-- Индексы таблицы `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_payments_order` (`order_id`),
  ADD KEY `idx_payments_transaction` (`transaction_id`),
  ADD KEY `fk_payments_method` (`payment_method_id`),
  ADD KEY `fk_payments_status` (`status_id`);

--
-- Индексы таблицы `payment_methods`
--
ALTER TABLE `payment_methods`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_payment_methods_code` (`code`),
  ADD KEY `idx_payment_methods_active` (`is_active`,`sort_order`);

--
-- Индексы таблицы `payment_statuses`
--
ALTER TABLE `payment_statuses`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_payment_statuses_code` (`code`);

--
-- Индексы таблицы `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_permissions_code` (`code`),
  ADD KEY `idx_permissions_module` (`module`);

--
-- Индексы таблицы `pickup_points`
--
ALTER TABLE `pickup_points`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_pickup_points_city` (`city`,`is_active`);

--
-- Индексы таблицы `price_history`
--
ALTER TABLE `price_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_price_history_variant` (`variant_id`,`changed_at`);

--
-- Индексы таблицы `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_products_slug` (`slug`),
  ADD KEY `idx_products_category` (`category_id`),
  ADD KEY `idx_products_brand` (`brand_id`),
  ADD KEY `idx_products_active` (`is_active`,`is_featured`);

--
-- Индексы таблицы `products_cart`
--
ALTER TABLE `products_cart`
  ADD PRIMARY KEY (`user_id`,`variant_id`),
  ADD KEY `idx_cart_variant` (`variant_id`);

--
-- Индексы таблицы `products_favorites`
--
ALTER TABLE `products_favorites`
  ADD PRIMARY KEY (`user_id`,`variant_id`),
  ADD KEY `idx_favorites_variant` (`variant_id`);

--
-- Индексы таблицы `products_variants`
--
ALTER TABLE `products_variants`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_variants_article` (`article`),
  ADD UNIQUE KEY `uk_variants_barcode` (`barcode`),
  ADD KEY `idx_variants_product` (`product_id`),
  ADD KEY `idx_variants_price` (`price`);

--
-- Индексы таблицы `product_attributes`
--
ALTER TABLE `product_attributes`
  ADD PRIMARY KEY (`variant_id`,`attribute_id`),
  ADD KEY `idx_product_attributes_attr` (`attribute_id`);

--
-- Индексы таблицы `product_images`
--
ALTER TABLE `product_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_product_images_product` (`product_id`),
  ADD KEY `idx_product_images_variant` (`variant_id`);

--
-- Индексы таблицы `promo_codes`
--
ALTER TABLE `promo_codes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_promo_codes_code` (`code`);

--
-- Индексы таблицы `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_reviews_product` (`product_id`,`status`),
  ADD KEY `idx_reviews_user` (`user_id`);

--
-- Индексы таблицы `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_roles_code` (`code`);

--
-- Индексы таблицы `role_permissions`
--
ALTER TABLE `role_permissions`
  ADD PRIMARY KEY (`role_id`,`permission_id`),
  ADD KEY `fk_role_permissions_permission` (`permission_id`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_users_email` (`email`),
  ADD UNIQUE KEY `uk_users_phone` (`phone`),
  ADD KEY `idx_users_role` (`role_id`),
  ADD KEY `idx_users_active` (`is_active`);

--
-- Индексы таблицы `user_addresses`
--
ALTER TABLE `user_addresses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_user_addresses_user` (`user_id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `attributes`
--
ALTER TABLE `attributes`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT для таблицы `brands`
--
ALTER TABLE `brands`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT для таблицы `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT для таблицы `delivery_methods`
--
ALTER TABLE `delivery_methods`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT для таблицы `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT для таблицы `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `order_statuses`
--
ALTER TABLE `order_statuses`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT для таблицы `order_status_history`
--
ALTER TABLE `order_status_history`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT для таблицы `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблицы `payment_methods`
--
ALTER TABLE `payment_methods`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT для таблицы `payment_statuses`
--
ALTER TABLE `payment_statuses`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT для таблицы `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT для таблицы `pickup_points`
--
ALTER TABLE `pickup_points`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT для таблицы `price_history`
--
ALTER TABLE `price_history`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT для таблицы `products`
--
ALTER TABLE `products`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT для таблицы `products_variants`
--
ALTER TABLE `products_variants`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `product_images`
--
ALTER TABLE `product_images`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT для таблицы `promo_codes`
--
ALTER TABLE `promo_codes`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблицы `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT для таблицы `user_addresses`
--
ALTER TABLE `user_addresses`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

-- --------------------------------------------------------

--
-- Структура для представления `v_catalog_products`
--
DROP TABLE IF EXISTS `v_catalog_products`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_catalog_products`  AS SELECT `p`.`id` AS `product_id`, `p`.`title` AS `product_title`, `p`.`slug` AS `product_slug`, `p`.`model_code` AS `model_code`, `p`.`short_description` AS `short_description`, `p`.`is_active` AS `is_active`, `p`.`is_featured` AS `is_featured`, `c`.`id` AS `category_id`, `c`.`title` AS `category_title`, `c`.`slug` AS `category_slug`, `b`.`id` AS `brand_id`, `b`.`title` AS `brand_title`, `b`.`slug` AS `brand_slug`, min(`v`.`price`) AS `min_price`, max(`v`.`price`) AS `max_price`, sum(`v`.`amount`) AS `total_stock`, count(distinct `v`.`id`) AS `variants_count`, (select `pi`.`image_url` from `product_images` `pi` where (`pi`.`product_id` = `p`.`id`) order by `pi`.`is_main` desc,`pi`.`sort_order`,`pi`.`id` limit 1) AS `main_image_url`, coalesce(round(avg(`r`.`rating`),1),0.0) AS `avg_rating`, count(distinct `r`.`id`) AS `reviews_count` FROM ((((`products` `p` join `categories` `c` on((`p`.`category_id` = `c`.`id`))) join `brands` `b` on((`p`.`brand_id` = `b`.`id`))) left join `products_variants` `v` on(((`v`.`product_id` = `p`.`id`) and (`v`.`is_active` = 1)))) left join `reviews` `r` on(((`r`.`product_id` = `p`.`id`) and (`r`.`status` = 'approved')))) GROUP BY `p`.`id`, `c`.`id`, `b`.`id` ;

-- --------------------------------------------------------

--
-- Структура для представления `v_order_details`
--
DROP TABLE IF EXISTS `v_order_details`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_order_details`  AS SELECT `o`.`id` AS `order_id`, `o`.`order_number` AS `order_number`, `o`.`created_at` AS `order_date`, `o`.`user_id` AS `user_id`, concat_ws(' ',`u`.`lastname`,`u`.`firstname`,`u`.`patronymic`) AS `customer_name`, `u`.`email` AS `customer_email`, `u`.`phone` AS `customer_phone`, `os`.`code` AS `order_status_code`, `os`.`title` AS `order_status_title`, `os`.`color_hex` AS `status_color`, `dm`.`code` AS `delivery_code`, `dm`.`title` AS `delivery_method_name`, `dm`.`requires_address` AS `is_home_delivery`, coalesce(`pp`.`title`,'Адресная доставка') AS `pickup_point_title`, coalesce(`o`.`delivery_address_snapshot`,`pp`.`address`) AS `final_destination_address`, `o`.`tracking_number` AS `tracking_number`, `pm`.`title` AS `payment_method_name`, `ps`.`code` AS `payment_status_code`, `ps`.`title` AS `payment_status_title`, `o`.`products_total` AS `products_total`, `o`.`discount_total` AS `discount_total`, `o`.`delivery_cost` AS `delivery_cost`, `o`.`final_total` AS `final_total`, count(`oi`.`id`) AS `total_items_positions`, coalesce(sum(`oi`.`amount`),0) AS `total_units_count` FROM (((((((`orders` `o` join `users` `u` on((`o`.`user_id` = `u`.`id`))) join `order_statuses` `os` on((`o`.`status_id` = `os`.`id`))) join `delivery_methods` `dm` on((`o`.`delivery_method_id` = `dm`.`id`))) left join `pickup_points` `pp` on((`o`.`pickup_point_id` = `pp`.`id`))) join `payment_methods` `pm` on((`o`.`payment_method_id` = `pm`.`id`))) join `payment_statuses` `ps` on((`o`.`payment_status_id` = `ps`.`id`))) left join `order_items` `oi` on((`oi`.`order_id` = `o`.`id`))) GROUP BY `o`.`id`, `u`.`id`, `os`.`id`, `dm`.`id`, `pp`.`id`, `pm`.`id`, `ps`.`id` ;

-- --------------------------------------------------------

--
-- Структура для представления `v_user_cart`
--
DROP TABLE IF EXISTS `v_user_cart`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_user_cart`  AS SELECT `c`.`user_id` AS `user_id`, `c`.`variant_id` AS `variant_id`, `c`.`amount` AS `amount`, `p`.`id` AS `product_id`, `p`.`title` AS `product_title`, `p`.`slug` AS `product_slug`, `v`.`title` AS `variant_title`, `v`.`article` AS `variant_article`, `v`.`price` AS `unit_price`, `v`.`old_price` AS `old_price`, `v`.`amount` AS `in_stock_amount`, (`c`.`amount` * `v`.`price`) AS `subtotal_price`, (select `pi`.`image_url` from `product_images` `pi` where ((`pi`.`variant_id` = `v`.`id`) or (`pi`.`product_id` = `p`.`id`)) order by (`pi`.`variant_id` = `v`.`id`) desc,`pi`.`is_main` desc,`pi`.`id` limit 1) AS `variant_image_url` FROM ((`products_cart` `c` join `products_variants` `v` on((`c`.`variant_id` = `v`.`id`))) join `products` `p` on((`v`.`product_id` = `p`.`id`))) WHERE ((`v`.`is_active` = 1) AND (`p`.`is_active` = 1)) ;

-- --------------------------------------------------------

--
-- Структура для представления `v_user_permissions`
--
DROP TABLE IF EXISTS `v_user_permissions`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_user_permissions`  AS SELECT `u`.`id` AS `user_id`, `u`.`email` AS `email`, `u`.`is_active` AS `user_is_active`, `r`.`id` AS `role_id`, `r`.`code` AS `role_code`, `r`.`name` AS `role_name`, `p`.`id` AS `permission_id`, `p`.`code` AS `permission_code`, `p`.`module` AS `permission_module` FROM (((`users` `u` join `roles` `r` on((`u`.`role_id` = `r`.`id`))) join `role_permissions` `rp` on((`rp`.`role_id` = `r`.`id`))) join `permissions` `p` on((`rp`.`permission_id` = `p`.`id`))) WHERE (`u`.`is_active` = 1) ;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `categories`
--
ALTER TABLE `categories`
  ADD CONSTRAINT `fk_categories_parent` FOREIGN KEY (`parent_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `fk_orders_address` FOREIGN KEY (`address_id`) REFERENCES `user_addresses` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_orders_delivery_method` FOREIGN KEY (`delivery_method_id`) REFERENCES `delivery_methods` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_orders_payment_method` FOREIGN KEY (`payment_method_id`) REFERENCES `payment_methods` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_orders_payment_status` FOREIGN KEY (`payment_status_id`) REFERENCES `payment_statuses` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_orders_pickup_point` FOREIGN KEY (`pickup_point_id`) REFERENCES `pickup_points` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_orders_status` FOREIGN KEY (`status_id`) REFERENCES `order_statuses` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_orders_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `fk_order_items_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_order_items_variant` FOREIGN KEY (`variant_id`) REFERENCES `products_variants` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `order_status_history`
--
ALTER TABLE `order_status_history`
  ADD CONSTRAINT `fk_order_status_history_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_order_status_history_status` FOREIGN KEY (`status_id`) REFERENCES `order_statuses` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_order_status_history_user` FOREIGN KEY (`changed_by_user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `fk_payments_method` FOREIGN KEY (`payment_method_id`) REFERENCES `payment_methods` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_payments_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_payments_status` FOREIGN KEY (`status_id`) REFERENCES `payment_statuses` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `price_history`
--
ALTER TABLE `price_history`
  ADD CONSTRAINT `fk_price_history_variant` FOREIGN KEY (`variant_id`) REFERENCES `products_variants` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `fk_products_brand` FOREIGN KEY (`brand_id`) REFERENCES `brands` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_products_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `products_cart`
--
ALTER TABLE `products_cart`
  ADD CONSTRAINT `fk_cart_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_cart_variant` FOREIGN KEY (`variant_id`) REFERENCES `products_variants` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `products_favorites`
--
ALTER TABLE `products_favorites`
  ADD CONSTRAINT `fk_favorites_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_favorites_variant` FOREIGN KEY (`variant_id`) REFERENCES `products_variants` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `products_variants`
--
ALTER TABLE `products_variants`
  ADD CONSTRAINT `fk_variants_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `product_attributes`
--
ALTER TABLE `product_attributes`
  ADD CONSTRAINT `fk_product_attributes_attribute` FOREIGN KEY (`attribute_id`) REFERENCES `attributes` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_product_attributes_variant` FOREIGN KEY (`variant_id`) REFERENCES `products_variants` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `product_images`
--
ALTER TABLE `product_images`
  ADD CONSTRAINT `fk_product_images_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_product_images_variant` FOREIGN KEY (`variant_id`) REFERENCES `products_variants` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `fk_reviews_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_reviews_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `role_permissions`
--
ALTER TABLE `role_permissions`
  ADD CONSTRAINT `fk_role_permissions_permission` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_role_permissions_role` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `fk_users_role` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `user_addresses`
--
ALTER TABLE `user_addresses`
  ADD CONSTRAINT `fk_user_addresses_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
