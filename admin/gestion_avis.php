<?php
require_once('../inc/init.inc.php');

//si l'utilisateur est connecté et admin
if (!userConnectedAdmin()){
	header('location:../index.php');
	exit();
} 



//Suppression d'un avis
if (isset($_GET['action']) && $_GET['action']=="suppression" && isset($_GET['id']) && preg_match('#[0-9]{1,3}#', $_GET['id'])){

	$id_suppression=htmlentities($_GET['id'],ENT_QUOTES);

	$resultat_suppression_avis = $pdo->exec("DELETE FROM avis WHERE id_avis=$id_suppression");
	if ($resultat_suppression_avis===false){
		$msg_info="<p class='error'>Une erreur est survenue lors de la suppression</p>";
	}
	else{
		$msg_info="<p class='success'>Suppression de l'avis effectué</p>";
	}
}



include('../inc/header.inc.php');
include('../inc/nav.inc.php');
?>
<section id="section-gestion-avis">
	<div class="container">

		<h1>Gestion des avis</h1>
		<hr>
		<!-- Liste avis -->
		<div class="row">
			<div class="col-sm-12">
				<table class="table table-striped table-bordered table-hover" id="table-liste-avis">
					<thead>
						<tr>
							<?php
							//Preparation de la liste des avis

							//en_têtes :

							$resultat_liste_avis = $pdo->query("
								SELECT a.id_avis, a.id_membre, m.pseudo, a.id_salle, s.titre_salle , a.commentaire, a.note, a.date_enregistrement FROM salle s
								RIGHT JOIN avis a ON a.id_salle=s.id_salle
								JOIN membre m ON a.id_membre=m.id_membre
								");
							
							$nb_champs = $resultat_liste_avis->columnCount();
							for ($i=0; $i < $nb_champs; $i++) {
							    $meta = $resultat_liste_avis->getColumnMeta($i);
							    echo '<th>' . $meta['name'] . '</th>';
							}
							?>
							<th>actions</th>
						</tr>
					</thead>
					<tbody>
					<?php

				//données de la table :

					$resultat_liste_avis = $pdo->query("SELECT a.id_avis, a.id_membre, m.pseudo, a.id_salle, s.titre_salle , a.commentaire, a.note, a.date_enregistrement FROM salle s
					RIGHT JOIN avis a ON a.id_salle=s.id_salle JOIN membre m ON a.id_membre=m.id_membre");
					while ($ligne=$resultat_liste_avis->fetch(PDO::FETCH_ASSOC)){
						
						echo "<tr>";
						foreach ($ligne as $key => $value) {
							if($key=='note'){
								echo '<td>';
								for ($i=0; $i < $value ; $i++) { 
									echo '<span class="glyphicon glyphicon-star"></span>';
								}
								for ($i=0; $i < (5-$value) ; $i++) { 
									echo '<span class="glyphicon glyphicon-star-empty"></span>';
								}
								echo '</td>';
							}
							else{
								echo '<td>'.$value.'</td>';
							}
						}

						//ajout d'une colonne pour les actions suppr
						echo '<td><a href="?action=suppression&id='. $ligne['id_avis'] .'"  class="btn btn-danger"><span class="glyphicon glyphicon-trash"></span></a>';
						echo "</td></tr>";
					}
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

