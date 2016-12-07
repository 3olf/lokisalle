<?php

// On redirige l'utilisateur s'il passer par l'url directe de la page
if (PAGE_AUTORISEE != "true")
{
	header('location:../index.php');
	exit();
}

/******** AFFICHAGE FORM DEFAULT ********/
$options_salles = ""; $id_salle=""; $prix=""; $date_arrivee=""; $date_depart="";
// Submit
$name_submit = "enregistrer_pdt";
$submit_value = "Enregistrer";


/******** FIN AFFICHAGE FORM DEFAULT ********/


/******** ENREGISTREMENT / MODIFICATION / SUPPRESSION PRODUIT ********/

/* TRAITEMENT FORMULAIRE */
if(isset($_POST['date_arrivee']) && isset($_POST['date_depart']) && isset($_POST['id_salle']) && isset($_POST['prix']))
{

	/* VERIFICATIONS */
		// Date arrivée
	if (empty($_POST['date_arrivee']) || !preg_match("#^([1-9]|([012][0-9])|(3[01]))-([0]{0,1}[1-9]|1[012])-\d\d\d\d [012]{0,1}[0-9]:[0-6][0-9]$#", $_POST['date_arrivee'])) {
		$msg_info .= "<p class='error'>Date arrivée non valide</p>";
	}
	if (new DateTime($_POST['date_arrivee']) < new DateTime(date("d-m-Y H:i")))
	{
		$msg_info .= "<p class='error'>La date d'arrivée doit correspondre à une date future</p>";
	}

		// Date départ
	if (empty($_POST['date_depart']) || !preg_match("#^([1-9]|([012][0-9])|(3[01]))-([0]{0,1}[1-9]|1[012])-\d\d\d\d [012]{0,1}[0-9]:[0-6][0-9]$#", $_POST['date_depart'])) {
		$msg_info .= "<p class='error'>Date départ non valide</p>";
	}
	if (new DateTime($_POST['date_depart']) < new DateTime(date("d-m-Y H:i")))
	{
		$msg_info .= "<p class='error'>La date de départ doit correspondre à une date future</p>";
	}
	if (new DateTime($_POST['date_depart']) < new DateTime($_POST['date_arrivee']))
	{
		$msg_info .= "<p class='error'>La date de départ doit correspondre à une date future à la date d'arrivée</p>";
	}
			
		// Prix
	if (empty($_POST['prix']) || !preg_match("#[0-9]#", $_POST['prix'])) {
		$msg_info .= "<p class='error'>Prix non valide</p>";
	}

	/* HTMLENTITIES & EXTRACT */
	
	foreach ($_POST as $key => $value) 
	{
		$_POST[$key] = htmlentities($value, ENT_QUOTES);
	}

	// Transformation du $_POST salle pour récupérer l'id en INT
	$recup_id_salle = explode(" - ", $_POST['id_salle']);
	$_POST['id_salle'] = (int)$recup_id_salle[0];

	// Transformation prix en INT
	$_POST['prix'] = (int)$_POST['prix'];



	extract($_POST);

	// Transformation dates en DATETIME (après le extract pour garder les valeurs des champs en cas d'erreur de saisie)
	$_POST['date_arrivee'] = date('Y-m-d H:i:s' ,strtotime($_POST['date_arrivee']));
	$_POST['date_depart'] = date('Y-m-d H:i:s' ,strtotime($_POST['date_depart']));

	// Verification en BDD si le produit existe
	$req = $pdo->query("SELECT COUNT(id_produit) FROM produit WHERE id_salle = '$id_salle' AND (date_arrivee BETWEEN '$_POST[date_arrivee]' AND '$_POST[date_depart]' OR date_depart BETWEEN '$_POST[date_arrivee]' AND '$_POST[date_depart]')");
	
	$count_col = $req->fetchColumn();

	if($count_col >= 1 && (isset($_POST['enregistrer_pdt']) && $_POST['enregistrer_pdt'] == 'Enregistrer'))
	{
		$msg_info .= "<p class='error'>Il existe déjà un produit pour cette salle entre ces dates</p>";
	}

	/* ENREGISTREMENT DB */
	if(empty($msg_info))
	{
		////////// INSERT INTO //////////
		if (isset($_POST['enregistrer_pdt']) && $_POST['enregistrer_pdt'] == 'Enregistrer')
		{ 
			// Preparation de la requête d'enregistrement
			$register_produit = $pdo->prepare("INSERT INTO produit (id_salle, date_arrivee, date_depart, prix, etat) VALUES (:id_salle, :date_arrivee, :date_depart, :prix, 'libre')");

			// BindParam protection requête
			$register_produit->bindParam(":id_salle", $id_salle, PDO::PARAM_INT);		
			$register_produit->bindParam(":date_arrivee", $_POST['date_arrivee'], PDO::PARAM_STR);
			$register_produit->bindParam(":date_depart", $_POST['date_depart'], PDO::PARAM_STR);
			$register_produit->bindParam(":prix", $prix, PDO::PARAM_INT);

			// Execution requête
			$register_produit->execute();	

			// Message de validation
			$msg_info .= "<p class='succes'> Enregistrement effectuée </p>";				
		}
		////////// UPDATE //////////
		elseif (isset($_GET['action']) && $_GET['action'] == 'modifier')
		{ 
			// Preparation de la requête d'update
			$update_produit = $pdo->prepare("UPDATE produit SET id_salle = :id_salle, date_arrivee = :date_arrivee, date_depart = :date_depart, prix = :prix WHERE id_produit= '$id_produit'"); 

			// BindParam protection requête
			$update_produit->bindParam(":id_salle", $id_salle, PDO::PARAM_INT);		
			$update_produit->bindParam(":date_arrivee", $_POST['date_arrivee'], PDO::PARAM_STR);
			$update_produit->bindParam(":date_depart", $_POST['date_depart'], PDO::PARAM_STR);
			$update_produit->bindParam(":prix", $prix, PDO::PARAM_INT);

			// Execution requête
			$update_produit->execute();

			// Message de validation
			$msg_info .= "<p class='succes'> Modification effectuée </p>";								
		}		
	}								
}

