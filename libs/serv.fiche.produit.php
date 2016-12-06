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

	//$req = $pdo->query("SELECT p.*, s.*, a.* FROM produit AS p JOIN salle AS s ON p.id_salle=s.id_salle JOIN avis AS a ON s.id_salle = a.id_salle WHERE p.id_produit = '$mon_id'");
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



}
else
{
	header('location:index.php');
	exit();
}