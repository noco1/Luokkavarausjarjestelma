CREATE TABLE classes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    capacity INT DEFAULT NULL,
    location VARCHAR(100) DEFAULT NULL,
    desctiption TEXT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO classes (name, capacity, location, description)
VALUES
('Luokka A101', 30, 'Rakennus A', 'Tietokone- ja projektorivarustelu luokka'),
('Luokka B202', 25, 'Rakennus B', 'Pienempi ryhmätyötila'),
('Laboratorio 1', 15, 'Rakennus C', 'Kemian laboratorio');