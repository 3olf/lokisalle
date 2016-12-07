<?php

// On redirige l'utilisateur s'il passer par l'url directe de la page
if (PAGE_AUTORISEE != "true")
{
	header('location:../index.php');
	exit();
}

/******** AFFICHAGE FORM DEFAULT ********/

// VALEUR DEFAUT FORMULAIRE
$titre_salle =""; $description_salle=""; $adresse_salle =""; $cp_salle=""; $ville_salle="paris"; $capacite_salle = "10"; $categorie_salle=""; $pays_salle ="france"; $photo_salle="";

// Submit
$name_submit = "enregistrer_salle";
$submit_value = "Enregistrer";

// OPTION SELECT CAPACITE SALLES
$options_capacite = "" ;
$options_categorie= "" ;
$preg_categorie ="";


// OPTION SELECT CATEGORIE SALLES
$req = $pdo->query("SHOW COLUMNS FROM salle WHERE field='categorie_salle'");
$cat_salle = $req->fetch(PDO::FETCH_ASSOC);

// Preg match pour les categories
$preg_cat_sub1 = substr($cat_salle['Type'],6,-2);
$preg_cat_sub2 = str_replace("','", "$|^", $preg_cat_sub1);
$preg_categorie = "#^".$preg_cat_sub2."$#";


/******** FIN AFFICHAGE FORM ********/



/******** ENREGISTREMENT / MODIFICATION / SUPPRESSION SALLE ********/

/* TRAITEMENT FORMULAIRE */

