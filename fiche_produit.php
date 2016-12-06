<?php
require_once('inc/init.inc.php');

// Service fiche produit
include("libs/serv.fiche.produit.php");

include("inc/header.inc.php");
include("inc/nav.inc.php");
?>
	<section id="section-fiche-produit">
		<div class="container">
		<?php //debug($retour); ?>
			<div class="row">
				<div class="col-sm-12">
					<h1><?php echo mb_ucfirst($titre_salle) ?></h1>
					<div id="note-produit">
						<?= $note_produit ?>
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
					<h4 class="h4infos"><strong>Informations complémentaires</strong></h4>
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
					<a href="?action=voir&id=<?= $retour[$pdt_tires[0]]["id_produit"] ?>"><img src="<?= $retour[$pdt_tires[0]]["photo_salle"] ?>" alt="<?= $retour[$pdt_tires[0]]["titre_salle"] ?>"></a>
				</div>
				<div class="col-sm-3">
					<a href="?action=voir&id=<?= $retour[$pdt_tires[1]]["id_produit"] ?>"><img src="<?= $retour[$pdt_tires[1]]["photo_salle"] ?>" alt="<?= $retour[$pdt_tires[1]]["titre_salle"] ?>"></a>
				</div>
				<div class="col-sm-3">
					<a href="?action=voir&id=<?= $retour[$pdt_tires[2]]["id_produit"] ?>"><img src="<?= $retour[$pdt_tires[2]]["photo_salle"] ?>" alt="<?= $retour[$pdt_tires[2]]["titre_salle"] ?>"></a>
				</div>
				<div class="col-sm-3">
					<a href="?action=voir&id=<?= $retour[$pdt_tires[3]]["id_produit"] ?>"><img src="<?= $retour[$pdt_tires[3]]["photo_salle"] ?>" alt="<?= $retour[$pdt_tires[3]]["titre_salle"] ?>"></a>
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