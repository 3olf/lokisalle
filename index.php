<?php
require_once("inc/init.inc.php");

// Service récupération des produits
include("libs/serv.tous.produits.php");

include("inc/header.inc.php");
include("inc/nav.inc.php");

?>
    <div class="container">
    <?php //debug($_SERVER['PHP_SELF']); ?>
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
			  					<?= $liste_cat; ?>
			  				</select>

						</div>

						<div class="form-group">
				  			<label for="ville"> Ville </label>
				  			<select name="ville" id="ville" class="form-control">
				  				<option value="tous">Toutes les villes</option>
				  				<?= $liste_ville; ?>
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

						<?= $msg_info; ?>

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
					<div class="row">
						<?= $produits; ?>
					</div>
					<?= $nav_pagination; ?>
				</section>
        	</div>
        </div>
    </div><!-- Fin Container -->

<?php
include("inc/footer.inc.php");

