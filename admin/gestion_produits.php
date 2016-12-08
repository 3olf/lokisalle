<?php
require_once("../inc/init.inc.php");

// Si l'utilisateur n'est pas admin on redirige
if (!userConnectedAdmin()){
	header('location:../index.php');
	exit();
} 

// Service gestion des salles
include("../libs/serv.gestion.produits.php");

include("../inc/header.inc.php");
include("../inc/nav.inc.php");
?>

<section id="section-gestion-produits">
	<div class="container">
		<h1>Gestion des produits</h1>
		<hr>
		<!-- debug et messages d'erreur -->
		<div class="row">
			<div class="col-sm-6 col-sm-offset-3">
				<?php //debug($_SERVER) ?>
				<?php echo $msg_info ?>
			</div>
		</div>
		<!-- Liste produits -->
		<div class="row">
			<div class="col-sm-12">
				<table class="table table-striped table-bordered table-hover" id="table-liste-produits">
					<thead>
						<tr><?= $content_thead ?></tr>
					</thead>
					<tbody>
						<?= $tr_produit ?>
					</tbody>
				</table>
			</div>
		</div>
		<!-- Formulaire -->
		<div class="row">
			<form method="post" action="">
				<div class="col-sm-5">
					<div class="form-group">
						<label for="date-arrive-pdt">Date d'arrivée</label>
						<div class="input-group">
							<div class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></div>
							<input type="text" class="form-control datepicker" name="date_arrivee" id="date-arrive-pdt" placeholder="JJ-MM-AAAA HH:MM" value="<?= $date_arrivee ?>">
						</div>
					</div>
					<div class="form-group">
						<label for="date-depart-pdt">Date de départ</label>
						<div class="input-group">
							<div class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></div>
							<input type="text" class="form-control datepicker" name="date_depart" id="date-depart-pdt" placeholder="JJ-MM-AAAA HH:MM" value="<?= $date_depart ?>">
						</div>
					</div>
					<?php if (isset($mon_produit)) {
						// En cas de modification ?>
		            <input type="hidden" class="form-control" id="id-produit" name="id_produit" value="<?= $id_produit ?>" readonly >
		            <?php } ?>						
				</div>				
				<div class="col-sm-5 col-sm-offset-2">
					<div class="form-group">
						<label for="salle_pdt">Salle</label>
						<select class="form-control" name="id_salle" id="salle_pdt">
							<?= $options_salles ?>
						</select>
					</div>
					<div class="form-group">
						<label for="prix_pdt">Tarif</label>
						<div class="input-group">
							<div class="input-group-addon"><span class="glyphicon glyphicon-euro"></span></div>
							<input type="text" class="form-control" name="prix" id="prix-pdt" placeholder="100,00" value="<?= $prix ?>">
						</div>
					</div>
					<div class="form-group">
						<input type="submit" name="<?= $name_submit ?>" value="<?= $submit_value ?>" class="btn btn-default pull-right">
					</div>										
				</div>
			</form>
		</div>						
	</div>
</section>
<!-- Fin corps de page-->
<?php
include("../inc/footer.inc.php");