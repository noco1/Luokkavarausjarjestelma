Tänne arkistoidaan vanhat versiot

Arkistointi syy VANHA_init_db.sql:

- Korjattiin virhe ilmoitus:
    Staattinen analyysi:

    5 virhettä havaittu analyysin aikana.

    Odottamaton ilmaisun alku. (near "generate" at position 54)
    Odottamaton ilmaisun alku. (near "UUIDs" at position 63)
    Odottamaton ilmaisun alku. (near "via" at position 69)
    Odottamaton ilmaisun alku. (near "MySQL" at position 73)
    Unrecognized statement type. (near "UUID" at position 79)
    SQL-kysely: Kopioi

    -- Tyyppiesimerkki (täydellinen MySQL-tietokanta): (generate UUIDs via MySQL UUID()) INSERT INTO users (id, email, password_hash, full_name, role) VALUES (UUID(), 'admin@koulu.fi', MD5('admin123'), 'Admin', 'admin'), (UUID(), 'opettaja@koulu.fi', MD5('opettaja123'), 'Opettaja', 'teacher'), (UUID(), 'opiskelija@koulu.fi', MD5('opiskelija123'), 'Opiskelija', 'student');

    MySQL ilmoittaa: Ohjeet

    #1064 - You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near 'generate UUIDs via MySQL UUID())
    INSERT INTO users (id, email, password_hash...' at line 2
