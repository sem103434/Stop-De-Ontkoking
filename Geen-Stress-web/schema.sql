CREATE TABLE IF NOT EXISTS gebruikers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    naam VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    wachtwoord_hash VARCHAR(255) NOT NULL,
    rol ENUM('gebruiker', 'admin') NOT NULL DEFAULT 'gebruiker',
    aangemaakt_op DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS categorieen (
    id INT AUTO_INCREMENT PRIMARY KEY,
    naam VARCHAR(50) NOT NULL UNIQUE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS recepten (
    id INT AUTO_INCREMENT PRIMARY KEY,
    gebruiker_id INT NOT NULL,
    categorie_id INT NOT NULL,
    titel VARCHAR(150) NOT NULL,
    bereidingswijze TEXT NOT NULL,
    bereidingstijd_min INT DEFAULT NULL,
    afbeelding_url VARCHAR(255) DEFAULT NULL,
    aangemaakt_op DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (gebruiker_id) REFERENCES gebruikers(id) ON DELETE CASCADE,
    FOREIGN KEY (categorie_id) REFERENCES categorieen(id) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS ingredienten (
    id INT AUTO_INCREMENT PRIMARY KEY,
    naam VARCHAR(100) NOT NULL UNIQUE,
    eenheid VARCHAR(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS recept_ingredienten (
    recept_id INT NOT NULL,
    ingredient_id INT NOT NULL,
    hoeveelheid DECIMAL(6,2) NOT NULL,
    PRIMARY KEY (recept_id, ingredient_id),
    FOREIGN KEY (recept_id) REFERENCES recepten(id) ON DELETE CASCADE,
    FOREIGN KEY (ingredient_id) REFERENCES ingredienten(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO categorieen (naam) VALUES
    ('Ontbijt'), ('Lunch'), ('Diner'), ('Voorgerecht'), ('Hoofdgerecht'), ('Nagerecht');

INSERT INTO gebruikers (naam, email, wachtwoord_hash, rol) VALUES
    ('Beheerder', 'admin@voorbeeld.nl', '$2b$10$hNL6.RuWamP2ko/ASA6VJO2asFnWvru86YOVA26SYjxzH60iLgNDe', 'admin');
