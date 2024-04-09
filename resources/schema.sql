-- Create the database
CREATE DATABASE IF NOT EXISTS TRTConseil_SQL;

-- Use the database
USE TRTConseil_SQL;

-- Create the Poste table
CREATE TABLE IF NOT EXISTS poste (
    id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    libelle VARCHAR(32) NOT NULL UNIQUE
);

INSERT INTO poste (`id`, `libelle`) VALUES (1, 'Barman'), (2, 'Barmaid'), (3, 'Cuisinier'), (4, 'Cuisinière'), (5, 'Consultant'), (6, 'Consultante'), (7, 'Directeur de restaurant'), (8, 'Directrice de restaurant'), (9, 'Directeur d\'hôtel'), (10, 'Directrice d\'hôtel'), (11, 'Employé de restaurant'), (12, 'Employée de restaurant'), (13, 'Femme de chambre'), (14, 'Valet de chambre'), (15, 'Garçon de café'), (16, 'Serveuse'), (17, 'Gérant de restauration collective'), (18, 'Gérante de restauration collective'), (19, 'Gouvernant'), (20, 'Gouvernante'), (21, 'Maître d\'hôtel'), (22, 'Maîtresse d\'hôtel'), (23, 'Pâtissier'), (24, 'Pâtissière'), (25, 'Personnel de hall d\'hôtel de luxe'), (26, 'Réceptionniste'), (27, 'Sommelier'), (28, 'Sommelière');

-- Create the User table
CREATE TABLE IF NOT EXISTS user (
  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `email` varchar(180) NOT NULL,
  `roles` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL COMMENT '(DC2Type:json)' CHECK (json_valid(`roles`)),
  `password` varchar(255) NOT NULL,
  `nom` varchar(50) NOT NULL,
  `prenom` varchar(50) NOT NULL,
  `role` varchar(40) NOT NULL,
  UNIQUE KEY UNIQ_IDENTIFIER_EMAIL (email)
);

INSERT INTO user (`id`, `email`, `roles`, `password`, `nom`, `prenom`, `role`) VALUES (1, 'admin@mail.com', '[\"ROLE_ADMIN\"]', '$2y$13$6rM/mQQX1NUI3E.m7fFtr.uTcOLD7xsIkrHla4vuyL9qP2SaSBkGO', 'TRT', 'Conseil', 'admin'), (2, 'consultant@mail.com', '[\"ROLE_CONSULTANT\"]', '$2y$13$6rM/mQQX1NUI3E.m7fFtr.uTcOLD7xsIkrHla4vuyL9qP2SaSBkGO', 'JONES', 'Adele', 'consultant'), (3, 'recruteur@mail.com', '[\"ROLE_RECRUTEUR\"]', '$2y$13$6rM/mQQX1NUI3E.m7fFtr.uTcOLD7xsIkrHla4vuyL9qP2SaSBkGO', 'MICHELLE', 'Eda', 'recruteur'), (4, 'candidat@mail.com', '[\"ROLE_CANDIDAT\"]', '$2y$13$6rM/mQQX1NUI3E.m7fFtr.uTcOLD7xsIkrHla4vuyL9qP2SaSBkGO', 'MARY', 'Lea', 'candidat');

-- Create the Recruteur table
CREATE TABLE IF NOT EXISTS recruteur (
  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `recruteur_user_id` int(11) NOT NULL,
  `consultant_id` int(11) DEFAULT NULL,
  `nom` varchar(255) DEFAULT NULL,
  `adresse` varchar(255) DEFAULT NULL,
  `code_postal` int(11) DEFAULT NULL,
  `ville` varchar(255) DEFAULT NULL,
  FOREIGN KEY (recruteur_user_id) REFERENCES User(id),
  FOREIGN KEY (consultant_id) REFERENCES User(id)
);

INSERT INTO recruteur (`id`, `recruteur_user_id`, `consultant_id`, `nom`, `adresse`, `code_postal`, `ville`) VALUES (1, 3, 1, 'Hotel Platza', '10 Rue de Rivoli', 75004, 'Paris');

