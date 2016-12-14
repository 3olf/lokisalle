<?php
require_once("inc/init.inc.php");

// Si l'utilisateur n'est pas admin on redirige
if (!userConnected()){
	header('location:index.php');
	exit();
} 

// Service profil
include("libs/serv.profil.php");

include("inc/header.inc.php");
include("inc/nav.inc.php");
?>
	<section id="section-profil">
		<div class="container">
			<h1>Profil</h1>
			<hr>
			<?php //echo debug($liste_commandes_user); ?>
			<div class="row">
				<div class="col-md-3 col-sm-12">
					<h2>Vos informations</h2>
					<p>Pseudo : <?php echo $pseudo; ?></p>
					<p>Nom : <?php echo $nom; ?></p>
					<p>Prénom : <?php echo $prenom; ?></p>
					<p>Email : <?php echo $email; ?></p>					
					<p>Civilité : <?php echo $civilite; ?></p>					
					<p>Membre depuis le : <?php echo $date_enregistrement; ?></p>					
					<?php if($statut == 1) { echo $statut; }?>			
				</div>
				<div class="col-md-9 col-sm-12">
					<h2>Réservations en cours</h2>
					<table class="table table-striped table-bordered table-hover" id="table-liste-salles">
						<thead>
							<tr><?= $content_thead ?></tr>
						</thead>
						<tbody>
							<?= $tr_commandes_user ?>
						</tbody>
					</table>
					<p class="pull-right"><strong>Montant total de vos réservation en cours</strong> : <?= $total_commandes ?> &euro;</p>
					<div class="clearfix"></div>	
					<hr>
					<h2>Historique de vos réservations</h2>
					<table class="table table-striped table-bordered table-hover" id="table-liste-salles">
						<thead>
							<tr><?= $content_thead ?></tr>
						</thead>
						<tbody>
							<?= $tr_commandes_user_p ?>
						</tbody>
					</table>
					<p class="pull-right"><strong>Montant total de vos réservations passées</strong> : <?= $total_commandes_p ?> &euro;</p>
					<div class="clearfix"></div>										
				</div>
			</div>		
		</div>
	</section>
<?php
include("inc/footer.inc.php");