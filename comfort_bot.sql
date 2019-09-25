-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Сен 25 2019 г., 10:37
-- Версия сервера: 5.7.25
-- Версия PHP: 7.1.32

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `comfort_bot`
--

-- --------------------------------------------------------

--
-- Структура таблицы `auth`
--

CREATE TABLE `auth` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `source` varchar(255) NOT NULL,
  `source_id` varchar(255) NOT NULL,
  `access_token` text
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Дамп данных таблицы `auth`
--

INSERT INTO `auth` (`id`, `user_id`, `source`, `source_id`, `access_token`) VALUES
(1, 1, 'telegram', '384607648', NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `base`
--

CREATE TABLE `base` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Дамп данных таблицы `base`
--

INSERT INTO `base` (`id`, `user_id`) VALUES
(2, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `bots`
--

CREATE TABLE `bots` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `base_id` int(11) DEFAULT NULL,
  `platform` varchar(255) NOT NULL,
  `platform_id` bigint(100) DEFAULT NULL,
  `username` varchar(255) DEFAULT NULL,
  `first_name` varchar(255) DEFAULT NULL,
  `token` text,
  `status` smallint(6) NOT NULL DEFAULT '0',
  `updated_at` int(11) DEFAULT NULL,
  `created_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Дамп данных таблицы `bots`
--

INSERT INTO `bots` (`id`, `user_id`, `base_id`, `platform`, `platform_id`, `username`, `first_name`, `token`, `status`, `updated_at`, `created_at`) VALUES
(6, 1, 2, 'telegram', 673442811, 'faberlic1331bot', 'faberlic1331bot', '673442811:AAGx67ASfQNz4gJpizyp9caIdVwCs_fYyLw', 10, 1568884241, 1558371504),
(7, 1, 2, 'viber', 5337748042716534067, 'faynatown_bot', 'ЖК Файна Таун ', '4a137d9072a7d533-a71e07b9084b17ed-b897132f75e5d928', 10, 1564678411, 1558438691);

-- --------------------------------------------------------

--
-- Структура таблицы `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `base_id` int(11) DEFAULT NULL,
  `bot_id` int(11) DEFAULT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `apartment` varchar(255) DEFAULT NULL COMMENT 'Квартира',
  `section` varchar(255) DEFAULT NULL COMMENT 'Секция',
  `score` varchar(255) DEFAULT NULL COMMENT 'Счет',
  `transfer_form` varchar(255) DEFAULT NULL COMMENT 'Форма передачи',
  `addition_info` varchar(255) DEFAULT NULL COMMENT 'Дополнительная информация',
  `status` smallint(6) DEFAULT NULL,
  `update_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `create_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Структура таблицы `commands`
--

CREATE TABLE `commands` (
  `id` int(11) NOT NULL,
  `base_id` int(11) DEFAULT NULL,
  `command` varchar(255) DEFAULT NULL,
  `text` text,
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Дамп данных таблицы `commands`
--

INSERT INTO `commands` (`id`, `base_id`, `command`, `text`, `created_at`, `updated_at`) VALUES
(2, 2, '/start', 'Здравствуйте 👋 \r\nЯ официальный чат-бот Живого Квартала \"Файна Таун\".🤝\r\nЯ помогу Вам оформить:\r\n- запросы на оформление счета\r\n- выбрать способ получения счета\r\nБыстро и удобно😉:) \r\nИ так начнем, нажмите кнопку «Оформить счет» 👇\r\n', 1558371505, 1564503006),
(3, 2, '/yoow', 'Yoow yoow yoow', 1564395345, 1564395345);

-- --------------------------------------------------------

--
-- Структура таблицы `customer`
--

CREATE TABLE `customer` (
  `id` int(11) NOT NULL,
  `platform` varchar(255) DEFAULT NULL,
  `platform_id` varchar(255) DEFAULT NULL,
  `base_id` int(11) DEFAULT NULL,
  `bot_id` int(11) DEFAULT NULL,
  `first_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `username` varchar(255) DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `tags` text NOT NULL,
  `relation` int(11) DEFAULT NULL,
  `status` smallint(6) NOT NULL DEFAULT '10',
  `link` varchar(255) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  `created_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Структура таблицы `menu`
--

CREATE TABLE `menu` (
  `id` int(11) NOT NULL,
  `base_id` int(11) DEFAULT NULL,
  `name` text,
  `function` json DEFAULT NULL,
  `text` text,
  `sort` smallint(6) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Дамп данных таблицы `menu`
--

INSERT INTO `menu` (`id`, `base_id`, `name`, `function`, `text`, `sort`, `created_at`, `updated_at`) VALUES
(7, 2, '📝Оформить cчет ', '\"{\\\"class\\\":\\\"app\\\\\\\\hook\\\\\\\\models\\\\\\\\{{platform}}\\\\\\\\BasePhrases\\\",\\\"method\\\":\\\"checkWaitingRecord\\\"}\"', '', 1, 1558371505, 1564671363);

-- --------------------------------------------------------

--
-- Структура таблицы `migration`
--

CREATE TABLE `migration` (
  `version` varchar(180) NOT NULL,
  `apply_time` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Дамп данных таблицы `migration`
--

INSERT INTO `migration` (`version`, `apply_time`) VALUES
('m000000_000000_base', 1554795246),
('m180102_141114_create_table_user', 1554795248),
('m180102_141115_create_table_base', 1554795248),
('m180102_141116_create_table_bots', 1554795248),
('m180102_141118_create_auth_social', 1554795249),
('m180102_141119_create_table_customer', 1554795249),
('m180102_141120_create_table_widget', 1554795249),
('m180102_141121_create_table_newsletter', 1554795249),
('m180102_141122_create_table_newsletter_messages', 1554795250),
('m180102_141127_create_table_menu', 1554795251),
('m180102_141128_create_table_commands', 1554795251),
('m180102_141129_create_table_setting', 1554795252),
('m180102_141130_create_table_cart', 1564414521),
('m180102_141131_create_table_order', 1564414522),
('m180102_141132_add_setting_fields', 1567780669),
('m180102_141133_add_setting_fields_btn', 1568883583),
('m190923_153847_add_link_column_to_customer_table', 1569254332);

-- --------------------------------------------------------

--
-- Структура таблицы `newsletter`
--

CREATE TABLE `newsletter` (
  `id` int(11) NOT NULL,
  `base_id` int(11) DEFAULT NULL,
  `settings` text,
  `message` text,
  `media` json DEFAULT NULL,
  `media_type` json DEFAULT NULL,
  `buttons` json DEFAULT NULL,
  `status` smallint(6) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Структура таблицы `newsletter_messages`
--

CREATE TABLE `newsletter_messages` (
  `id` int(11) NOT NULL,
  `bot_id` int(11) DEFAULT NULL,
  `base_id` int(11) DEFAULT NULL,
  `platform_id` varchar(255) DEFAULT NULL,
  `platform` varchar(255) DEFAULT NULL,
  `newsletter_id` int(11) DEFAULT NULL,
  `message` text,
  `media` json DEFAULT NULL,
  `media_type` json DEFAULT NULL,
  `buttons` json DEFAULT NULL,
  `status` smallint(6) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Структура таблицы `order`
--

CREATE TABLE `order` (
  `id` int(11) NOT NULL,
  `base_id` int(11) DEFAULT NULL,
  `bot_id` int(11) DEFAULT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `apartment` varchar(255) DEFAULT NULL COMMENT 'Квартира',
  `section` varchar(255) DEFAULT NULL COMMENT 'Секция',
  `score` varchar(255) DEFAULT NULL COMMENT 'Счет',
  `transfer_form` varchar(255) DEFAULT NULL COMMENT 'Форма передачи',
  `addition_info` varchar(255) DEFAULT NULL COMMENT 'Дополнительная информация',
  `status` smallint(6) DEFAULT NULL,
  `update_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `create_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Структура таблицы `setting`
--

CREATE TABLE `setting` (
  `id` int(11) NOT NULL,
  `base_id` int(11) DEFAULT NULL,
  `key` varchar(255) DEFAULT NULL,
  `value` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Дамп данных таблицы `setting`
--

INSERT INTO `setting` (`id`, `base_id`, `key`, `value`) VALUES
(55, 2, 'menu_line_items', '2'),
(78, 2, 'message_write_name', 'Введите свое имя ✏️'),
(80, 2, 'message_write_phone_number', 'Нажмите кнопку снизу, что бы отправить номер телефона.\r\nНе вводите вручную!'),
(81, 2, 'button_send_phone_number', '☎️ Отправить телефон'),
(83, 2, 'button_send_order', '✅ Подтвердить и отправить'),
(103, 2, 'message_write_phone', 'Напишите Ваш телефон в формате +380999999999:'),
(113, 2, 'admin_email', 'voronayu1331@gmail.com'),
(114, 2, 'message_write_apartment', 'Введите номер квартиры ✏️'),
(115, 2, 'message_write_section', 'Введите номер секции  ✏️'),
(116, 2, 'message_write_score', 'Выберите счет 🔘'),
(117, 2, 'message_write_transfer_form', 'Выберите форму передачи ☑'),
(118, 2, 'message_write_email', 'Введите свой email ✏'),
(119, 2, 'message_success_order', 'Спасибо за Вашу заявку. Счёт будет готов в ближайшее время.😊'),
(121, 2, 'message_write_comment', 'Введите свой комментарий ✏'),
(122, 2, 'button_edit_order_full', '✏️ Редактировать все данные'),
(123, 2, 'button_edit_order_apartment', '✏️ Редактировать данные апартаментов'),
(124, 2, 'message_email_format', 'Введите email в формате <b>email@email.com</b>'),
(125, 2, 'message_phone_format', 'Введите телефон в формате +380999999999'),
(126, 2, 'message_error_to_checkout', 'Ошибка. Нажмите кнопку \"Оформить заявку\"'),
(127, 2, 'message_email_title', 'Новая заявка с бота'),
(128, 2, 'message_order_info', 'Имя: {{name}}\r\nТелефон: {{phone}}\r\nКвартира №: {{apartment}}\r\nСекция №: {{section}}\r\nСчет: {{score}}\r\nФорма передачи: {{transfer_form}}\r\nEmail: {{email}}\r\nКомментарий: {{comment}}'),
(129, 2, 'button_score_1', 'Плановый'),
(130, 2, 'button_score_2', 'Досрочно'),
(131, 2, 'button_score_3', 'Каникулы'),
(132, 2, 'button_score_4', 'Индивидуальный'),
(133, 2, 'button_score_5', 'Не выбрано'),
(134, 2, 'button_transfer_form_1', 'E-mail'),
(135, 2, 'button_transfer_form_2', 'Лично в руки в отделе продаж'),
(136, 2, 'button_transfer_form_3', 'Не выбрано');

-- --------------------------------------------------------

--
-- Структура таблицы `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `surname` varchar(255) DEFAULT NULL,
  `lastname` varchar(255) DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `auth_key` varchar(32) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `password_reset_token` varchar(255) DEFAULT NULL,
  `status` smallint(6) NOT NULL DEFAULT '0',
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Дамп данных таблицы `user`
--

INSERT INTO `user` (`id`, `name`, `surname`, `lastname`, `avatar`, `phone`, `email`, `auth_key`, `password_hash`, `password_reset_token`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Admin', '', 'ComfortTown', '1_1564393250.jpg', '', 'admin@admin.net', 'qSSvyU1R9sXR9rdVzGu8qLy21d6oNnFI', '$2y$10$bGI46OymtidnhzU4KcLuCeFU1bQNapPNlCfuNvTDQwQ4kM9/W2CLK', '5PgnnS_KsbjvncvfcVOlKO-JXBRUN6jp_1554795752', 10, 1554795752, 1564393250);

-- --------------------------------------------------------

--
-- Структура таблицы `widget`
--

CREATE TABLE `widget` (
  `id` int(11) NOT NULL,
  `base_id` int(11) DEFAULT NULL,
  `settings` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `auth`
--
ALTER TABLE `auth`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk-auth-user_id-user-id` (`user_id`);

--
-- Индексы таблицы `base`
--
ALTER TABLE `base`
  ADD PRIMARY KEY (`id`),
  ADD KEY `base-user_id` (`user_id`);

--
-- Индексы таблицы `bots`
--
ALTER TABLE `bots`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk-bots-user_id-id` (`user_id`),
  ADD KEY `fk-bots-base_id-id` (`base_id`);

--
-- Индексы таблицы `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx-cart-customer_id` (`customer_id`),
  ADD KEY `idx-cart-id` (`id`),
  ADD KEY `idx-cart-bot_id` (`bot_id`),
  ADD KEY `idx-cart-base_id` (`base_id`);

--
-- Индексы таблицы `commands`
--
ALTER TABLE `commands`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx-commands-base_id` (`base_id`);

--
-- Индексы таблицы `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk-customer-base_id-base_id` (`base_id`),
  ADD KEY `fk-customer-bot_id-bot_id` (`bot_id`);

--
-- Индексы таблицы `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx-menu-base_id` (`base_id`);

--
-- Индексы таблицы `migration`
--
ALTER TABLE `migration`
  ADD PRIMARY KEY (`version`);

--
-- Индексы таблицы `newsletter`
--
ALTER TABLE `newsletter`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk-newsletter-base_id-id` (`base_id`);

--
-- Индексы таблицы `newsletter_messages`
--
ALTER TABLE `newsletter_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk-newsletter_messages-base_id-id` (`base_id`),
  ADD KEY `fk-newsletter_messages-bot_id-id` (`bot_id`),
  ADD KEY `fk-newsletter_messages-newsletter_id-id` (`newsletter_id`);

--
-- Индексы таблицы `order`
--
ALTER TABLE `order`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx-order-customer_id` (`customer_id`),
  ADD KEY `idx-order-id` (`id`),
  ADD KEY `idx-order-bot_id` (`bot_id`),
  ADD KEY `idx-order-base_id` (`base_id`);

--
-- Индексы таблицы `setting`
--
ALTER TABLE `setting`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx-setting-base_id` (`base_id`),
  ADD KEY `idx-setting-key` (`key`);

--
-- Индексы таблицы `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `password_reset_token` (`password_reset_token`);

--
-- Индексы таблицы `widget`
--
ALTER TABLE `widget`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk-widget-base_id-base_id` (`base_id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `auth`
--
ALTER TABLE `auth`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT для таблицы `base`
--
ALTER TABLE `base`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблицы `bots`
--
ALTER TABLE `bots`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT для таблицы `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `commands`
--
ALTER TABLE `commands`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT для таблицы `customer`
--
ALTER TABLE `customer`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `menu`
--
ALTER TABLE `menu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT для таблицы `newsletter`
--
ALTER TABLE `newsletter`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `newsletter_messages`
--
ALTER TABLE `newsletter_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `order`
--
ALTER TABLE `order`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `setting`
--
ALTER TABLE `setting`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=137;

--
-- AUTO_INCREMENT для таблицы `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT для таблицы `widget`
--
ALTER TABLE `widget`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `auth`
--
ALTER TABLE `auth`
  ADD CONSTRAINT `fk-auth-user_id-user-id` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `base`
--
ALTER TABLE `base`
  ADD CONSTRAINT `base-user_id` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `bots`
--
ALTER TABLE `bots`
  ADD CONSTRAINT `fk-bots-base_id-id` FOREIGN KEY (`base_id`) REFERENCES `base` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk-bots-user_id-id` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `fk-cart-base_id-id` FOREIGN KEY (`base_id`) REFERENCES `base` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk-cart-bot_id-id` FOREIGN KEY (`bot_id`) REFERENCES `bots` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk-cart-customer_id-id` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `commands`
--
ALTER TABLE `commands`
  ADD CONSTRAINT `fk-commands-base_id-id` FOREIGN KEY (`base_id`) REFERENCES `base` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `customer`
--
ALTER TABLE `customer`
  ADD CONSTRAINT `fk-customer-base_id-base_id` FOREIGN KEY (`base_id`) REFERENCES `base` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk-customer-bot_id-bot_id` FOREIGN KEY (`bot_id`) REFERENCES `bots` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `menu`
--
ALTER TABLE `menu`
  ADD CONSTRAINT `fk-menu-base_id-id` FOREIGN KEY (`base_id`) REFERENCES `base` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `newsletter`
--
ALTER TABLE `newsletter`
  ADD CONSTRAINT `fk-newsletter-base_id-id` FOREIGN KEY (`base_id`) REFERENCES `base` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `newsletter_messages`
--
ALTER TABLE `newsletter_messages`
  ADD CONSTRAINT `fk-newsletter_messages-base_id-id` FOREIGN KEY (`base_id`) REFERENCES `base` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk-newsletter_messages-bot_id-id` FOREIGN KEY (`bot_id`) REFERENCES `bots` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk-newsletter_messages-newsletter_id-id` FOREIGN KEY (`newsletter_id`) REFERENCES `newsletter` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `order`
--
ALTER TABLE `order`
  ADD CONSTRAINT `fk-order-base_id-id` FOREIGN KEY (`base_id`) REFERENCES `base` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk-order-bot_id-id` FOREIGN KEY (`bot_id`) REFERENCES `bots` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk-order-customer_id-id` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `setting`
--
ALTER TABLE `setting`
  ADD CONSTRAINT `fk-setting-base_id-id` FOREIGN KEY (`base_id`) REFERENCES `base` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `widget`
--
ALTER TABLE `widget`
  ADD CONSTRAINT `fk-widget-base_id-base_id` FOREIGN KEY (`base_id`) REFERENCES `base` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
