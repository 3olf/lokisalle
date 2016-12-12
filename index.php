<?php
require_once("inc/init.inc.php");

/***************** CONSTRUCTION REQUETE TRI PRODUITS *****************/
$filtre="";
$date_arrivee= "";
$date_depart="";
$prix=3000;   //le plus cher par defaut
$capacite=0;
$capacite_affichage = "toutes";
$filtre=array("p.etat='libre'");


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
			$date_arrivee=$_GET['date_arrivee'];
			$_GET['date_arrivee'] = date('Y-m-d H:i:s' ,strtotime($_GET['date_arrivee']));
			array_push($filtre, "p.date_arrivee > '".$_GET['date_arrivee']."'");		
		}
		if(!empty($_GET['date_depart']))	
		{
			$date_depart=$_GET['date_depart'];
			$_GET['date_depart'] = date('Y-m-d H:i:s' ,strtotime($_GET['date_depart']));
			array_push($filtre, "p.date_depart < '".$_GET['date_depart']."'");						
		}
	}
}
// Creation du filtre de requête
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

include("inc/header.inc.php");
include("inc/nav.inc.php");

?>
    <div class="container">
        <h1 id="h1-index">Nos produits</h1>
        <hr>  
        <div class="row">
        	<div class="col-sm-3"><!--panneau filtre-->
	        	<aside id="filtres-boutique">
	        		<form method="GET" action="" class="form">
			  		 	<div class="form-group">
			  		 		<label for="cat">Catégorie </label>
			  				<select name="cat" id="cat" class="form-control">
			  					<option value="tous">Tous les produits</option>
			  					<?php
			  					echo $liste_cat;
			  					?>
			  				</select>

						</div>

						<div class="form-group">
				  			<label for="ville"> Ville </label>
				  			<select name="ville" id="ville" class="form-control">
				  				<option value="tous">Toutes les villes</option>
				  				<?php
				  				echo $liste_ville;
				  				?>
				  			</select>
						</div>

						<!-- ajouter du javascript pour afficher la valeur des input dessous -->
			  			<div class="form-group">
				  			<label for="capacite"> Capacité : <span id="capaciteFiltre"><?= $capacite_affichage ?></span> <span class="small">(maximum)</span></label>
				  			<input id="capacite" type="range" value="<?= $capacite ?>" max="100" min="0" step="10" name="capacite">
						</div>

						<div class="form-group">
							<label for="prix"> Prix : <span id="prixFiltre"><?= $prix ?></span> &euro; <span class="small">(maximum)</span></label>
							<input type="range" value="<?= $prix ?>" max="3000" min="0" step="300" name="prix" id="prix">
						</div>

						<?= $msg_info ?>

						<div class="form-group">
							<label for="date-arrive-pdt">Date d'arrivée</label>
							<div class="input-group">
								<div class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></div>
								<input type="text" class="form-control datepicker" name="date_arrivee" id="date-arrive-pdt" placeholder="Date d'arrivée" value="<?= $date_arrivee ?>">
							</div>
						</div>
						<div class="form-group">
							<label for="date-depart-pdt">Date de départ</label>
							<div class="input-group">
								<div class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></div>
								<input type="text" class="form-control datepicker" name="date_depart" id="date-depart-pdt" placeholder="Date de départ" value="<?= $date_depart ?>">
							</div>
						</div>

			  			<input type="submit" value="Valider" class="btn btn-default btn-ok">
			  		</form>
			  	</aside>	
        	</div>

        	<div class="col-sm-9"><!--boutique-->
	        	<section id="section-boutique">
	<?php    		
				$resultat_produit= $pdo->query("
					SELECT p.id_produit, s.photo_salle, s.titre_salle, p.prix, s.description_salle, DATE_FORMAT(p.date_arrivee, '%d %b %Y %T') AS date_arrivee, DATE_FORMAT(p.date_depart,'%d %b %Y %T') AS date_depart, ROUND(AVG(a.note)) AS note_moyenne, COUNT(a.note) AS nb_note FROM produit p 
					JOIN salle s ON p.id_salle=s.id_salle
					LEFT JOIN avis a ON a.id_salle=s.id_salle
					$filtre
					GROUP BY p.id_produit");

				//joindre avec la note moyenne !
					  	echo "<div class='row'>";
					  	if ($resultat_produit->rowCount()>0){
					  		while($produit=$resultat_produit->fetch(PDO::FETCH_ASSOC)){
						  		extract($produit);
						  		//affichage de chaque vignette de salle

						  		echo "<div class='col-xs-6 col-sm-6 col-md-4'>";
						  		echo "<div class='vignette-produit'><a href='fiche_produit.php?action=voir&id=".$id_produit."'><img src='".$photo_salle."' alt='photo salle'>";
						  		echo "<h3 class='text-center'>".mb_ucfirst($titre_salle)."</h3></a>";
						  		echo "<div class='description-produit'><a href='fiche_produit.php?action=voir&id=".$id_produit."'><p>Prix : ".$prix." &euro;</p>";
						  		echo '<p class="hidden-xs"><em>'.substr($description_salle,0,22).'...</em></p>';
						  		echo "<p><span class='glyphicon glyphicon-calendar'></span> ".$date_arrivee."</p>";
						  		echo "<p><span class='glyphicon glyphicon-calendar'></span> ".$date_depart."</p></a>";
						  		echo "<p class='avis-produit'><a href='fiche_produit.php?action=voir&id=".$id_produit."#liste-commentaires'>";

						  		//note moyenne
						  		for ($i=0; $i < $note_moyenne ; $i++) { 
									echo '<span class="glyphicon glyphicon-star"></span>';
								}
								for ($i=0; $i < (5-$note_moyenne) ; $i++) { 
									echo '<span class="glyphicon glyphicon-star-empty"></span>';
								}

								echo " (<span class='nb-avis'>".$nb_note." avis</span>)</a></p>";
								echo '</div></div>';

						  		echo "</div>";
					  		}
					  	}
					echo "</div>";	
	?>
				</section>
        	</div>

        </div>

    </div><!-- Fin Container -->

<?php
include("inc/footer.inc.php");

