<?php

/***************** CONSTRUCTION REQUETE TRI PRODUITS *****************/
$filtre="";
$date_arrivee= "";
$date_depart="";
$prix=3000;   //le plus cher par defaut
$capacite=0;
$capacite_affichage = "toutes";
$date_default = "p.date_arrivee > NOW()";
$filtre=array("p.etat='libre'", "$date_default");


if (isset($_GET['cat']) || isset($_GET['ville']) || isset($_GET['capacite']) || isset($_GET['prix']) || isset($_GET['date_arrivee']) || isset($_GET['date_depart'])){

	/* HTMLENTITIES pour matcher sur la BDD */
	foreach ($_GET as $key => $value) {
		$_GET[$key]=htmlentities($value,ENT_QUOTES);
	}

	/******* TRIS HORS DATE ********/
	if (!empty($_GET['cat']) && $_GET['cat'] != "tous"){
		array_push($filtre, "s.categorie_salle='".$_GET['cat']."'");

	}
	if (!empty($_GET['ville']) && $_GET['ville'] != "tous"){
		array_push($filtre, "s.ville_salle='".$_GET['ville']."'");
	}
	if (!empty($_GET['capacite'])){
		array_push($filtre, "s.capacite_salle>".$_GET['capacite']);
		$capacite=$_GET['capacite'];
		$capacite_affichage=$_GET['capacite'];
	}
	else
	{
		$capacite_affichage = "toutes";
	}
	if (!empty($_GET['prix'])){
		array_push($filtre, "p.prix < ".$_GET['prix']);
		$prix=$_GET['prix'];
	}

	/******* VERIFICATIONS DATE ********/
		// Date arrivée
	if(!empty($_GET['date_arrivee'])) 
	{	
		if (new DateTime($_GET['date_arrivee']) < new DateTime(date("d-m-Y H:i")))
		{
			$msg_info .= "<p class='error'>La date d'arrivée doit correspondre à une date future</p>";
		}
	}	

	if(!empty($_GET['date_depart'])) 
	{
		// Date départ
		if (new DateTime($_GET['date_depart']) < new DateTime(date("d-m-Y H:i")))
		{
			$msg_info .= "<p class='error'>La date de départ doit correspondre à une date future</p>";
		}
	}

	if(!empty($_GET['date_depart']) && !empty($_GET['date_arrivee']))
	{
		if (new DateTime($_GET['date_depart']) < new DateTime($_GET['date_arrivee']))
		{
			$msg_info .= "<p class='error'>La date de départ doit correspondre à une date future à la date d'arrivée</p>";
		}		
	} 		

	/******* TRI DATE ********/
	if(empty($msg_info))
	{

		if(!empty($_GET['date_arrivee'])) 
		{
			array_splice($filtre, 1, 1);
			$date_arrivee=$_GET['date_arrivee'];
			$_GET['date_arrivee'] = date('Y-m-d H:i:s' ,strtotime($_GET['date_arrivee']));
			array_push($filtre, "p.date_arrivee > '".$_GET['date_arrivee']."'");		
		}
		if(!empty($_GET['date_depart']))	
		{
			$date_default ="";
			$date_depart=$_GET['date_depart'];
			$_GET['date_depart'] = date('Y-m-d H:i:s' ,strtotime($_GET['date_depart']));
			array_push($filtre, "p.date_depart < '".$_GET['date_depart']."'");						
		}
	}
}
// Creation du filtre de requête
//debug($filtre);
$filtre="WHERE ".implode($filtre, " AND ");
//debug($filtre);

/***************** FIN CONSTRUCTION REQUETE TRI PRODUITS *****************/


//liste pour le filtre
$resultat_ville=$pdo->query("SELECT DISTINCT ville_salle FROM salle");
$liste_ville="";
while ($element=$resultat_ville->fetch(PDO::FETCH_NUM)){
	if (isset($_GET['ville']) && $_GET['ville']==$element[0]){
		$liste_ville.='<option selected value="'.$element[0].'">'.ucfirst($element[0]).'</option>';
	}
	else{
		$liste_ville.='<option value="'.$element[0].'">'.ucfirst($element[0]).'</option>';
	}
}

$resultat_cat=$pdo->query("SELECT DISTINCT categorie_salle FROM salle");
$liste_cat="";
while ($element=$resultat_cat->fetch(PDO::FETCH_NUM)){
	if (isset($_GET['cat']) && $_GET['cat']==$element[0]){
		$liste_cat.='<option selected value="'.$element[0].'">'.ucfirst($element[0]).'</option>';
	}
	else{
		$liste_cat.='<option value="'.$element[0].'">'.ucfirst($element[0]).'</option>';
	}
}

