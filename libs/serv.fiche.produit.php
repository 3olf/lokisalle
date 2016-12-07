<?php

// On redirige l'utilisateur s'il passer par l'url directe de la page
if (PAGE_AUTORISEE != "true")
{
	header('location:../index.php');
	exit();
}

/* DEFAULT VALUES */
$btn_reservation = '<a href="#" class="btn btn-default btn-success pull-right>Réserver</a>';
$titre_salle = "";
$photo_salle ="";
$description_salle="";
$name_submit = "enregistrer_commentaire";
$submit_value = "Envoyer";
$url_connexion_commentaire ="";
$commentaires = "";

if(isset($_GET['action']) && $_GET['action'] == 'voir' && isset($_GET['id'])) 
{
	// Préparation requête pour récupéer les infos du produit et de la salle 
	$_GET['id'] = htmlentities($_GET['id'], ENT_QUOTES);
	$mon_id = (int)$_GET['id'];

	// CONTROLE VALIDITE DE LA PAGE
	$req_controle = $pdo->prepare("SELECT id_produit FROM produit WHERE id_produit = '$mon_id'");
	$req_controle->execute();
	$verification  = $req_controle->rowCount();


	/************* OPERATIONS AFFICHAGE ELEMENTS + AJOUT COMMENTAIRE *************/
	if($verification >= 1)
	{
		$req = $pdo->prepare("SELECT s.titre_salle, s.description_salle, s.photo_salle, s.id_salle, s.categorie_salle, s.capacite_salle, s.adresse_salle, s.cp_salle, s.ville_salle, p.id_produit, p.id_salle, p.date_arrivee, p.date_depart, p.prix, p.etat, AVG(a.note) as note FROM produit AS p JOIN salle AS s ON p.id_salle=s.id_salle LEFT JOIN avis AS a ON s.id_salle = a.id_salle WHERE p.id_produit = '$mon_id'");

		$req->execute();	
			
		$resultat = $req->fetch(PDO::FETCH_ASSOC);
		extract($resultat);

		// Lien de retour sur la page consultée avant connexion
		$url_page_encours = "connexion.php?page=produit&id=".$resultat['id_produit'];	

		/************* AJOUT COMMENTAIRE *************/
		// Manque la maj des étoiles au reload (pas possible vu que la requête est construite au dessus)
		if(isset($_POST['commentaire']) && isset($_POST['note']) && isset($_POST['enregistrer_commentaire']))
		{
			/* CONTROLES */
				// Note
			if (empty($_POST['note']) || !preg_match('#[1-5]{1}#', $_POST['note'])) 
			{
				$msg_info .= "<p class='error'>Erreur sur la note</p>";
			}

			/* HTMLENTITIES & INT */
			$_POST['note'] = htmlentities($_POST['note'], ENT_QUOTES);
			$_POST['commentaire'] = htmlentities($_POST['commentaire'], ENT_QUOTES);

			$_POST['note'] = (int)$_POST['note'];

			/* ENREGISTREMENT DU COMMENTAIRE */
			if(empty($msg_info))
			{
				// Preparation de la requête d'enregistrement
				$register_commentaire = $pdo->prepare("INSERT INTO avis (id_membre, id_salle, commentaire, note, date_enregistrement ) VALUES (:membre, :salle, :commentaire, :note, NOW())");

				// BindParam protection requête
				$register_commentaire->bindParam(":membre", $_SESSION['utilisateur']['id_membre'], PDO::PARAM_INT);		
				$register_commentaire->bindParam(":salle", $resultat['id_salle'], PDO::PARAM_STR);
				$register_commentaire->bindParam(":commentaire", $_POST['commentaire'], PDO::PARAM_STR);
				$register_commentaire->bindParam(":note", $_POST['note'], PDO::PARAM_INT);	

				// Execute
				$register_commentaire->execute();
				// Message de validation
				$msg_info .= "<p class='succes'> Commentaire enregistré </p>";				
			}					
		}
		/************* FIN AJOUT COMMENTAIRE *************/	

		/* PRODUIT */

		// BOUTON RESERVATION
		if ($resultat['etat'] == 'libre')
		{
			if(userConnected())
			{
				$btn_reservation = '<a class="btn btn-default btn-success pull-right" href="?action=reserver&id='.$resultat['id_produit'].'">Réserver</a>';
			}
			else
			{
				$btn_reservation = '<a class="btn btn-default btn-success pull-right" href="'.$url_page_encours.'">Réserver</a>';
			}
		}
		else
		{
			$btn_reservation = '<div class="btn btn-default btn-danger pull-right">Réservé</div>';
		}

		// NOTE PRODUIT
		$note_produit = "";
		$count = (int)$resultat['note'];
		for ($i=0; $i < $count ; $i++) 
		{ 
			$note_produit .= '<span class="glyphicon glyphicon-star"></span>';
		}
		for ($i=0; $i < (5-$count) ; $i++) { 
			$note_produit .= '<span class="glyphicon glyphicon-star-empty"></span>';
		}
		/* FIN PRODUIT */

		/* AUTRES PRODUITS */
		$requete = $pdo->prepare("SELECT s.photo_salle, s.id_salle, s.titre_salle, p.id_salle, p.id_produit FROM produit AS p JOIN salle AS s ON p.id_salle = s.id_salle WHERE p.etat = 'libre' ORDER BY p.id_produit ASC");
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
		/* FIN AUTRES PRODUITS */

		/* COMMENTAIRES */

		// Récupération des commentaire
		$req_coms = $pdo->prepare("SELECT a.id_avis, a.id_salle, a.id_membre, a.commentaire, a.note, a.date_enregistrement, m.id_membre, m.pseudo FROM avis AS a JOIN membre AS m ON a.id_membre = m.id_membre WHERE a.id_salle =".$resultat['id_salle']." ORDER BY a.date_enregistrement DESC LIMIT 5");

		$req_coms->execute();

		$liste_commentaires = $req_coms->fetchall(PDO::FETCH_ASSOC);

		// Affichage sur la page
		if (empty($liste_commentaires))
		{
			$commentaires .= "<p>Aucun commentaire sur la salle ".$resultat['titre_salle']."</p>";
		}
		else
		{
			foreach ($liste_commentaires as $commentaire) 
			{

				$note_salle = "";
				$count = (int)$commentaire['note'];
				for ($i=0; $i < $count ; $i++) 
				{ 
					$note_salle .= '<span class="glyphicon glyphicon-star"></span>';
				}
				for ($i=0; $i < (5-$count) ; $i++) { 
					$note_salle .= '<span class="glyphicon glyphicon-star-empty"></span>';
				}

				$date_com = date('d-m-Y' , strtotime($commentaire['date_enregistrement']))." à ".date('h:i' , strtotime($commentaire['date_enregistrement']));
				$commentaires .= "<p><strong>".$commentaire['pseudo']."</strong> le ".$date_com."</p><p class='small'><em> Note </em>".$note_salle."</p><p>".$commentaire['commentaire']."</p><hr>";
			}	
		}
		/* FIN COMMENTAIRES */

		/************* FIN OPERATIONS AFFICHAGE *************/
	}
	else
	{
		header('location:index.php');
		exit();		
	}
}
elseif (isset($_GET['action']) && $_GET['action'] == 'reserver' && isset($_GET['id']))
{
	if(userConnected())
	{
		// Requête pour récupérer vérifier la disponibilité de la salle avant réservation
		$_GET['id'] = htmlentities($_GET['id'], ENT_QUOTES);
		$mon_id = (int)$_GET['id'];
		$req = $pdo->prepare("SELECT etat, id_produit FROM produit WHERE id_produit = '$mon_id'");
		$req->execute();

		$etat_produit = $req->fetch(PDO::FETCH_ASSOC);

		if($etat_produit["etat"] == 'libre')
		{
			$etat_reservation = $pdo->exec("UPDATE produit SET etat = 'reservation' WHERE id_produit = '$mon_id'");
			$register_commande = $pdo->prepare("INSERT INTO commande (id_membre, id_produit, date_enregistrement) VALUES (:membre, :produit, NOW() )");
			
			// BindParam protection requête
			$register_commande->bindParam(":membre", $_SESSION['utilisateur']['id_membre'], PDO::PARAM_INT);		
			$register_commande->bindParam(":produit", $etat_produit['id_produit'], PDO::PARAM_INT);

			// Execution
			$register_commande->execute();

			// Header location à remplacer par un renvoi vers la page profil
			header("location:".URL."fiche_produit.php?action=voir&id=$mon_id");
			exit();
		}
	}	
	else
	{
		header("location:".URL."fiche_produit.php?action=voir&id=$mon_id");
		exit();
	}
}
else
{
	header('location:index.php');
	exit();
}