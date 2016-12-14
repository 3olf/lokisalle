<?php
require_once("../inc/init.inc.php");


// css grossir classe glyphicon-big
//date du jour et date il y a un mois :
$today = new DateTime(date('Y-m-d H:i:s'));
$three_month_ago = new DateTime(date('Y-m-d H:i:s') . '-3 month');
$three_month_ago_string = $three_month_ago->format('Y-m-d H:i:s');
//debug($three_month_ago_string);
//preparation des requêtes pour les 4 premiers panneaux
//avis
$resultat_nb_avis = $pdo->query("SELECT COUNT(id_avis) FROM avis WHERE date_enregistrement>'$three_month_ago_string'");
$nb_avis = $resultat_nb_avis->fetch(PDO::FETCH_NUM);
$nb_avis = $nb_avis[0];

//commandes
$resultat_nb_commandes = $pdo->query("SELECT COUNT(id_commande) FROM commande WHERE date_enregistrement>'$three_month_ago_string'");
$nb_commandes = $resultat_nb_commandes->fetch(PDO::FETCH_NUM);
$nb_commandes = $nb_commandes[0];

//membres
$resultat_nb_membres = $pdo->query("SELECT COUNT(id_membre) FROM membre WHERE date_enregistrement>'$three_month_ago_string'");
$nb_membres = $resultat_nb_membres->fetch(PDO::FETCH_NUM);
$nb_membres = $nb_membres[0];

//Pourcentage de produits reservés
$resultat_nb_produits_reserves = $pdo->query("SELECT COUNT(id_produit) FROM produit WHERE date_depart>'$three_month_ago_string' AND etat='reservation'");
$nb_produits_reserves = $resultat_nb_produits_reserves->fetch(PDO::FETCH_NUM);
$nb_produits_reserves = $nb_produits_reserves[0];


