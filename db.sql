
CREATE DATABASE IF NOT EXISTS luokkavarausjarjestelma;
USE luokkavarausjarjestelma;

CREATE TABLE IF NOT EXISTS kayttajat (
    kayttaja_id INT AUTO_INCREMENT PRIMARY KEY,
    nimi VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL
);
CREATE TABLE IF NOT EXISTS tunnit (
    tunti_id INT AUTO_INCREMENT PRIMARY KEY,
    nimi VARCHAR(100),
    kayttaja_id INT NOT NULL,
    FOREIGN KEY (kayttaja_id) REFERENCES kayttajat(kayttaja_id)
);
CREATE TABLE IF NOT EXISTS luokat (
    luokka_id INT AUTO_INCREMENT PRIMARY KEY,
    nimi VARCHAR(100) NOT NULL,
    sijainti VARCHAR(100) NOT NULL,
    kapasiteetti INT NOT NULL
);
CREATE TABLE IF NOT EXISTS varaukset (
    varaus_id INT AUTO_INCREMENT PRIMARY KEY,
    kayttaja_id INT NOT NULL,
    luokka_id INT NOT NULL,
    tunti_id INT NOT NULL,
    aloitusaika DATETIME NOT NULL,
    lopetusaika DATETIME NOT NULL,
    aika_leima TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (kayttaja_id) REFERENCES kayttajat(kayttaja_id),
    FOREIGN KEY (luokka_id) REFERENCES luokat(luokka_id),
    FOREIGN KEY (tunti_id) REFERENCES tunnit(tunti_id)
);

INSERT INTO luokat (nimi, sijainti, kapasiteetti) VALUES 
('Tietokoneluokka 1', '1.kerros', 25),
('Tietokoneluokka 2', '1.kerros', 25),
('Tietokoneluokka 3', '2.kerros', 20),
('Tietokoneluokka 4', '2.kerros', 20),
('Perusluokka 1', '1.kerros', 25),
('Perusluokka 2', '1.kerros', 25),
('Perusluokka 3', '2.kerros', 30),
('Perusluokka 4', '2.kerros', 30),
('Auditorio', '1.kerros', 100),