/******** PAGINATION PRODUIT ********/
$requete = $pdo->prepare("
	SELECT COUNT(p.id_produit)
	FROM produit p 
	JOIN salle s ON p.id_salle=s.id_salle
	LEFT JOIN avis a ON a.id_salle=s.id_salle
	$filtre
	GROUP BY p.id_produit ORDER BY p.date_arrivee ASC");

$requete->execute();
$nb_resultat = $requete->rowCount();
$nb_pages = floor($nb_resultat/6);

$nav_pagination ="";
$pagination = "";
$offset_requete = 0;
if ($nb_pages == 0)
{
	// Si aucune page, pas de navigation
	$nb_pages = 0;	
}
elseif ($nb_pages%6 != 0)
{
	// Nombre de pages
	$nb_pages++;


	// Affichage du nombre de pages
	for ($i = 0; $i < $nb_pages; $i++)
	{
		if(!isset($_GET['page'])) // Page sans paramètres
		{
			if($i == 0)
			{
				// Page active = disabled
				$pagination .= '<li class="disabled"><a href="#section-boutique">'.($i+1).'</a></li>';
			}
			else
			{	
				// Page inactive
				$pagination .= '<li><a href="?page='.($i+1).'">'.($i+1).'</a></li>';
			}
		}
		elseif(isset($_GET['page']) && $_GET['page'] == ($i+1)) // Page en cours (paramètre)
		{
			// Page active = disabled
			$pagination .= '<li class="disabled"><a href="#section-boutique">'.($i+1).'</a></li>';
		}
		else // Autres pages (paramètre)
		{
			// Page inactive
			$pagination .= '<li><a href="?page='.($i+1).'">'.($i+1).'</a></li>';
		}					
	}
	
	// Wrapper pagination
	if(!isset($_GET['page']) || $_GET['page'] == 1) // Page de début
	{
		$nav_pagination = '<nav aria-label="Page navigation">
							<ul class="pagination">
								'.$pagination.'
								<li>
									<a href="?page=2" aria-label="Next">
									<span aria-hidden="true">&raquo;</span>
									</a>
								</li>
							</ul>
						</nav>';
	}
	elseif (isset($_GET['page']) && $_GET['page'] != $nb_pages) 
	{
		// OFFSET requete
		$offset_requete = ($_GET['page']-1)*6;

		// PAGINATION		
		$nav_pagination = '<nav aria-label="Page navigation">
							<ul class="pagination">
								<li>
									<a href="?page='.($_GET['page']-1).'" aria-label="Previous">
									<span aria-hidden="true">&laquo;</span>
									</a>
								</li>
								'.$pagination.'
								<li>
									<a href="?page='.($_GET['page']+1).'" aria-label="Next">
									<span aria-hidden="true">&raquo;</span>
									</a>
								</li>
							</ul>
						</nav>';	
	}	
	elseif(isset($_GET['page']) && $_GET['page'] == $nb_pages)
	{
		// OFFSET requete
		$offset_requete = ($_GET['page']-1)*6;

		// PAGINATION		
		$nav_pagination = '<nav aria-label="Page navigation">
							<ul class="pagination">
								<li>
									<a href="?page='.($_GET['page']-1).'" aria-label="Previous">
									<span aria-hidden="true">&laquo;</span>
									</a>
								</li>
								'.$pagination.'
							</ul>
						</nav>';
	}	
}




/******** AFFICHAGE DES PRODUITS ********/
$requete = $pdo->prepare("
	SELECT p.id_produit, s.photo_salle, s.titre_salle, p.prix, s.description_salle, DATE_FORMAT(p.date_arrivee, '%d %b %Y %T') AS date_arrivee, DATE_FORMAT(p.date_depart,'%d %b %Y %T') AS date_depart, ROUND(AVG(a.note)) AS note_moyenne, COUNT(a.note) AS nb_note 
	FROM produit p 
	JOIN salle s ON p.id_salle=s.id_salle
	LEFT JOIN avis a ON a.id_salle=s.id_salle
	$filtre
	GROUP BY p.id_produit ORDER BY p.date_arrivee ASC LIMIT 6 OFFSET $offset_requete");

$requete->execute();

$liste_produits = $requete->fetchAll(PDO::FETCH_ASSOC);

$produits ="";
if (empty($liste_produits))
{
	$produits .= "<p>Aucun produit</p>";
}
else
{
	foreach ($liste_produits as $produit) 
	{
		$note_moyenne = "";
  		for ($i=0; $i < $produit['note_moyenne'] ; $i++) { 
			$note_moyenne .= '<span class="glyphicon glyphicon-star"></span>';
		}
		for ($i=0; $i < (5-$produit['note_moyenne']) ; $i++) { 
			$note_moyenne .= '<span class="glyphicon glyphicon-star-empty"></span>';
		}

		$produits .= "<div class='col-xs-6 col-sm-6 col-md-4'>
						<div class='vignette-produit'>
							<a href='fiche_produit.php?action=voir&id=".$produit['id_produit']."'>
								<img src='".$produit['photo_salle']."' alt='photo salle'>
								<h3 class='text-center'>".mb_ucfirst($produit['titre_salle'])."</h3>
							</a>
							<div class='description-produit'>
								<a href='fiche_produit.php?action=voir&id=".$produit['id_produit']."'>
									<p>Prix : ".$produit['prix']." &euro;</p>
									<p class='hidden-xs'><em>".substr($produit['description_salle'],0,22)."...</em></p>
									<p><span class='glyphicon glyphicon-calendar'></span> ".$produit['date_arrivee']."</p>
									<p><span class='glyphicon glyphicon-calendar'></span> ".$produit['date_depart']."</p>
								</a>
								<p class='avis-produit'>
									<a href='fiche_produit.php?action=voir&id=".$produit['id_produit']."#liste-commentaires'>".$note_moyenne." (<span class='nb-avis'>".$produit['nb_note']." avis</span>)
									</a>
								</p>
							</div>	
						</div>	
					</div>";
	}
}