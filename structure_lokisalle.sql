
CREATE TABLE `avis` (
  `id_avis` int(3) NOT NULL,
  `id_membre` int(3) NOT NULL,
  `id_salle` int(11) NOT NULL,
  `commentaire` text NOT NULL,
  `note` int(2) NOT NULL,
  `date_enregistrement` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `commande` (
  `id_commande` int(3) NOT NULL,
  `id_membre` int(3) NOT NULL,
  `id_produit` int(3) NOT NULL,
  `date_enregistrement` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `membre` (
  `id_membre` int(3) NOT NULL,
  `pseudo` varchar(20) NOT NULL,
  `mdp` varchar(60) NOT NULL,
  `nom` varchar(20) NOT NULL,
  `prenom` varchar(20) NOT NULL,
  `email` varchar(50) NOT NULL,
  `civilite` enum('m','f') NOT NULL,
  `statut` int(1) NOT NULL,
  `date_enregistrement` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `produit` (
  `id_produit` int(11) NOT NULL,
  `id_salle` int(11) NOT NULL,
  `date_arrivee` datetime NOT NULL,
  `date_depart` datetime NOT NULL,
  `prix` int(11) NOT NULL,
  `etat` enum('libre','reservation') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `salle` (
  `id_salle` int(11) NOT NULL,
  `titre_salle` varchar(200) NOT NULL,
  `description_salle` text NOT NULL,
  `photo_salle` varchar(200) NOT NULL,
  `pays_salle` varchar(20) NOT NULL,
  `ville_salle` varchar(20) NOT NULL,
  `adresse_salle` varchar(50) NOT NULL,
  `cp_salle` int(5) NOT NULL,
  `capacite_salle` int(3) NOT NULL,
  `categorie_salle` enum('reunion','bureau','formation') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


ALTER TABLE `avis`
  ADD PRIMARY KEY (`id_avis`),
  ADD KEY `id_membre` (`id_membre`),
  ADD KEY `id_salle` (`id_salle`);

ALTER TABLE `commande`
  ADD PRIMARY KEY (`id_commande`),
  ADD KEY `id_membre` (`id_membre`),
  ADD KEY `id_produit` (`id_produit`);

ALTER TABLE `membre`
  ADD PRIMARY KEY (`id_membre`);

ALTER TABLE `produit`
  ADD PRIMARY KEY (`id_produit`),
  ADD KEY `id_salle` (`id_salle`);

ALTER TABLE `salle`
  ADD PRIMARY KEY (`id_salle`),
  ADD UNIQUE KEY `titre_salle` (`titre_salle`);


ALTER TABLE `avis`
  MODIFY `id_avis` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
ALTER TABLE `commande`
  MODIFY `id_commande` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
ALTER TABLE `membre`
  MODIFY `id_membre` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
ALTER TABLE `produit`
  MODIFY `id_produit` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;
ALTER TABLE `salle`
  MODIFY `id_salle` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

ALTER TABLE `avis`
  ADD CONSTRAINT `fk_commentaire_membre` FOREIGN KEY (`id_membre`) REFERENCES `membre` (`id_membre`),
  ADD CONSTRAINT `fk_commentaire_salle` FOREIGN KEY (`id_salle`) REFERENCES `salle` (`id_salle`);

ALTER TABLE `commande`
  ADD CONSTRAINT `fk_commande_membre` FOREIGN KEY (`id_membre`) REFERENCES `membre` (`id_membre`),
  ADD CONSTRAINT `fk_commande_produit` FOREIGN KEY (`id_produit`) REFERENCES `produit` (`id_produit`);

ALTER TABLE `produit`
  ADD CONSTRAINT `fk_salle_pdt` FOREIGN KEY (`id_salle`) REFERENCES `salle` (`id_salle`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
