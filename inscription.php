<?php
require_once("inc/init.inc.php");



//test sur données inscription

$nom="";
$prenom="";
$pseudo="";
$email="";
$civilite="f";
$statut="0";


if (isset($_POST['nom']) && isset($_POST['prenom'])  && isset($_POST['mdp'])  && isset($_POST['mdp2'])  && isset($_POST['pseudo'])  && isset($_POST['civilite']) && isset($_POST['email'])&& isset($_GET['action'])&& $_GET['action']=="inscription"){

	foreach ($_POST as $value) {
		$value = htmlentities($value, ENT_QUOTES);
	}

	extract($_POST);

	//controle de la validité des champs :

	if (strlen($nom)<2 || strlen($nom)>20){
		$msg_info.="<p class='error'>Votre nom doit comporter entre 2 et 20 caractères</p> ";
	}

	if (!preg_match('#^[a-zéèàïîëêôö]{1,}[-]?[a-zôöéèàïîëê]{1,}$#i', $nom)){
		$msg_info.="<p class='error'>Votre nom doit comporter des lettres et au maximum un tiret</p> ";
	}

	if (strlen($prenom)<2 || strlen($prenom)>20){
		$msg_info.="<p class='error'>Votre prénom doit comporter entre 2 et 20 caractères</p> ";
	}

	if (!preg_match('#^[a-zéèàïîëêôö]{1,}[-]?[a-zôöéèàïîëê]{1,}$#i', $prenom)){
		$msg_info.="<p class='error'>Votre prénom doit comporter des lettres et au maximum un tiret</p> ";
	}

	if (strlen($pseudo)<2 || strlen($pseudo)>20){
		$msg_info.="<p class='error'>Votre pseudo doit comporter entre 2 et 20 caractères</p> ";
	}

	if (!preg_match('#^[a-z]{1,}#i', $pseudo)){
		$msg_info.="<p class='error'>Votre pseudo doit commencer par une lettre</p> ";
	}

	$test_unicite_pseudo = $pdo->query("SELECT * FROM membre WHERE pseudo='$pseudo'");
	if ($test_unicite_pseudo->rowCount()){
		$msg_info.="<p class='error'>Le pseudo ". $pseudo ." existe deja, veuillez entrer un autre pseudo</p> ";
	}

	if (!(preg_match("#[a-z]#i", $mdp) && preg_match("#[0-9]#", $mdp) && preg_match("#[^a-zA-Z0-9]#", $mdp))){
		$msg_info.="<p class='error'>Votre mot de passe doit comporter au moins une lettre, un chiffre et un caractère spécial</p>";
	}
	if (mb_strlen($mdp)<8){
		$msg_info.="<p class='error'>Votre mot de passe doit comporter au moins 8 caractères</p>";
	}

	if ($mdp!==$mdp2){
		$msg_info.="<p class='error'>Les deux champs password doivent être identiques</p>";
	}

	if (!filter_var($email, FILTER_VALIDATE_EMAIL)){
		$msg_info.="<p class='error'>Vous devez entrer un email valide</p>";
	}

	if($civilite != "m" && $civilite != "f"){
		$msg_info.="<p class='error'>Erreur sur les boutons radios 'civilités'</p>";
	}



	//si tous les champs sont valides, on procède à l'inscription
	if (!$msg_info){

		//cryptage du mot de passe :
		$mdp=SHA1($mdp);

		$resultat_insertion=$pdo->exec("INSERT INTO membre (pseudo, mdp, nom, prenom, email, civilite, statut, date_enregistrement) VALUES ('$pseudo', '$mdp', '$nom', '$prenom', '$email', '$civilite', '$statut', NOW())");

		if ($resultat_insertion!==false){
			$_SESSION['utilisateur']['nom']=$nom;
			$_SESSION['utilisateur']['prenom']=$prenom;
			$_SESSION['utilisateur']['pseudo']=$pseudo;
			$_SESSION['utilisateur']['email']=$email;
			$_SESSION['utilisateur']['civilite']=$civilite;
			$_SESSION['utilisateur']['statut']=$statut;
			$_SESSION['utilisateur']['id_membre']=$pdo->lastInsertId();

		}
		else{
			$msg_info.="<p class='error'>Un problème est survenu lors de l'enregistrement de vote inscription, merci de contacter</p>";
		}
	}
}

//Si l'utilisateur est connecte, on le redirige
if (userConnected()){
	header('location:index.php');
	exit();
}


include("inc/header.inc.php");
include("inc/nav.inc.php");

?>
<section id="section-inscription">
	<div class="container">
		<h1>Inscription</h1>
		<hr>
		<?php echo $msg_info; ?>
		<div class="row">
			<div class="col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3">
				<form class="form" method="POST" action="?action=inscription">
					<div class="form-group">
						<label for="email">Email address</label>
						<input type="email" class="form-control" id="email" placeholder="Email" name="email" value="<?php echo $email; ?>">
					</div>
					<div class="form-group">
						<label for="mdp">Password</label>
						<input type="password" class="form-control" id="mdp" placeholder="Password" name="mdp">
					</div>
					<div class="form-group">
						<label for="mdp2">Confirmation Password</label>
						<input type="password" class="form-control" id="mdp2" placeholder="Password" name="mdp2">
					</div>
					<div class="form-group">
						<label for="pseudo">Pseudo</label>
						<input type="text" class="form-control" id="pseudo" placeholder="pseudo" name="pseudo" value="<?php echo $nom; ?>" >
					</div>
					<div class="form-group">
						<label for="nom">Nom</label>
						<input type="text" class="form-control" id="nom" placeholder="nom" name="nom" value="<?php echo $nom; ?>" >
					</div>
					<div class="form-group">
						<label for="prenom">Prénom</label>
						<input type="text" class="form-control" id="prenom" placeholder="prenom" name="prenom" value="<?php echo $nom; ?>" >
					</div>
					<div class="radio">
						<label class="radio-inline">
							<input type="radio" name="civilite" id="m" value="m" <?php if ($civilite=="m"){echo "checked";} ?>> Monsieur
						</label>
						<label class="radio-inline">
							<input type="radio" name="civilite" id="f" value="f" <?php if ($civilite!="m"){echo "checked";} ?>> Madame
						</label>
					</div>
					<hr>
					<input type="submit" class="btn btn-default btn-ok form-control" value="S'inscrire" name="inscription">
				</form>
			</div>
		</div>		
	</div>
</section>
<?php 
include("inc/footer.inc.php"); 