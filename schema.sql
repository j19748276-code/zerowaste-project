
CREATE TABLE IF NOT EXISTS `categories` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `nom` VARCHAR(255) NOT NULL UNIQUE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



CREATE TABLE IF NOT EXISTS `projets_upcycling` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `titre` VARCHAR(255) NOT NULL,
    `description` TEXT NOT NULL,
    `materiel_principal` VARCHAR(255) NOT NULL,
    `cover_image_url` VARCHAR(255) NOT NULL,
    `id_auteur` INT NOT NULL, 
    `id_categorie` INT NOT NULL,
    `date_creation` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,


    CONSTRAINT `fk_projet_categorie`
        FOREIGN KEY (`id_categorie`) REFERENCES `categories`(`id`)
        ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


INSERT INTO `categories` (`nom`) VALUES
('Décoration'),
('Jardin'),
('Mode'),
('Rangement'),
('Mobilier');
