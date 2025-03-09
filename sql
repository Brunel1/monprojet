-- Créer la base de données
CREATE DATABASE emploi_du_temps;

-- Utiliser la base de données
USE emploi_du_temps;

-- Créer la table 'horaire'
CREATE TABLE horaire (
    id INT AUTO_INCREMENT PRIMARY KEY,
    jour VARCHAR(10) NOT NULL,
    heure VARCHAR(20) NOT NULL,
    matiere VARCHAR(100) NOT NULL
);