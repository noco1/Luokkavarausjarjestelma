CREATE DATABASE IF NOT EXISTS luokkavarausjarjestelma CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE luokkavarausjarjestelma;

-- Taulu: users

CREATE TABLE users (
    id CHAR(36) PRIMARY KEY, -- UUID (esim. PHP: uuid_create(UUID_TYPE_RANDOM))
    email VARCHAR(255) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    full_name VARCHAR(150) NOT NULL,
    role ENUM('admin', 'teacher', 'student') NOT NULL DEFAULT 'student',
    phone VARCHAR(50),
    is_active BOOLEAN NOT NULL DEFAULT TRUE,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Käytä PHP:n password_hash() / password_verify() salasanoille.

-- Luo UUID esim. UUID()-funktiolla MySQL:ssä tai PHP:ssa uuid_create(UUID_TYPE_RANDOM).

-- Taulu: classrooms

CREATE TABLE classrooms (
    id CHAR(36) PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE,
    location VARCHAR(100),
    capacity INT NOT NULL DEFAULT 0,
    resources JSON DEFAULT (JSON_OBJECT()),
    is_active BOOLEAN NOT NULL DEFAULT TRUE,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- resources-kentässä voi säilyttää varusteita:
esim. { "projector": true, "computers": 25 }.

-- JSON-tietotyyppi on tuettu MySQL 5.7+:ssa.

-- Taulu: reservations

CREATE TABLE reservations (
    id CHAR(36) PRIMARY KEY,
    user_id CHAR(36) NOT NULL,
    classroom_id CHAR(36) NOT NULL,
    start_time DATETIME NOT NULL,
    end_time DATETIME NOT NULL,
    purpose VARCHAR(255),
    participants INT,
    status ENUM('pending', 'confirmed', 'cancelled') DEFAULT 'pending',
    created_by CHAR(36),
    recurring_id CHAR(36) NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_res_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE RESTRICT,
    CONSTRAINT fk_res_class FOREIGN KEY (classroom_id) REFERENCES classrooms(id) ON DELETE RESTRICT,
    CONSTRAINT fk_res_creator FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL,
    CONSTRAINT chk_time_valid CHECK (end_time > start_time)
);

-- Huomio: Käytä application logicissa päällekkäisyyden tarkistusta:

SELECT COUNT(*) FROM reservations
WHERE classroom_id = ?
AND status IN ('pending','confirmed')
AND (
  (start_time < @end_time) AND (end_time > @start_time)
);

-- Jos tulos > 0 → päällekkäinen varaus → hylätään.


-- Taulu: reservation_audit_log

-- Auditointia varten — tallentaa muutokset varauksiin.

CREATE TABLE reservation_audit_log (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    reservation_id CHAR(36) NOT NULL,
    user_id CHAR(36) NOT NULL,
    action ENUM('create','update','delete','status_change') NOT NULL,
    old_data JSON,
    new_data JSON,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (reservation_id) REFERENCES reservations(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);

-- Voit laukaista tämän AFTER INSERT, AFTER UPDATE, AFTER DELETE -triggereillä:

DELIMITER $$

CREATE TRIGGER reservation_insert_audit
AFTER INSERT ON reservations
FOR EACH ROW
BEGIN
  INSERT INTO reservation_audit_log (reservation_id, user_id, action, new_data)
  VALUES (NEW.id, NEW.user_id, 'create', JSON_OBJECT('start_time', NEW.start_time, 'end_time', NEW.end_time));
END$$

DELIMITER ;

-- Indeksit ja optimointi

CREATE INDEX idx_reservations_classroom_time ON reservations (classroom_id, start_time);
CREATE INDEX idx_users_email ON users (email);
CREATE INDEX idx_classrooms_name ON classrooms (name);

-- Näillä: Haku luokan varauksista nopeutuu ja käyttäjähaku sähköpostilla on tehokas.

-- MySQL ei tue GENERATE_SERIES() kuten PostgreSQL, joten vaihtoehdot:

	-- 1. Sovelluslogiikassa: luo useita varausrivejä ennakkoon.

	-- 2. Käytä recurring_id-kenttää yhdistämään varaukset samaan sarjaan.