-- Create the Candidat table
CREATE TABLE IF NOT EXISTS candidat (
  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `candidat_user_id` int(11) NOT NULL,
  `consultant_id` int(11) DEFAULT NULL,
  `cv` varchar(255) DEFAULT NULL,
  FOREIGN KEY (candidat_user_id) REFERENCES User(id),
  FOREIGN KEY (consultant_id) REFERENCES User(id)
);

INSERT INTO candidat (`id`, `candidat_user_id`, `consultant_id`, `cv`) VALUES (1, 4, 1, '');

-- Create the Annonce table
CREATE TABLE IF NOT EXISTS annonce (
  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `recruteur_id` int(11) NOT NULL,
  `consultant_id` int(11) DEFAULT NULL,
  `poste_id` int(11) NOT NULL,
  `titre` varchar(100) NOT NULL,
  `ville` varchar(60) NOT NULL,
  `description` longtext NOT NULL,
  `dateajout` datetime NOT NULL,
  `datedebut` date NOT NULL,
  `validation` tinyint(1) NOT NULL,
  `typecontrat` varchar(20) NOT NULL,
  `nombreheures` int(11) NOT NULL,
  `salaire` int(11) NOT NULL,
  `datefin` date DEFAULT NULL,
  FOREIGN KEY (poste_id) REFERENCES Poste(id),
  FOREIGN KEY (recruteur_id) REFERENCES Recruteur(id),
  FOREIGN KEY (consultant_id) REFERENCES User(id)
);


INSERT INTO annonce (`id`, `recruteur_id`, `consultant_id`, `poste_id`, `titre`, `ville`, `description`, `dateajout`, `datedebut`, `validation`, `typecontrat`, `nombreheures`, `salaire`, `datefin`) VALUES
(1, 1, 2, 14, 'Valet de chambre CDI Paris', 'Paris', 'Cupiditate nulla quod natus pariatur nam eaque quis ut. Exercitationem a sunt quo delectus voluptatem dolor voluptatem. Quo tenetur quod illo itaque. Cupiditate magnam in expedita maiores iusto magni et. Sapiente quia praesentium dolores aliquid est. Optio in provident ipsa est dolores. A possimus beatae omnis numquam aspernatur quae. Voluptate odit necessitatibus totam quasi ea eligendi.\r\n\r\nCupiditate nulla quod natus pariatur nam eaque quis ut. Exercitationem a sunt quo delectus voluptatem dolor voluptatem. Quo tenetur quod illo itaque. Cupiditate magnam in expedita maiores iusto magni et. Sapiente quia praesentium dolores aliquid est. Optio in provident ipsa est dolores. A possimus beatae omnis numquam aspernatur quae. Voluptate odit necessitatibus totam quasi ea eligendi.', '2024-03-26 17:35:43', '2024-05-06', 1, 'CDI', 36, 4977, NULL);

-- Create the Candidature table
CREATE TABLE IF NOT EXISTS candidature (
  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `annonce_id` int(11) NOT NULL,
  `candidat_id` int(11) NOT NULL,
  `consultant_approval_id` int(11) DEFAULT NULL,
  `etat` varchar(10) NOT NULL,
  FOREIGN KEY (annonce_id) REFERENCES Annonce(id),
  FOREIGN KEY (candidat_id) REFERENCES Candidat(id),
  FOREIGN KEY (consultant_approval_id) REFERENCES User(id)
);

INSERT INTO candidature (`id`, `annonce_id`, `candidat_id`, `consultant_approval_id`, `etat`) VALUES (1, 1, 1, 2, 'tovalid');

-- Create the Annonce_User relationship table
CREATE TABLE IF NOT EXISTS Annonce_User (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `annonce_id` INT NOT NULL,
    `consultant_id` INT NOT NULL,
    FOREIGN KEY (annonce_id) REFERENCES Annonce(id),
    FOREIGN KEY (consultant_id) REFERENCES User(id)
);