//preparation requêtes pour les TOP 5
//membres
$resultat_top_membres = $pdo->query("SELECT m.pseudo, m.nom, m.prenom, SUM(p.prix) AS depense FROM membre m
		JOIN commande c ON m.id_membre=c.id_membre
		JOIN produit p ON p.id_produit=c.id_produit
		WHERE c.date_enregistrement>'$three_month_ago_string'
		GROUP BY m.pseudo, m.nom, m.prenom
		ORDER BY depense DESC 
		LIMIT 5");
$tab_resultat_top_membres = $resultat_top_membres->fetchAll();

$resultat_top_avis = $pdo->query("SELECT s.id_salle, s.titre_salle, s.ville_salle, AVG(a.note) AS moyenne_avis FROM salle s
		JOIN avis a ON a.id_salle=s.id_salle
		WHERE a.date_enregistrement>'$three_month_ago_string'
		GROUP BY s.titre_salle
		ORDER BY moyenne_avis DESC 
		LIMIT 5");
$tab_resultat_top_avis = $resultat_top_avis->fetchAll();

include("../inc/header.inc.php");
include("../inc/nav.inc.php");
?>
<div class="container-fluid">

	<!-- Page Heading -->
	<div class="row">
		<div class="col-lg-12">
			<h1 class="page-header">
				Dashboard <small>Statistiques des 3 derniers mois</small>
			</h1>
			<ol class="breadcrumb">
				<li class="active">
					<span class="glyphicon glyphicon-dashboard"></span> Données de la période du <?php echo $three_month_ago->format('d/m/Y') . " au " . $today->format('d/m/Y'); ?>  
				</li>
			</ol>
		</div>
	</div>
	<!-- /.row -->


	<div class="row">
		<div class="col-lg-3 col-md-6">
			<div class="panel panel-info">
				<div class="panel-heading">
					<div class="row">
						<div class="col-xs-3">
							<span class="glyphicon glyphicon-edit glyphicon-big"></span>
						</div>
						<div class="col-xs-9 text-right">
							<div class="huge"><?php echo $nb_commandes; ?></div>
							<div>Commandes passées!</div>
						</div>
					</div>
				</div>

				<a href="gestion_commandes.php">
					<div class="panel-footer">
						<span class="pull-left">Voir la liste des commandes</span>
						<span class="pull-right"><i class="glyphicon glyphicon-step-forward"></i></span>
						<div class="clearfix"></div>
					</div>
				</a>
			</div>
		</div>
		<div class="col-lg-3 col-md-6">
			<div class="panel panel-success">
				<div class="panel-heading">
					<div class="row">
						<div class="col-xs-3">
							<span class="glyphicon glyphicon-user glyphicon-big"></span>
						</div>
						<div class="col-xs-9 text-right">
							<div class="huge"><?php echo $nb_membres; ?></div>
							<div>Membres inscrits!</div>
						</div>
					</div>
				</div>

				<a href="gestion_membres.php">
					<div class="panel-footer">
						<span class="pull-left">Voir la liste des membres</span>
						<span class="pull-right"><i class="glyphicon glyphicon-step-forward"></i></span>
						<div class="clearfix"></div>
					</div>
				</a>
			</div>
		</div>
		<div class="col-lg-3 col-md-6">
			<div class="panel panel-warning">
				<div class="panel-heading">
					<div class="row">
						<div class="col-xs-3">
							<span class="glyphicon glyphicon-star-empty glyphicon-big"></span>
						</div>
						<div class="col-xs-9 text-right">
							<div class="huge"><?php echo $nb_avis; ?></div>
							<div>Avis reçus!</div>
						</div>
					</div>
				</div>

				<a href="gestion_avis.php">
					<div class="panel-footer">
						<span class="pull-left">Voir la liste des avis</span>
						<span class="pull-right"><i class="glyphicon glyphicon-step-forward"></i></span>
						<div class="clearfix"></div>
					</div>
				</a>
			</div>
		</div>
		<div class="col-lg-3 col-md-6">
			<div class="panel panel-danger">
				<div class="panel-heading">
					<div class="row">
						<div class="col-xs-3">
							<span class="glyphicon glyphicon-shopping-cart glyphicon-big"></span>
						</div>
						<div class="col-xs-9 text-right">
							<div class="huge"><?php echo $nb_produits_reserves; ?></div>
							<div>Produits réservés!</div>
						</div>
					</div>
				</div>
				<a href="gestion_produits.php">
					<div class="panel-footer">
						<span class="pull-left">Voir la liste des produits</span>
						<span class="pull-right"><i class="glyphicon glyphicon-step-forward"></i></span>
						<div class="clearfix"></div>
					</div>
				</a>
			</div>
		</div>
	</div>
	<!-- /.row -->

	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h3 class="panel-title"><i class="fa fa-bar-chart-o fa-fw"></i> Cumul du chiffre d'affaire des 30 derniers jours</h3>
				</div>
				<div class="panel-body">
					<div id="morris-area-chart">
						<canvas id="myChart" width="800" height="100"></canvas>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- /.row -->

	<div class="row">
		<div class="col-lg-4">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h3 class="panel-title"><i class=" glyphicon glyphicon-signal"></i> Pourcentage d'occupation des salles au cours du mois</h3>
				</div>
				<div class="panel-body">
					<div id="morris-donut-chart">

						<canvas id="myChart2" width="100" height="100"></canvas>
					</div>

				</div>
				<div class='panel-footer'>
					<ul>
						<li>Le pourcentage représente le nombre de produits réservés par rapport aux produits totaux proposés sur le mois, peu importe la durée de la location</li>
						<li>Un produit est retenu dans la période si au moins un jour est inclus dans la période de ce tableau de bord</li>
						<li>Les salles avec un astérisque ne proposent pas de produits dans le mois</li>
					</ul>
				</div>
			</div>
		</div>

		<div class="col-lg-4">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h3 class="panel-title"><i class="glyphicon glyphicon-user"></i> Top 5 des meilleurs client du mois</h3>
				</div>
				<div class="panel-body">
					<div class="list-group">

<?php foreach ($tab_resultat_top_membres as $key => $value) { ?>

							<div class="list-group-item">
								<span class="badge"><?php echo $value['depense']; ?></span>
							<?php echo $value['prenom'] . ' ' . $value['nom'] . ' ( ' . $value['pseudo'] . ' )'; ?>
							</div>

<?php } ?>

					</div>
					<div class="text-right">
						<a href="gestion_membres.php">Voir la table des membres <i class="glyphicon glyphicon-step-forward"></i></a>
					</div>
				</div>
			</div>
		</div>


		<div class="col-lg-4">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h3 class="panel-title"><i class="glyphicon glyphicon-user"></i> Top 5 des salles les mieux notées les 3 derniers mois</h3>
				</div>
				<div class="panel-body">
					<div class="list-group">

<?php foreach ($tab_resultat_top_avis as $key => $value) { ?>

							<div class="list-group-item">
								<span class="badge"><?php echo number_format($value['moyenne_avis'],1,','," "); ?></span>
							<?php echo $value['titre_salle'] . ' ( ' . ucfirst($value['ville_salle']) . ')'; ?>
							</div>

<?php } ?>

					</div>
					<div class="text-right">
						<a href="gestion_avis.php">Voir la table des avis <i class="glyphicon glyphicon-step-forward"></i></a>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- /.row -->

</div>
<!-- /.container-fluid -->


<?php
include("../inc/footer.inc.php");