/* MODIFICATION PRODUIT */

if (isset($_GET['id']) && $_GET['action'] == 'modifier') 
{
	// Requête BDD pour récupérer les valeurs 
	$_GET['id'] = htmlentities($_GET['id'], ENT_QUOTES);
	$id_produit = (int)$_GET['id'];
	$resultat = $pdo->query("SELECT * FROM produit WHERE id_produit ='$id_produit'");
	$mon_produit = $resultat->fetch(PDO::FETCH_ASSOC);

	extract($mon_produit);
	$date_arrivee = date('d-m-Y H:i' ,strtotime($date_arrivee));
	$date_depart = date('d-m-Y H:i' ,strtotime($date_depart));

	// VALEURS MODIFIER SUBMIT FORMULAIRE (pas utilisé)
	$name_submit = "modifier_pdt";
	$submit_value = "Modifier";
}

/* SUPPRESSION PRODUIT */
if (isset($_GET['id']) && $_GET['action'] == 'supprimer') 
{
	$_GET['id'] = htmlentities($_GET['id'], ENT_QUOTES);
	$id_produit = (int)$_GET['id'];
	
	// Vérification que la salle existe avant suppression
	$req = $pdo->query("SELECT COUNT(id_produit) FROM produit WHERE id_produit ='$id_produit'");

	$count_col = $req->fetchColumn();

	if($count_col >= 1)
	{	
		$resultat= $pdo->query("SELECT id_produit FROM produit WHERE id_produit ='$id_produit'");

		// Suppression du produit
		$pdo->exec("DELETE FROM produit WHERE id_produit ='$id_produit'");

		// Message de succès	
		header("location:gestion_produits.php");	
		$msg_info .= "<p class='succes'> Suppression effectuée </p>";	
	}
	else
	{
		$msg_info .= "<p class='error'> Produit inexistant </p>";		
	}
}	

/******** FIN ENREGISTREMENT / MODIFICATION / SUPPRESSION PRODUIT ********/


