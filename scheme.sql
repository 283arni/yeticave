CREATE DATABASE yeticave
    DEFAULT CHARACTER SET utf8
    DEFAULT COLLATE utf8_general_ci;
USE yeticave;

CREATE TABLE categories (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name_cat VARCHAR(60) NOT NULL UNIQUE,
    code_cat VARCHAR(60) NOT NULL UNIQUE
);

CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    dt_add TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    name_user VARCHAR(128) UNIQUE,
    email VARCHAR(128) UNIQUE,
    password_user VARCHAR(255),
    contact TEXT
);

CREATE TABLE lots (
    id INT PRIMARY KEY AUTO_INCREMENT,
    dt_add TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    name_lot VARCHAR(128) NOT NULL,
    desc_lot TEXT,
    image_lot VARCHAR(128),
    price DECIMAL(10, 2) NOT NULL,
    dt_end TIMESTAMP,
    step DECIMAL(10, 2) NOT NULL,
    winner_id INT,
    author_id INT,
    category_id INT,
    FOREIGN KEY (author_id) REFERENCES users(id),
    FOREIGN KEY (winner_id) REFERENCES users(id),
    FOREIGN KEY (category_id) REFERENCES categories(id)
);

CREATE TABLE bets (
    id INT PRIMARY KEY AUTO_INCREMENT,
    dt_add TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    price DECIMAL(10, 2) NOT NULL,
    lot_id INT,
    user_id INT,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (lot_id) REFERENCES lots(id)
);
