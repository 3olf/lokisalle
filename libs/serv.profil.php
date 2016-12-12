<?php

/**** INFOS UTILISATEUR ****/
extract($_SESSION['utilisateur']);
// Gestion des cas spécifiques
if ($civilite == 'm') {
	$civilite = "homme";
}
else {
	$civilite = 'femme';
}
if ($statut == 1) {
	$statut = '<p><strong>Administrateur</strong></p>';
}
$date_enregistrement = date('d-m-Y', strtotime($date_enregistrement));


/**** COMMANDES UTILISATEUR ****/
$requete = $pdo->query("SELECT c.date_enregistrement AS 'Date de réservation', s.titre_salle AS salle, p.date_arrivee AS arrivée, p.date_depart AS départ, p.id_produit, p.prix FROM commande c
JOIN produit p ON p.id_produit=c.id_produit
JOIN membre m ON c.id_membre=m.id_membre
JOIN salle s ON s.id_salle=p.id_salle
WHERE m.id_membre='$id_membre' ORDER BY c.id_commande DESC");


////// THEAD //////
$nb_col = $requete->columnCount();
$content_thead = "";
	// Voir a virer le rowCount qui ne marche pas partour avec PDO query
if($requete->rowCount() > 0)
{
	for ($i = 0; $i < $nb_col; $i++) {
		$col_table = $requete->getColumnMeta($i);
		if($col_table["name"] != "id_produit")
		{
			$content_thead .= "<th>".mb_ucfirst($col_table["name"])."</th>";
		}		
	}
	$content_thead .= "<th> Actions </th>";
}


////// TBODY //////
$total_commandes = 0;
$tr_commandes_user = "";
$td_commandes_user = "";
$liste_commandes_user = $requete->fetchall(PDO::FETCH_ASSOC);

foreach ($liste_commandes_user as $commande) 
{
	$td_commandes_user= "";

	foreach ($commande as $key => $value)
	{
		if($key != 'id_produit')
		{
			if($key == 'Date de réservation' || $key == 'arrivée' || $key == 'départ')
			{
				$td_commandes_user .= "<td> Le ".substr(date('d F Y H:i', strtotime($value)), 0, 16)." à ".substr(date('m-d-Y H:i', strtotime($value)), 11)."</td>";
			}
			else
			{
				$td_commandes_user .= "<td>".$value."</td>";
			}		
		}
		if($key == 'prix')
		{
			$total_commandes += $value;
		}			
	}
	$td_commandes_user .= "<td><a title='Voir le produit' href='".URL."fiche_produit.php?action=voir&id=".$commande['id_produit']."' class='btn btn-default btn-ok'><span class='glyphicon glyphicon-search'></span></a></td>";	
	$tr_commandes_user .= "<tr>".$td_commandes_user."</tr>";
}