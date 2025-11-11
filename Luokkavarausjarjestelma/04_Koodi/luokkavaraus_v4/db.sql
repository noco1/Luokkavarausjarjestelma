CREATE DATABASE luokkavaraus;

USE luokkavaraus;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE classes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    capacity INT DEFAULT NULL,
    location VARCHAR(100) DEFAULT NULL,
    description TEXT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
);

INSERT INTO classes (name, capacity, location, description) VALUES
('Luokka A101', 30, 'Rakennus A', 'Tietokone- ja projektorivarusteltu luokka'),
('Luokka B202', 20, 'Rakennus B', 'Pienempi ryhmätyötila'),
('Laboratorio 1', 15, 'Rakennus C', 'Kemian laboratorio');