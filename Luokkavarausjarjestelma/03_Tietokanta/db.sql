CREATE DATABASE luokkavarausjarjestelma;
USE luokkavarausjarjestelma;
CREATE TABLE kayttajat (
    kayttaja_id INT AUTO_INCREMENT PRIMARY KEY,
    nimi VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    salasana VARCHAR(255) NOT NULL,
    rooli ENUM('opiskelija', 'opettaja', 'admin') DEFAULT 'opiskelija'
);
CREATE TABLE tunnit (
    tunti_id INT AUTO_INCREMENT PRIMARY KEY,
    nimi VARCHAR(100) NOT NULL,
    kayttaja_id INT NOT NULL,
    FOREIGN KEY (kayttaja_id) REFERENCES kayttajat(kayttaja_id)
);
CREATE TABLE luokat (
    luokka_id INT AUTO_INCREMENT PRIMARY KEY,
    nimi VARCHAR(100) NOT NULL,
    sijainti VARCHAR(100) NOT NULL,
    kapasiteetti INT NOT NULL
);
CREATE TABLE varaukset (
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
INSERT INTO kayttajat (nimi, email, salasana, rooli) VALUES
('Admin', 'admin@koulu.fi', MD5('admin123'), 'admin'),
('Opettaja', 'opettaja@koulu.fi', MD5('opettaja123'), 'opettaja'),
('Opiskelija', 'opiskelija@koulu.fi', MD5('opiskelija123'), 'opiskelija');
INSERT INTO luokat (nimi, sijainti, kapasiteetti) VALUES 
('Tietokoneluokka 1', '1.kerros', 25),
('Tietokoneluokka 2', '1.kerros', 25),
('Perusluokka 1', '2.kerros', 30);
