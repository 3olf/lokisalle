<?php
require_once("../inc/init.inc.php");

// Si l'utilisateur n'est pas admin on redirige
if (!userConnectedAdmin()){
	header('location:../index.php');
	exit();
} 

// Service gestion des salles
include("../libs/serv.gestion.salles.php");

include("../inc/header.inc.php");
include("../inc/nav.inc.php");
?>
	<section id="section-gestion-salles" class="section-back-office">
		<div class="container">
			<h1>Gestion des salles</h1>
			<hr>	
			<!-- debug et messages d'erreur -->
			<div class="row">
				<div class="col-sm-6 col-sm-offset-3">
					<?php echo $msg_info ?>
				</div>
			</div>
			<!-- Liste salles -->
			<div class="row">
				<div class="col-sm-12">
					<table class="table table-striped table-bordered table-hover" id="table-liste-salles">
						<thead>
							<tr><?= $content_thead ?></tr>
						</thead>
						<tbody>
							<?= $tr_salle ?>
						</tbody>
					</table>
				</div>
			</div>
			<!-- Formulaire -->
			<div class="row">
				<form method="post" action="" enctype="multipart/form-data">
					<div class="col-sm-5">
						<div class="form-group">
							<label for="titre-salle">Titre</label>
							<input type="text" class="form-control" name="titre_salle" id="titre-salle" placeholder="Titre de la salle" value="<?= $titre_salle ?>">
						</div>
						<div class="form-group">
							<label for="desc-salle">Description</label>
							<textarea class="form-control" rows="3" name="description_salle" placeholder="Description de la salle"><?= $description_salle ?></textarea>
						</div>
						<div class="form-group">
							<label for="photo-salle">Photo</label>
							<input type="file" class="form-control" id="photo-salle" name="photo_salle">
						</div>

						<?php if (isset($ma_salle)) {
							// En cas de modification, on affiche la photo ?>
			            <input type="hidden" class="form-control" id="id-salle" name="id_salle" value="<?= $id_salle ?>" readonly >
			            <input type="hidden" class="form-control" id="photo-salle-actuelle" name="photo_actuelle" value="<?= $photo_salle ?>" readonly >

						<div class="form-group">	
							<label>Photo actuelle</label>
							<img class="imgpdt form-control" src="<?= URL.$photo_salle ?>" alt="" />
						</div>	
			            <?php } ?>	

						<div class="form-group">
							<label for="capacite-salle">Capacité</label>
							<select class="form-control" name="capacite_salle" id="capacite-salle">
								<?= $options_capacite ?>
							</select>
						</div>
						<div class="form-group">
							<label for="categorie-salle">Categorie</label>
							<select class="form-control" name="categorie_salle" id="categorie-salle">
								<?= $options_categorie ?>
							</select>
						</div>
					</div>
					<div class="col-sm-5 col-sm-offset-2">
						<div class="form-group">
							<label for="pays-salle">Pays</label>
							<select class="form-control" name="pays_salle" id="pays-salle">
								<option value="france" <?php if($pays_salle == 'france') {echo 'selected'; }?>>France</option>
							</select>
						</div>	
						<div class="form-group">
							<label for="ville-salle">Ville</label>
							<select class="form-control" name="ville_salle" id="ville-salle">
								<option value="paris" <?php if($ville_salle == 'paris') {echo 'selected'; }?> >Paris</option>
								<option value="lyon" <?php if($ville_salle == 'lyon') {echo 'selected'; }?> >Lyon</option>
								<option value="marseille" <?php if($ville_salle == 'marseille') {echo 'selected'; }?> >Marseille</option>
							</select>
						</div>
						<div class="form-group">
							<label for="adresse-salle">Adresse</label>
							<textarea class="form-control" rows="3" name="adresse_salle" placeholder="Adresse de la salle"><?= $adresse_salle ?></textarea>
						</div>
						<div class="form-group">
							<label for="cp-salle">Code postal</label>
							<input type="text" class="form-control" name="cp_salle" id="cp-salle" placeholder="Code postal" value="<?= $cp_salle ?>">
						</div>
						<hr>
						<div class="form-group">
							<input type="submit" name="<?= $name_submit ?>" value="<?= $submit_value ?>" class="btn btn-default btn-ok">
						</div>
					</div>
				</form>
			</div>
		</div>
	</section>
	<!-- Fin corps de page-->
<?php
include("../inc/footer.inc.php");