CREATE DATABASE IF NOT EXISTS newsdb;
USE newsdb;

CREATE TABLE articles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255),
    description TEXT,
    content LONGTEXT,
    image VARCHAR(500),
    url VARCHAR(500),
    source_name VARCHAR(100),
    published_at DATETIME,
    category ENUM('nation', 'world', 'business', 'sports', 'entertainment', 'technology', 'health'),
    UNIQUE(title)
);
