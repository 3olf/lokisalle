<?php
require_once('inc/init.inc.php');

// Service fiche produit
include("libs/serv.fiche.produit.php");

include("inc/header.inc.php");
include("inc/nav.inc.php");
?>
	<section id="section-fiche-produit">
		<div class="container">
		<?php debug($resultat); ?>
			<div class="row">
				<div class="col-sm-12">
					<h1><?php echo mb_ucfirst($titre_salle) ?></h1>
					<div id="note-produit">
						3 étoiles
					</div>
					<?= $btn_reservation ?>
					<div class="clearfix"></div>
				</div>			
			</div>
			<hr>
			<div class="row">
				<div class="col-sm-8">
					<img src="<?= $photo_salle ?>" alt="Salle <?= $titre_salle?>">
				</div>
				<div class="col-sm-4">
					<h4><strong>Description</strong></h4>
					<p><?= $description_salle ?></p>
				</div>
				<div class="col-sm-12">
					<h4><strong>Informations complémentaires</strong></h4>
					<div class="col-sm-4"><span class="glyphicon glyphicon-calendar"></span> Arrivée : <?= $date_arrivee ?></div>
					<div class="col-sm-4"><span class="glyphicon glyphicon-user"></span> Capacité : <?= $capacite_salle ?> places</div>
					<div class="col-sm-4"><span class="glyphicon glyphicon-map-marker"></span> Adresse : <?php echo $adresse_salle.", ".$cp_salle.", ".$ville_salle ?></div>
					<div class="col-sm-4"><span class="glyphicon glyphicon-calendar"></span> Départ : <?= $date_depart ?></div>
					<div class="col-sm-4"><span class="glyphicon glyphicon-inbox"></span> Catégorie : <?= $categorie_salle ?></div>
					<div class="col-sm-4"><span class="glyphicon glyphicon-euro"></span> Tarif : <?= $prix ?> &euro;</div>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-12">
					<h2>Autres produits</h2>
				</div>
			</div>
			<hr>
			<div class="row">
				<div class="col-sm-3">
					Pdt1
				</div>
				<div class="col-sm-3">
					Pdt2
				</div>
				<div class="col-sm-3">
					pdt3
				</div>
				<div class="col-sm-3">
					pdt4
				</div>
			</div>
			<hr>
			<div class="row">
				<div class="col-sm-4">
					Deposer un commentaire
				</div>
				<div class="col-sm-4">
					Commentaires
				</div>
				<div class="col-sm-4">
					Retour vers le catalogue
				</div>
			</div>			
		</div>
	</section>
<!-- Fin corps de page-->
<?php
include("inc/footer.inc.php");