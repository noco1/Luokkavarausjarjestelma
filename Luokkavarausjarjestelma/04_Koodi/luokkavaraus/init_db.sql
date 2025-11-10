-- init_db.sql
CREATE DATABASE IF NOT EXISTS luokkavarausjarjestelma CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE luokkavarausjarjestelma;


-- Users
CREATE TABLE IF NOT EXISTS users (
    id CHAR(36) PRIMARY KEY,  -- UUID (esim. PHP: uuid_create(UUID_TYPE_RANDOM))
    email VARCHAR(255) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    full_name VARCHAR(150) NOT NULL,
    role ENUM('admin','teacher','student') NOT NULL DEFAULT 'student',
    phone VARCHAR(50),
    is_active BOOLEAN NOT NULL DEFAULT TRUE,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Käytä PHP:n password_hash() / password_verify() salasanoille.

-- Luo UUID esim. UUID()-funktiolla MySQL:ssä tai PHP:ssa uuid_create(UUID_TYPE_RANDOM).

-- Classrooms
CREATE TABLE IF NOT EXISTS classrooms (
    id CHAR(36) PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE,
    location VARCHAR(100),
    capacity INT NOT NULL DEFAULT 0,
    resources JSON DEFAULT (JSON_OBJECT()),
    is_active BOOLEAN NOT NULL DEFAULT TRUE,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);


-- Reservations
CREATE TABLE IF NOT EXISTS reservations (
    id CHAR(36) PRIMARY KEY,
    user_id CHAR(36) NOT NULL,
    classroom_id CHAR(36) NOT NULL,
    start_time DATETIME NOT NULL,
    end_time DATETIME NOT NULL,
    purpose VARCHAR(255),
    participants INT,
    status ENUM('pending','confirmed','cancelled') DEFAULT 'pending',
    created_by CHAR(36),
    recurring_id CHAR(36),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_res_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE RESTRICT,
    CONSTRAINT fk_res_class FOREIGN KEY (classroom_id) REFERENCES classrooms(id) ON DELETE RESTRICT,
    CONSTRAINT fk_res_creator FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL,
    CONSTRAINT chk_time_valid CHECK (end_time > start_time)
);


-- Audit log
-- Auditointia varten — tallentaa muutokset varauksiin.
CREATE TABLE IF NOT EXISTS reservation_audit_log (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    reservation_id CHAR(36) NOT NULL,
    user_id CHAR(36),
    action ENUM('create','update','delete','status_change') NOT NULL,
    old_data JSON,
    new_data JSON,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_audit_res FOREIGN KEY (reservation_id) REFERENCES reservations(id) ON DELETE CASCADE,
    CONSTRAINT fk_audit_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);


-- Indeksit ja optimointi
CREATE INDEX idx_reservations_classroom_time ON reservations (classroom_id, start_time);
CREATE INDEX idx_users_email ON users (email);
CREATE INDEX idx_classrooms_name ON classrooms (name);

-- Tyypit: Käyttäjien lisäys UUID-tunnuksilla
INSERT INTO users (id, email, password_hash, full_name, role)
VALUES
(UUID(), 'admin@koulu.fi', MD5('admin123'), 'Admin', 'admin'),
(UUID(), 'opettaja@koulu.fi', MD5('opettaja123'), 'Opettaja', 'teacher'),
(UUID(), 'opiskelija@koulu.fi', MD5('opiskelija123'), 'Opiskelija', 'student');

-- Luokat: Esimerkkejä luokkien lisäämisestä UUID-tunnuksilla
INSERT INTO classrooms (id, name, location, capacity, resources) VALUES
(1, 'Luokka 101', 'Rakennus A, 1. kerros', 30, JSON_OBJECT('projector', true, 'whiteboard', true)),
(2, 'Luokka 102', 'Rakennus A, 1. kerros', 30, JSON_OBJECT('projector', true, 'whiteboard', true)),
(3, 'Luokka 103', 'Rakennus A, 1. kerros', 30, JSON_OBJECT('projector', true, 'whiteboard', true)),
(4, 'Luokka 104', 'Rakennus A, 1. kerros', 30, JSON_OBJECT('projector', true, 'whiteboard', true)),
(5, 'Luokka 105', 'Rakennus B, 1. kerros', 30, JSON_OBJECT('projector', true, 'whiteboard', true)),
(6, 'Luokka 106', 'Rakennus B, 1. kerros', 30, JSON_OBJECT('projector', true, 'whiteboard', true)),
(7, 'Luokka 107', 'Rakennus B, 1. kerros', 30, JSON_OBJECT('projector', true, 'whiteboard', true)),
(8, 'Luokka 108', 'Rakennus B, 1. kerros', 30, JSON_OBJECT('projector', true, 'whiteboard', true)),
(9, 'Luokka 201', 'Rakennus A, 2. kerros', 30, JSON_OBJECT('projector', true, 'whiteboard', true)),
(10, 'Luokka 202', 'Rakennus A, 2. kerros', 30, JSON_OBJECT('projector', true, 'whiteboard', true)),
(11, 'Luokka 203', 'Rakennus A, 2. kerros', 30, JSON_OBJECT('projector', true, 'whiteboard', true)),
(12, 'Luokka 204', 'Rakennus A, 2. kerros', 30, JSON_OBJECT('projector', true, 'whiteboard', true)),
(13, 'Luokka 205', 'Rakennus B, 2. kerros', 30, JSON_OBJECT('projector', true, 'whiteboard', true)),
(14, 'Luokka 206', 'Rakennus B, 2. kerros', 30, JSON_OBJECT('projector', true, 'whiteboard', true)),
(15, 'Luokka 207', 'Rakennus B, 2. kerros', 30, JSON_OBJECT('projector', true, 'whiteboard', true)),
(16, 'Luokka 208', 'Rakennus B, 2. kerros', 30, JSON_OBJECT('projector', true, 'whiteboard', true));

DELIMITER ;