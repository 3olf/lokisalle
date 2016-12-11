<?php
require_once('../inc/init.inc.php');

//si l'utilisateur est connecté et admin
if (!userConnectedAdmin()){
	header('location:../index.php');
	exit();
} 

//Switch du statut avec precision de l'id du membre et du new statut
if (isset($_GET['action']) && $_GET['action']=="switchstatut" && isset($_GET['newstatut']) && preg_match('#^0$|^1$#', $_GET['newstatut']) && isset($_GET['id']) && preg_match('#[0-9]{1,3}#', $_GET['id'])){

	//protection et préparation des variables de la requete
	$nouveau_statut=htmlentities($_GET['newstatut'],ENT_QUOTES);
	$id_nouveau_statut=htmlentities($_GET['id'],ENT_QUOTES);

	//maj en base de données et information du changement:
	$resultat_changement_statut = $pdo->exec("UPDATE membre SET statut='$nouveau_statut' WHERE id_membre=$id_nouveau_statut");
	if ($resultat_changement_statut===false){
		$msg_info="<p class='error'>Une erreur est survenue lors du changement de statut</p>";
	}
	else{
		$msg_info="<p class='success'>Modification de statut enregistrée</p>";
	}
} 


//Suppression d'un membre
if (isset($_GET['action']) && $_GET['action']=="suppression" && isset($_GET['id']) && preg_match('#[0-9]{1,3}#', $_GET['id'])){

	$id_suppression=htmlentities($_GET['id'],ENT_QUOTES);

	$resultat_suppression_membre = $pdo->exec("DELETE FROM membre WHERE id_membre=$id_suppression");
	if ($resultat_suppression_membre===false){
		$msg_info="<p class='error'>Une erreur est survenue lors de la suppression</p>";
	}
	else{
		$msg_info="<p class='success'>Suppression du membre effectuée</p>";
	}
}


include('../inc/header.inc.php');
include('../inc/nav.inc.php');
?>
<section id="section-gestion-membres" class="section-back-office">
	<div class="container">

		<h1>Gestion des membres</h1>
		<hr>
		<div class="row">
			<div class="col-sm-12">
				<table class="table table-striped table-bordered table-hover" id="table-liste-membres">
					<thead>
						<tr>
							<?php
							//Preparation de la liste des membres

							//en_têtes :

							$resultat_liste_membre = $pdo->query("SELECT m.id_membre, m.pseudo, m.nom, m.prenom, m.email, m.civilite, m.statut, m.date_enregistrement FROM membre m");
							
							$nb_champs = $resultat_liste_membre->columnCount();
							for ($i=0; $i < $nb_champs; $i++) {
							    $meta = $resultat_liste_membre->getColumnMeta($i);
							    echo '<th>' . $meta['name'] . '</th>';
							}
							?>
							<th>actions</th>
						</tr>
					</thead>
					<tbody>
					<?php 

				//données de la table :

					$resultat_liste_membre = $pdo->query("SELECT m.id_membre, m.pseudo, m.nom, m.prenom, m.email, m.civilite, m.statut, m.date_enregistrement FROM membre m");
					while ($ligne=$resultat_liste_membre->fetch(PDO::FETCH_ASSOC)){
						
						echo "<tr>";
						foreach ($ligne as $key => $value) {
							echo '<td>'.$value.'</td>';
						}

						//ajout d'une colonne pour les actions modif /suppr
						echo '<td><a href="?action=modification&id='. $ligne['id_membre'] .'" class="btn btn-default" ><span class="glyphicon glyphicon-pencil"></span></a>';
						echo '<a href="?action=suppression&id='. $ligne['id_membre'] .'"  class="btn btn-default"><span class="glyphicon glyphicon-trash"></span></a>';
						echo "</td></tr>";
					}
					echo '</tbody>';
				echo '</table>';

				echo $msg_info;

			//affichage du formulaire de modification

			if (isset($_GET['action']) && $_GET['action']=="modification" && isset($_GET['id']) && preg_match('#[0-9]{1,3}#', $_GET['id'])){ 
				$resultat_id_recherche=$pdo->query("SELECT id_membre, pseudo, statut FROM membre WHERE id_membre='$_GET[id]'");
				$membre_modifie=$resultat_id_recherche->fetch(PDO::FETCH_ASSOC);
				extract($membre_modifie);
				if ($statut==1){$newstatut=0;}else{$newstatut=1;}
			?>
				<h3>Modification du statut de <?= $pseudo ?></h3>
				<p><?= $pseudo ?> possède actuellement un accès <?php 
				if($statut=="1"){
					echo "administrateur, voulez-vous lui supprimer ses droits administrateur ?";
				}
				else{
					echo "visiteur simple, voulez-vous lui conférer des droits administrateur ?";
				} ?></p>
				<a href=?action=switchstatut&newstatut=<?= $newstatut ?>&id=<?= $id_membre ?> class="btn btn-primary"><span class="glyphicon glyphicon-transfer"></span></a>

				<?php
				}
				?>
			</div>
		</div>
	</div>
</section>
<?php
include('../inc/footer.inc.php');