if(isset($_POST['titre_salle']) && isset($_POST['description_salle']) && isset($_POST['capacite_salle']) && isset($_POST['categorie_salle']) && isset($_POST['pays_salle']) && isset($_POST['ville_salle']) && isset($_POST['adresse_salle']) && isset($_POST['cp_salle']))
{

	// Capacité et code postal en int
	$_POST['capacite_salle'] = (int)$_POST['capacite_salle'];
	$_POST['cp_salle'] = (int)$_POST['cp_salle'];

	/* VERIFICATIONS */
		// Titre
	if (empty($_POST['titre_salle']) || mb_strlen($_POST['titre_salle']) > 200) {
		$msg_info .= "<p class='error'>Titre non valide</p>";
	}
		// Description
	if (empty($_POST['description_salle'])) {
		$msg_info .= "<p class='error'>Description non valide</p>";
	}
		// Capacite
	if (empty($_POST['capacite_salle']) || !preg_match('#[0-9]#', $_POST['capacite_salle'])) {
		$msg_info .= "<p class='error'>Capacité non valide</p>";
	}
		// Categorie
	if (empty($_POST['categorie_salle']) || !preg_match($preg_categorie, $_POST['categorie_salle'])) {
		$msg_info .= "<p class='error'>Categorie non valide</p>";
	}
		// Pays
	if (empty($_POST['pays_salle']) || !preg_match("#france#i", $_POST['pays_salle'])) {
		$msg_info .= "<p class='error'>Pays non valide</p>";
	}	
		// Ville
	if (empty($_POST['ville_salle']) || !preg_match("#paris|lyon|marseille#i", $_POST['ville_salle'])) {
		$msg_info .= "<p class='error'>Ville non valide</p>";
	}
		// Adresse
	if (empty($_POST['adresse_salle'])) {
		$msg_info .= "<p class='error'>Adresse non valide</p>";
	}
		// Code postal
	if (empty($_POST['cp_salle']) || !preg_match('#[0-9]{5}#', $_POST['cp_salle'])) {
		$msg_info .= "<p class='error'>Code postal non valide</p>";
	}

	/* HTMLENTITIES & EXTRACT */
	
	foreach ($_POST as $key => $value) 
	{
		$_POST[$key] = htmlentities($value, ENT_QUOTES);
	}
	
	extract($_POST);

	/* VERIF SALLE EN DB + TRAITEMENT PHOTO*/
	$req2 = $pdo->query("SELECT COUNT(id_salle) FROM salle WHERE titre_salle = '$titre_salle'");
	$count_col = $req2->fetchColumn();

	if ($count_col >= 1 && (isset($_POST['enregistrer_salle']) && $_POST['enregistrer_salle'] == 'Enregistrer'))
	{
		$msg_info .= "<p class='error'>Salle existante</p>";
	}
	else
	{
	  	$photo_bdd = "";
	  	// Si on modifie l'entrée
	  	if (isset($_GET['action']) && $_GET['action'] == 'modifier')
		{
			$photo_bdd = $_POST['photo_actuelle'];	
		}

	  	/* CHECK FILES */	  	
	  	if (!empty($_FILES['photo_salle']['name']) && empty($msg_info))
	  	{
	  		if(checkImgExt()) 
	  		{
	  			// Suppression de l'ancienne photo
				if (!empty($photo_bdd) && file_exists('../'.$photo_bdd))
				{
					unlink('../'.$photo_bdd);
				}

	  			// On donne un nom unique au fichier à envoyer pour éviter d'en écraser un autre
	  			$replace_nom_photo = array(" ", "(", ")", "@");
	  			$nom_photo = str_replace($replace_nom_photo, '', $titre_salle."-".$_FILES['photo_salle']['name']);
	  			// Chemin src du fichier 
	  			$photo_bdd = "img/".$nom_photo;
	  			// Chemin du dossier depuis la racine serveur
	  			$chemin_dossier = SERVER_ROOT.URL.'img/'.$nom_photo;
	  			// copy permet de copier un fichier d'un endroit vers un autre. tmp_name est le repertoire temporaire
	  			copy($_FILES['photo_salle']['tmp_name'], $chemin_dossier);


	  		}
	  		else
	  		{
	  			$msg_info .= "<p class='error'>Format acceptés : jpg, jpeg, gif, png</p>";
	  		}
	  	}
	}
	
	/* ENREGISTREMENT DB */
	if (empty($msg_info)) 
	{
		////////// INSERT INTO //////////
		if (isset($_POST['enregistrer_salle']) && $_POST['enregistrer_salle'] == 'Enregistrer')
		{ 
			// Preparation de la requête d'enregistrement
			$register_salle = $pdo->prepare("INSERT INTO salle (titre_salle, description_salle, photo_salle, pays_salle, ville_salle, adresse_salle, cp_salle, capacite_salle, categorie_salle) VALUES (:titre, :description, :photo, :pays, :ville, :adresse, :cp, :capacite, :categorie)");

			// BindParam protection requête
			$register_salle->bindParam(":titre", $titre_salle, PDO::PARAM_STR);		
			$register_salle->bindParam(":description", $description_salle, PDO::PARAM_STR);
			$register_salle->bindParam(":photo", $photo_bdd, PDO::PARAM_STR);
			$register_salle->bindParam(":pays", $pays_salle, PDO::PARAM_STR);
			$register_salle->bindParam(":ville", $ville_salle, PDO::PARAM_STR);
			$register_salle->bindParam(":adresse", $adresse_salle, PDO::PARAM_STR);
			$register_salle->bindParam(":cp", $cp_salle, PDO::PARAM_INT);
			$register_salle->bindParam(":capacite", $capacite_salle, PDO::PARAM_INT);
			$register_salle->bindParam(":categorie", $categorie_salle, PDO::PARAM_STR);

			// Execution requête
			$register_salle->execute();	

			// Message de validation
			$msg_info .= "<p class='succes'> Enregistrement effectuée </p>";				
		}	
		////////// UPDATE //////////
		elseif (isset($_GET['action']) && $_GET['action'] == 'modifier')
		{ 
			// Preparation de la requête d'update
			$update_salle = $pdo->prepare("UPDATE salle SET titre_salle = :titre, description_salle = :description, photo_salle = :photo, pays_salle = :pays, ville_salle = :ville, adresse_salle = :adresse, cp_salle = :cp, capacite_salle = :capacite, categorie_salle = :categorie WHERE id_salle= '$id_salle'"); 

			// BindParam protection requête
			$update_salle->bindParam(":titre", $titre_salle, PDO::PARAM_STR);		
			$update_salle->bindParam(":description", $description_salle, PDO::PARAM_STR);
			$update_salle->bindParam(":photo", $photo_bdd, PDO::PARAM_STR);
			$update_salle->bindParam(":pays", $pays_salle, PDO::PARAM_STR);
			$update_salle->bindParam(":ville", $ville_salle, PDO::PARAM_STR);
			$update_salle->bindParam(":adresse", $adresse_salle, PDO::PARAM_STR);
			$update_salle->bindParam(":cp", $cp_salle, PDO::PARAM_INT);
			$update_salle->bindParam(":capacite", $capacite_salle, PDO::PARAM_INT);
			$update_salle->bindParam(":categorie", $categorie_salle, PDO::PARAM_STR);

			// Execution requête
			$update_salle->execute();

			// Message de validation
			$msg_info .= "<p class='succes'> Modification effectuée </p>";								
		}
	}	
}

/* MODIFICATION SALLE */

