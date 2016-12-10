<?php

require_once('../inc/init.inc.php');

//si l'utilisateur est connecté et admin
if (!userConnectedAdmin()){
	header('location:index.php');
	exit();
} 



// Annulation d'une commande
if (isset($_GET['action']) && $_GET['action']=="suppression" && isset($_GET['id']) && preg_match('#[0-9]{1,3}#', $_GET['id'])){

	$id_suppression=htmlentities($_GET['id'],ENT_QUOTES);

	$resultat_suppression_commande = $pdo->exec("DELETE FROM commande WHERE id_commande=$id_suppression");
	if ($resultat_suppression_commande===false){
		$msg_info="<p class='error'>Une erreur est survenue lors de la suppression</p>";
	}
	else{
		$msg_info="<p class='success'>Annulation de la commande effectué</p>";
	}
}



include('../inc/header.inc.php');
include('../inc/nav.inc.php');
?>
<section id="section-gestion-commandes">
	<div class="container">

		<h1>Gestion des commandes</h1>
		<hr>
		<div class="row">
			<div class="col-sm-12">
				<table class="table table-striped table-bordered table-hover" id="table-liste-commandes">
					<thead>
						<tr>
							<?php
							//Preparation de la liste des commandes

							//en_têtes :

							$resultat_liste_commande = $pdo->query("
								SELECT c.id_commande, c.id_membre, m.pseudo, c.id_produit, p.prix, c.date_enregistrement FROM commande c
								JOIN produit p ON p.id_produit=c.id_produit
								JOIN membre m ON c.id_membre=m.id_membre
								");
							
							$nb_champs = $resultat_liste_commande->columnCount();
							for ($i=0; $i < $nb_champs; $i++) {
							    $meta = $resultat_liste_commande->getColumnMeta($i);
							    echo '<th>' . $meta['name'] . '</th>';
							}
							?>
							<th>actions</th>
						</tr>
					</thead>
					<tbody>
					<?php

				//données de la table :

					$resultat_liste_commande = $pdo->query("SELECT c.id_commande, c.id_membre, m.pseudo, c.id_produit, p.prix, c.date_enregistrement FROM commande c
					JOIN produit p ON p.id_produit=c.id_produit
					JOIN membre m ON c.id_membre=m.id_membre");
					$total_ttc=0;
					while ($ligne=$resultat_liste_commande->fetch(PDO::FETCH_ASSOC)){
						
						echo "<tr>";
						foreach ($ligne as $key => $value) {
							echo '<td>'.$value.'</td>';
							
							if($key=='prix'){
								$total_ttc+=$value;
							}
						}

						//ajout d'une colonne pour les actions modif /suppr
						echo '<td><a href="'.URL.'fiche_produit.php?id='. $ligne['id_produit'] .'" class="btn btn-default" ><span class="glyphicon glyphicon-search"></span></a>';
						echo '<a href="?action=modification&id='. $ligne['id_commande'] .'" class="btn btn-default" ><span class="glyphicon glyphicon-pencil"></span></a>';
						echo '<a href="?action=suppression&id='. $ligne['id_commande'] .'"  class="btn btn-default"><span class="glyphicon glyphicon-trash"></span></a>';
						echo "</td></tr>";
					}

						echo '<tr><td colspan="4">Total</td><td><strong>'.$total_ttc.' €</strong></td>';
					echo '</tbody>';
				echo '</table>';

				echo $msg_info;




			?>
			</div>
		</div>
	</div>
</section>
<?php
include('../inc/footer.inc.php');

