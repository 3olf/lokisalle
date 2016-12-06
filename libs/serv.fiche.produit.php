<?php

/* DEFAULT VALUES */
$btn_reservation = '<button class="btn btn-default btn-success pull-right"><a href="#">Réserver</a></button>';
$titre_salle = "";
$photo_salle ="";
$description_salle="";

if(isset($_GET['action']) && $_GET['action'] == 'voir' && isset($_GET['id'])) 
{
	// Préparation requête pour récupéer les infos du produit et de la salle (hors avis)
	$mon_id = (int)$_GET['id'];

	$req = $pdo->prepare("SELECT s.titre_salle, s.description_salle, s.photo_salle, s.id_salle, s.categorie_salle, s.capacite_salle, s.adresse_salle, s.cp_salle, s.ville_salle, p.id_produit, p.id_salle, p.date_arrivee, p.date_depart, p.prix, p.etat, a.id_salle, AVG(a.note) as note FROM produit AS p JOIN salle AS s ON p.id_salle=s.id_salle LEFT JOIN avis AS a ON s.id_salle = a.id_salle WHERE p.id_produit = '$mon_id'");

	$req->execute();

	/************* OPERATIONS AFFICHAGE *************/
	

	$resultat = $req->fetch(PDO::FETCH_ASSOC);
	extract($resultat);
	/* PRODUIT */

	// BOUTON RESERVATION
	if ($resultat['etat'] == 'libre')
	{
		$btn_reservation = '<button class="btn btn-default btn-success pull-right"><a href="#">Réserver</a></button>';
	}
	else
	{
		$btn_reservation = '<button class="btn btn-default btn-danger pull-right"><a href="#">Réservé</a></button>';
	}

	// NOTE ETOILE
	$note_produit = "";
	$count = (int)$resultat['note'];
	for ($i=0; $i < $count ; $i++) 
	{ 
		$note_produit .= '<span class="glyphicon glyphicon-star"></span>';
	}
	for ($i=0; $i < (5-$count) ; $i++) { 
		$note_produit .= '<span class="glyphicon glyphicon-star-empty"></span>';
	}

	/* AUTRES PRODUITS */
	$requete = $pdo->prepare("SELECT s.photo_salle, s.id_salle, s.titre_salle, p.id_salle, p.id_produit FROM produit AS p JOIN salle AS s ON p.id_salle = s.id_salle ORDER BY p.id_produit ASC");
	$requete->execute();

	$retour = $requete->fetchall(PDO::FETCH_ASSOC);

	// On récupère aléatoirement 4 items de la requête
	$nb_pdts = count($retour)-1;

	$pdt_tires = array(); 

	$indice = 0;
	while($indice < 4)
	{
		$nb_en_cours = mt_rand(0, $nb_pdts);
		$i = true;
		while($i)
		{
			if(in_array($nb_en_cours, $pdt_tires))
			{
				// Si le nombre existe déjà on le recrée
				$nb_en_cours = mt_rand(0, $nb_pdts); 
			}	
			else
			{
				$i = false;
			}
			 
		}
		// on stocke le nombre dans l'array
		$pdt_tires[$indice] = $nb_en_cours; 

		$indice++;
	}

	// for ($i = 0; $i < 4; $i++)
	// {

	// }


}
else
{
	header('location:index.php');
	exit();
}