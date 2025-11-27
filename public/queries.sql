
INSERT INTO categories (name_cat, code_cat) VALUES
        ('Доски и лыжи', 'boards'),
        ('Крепления', 'fastenings'),
        ('Ботинки', 'boots'),
        ('Одежда', 'clothes'),
        ('Инструменты', 'tools'),
        ('Разное', 'another');

INSERT INTO users (name_user, email, password_user, contact) VALUES
         ('Вова', 'test@test.ru', 'as1234', '89150000020'),
         ('Боря', 'test2@test.ru', 'qw1234', '89151111120');

INSERT INTO lots (name_lot, desc_lot, image_lot, price, dt_end, step, author_id, category_id) VALUES
        ('2014 Rossignol District Snowboard', 'Супер лот 1', 'img/lot-1.jpg', 10999.00, '2025-11-09', 100, 1, 1),
        ('DC Ply Mens 2016/2017 Snowboard', 'Супер лот 2', 'img/lot-2.jpg', 15999.00, '2025-11-08', 200, 1, 1),
        ('Крепления Union Contact Pro 2015 года размер L/XL', 'Супер лот 3', 'img/lot-3.jpg', 8000.00, '2025-11-11', 300, 1, 2),
        ('Ботинки для сноуборда DC Mutiny Charocal', 'Супер лот 4', 'img/lot-4.jpg', 10999.00, '2025-11-12', 400, 1, 3),
        ('Куртка для сноуборда DC Mutiny Charocal', 'Супер лот 5', 'img/lot-5.jpg', 7500.00, '2025-11-13', 500, 2, 4),
        ('Маска Oakley Canopy', 'Супер лот 6', 'img/lot-6.jpg', 5400.00, '2025-11-14', 600, 2, 6);

INSERT INTO bets (price, user_id, lot_id) VALUES
         (15000.00, 1, 1),
         (16000.00, 1, 2);


SELECT name_cat AS "Катигории" FROM categories;
SELECT l.name_lot, l.price, l.image_lot, c.name_cat FROM lots l JOIN categories c ON l.category_id = c.id;
SELECT l.id, dt_add, name_lot, desc_lot, price, name_cat FROM lots l JOIN categories c ON l.category_id = c.id WHERE l.id = 1 ;
UPDATE lots SET dt_end = '2025-12-08' WHERE id = 6;
SELECT b.id, b.dt_add, b.price, b.lot_id, b.user_id FROM bets b JOIN lots l ON b.lot_id = l.id JOIN users u ON b.user_id = u.id WHERE l.id = 2 ORDER BY b.dt_add DESC;
SELECT l.id, l.dt_add, l.name_lot, l.image_lot, c.name_cat FROM lots l JOIN categories c ON l.category_id = c.id ORDER BY l.dt_add DESC LIMIT 9
