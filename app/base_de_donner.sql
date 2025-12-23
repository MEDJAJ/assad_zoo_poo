
CREATE DATABASE   assad_zoo

USE assad_zoo;

CREATE TABLE Utilisateur (
    id_utilisateure INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    role ENUM('admin','guide','visitor') NOT NULL,
    mot_passe VARCHAR(255) NOT NULL,
    status_utilisateure BOOLEAN DEFAULT FALSE,
    paye VARCHAR(100),
    date_inscription TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


CREATE TABLE habitats (
    id_habitat INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    typeclimat VARCHAR(100),
    description TEXT,
    zonezoo VARCHAR(100)
);


CREATE TABLE animaux (
    id_animal INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    espece VARCHAR(100),
    alimentation VARCHAR(100),
    image VARCHAR(255),
    pays_origine VARCHAR(100),
    description TEXT,
    id_habitat INT,
    FOREIGN KEY (id_habitat) REFERENCES habitats(id_habitat) ON DELETE SET NULL
);


CREATE TABLE visite_guidee (
    id_visiteguide INT AUTO_INCREMENT PRIMARY KEY,
    titre VARCHAR(150),
    date_heure DATETIME,
    langue VARCHAR(50),
    capaciter_max INT,
    duree INT,
    prix DECIMAL(10,2),
    status_visiteguide ENUM('Disponible','Limité','Annulée') DEFAULT 'Disponible',
    id_guide INT,
    FOREIGN KEY (id_guide) REFERENCES Utilisateur(id_utilisateure)
);


CREATE TABLE etapevisite (
    id_etape INT AUTO_INCREMENT PRIMARY KEY,
    titre_etape VARCHAR(150),
    description_etape TEXT,
    ordre_etape INT,
    id_visite INT,
    FOREIGN KEY (id_visite) REFERENCES visite_guidee(id_visiteguide) ON DELETE CASCADE
);


CREATE TABLE reservation (
    id_reservation INT AUTO_INCREMENT PRIMARY KEY,
    nb_personnes INT,
    id_utilisateure INT,
    id_visiteguide INT,
    date_reservation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_utilisateure) REFERENCES Utilisateur(id_utilisateure),
    FOREIGN KEY (id_visiteguide) REFERENCES visite_guidee(id_visiteguide)
);



SELECT * FROM Utilisateur;
SELECT * FROM animaux;
SELECT * FROM visite_guidee;
SELECT * FROM reservation;

SELECT * FROM Utilisateur WHERE role='visitor';
SELECT * FROM Utilisateur WHERE role='guide';
SELECT * FROM Utilisateur WHERE role != 'admin';


INSERT INTO Utilisateur (nom, email, role, mot_passe, status_utilisateure, paye)
VALUES ('mohammede', 'jajaa@gmail.com', 'guide', 'Jajaa1code', FALSE, 'morroco');


SELECT * FROM Utilisateur WHERE email='jajaa@gmail.com';


INSERT INTO animaux 
(nom, espece, alimentation, image, pays_origine, description, id_habitat)
VALUES ('Lion de l’Atlas', 'Panthera leo leo', 'Carnivore', 'lion_atlas.jpg', 'Maroc', 'Le lion de l’Atlas est un lion emblématique du Maghreb, en danger critique d’extinction.', 1);


UPDATE animaux SET 
    nom='Lion de l’Atlas',
    espece='Panthera leo leo',
    alimentation='Carnivore',
    image='lion_atlas_updated.jpg',
    pays_origine='Maroc',
    description='Le lion de l’Atlas est un lion emblématique du Maghreb, en danger critique d’extinction. Mise à jour des informations.',
    id_habitat=1
WHERE id_animal=1;



DELETE FROM animaux WHERE id_animal=1;


INSERT INTO habitats (nom, typeclimat, description, zonezoo)
VALUES (
    'Savane Africaine',
    'Tropical Sec',
    'Habitat représentant la savane africaine avec des herbes hautes et quelques arbres isolés, idéal pour les lions, éléphants et girafes.',
    'Zone Mammifères'
);


UPDATE habitats SET 
    nom='Savane Africaine',
    typeclimat='Tropical Sec',
    description='Habitat représentant la savane africaine avec des herbes hautes et quelques arbres isolés, idéal pour les lions, éléphants et girafes. Mise à jour des informations.',
    zonezoo='Zone Mammifères'
WHERE id_habitat=1;



DELETE FROM habitats WHERE id_habitat=1;


UPDATE Utilisateur 
SET status_utilisateure=TRUE
WHERE id_utilisateure=3;


INSERT INTO visite_guidee 
(titre, date_heure, langue, capaciter_max, duree, prix, status_visiteguide, id_guide)
VALUES(
    'Découverte des Lions de l’Atlas',
    '2025-01-15 10:00:00',
    'Français',
    20,
    120,
    50.00,
    'Disponible',
    2
);


UPDATE visite_guidee SET 
    titre='Découverte des Lions et des Éléphants',
    date_heure='2025-01-15 11:00:00',
    langue='Français',
    capaciter_max=25,
    duree=150,
    prix=60.00,
    status_visiteguide='Disponible',
    id_guide=2
WHERE id_visiteguide=1;


DELETE FROM visite_guidee WHERE id_visiteguide=1;

SELECT * FROM visite_guidee 
WHERE status_visiteguide='Disponible' OR status_visiteguide='Limité';


INSERT INTO etapevisite (titre_etape, description_etape, ordre_etape, id_visite) VALUES
('Zone Oiseaux', 'Observation des perroquets et autres oiseaux exotiques.', 2, 1),
('Zone Reptiles', 'Découverte des crocodiles et serpents.', 3, 1);


INSERT INTO reservation (nb_personnes, id_utilisateure, id_visiteguide)
VALUES (4, 5, 1);


CREATE TABLE commentaires (
    id_commentaire INT AUTO_INCREMENT PRIMARY KEY,
    id_visiteguide INT NOT NULL,
    id_utilisateure INT NOT NULL,
    note INT CHECK (note BETWEEN 1 AND 5),
    texte TEXT,
    date_commentaire TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_visiteguide) REFERENCES visite_guidee(id_visiteguide) ON DELETE CASCADE,
    FOREIGN KEY (id_utilisateure) REFERENCES Utilisateur(id_utilisateure) ON DELETE CASCADE
);

INSERT INTO commentaires (id_visiteguide, id_utilisateure, note, texte) VALUES
(1, 6, 4, 'Très belle visite, guide très compétent.'),
(2, 7, 3, 'Intéressant mais un peu long pour les enfants.');

SELECT *
FROM commentaires
WHERE id_visiteguide = 1;


SELECT SUM(nb_personnes) AS total_personnes
FROM reservation
WHERE id_visiteguide=1;


SELECT *
FROM Utilisateur u
INNER JOIN reservation r ON r.id_utilisateure=u.id_utilisateure
INNER JOIN visite_guidee v ON r.id_visiteguide=v.id_visiteguide
WHERE v.id_guide=1;

SELECT *
FROM Utilisateur u
INNER JOIN visite_guidee v ON v.id_guide=u.id_utilisateure
WHERE v.id_visiteguide=1;
