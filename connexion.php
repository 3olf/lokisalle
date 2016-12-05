<?php
require_once("inc/init.inc.php");
if (isset($_GET['action']) && ($_GET['action']=='deconnexion')){
	unset($_SESSION['utilisateur']);

}

if(userConnected()){
	header("location: index.php");
	exit(); 
}

if (isset($_POST['pseudo']) && isset($_POST['mdp'])){
	extract($_POST);
	$pseudo=htmlentities($pseudo, ENT_QUOTES);
	$mdp=sha1(htmlentities($mdp, ENT_QUOTES));

	$selection_membre=$pdo->query("SELECT * FROM membre WHERE pseudo='$pseudo' AND mdp='$mdp'");
	if ($selection_membre->rowCount() == 1){
		$_SESSION['utilisateur'] = array();
		$membre = $selection_membre->fetch(PDO::FETCH_ASSOC);
		//on transforme l'objet $selection_membre en tableau array avec la methode fetch_assoc()
		foreach ($membre as $indice => $valeur) {
			if($indice != 'mdp'){
				$_SESSION['utilisateur'][$indice]=$valeur;
			}
		}
		header("location:index.php");
	}
	else{
		$msg_info.="<div class='erreur'>Votre pseudo et/ou votre mot de passe est invalide.</div>";
	}
}


include("inc/header.inc.php");
include("inc/nav.inc.php");
?>

<div class="container">


  <h1>Connexion</h1>
    <?php echo $msg_info; 
    //debug($_POST);?>
  </div>

	<div class="row">
		<div class="col-sm-4 col-sm-offset-4">
			<form method='post' action="">

				<div class="form-group">
	    			<label for="pseudo">Pseudo</label>
	    			<input type="text" class="form-control" id="pseudo" placeholder="Pseudo..." name="pseudo">
	    		</div>

	    		<div class="form-group">
	    			<label for="mdp">Mot de passe</label>
	    			<input type="password" class="form-control" id="mdp" placeholder="mot de passe" name="mdp">
	    		</div>

		    	<hr>

		    	<input type="submit" class="form-control btn btn-info" id="connexion" name="connexion" value="Connexion">

			</form>

			<p>Vous n'avez pas de compte, <a href="inscription.php">inscrivez-vous dès maintenant ici</a>.</p>
		</div>
		
	</div>
</div>

<?php
include("inc/footer.inc.php");
?>