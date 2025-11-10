Tiedostot

Seuraavat tiedostot sisältyvät projektiin (kansioon htdocs/luokkavaraus):

- init_db.sql — MySQL-tietokannan luonti + triggerit

- db.php — tietokantayhteys + session

- utils.php — apufunktiot (UUID, respond-json, auth-check)

- index.php — yksi tiedosto joka sisältää kirjautumisen ja sovelluksen käyttöliittymän

- api/login.php, api/logout.php, api/get_session.php — autentikaatio

- api/classrooms.php — CRUD luokkahuoneille (admin)

- api/reservations.php — GET (list), POST (create), PUT (update), DELETE (delete)

- api/users.php — adminin käyttäjähallinta (create)

- assets/style.css — perus-tyylit

- assets/main.js — frontendin JavaScript (fetch API)

- README.md — asennusohjeet ja huomiot