if (isset($_GET['id']) && $_GET['action'] == 'modifier') 
{
	// Requête BDD pour récupérer les valeurs 
	$_GET['id'] = htmlentities($_GET['id'], ENT_QUOTES);
	$id_salle = (int)$_GET['id'];
	$resultat = $pdo->query("SELECT * FROM salle WHERE id_salle='$id_salle'");
	$ma_salle = $resultat->fetch(PDO::FETCH_ASSOC);

	extract($ma_salle);

	// VALEURS MODIFIER SUBMIT FORMULAIRE (pas utilisé)
	$name_submit = "modifier_salle";
	$submit_value = "Modifier";
}	

/* SUPPRESSION SALLE */
if (isset($_GET['id']) && $_GET['action'] == 'supprimer') 
{
	$_GET['id'] = htmlentities($_GET['id'], ENT_QUOTES);
	$id_salle = (int)$_GET['id'];
	
	// Vérification que la salle existe avant suppression
	$req3 = $pdo->query("SELECT COUNT(id_salle) FROM salle WHERE id_salle='$id_salle'");

	$count_col_2 = $req3->fetchColumn();

	if($count_col_2 == 1)
	{	
		$resultat= $pdo->query("SELECT id_salle, photo_salle FROM salle WHERE id_salle='$id_salle'");
		$salle_a_supprimer = $resultat->fetch(PDO::FETCH_ASSOC);

		// Suppression de la photo
		if (!empty($salle_a_supprimer['photo_salle']) && file_exists('../'.$salle_a_supprimer['photo_salle']))
		{
			unlink('../'.$salle_a_supprimer['photo_salle']);
		}
		// Suppression de la salle
		$pdo->exec("DELETE FROM salle WHERE id_salle='$id_salle'");

		// Message de succès	
		header("location:gestion_salles.php");	
		$msg_info .= "<p class='succes'> Suppression effectuée </p>";	
	}
	else
	{
		$msg_info .= "<p class='error'> Salle inexistante </p>";		
	}
}

/******** FIN ENREGISTREMENT / MODIFICATION / SUPPRESSION SALLE ********/



/******** AFFICHAGE FORM ********/

// OPTION SELECT CAPACITE
for ($i = 1; $i < 51; $i++)
{	
	if($capacite_salle == $i)
	{
		$options_capacite .= "<option value=".$i." selected>".$i."</option>";
	}
	else
	{
		$options_capacite .= "<option value=".$i.">".$i."</option>";
	}	
}

// OPTION SELECT CATEGORIE SALLES
foreach(explode("','",substr($cat_salle['Type'],6,-2)) as $option) 
{
	if($categorie_salle == $option)
	{
		$options_categorie .= "<option value=".$option." selected >".$option."</option>";
	}
	else
	{
		$options_categorie .= "<option value=".$option.">".$option."</option>";
	}
}
/******** FIN AFFICHAGE FORM  ********/


/******** AFFICHAGE RESULTATS ********/
$requete = $pdo->query("SELECT id_salle, titre_salle, description_salle, photo_salle, pays_salle, ville_salle, adresse_salle, cp_salle, capacite_salle, categorie_salle FROM salle");

////// THEAD //////
$nb_col = $requete->columnCount();
$content_thead = "";
	// Voir a virer le rowCount qui ne marche pas partour avec PDO query
if($requete->rowCount() > 0)
{
	for ($i = 0; $i < $nb_col; $i++) {
		$col_table = $requete->getColumnMeta($i);
		$content_thead .= "<th>".$col_table["name"]."</th>";
	}
	$content_thead .= "<th> Actions </th>";
}


////// TBODY //////
$tr_salle ="";
$td_salle ="";
$liste_salles = $requete->fetchall(PDO::FETCH_ASSOC);

foreach ($liste_salles as $salle) {
	$td_salle= "";
	foreach ($salle as $key => $value) {
		

		if($key == "photo_salle")
		{
			$td_salle .= "<td><img src='../".$value."'></td>";
		}
		else
		{
			$td_salle .= "<td>".$value."</td>";	
		}		
	}
	$td_salle .= "<td><a href='?action=modifier&id=".$salle['id_salle']."' class='btn btn-default'><span class='glyphicon glyphicon-pencil'></span></a><a href='?action=supprimer&id=".$salle['id_salle']."' class='btn btn-default'><span class='glyphicon glyphicon-remove-circle'></span></a></td>";	
	$tr_salle .= "<tr>".$td_salle."</tr>";
}

/******** FIN AFFICHAGE RESULTATS ********/