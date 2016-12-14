<?php

require_once("../inc/init.inc.php");

//date du jour et date il y a un mois :
$today= new DateTime(date('Y-m-d H:i:s'));
$three_month_ago= new DateTime(date('Y-m-d H:i:s').'-3 month');
$three_month_ago_string=$three_month_ago->format('Y-m-d H:i:s');
$today_string=$today->format('Y-m-d H:i:s');

// **************Graphique en point cumul chiffre affaire


//preparation des requêtes

//avis
$resultat=$pdo->query("SELECT DATE_FORMAT(c.date_enregistrement,'%Y-%m-%d') AS date_c, p.prix FROM commande c
	JOIN produit p ON p.id_produit=c.id_produit
	WHERE date_enregistrement>'$three_month_ago_string'");
$tab_resultat=$resultat->fetchAll();
//debug($tab_resultat);

//données compteur pour boucle while :
$compteur_date=clone $three_month_ago;
$compteur_prix=0;
//données renvoyées pour graphique construites dans la boucle while:
$labels_date;
$data_prix; 

while ($today->format('d-m-Y') != $compteur_date->format('d-m-Y')){
	$labels_date[]= $compteur_date->format('d-m-Y');
	foreach ($tab_resultat as $key => $value) {
		if ($tab_resultat[$key]['date_c']==$compteur_date->format('Y-m-d')){
			$compteur_prix+=$tab_resultat[$key]['prix'];
		}
	}
	$data_prix[]=$compteur_prix;

	$compteur_date->modify("+ 1 days");
	//var_dump($compteur_date);
	//echo $compteur_prix;
}

//************Graphique radar taux de reservation des salles

// liste des salles
$resultat_liste_salle=$pdo->query("SELECT s.id_salle, s.titre_salle FROM salle s");
$liste_salle=$resultat_liste_salle->fetchAll(PDO::FETCH_ASSOC);


//liste des salles avec leurs nombres de produits reservées sur le mois
$resultat_reserv=$pdo->query("SELECT s.id_salle, s.titre_salle, COUNT(p.id_produit) AS nb_reserv FROM salle s
		LEFT JOIN produit p ON p.id_salle = s.id_salle
		WHERE p.date_depart > '$three_month_ago_string'
		AND p.date_arrivee<'$today_string'
		AND p.etat='reservation'
		GROUP BY s.titre_salle");
$tab_resultat_reserv=$resultat_reserv->fetchAll(PDO::FETCH_ASSOC);


//liste des salles avec leurs nombres de produits totaux sur le mois
$resultat_total=$pdo->query("SELECT  s.id_salle, s.titre_salle, COUNT(p.id_produit) AS nb_total FROM salle s
		LEFT JOIN produit p ON p.id_salle = s.id_salle
		WHERE p.date_depart > '$three_month_ago_string'
		AND p.date_arrivee<'$today_string'
		GROUP BY s.titre_salle");
$tab_resultat_total=$resultat_total->fetchAll(PDO::FETCH_ASSOC);



//données renvoyées pour graphique construites dans la boucle while:
$labels_salle;
$data_taux; 


//On ajoute à la liste des salles le nombre de produits reservé, le nombre de produi libre et on calcule le taux

foreach ($liste_salle as $key_salle => $value_salle) {
	//colonne nombre de reservation
	$liste_salle[$key_salle]['nb_reserv']=0;
	
	foreach ($tab_resultat_reserv as $key_reserv => $value_reserv) {
		if ($tab_resultat_reserv[$key_reserv]['id_salle']==$value_salle['id_salle']){
			$liste_salle[$key_salle]['nb_reserv'] = $value_reserv['nb_reserv'];
		}
	}
	
	//colonne nombre total de prduit dispo sur le mois
	$liste_salle[$key_salle]['nb_total']=0;
	
	foreach ($tab_resultat_total as $key_tot => $value_tot) {
		if ($tab_resultat_total[$key_tot]['id_salle']==$value_salle['id_salle']){
			$liste_salle[$key_salle]['nb_total'] = $value_tot['nb_total'];
		}
	}
	
	//calul du taux
	if ($liste_salle[$key_salle]['nb_total']===0){
		$data_taux [] =0;
		$labels_salle[]=$liste_salle[$key_salle]['titre_salle']. '*';
	}
	else{
		$data_taux [] = round($liste_salle[$key_salle]['nb_reserv'] *100/ $liste_salle[$key_salle]['nb_total'] );
		$labels_salle[]=$liste_salle[$key_salle]['titre_salle'];
	}
}
/*
var_dump($data_taux);
var_dump($labels_salle);
var_dump($liste_salle);
*/

echo json_encode(array($labels_date,$data_prix,$labels_salle, $data_taux));