/******** AFFICHAGE FORM ********/

// OPTION SELECT SALLE
$req = $pdo->query("SELECT id_salle, titre_salle, adresse_salle, cp_salle, ville_salle, capacite_salle FROM salle");
$resultat = $req->fetchall(PDO::FETCH_ASSOC);

foreach ($resultat as $salle) {
	$salle_string = implode(" - ", $salle)." places";
	if($id_salle == $salle["id_salle"])
	{
		$options_salles .= "<option selected>".$salle_string."</option>";
	}
	else
	{
		$options_salles .= "<option>".$salle_string."</option>";
	}
}

/******** FIN AFFICHAGE FORM  ********/

/******** AFFICHAGE RESULTATS ********/
$requete = $pdo->prepare("SELECT p.id_produit AS Produit, p.date_arrivee AS Date_arrivee, p.date_depart AS Date_depart, s.id_salle AS Salle, s.titre_salle AS Nom, s.photo_salle AS Photo, p.prix AS Prix, p.etat AS Etat FROM produit AS p INNER JOIN salle AS s ON p.id_salle = s.id_salle");

$requete->execute();

////// THEAD //////
$nb_col = $requete->columnCount();
$content_thead = "";
	// Voir a virer le rowCount qui ne marche pas partour avec PDO query
if($requete->rowCount() > 0)
{
	for ($i = 0; $i < $nb_col; $i++) {
		$col_table = $requete->getColumnMeta($i);
		if($col_table['name'] == 'Salle' )
		{
			$content_thead .= "<th colspan='3'>".$col_table["name"]."</th>";
		}
		elseif ($col_table['name'] == 'Nom' || $col_table['name'] == 'Photo' ) 
		{
			$content_thead .= "";
		}
		else
		{
			$content_thead .= "<th>".$col_table["name"]."</th>";
		}		
	}
	$content_thead .= "<th> Actions </th>";
}


////// TBODY //////
$tr_produit ="";
$td_produit ="";

$liste_produit = $requete->fetchall(PDO::FETCH_ASSOC);

foreach ($liste_produit as $produit) {
	$td_produit= "";
	foreach ($produit as $key => $value) {
		if($key == "Salle")
		{
			$td_produit .= "<td colspan='3' class='text-center'><p>".$value;
		}		
		elseif($key == "Nom")
		{
			$td_produit .= " - ".$value."</p>";
		}
		elseif($key == "Photo")
		{
			$td_produit .= "<img src='../".$value."'></td>";
		}
		else
		{
			$td_produit .= "<td>".$value."</td>";	
		}		
	}
	$td_produit .= "<td><a href='".URL."fiche_produit.php?action=voir&id=".$produit['Produit']."' class='btn btn-default'><span class='glyphicon glyphicon-search'></span></a><a href='?action=modifier&id=".$produit['Produit']."' class='btn btn-default'><span class='glyphicon glyphicon-pencil'></span></a><a href='?action=supprimer&id=".$produit['Produit']."' class='btn btn-default'><span class='glyphicon glyphicon-remove-circle'></span></a></td>";	
	$tr_produit .= "<tr>".$td_produit."</tr>";
}

/******** FIN AFFICHAGE RESULTATS ********/


// Commentaires divers
	// ^([1-9]|([012][0-9])|(3[01]))-([0]{0,1}[1-9]|1[012])-\d\d\d\d [012]{0,1}[0-9]:[0-6][0-9]$ => regex dd-mm-yyyy hh:mm //
	// ^\d\d\d\d-(0?[1-9]|1[0-2])-(0?[1-9]|[12][0-9]|3[01]) (00|[0-9]|1[0-9]|2[0-3]):([0-9]|[0-5][0-9]):([0-9]|[0-5][0-9])$ => regex DATETIME

// SELECT COUNT(id_produit) FROM produit WHERE id_salle = 3 AND date_arrivee BETWEEN '2016-12-01 12:12:00' AND '2016-12-20 09:00:00' OR date_depart BETWEEN '2016-12-01 12:12:00' AND '2016-12-20 09:00:00'

