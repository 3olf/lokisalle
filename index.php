<?php
require_once("inc/init.inc.php");

//afficher $mes_categories $mes_villes

include("inc/header.inc.php");
include("inc/nav.inc.php");

?>
    <div class="container">

        <h1>Accueil</h1>
        
        <div class="row">
        	<div class="col-sm-4"><!--panneau filtre-->
        		<form method="GET" action="" class="form">
		  		 	<!--div class="form-group"-->
		  		 	<label for="cat">Tri par catégorie : </label>
		  			<select name="cat" id="cat" class="form-control">
		  				<option value="tous">Tous les produits</option>
		  				<?php
		  				echo $mes_categories;
		  				?>
		  			</select>

		  			<label for="ville"> par ville : </label>
		  			<select name="ville" id="ville" class="form-control">
		  				<option value="tous">Toutes les villes</option>
		  				<?php
		  				echo $mes_villes;
		  				?>
		  			</select>

					<!-- ajouter du javascript pour afficher la valeur des input dessous -->
		  			<label for="capacite"> capacite minimum : </label>
		  			<input type="range" value="0" max="500" min="0" step="20">
					
					<label for="coul"> prix maximum : </label>
		  			<input type="range" value="1000" max="1000" min="0" step="100">
		  			<!--/div-->

		  			<input 
					
					<p>ajouter la date</p>

		  			<input type="submit" value="trier" class="btn btn-info">
		  		</form>
        	</div>

        	<div class="col-sm-8"><!--boutique-->
<?php    		
			$resultat_produit= $pdo->query("
				SELECT p.id_produit, s.photo_salle, s.titre_salle, p.prix, s.description_salle, DATE_FORMAT(p.date_arrivee, '%d %b %Y %T') AS date_arrivee, DATE_FORMAT(p.date_depart,'%d %b %Y %T') AS date_depart, ROUND(AVG(a.note)) AS note_moyenne, COUNT(a.note) AS nb_note FROM produit p 
				JOIN salle s ON p.id_salle=s.id_salle
				LEFT JOIN avis a ON a.id_salle=s.id_salle
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
					  		echo '<p><em>'.substr($description_salle,0,20).'...</em></p>';
					  		echo '<p><span class="glyphicon glyphicon-calendar"></span>'.$date_arrivee." - ".$date_depart.'</p><p>';

					  		//note moyenne
					  		for ($i=0; $i < $note_moyenne ; $i++) { 
								echo '<span class="glyphicon glyphicon-star"></span>';
							}
							for ($i=0; $i < (5-$note_moyenne) ; $i++) { 
								echo '<span class="glyphicon glyphicon-star-empty"></span>';
							}

							echo ' ('.$nb_note.' avis)</p>';
							echo '<a class="btn btn-default" href="fiche_produit.php?salle='.$id_produit.'""><span class="glyphicon glyphicon-search"></span></a>';

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

