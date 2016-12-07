<?php
require_once("inc/init.inc.php");

$filtre="";
$date_arrivee= date("Y-m-d H:i:s"); //par défaut, la date du jour
$date_depart='';
$prix=3000;   //le plus cher par defaut
$capacite=0; 

if (isset($_GET['cat']) && isset($_GET['ville']) && isset($_GET['capacite']) && isset($_GET['prix']) ){

	foreach ($_GET as $key => $value) {
		$_GET[$key]=htmlentities($value,ENT_QUOTES);
	}

	$filtre=[];

	if (!empty($_GET['cat']) && $_GET['cat'] != "tous"){
		array_push($filtre, "s.categorie_salle='".$_GET['cat']."'");

	}
	if (!empty($_GET['ville']) && $_GET['ville'] != "tous"){
		array_push($filtre, "s.ville_salle='".$_GET['ville']."'");
	}
	if (!empty($_GET['capacite'])){
		array_push($filtre, "s.capacite_salle>".$_GET['capacite']);
		$capacite=$_GET['capacite'];
	}
	if (!empty($_GET['prix'])){
		array_push($filtre, "p.prix<".$_GET['prix']);
		$prix=$_GET['prix'];
	}

	if (!empty($_GET['prix'])){
		array_push($filtre, "p.prix<".$_GET['prix']);
		$prix=$_GET['prix'];
	}

	if (!empty($_GET['date_depart']))
	
	//if(!empty($_GET['date_depart']) XOR)


	$filtre="WHERE ".implode($filtre, " AND ");
	debug($filtre);
	debug($date_arrivee);
}

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

        <h1>Accueil</h1>
        
        <div class="row">
        	<div class="col-sm-3"><!--panneau filtre-->
        		<form method="GET" action="" class="form">
		  		 	<div class="form-group">
		  		 		<label for="cat">Tri par catégorie : </label>
		  				<select name="cat" id="cat" class="form-control">
		  					<option value="tous">Tous les produits</option>
		  					<?php
		  					echo $liste_cat;
		  					?>
		  				</select>

					</div>

					<div class="form-group">
			  			<label for="ville"> par ville : </label>
			  			<select name="ville" id="ville" class="form-control">
			  				<option value="tous">Toutes les villes</option>
			  				<?php
			  				echo $liste_ville;
			  				?>
			  			</select>
					</div>

					<!-- ajouter du javascript pour afficher la valeur des input dessous -->
		  			<div class="form-group">
			  			<label for="capacite"> capacite minimum : <span id="capaciteFiltre"></span></label>
			  			<input id="capacite" type="range" value="<?= $capacite ?>" max="500" min="0" step="20" name="capacite">
					</div>

					<div class="form-group">
						<label for="prix"> prix maximum : <span id="prixFiltre"></span></label>
						<input type="range" value="<?= $prix ?>" max="3000" min="0" step="300" name="prix" id="prix">
					</div>

					<p>Disponible entre les dates suivantes :</p>

					<div class="form-group">
						<label for="date-arrive-pdt">Date d'arrivée</label>
						<div class="input-group">
							<div class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></div>
							<input type="text" class="form-control" name="date_arrivee" id="date-arrive-pdt" placeholder="JJ-MM-AAAA HH:MM" value="<?= $date_arrivee ?>">
						</div>
					</div>
					<div class="form-group">
						<label for="date-depart-pdt">Date de départ</label>
						<div class="input-group">
							<div class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></div>
							<input type="text" class="form-control" name="date_depart" id="date-depart-pdt" placeholder="JJ-MM-AAAA HH:MM" value="<?= $date_depart ?>">
						</div>
					</div>

		  			<input type="submit" value="trier" class="btn btn-info">
		  		</form>
        	</div>

        	<div class="col-sm-9"><!--boutique-->
<?php    		
			$resultat_produit= $pdo->query("
				SELECT p.id_produit, s.photo_salle, s.titre_salle, p.prix, s.description_salle, DATE_FORMAT(p.date_arrivee, '%d %b %Y %T') AS date_arrivee, DATE_FORMAT(p.date_depart,'%d %b %Y %T') AS date_depart, ROUND(AVG(a.note)) AS note_moyenne, COUNT(a.note) AS nb_note FROM produit p 
				JOIN salle s ON p.id_salle=s.id_salle
				LEFT JOIN avis a ON a.id_salle=s.id_salle
				$filtre
				GROUP BY p.id_produit");
			//joindre avec la note moyenne !
				  	echo "<div class='row'>";
				  	$compteur_ligne=0;
				  	if ($resultat_produit->rowCount()>0){
				  		echo '<div class="row">';
				  		while($produit=$resultat_produit->fetch(PDO::FETCH_ASSOC)){
					  		extract($produit);
					  		//affichage de chaque vignette de salle

					  		echo "<div class='col-sm-4'>";
					  		echo "<img class='col-xs-12' src='".$photo_salle."' alt='photo salle'>";
					  		echo '<p> Salle : '.$titre_salle.'</p>';
					  		echo '<p>Prix : '.$prix.'</p>';
					  		echo '<p><em>'.substr($description_salle,0,30).'...</em></p>';
					  		echo '<p><span class="glyphicon glyphicon-calendar"></span>'.$date_arrivee." - ".$date_depart.'</p><p>';

					  		//note moyenne
					  		for ($i=0; $i < $note_moyenne ; $i++) { 
								echo '<span class="glyphicon glyphicon-star"></span>';
							}
							for ($i=0; $i < (5-$note_moyenne) ; $i++) { 
								echo '<span class="glyphicon glyphicon-star-empty"></span>';
							}

							echo ' ('.$nb_note.' avis)</p>';
							echo '<a class="btn btn-default" href="fiche_produit.php?action=voir&id='.$id_produit.'""><span class="glyphicon glyphicon-search"></span></a>';

					  		echo "</div>";
					  		$compteur_ligne++;
					  		if ($compteur_ligne%3==0){
					  			echo "</div><div class='row'>";
					  		}


				  		}
				  		echo '</div>';
				  	}
?>
        	</div>

        </div>

    </div><!-- Fin Container -->

<?php
include("inc/footer.inc.php");

