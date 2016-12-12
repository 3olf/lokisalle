<?php

require_once('inc/init.inc.php');

//si l'utilisateur est connecté et admin
if (!userConnected()){
	header('location:index.php');
	exit();
} 
//debug($_SESSION['utilisateur']);
extract($_SESSION['utilisateur']);


include('inc/header.inc.php');
include('inc/nav.inc.php');
?>

<div class="container">

	<h1>Profil</h1>

	<h2>Information</h2>
	
	<p>Bonjour <?php echo $pseudo; ?>,voici vos informations :</p>

	<p>Nom : <?php echo $nom; ?></p>
	<p>Prénom : <?php echo $prenom; ?></p>
	<p>email : <?php echo $email; ?></p>
	<?php if (userConnectedAdmin()){
	echo'<p>Vous avez des <strong>doits administrateur</strong> sur ce site</p>';
	} ?>

	<h2>Liste de commandes</h2>

	<table class="table table-bordered">
		<tr>


	//Preparation de la liste des commandes

	//en_têtes :

		<tr>
			<th>Titre salle</th>
			<th>Date_arrivée</th>
			<th>Date départ</th>
			<th>date_enregistrement</th>
			<th>Prix</th>
			<th>Fiche produit</th>
		</tr>
		<?php

	//données de la table :

		$resultat_liste_commande = $pdo->query("SELECT s.titre_salle, p.date_arrivee, p.date_depart,  DATE_FORMAT(c.date_enregistrement, '%d %b %Y %T'), p.prix FROM commande c
		JOIN produit p ON p.id_produit=c.id_produit
		JOIN membre m ON c.id_membre=m.id_membre
		JOIN salle s ON s.id_salle=p.id_salle
		WHERE m.id_membre='$id_membre'");
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
			echo '<td><a href="'.URL.'fiche_produit.php?salle='. $ligne['id_produit'] .'" class="btn btn-default" ><span class="glyphicon glyphicon-search"></span></a>';
			echo "</td></tr>";
		}

	echo '<tr><td colspan="4">Total</td><td><strong>'.$total_ttc.' €</strong></td>';
	echo '</table>';

	echo $msg_info;



echo '</div>';

include('inc/footer.inc.php');

