<?php
require_once('inc/init.inc.php');

// Service fiche produit
include("libs/serv.fiche.produit.php");

include("inc/header.inc.php");
include("inc/nav.inc.php");
?>
	<section id="section-fiche-produit">
		<div class="container">
		<?php //debug($retour); //debug($_SERVER['PHP_SELF']); ?>
			<h1><?php echo mb_ucfirst($titre_salle) ?></h1>
			<div id="note-produit">
				<p class='avis-produit'><a href='#liste-commentaires'><?php echo $note_produit." (<span class='nb-avis'>".$nb_note." avis</span>)" ?></a></p>
			</div>
			<?= $btn_reservation ?>
			<div class="clearfix"></div>
			<hr class="hr-page-produit">
			<div class="row">
				<div class="col-sm-8">
					<img src="<?= $photo_salle ?>" alt="Salle <?= $titre_salle?>">
				</div>
				<div class="col-sm-4">
					<h3>Description</h3>
					<p><?= $description_salle ?></p>
				</div>
				<div class="col-sm-12" id="infos-supplementaires">
					<h3>Informations complémentaires</h3>
					<div class="col-sm-4"><p><span class="glyphicon glyphicon-calendar"></span> Arrivée : <?= $date_arrivee ?></p></div>
					<div class="col-sm-4"><p><span class="glyphicon glyphicon-user"></span> Capacité : <?= $capacite_salle ?> places</p></div>
					<div class="col-sm-4"><p><span class="glyphicon glyphicon-map-marker"></span> Adresse : <?php echo $adresse_salle.", ".$cp_salle.", ".$ville_salle ?></p></div>
					<div class="col-sm-4"><p><span class="glyphicon glyphicon-calendar"></span> Départ : <?= $date_depart ?></p></div>
					<div class="col-sm-4"><p><span class="glyphicon glyphicon-inbox"></span> Catégorie : <?= $categorie_salle ?></p></div>
					<div class="col-sm-4"><p><span class="glyphicon glyphicon-euro"></span> Tarif : <?= $prix ?> &euro;</p></div>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-12">
					<h2>Autres produits</h2>
				</div>
			</div>
			<hr>
			<div class="row">
				<div class="col-sm-3 col-xs-6">
					<div class="produits-complementaires">
						<img class="img-produit-complentaire" src="<?= $retour[$pdt_tires[0]]["photo_salle"] ?>" alt="<?= $retour[$pdt_tires[0]]["titre_salle"] ?>">
						<a href="?action=voir&id=<?= $retour[$pdt_tires[0]]["id_produit"] ?>">
							<div class="hover-produits-complementaires hideHover">
								<h4 class="text-center"><?= $retour[$pdt_tires[0]]["titre_salle"] ?></h4>
							</div>
						</a>
					</div>
				</div>
				
				<div class="col-sm-3 col-xs-6">
					<div class="produits-complementaires">
						<img class="img-produit-complentaire" src="<?= $retour[$pdt_tires[1]]["photo_salle"] ?>" alt="<?= $retour[$pdt_tires[1]]["titre_salle"] ?>">
						<a href="?action=voir&id=<?= $retour[$pdt_tires[1]]["id_produit"] ?>">
							<div class="hover-produits-complementaires hideHover">
								<h4 class="text-center"><?= $retour[$pdt_tires[1]]["titre_salle"] ?></h4>
							</div>
						</a>
					</div>
				</div>
				
				<div class="col-sm-3 hidden-xs">
					<div class="produits-complementaires">
						<img class="img-produit-complentaire" src="<?= $retour[$pdt_tires[2]]["photo_salle"] ?>" alt="<?= $retour[$pdt_tires[2]]["titre_salle"] ?>">
						<a href="?action=voir&id=<?= $retour[$pdt_tires[2]]["id_produit"] ?>">
							<div class="hover-produits-complementaires hideHover">
								<h4 class="text-center"><?= $retour[$pdt_tires[2]]["titre_salle"] ?></h4>
							</div>
						</a>
					</div>
				</div>
				
				<div class="col-sm-3 hidden-xs">
					<div class="produits-complementaires">
						<img class="img-produit-complentaire" src="<?= $retour[$pdt_tires[3]]["photo_salle"] ?>" alt="<?= $retour[$pdt_tires[3]]["titre_salle"] ?>">
						<a href="?action=voir&id=<?= $retour[$pdt_tires[3]]["id_produit"] ?>">
							<div class="hover-produits-complementaires hideHover">
								<h4 class="text-center"><?= $retour[$pdt_tires[3]]["titre_salle"] ?></h4>
							</div>
						</a>
					</div>
				</div>
				
			</div>
			<hr>
			<div class="row">
				<div class="col-sm-4">
					<?php if(userConnected()) { 
					echo $msg_info;
					?>
					<h3>Laisser un message</h3>
					<form method="post" action="">
						<div class="form-group">
							<label for="commentaire_salle">Commentaire <span class="glyphicon glyphicon-comment"></span></label>
							<textarea name="commentaire" id="commentaire_salle" class="form-control" rows="4" placeholder="Laisser un commentaire"></textarea>
						</div>
						<div class="form-group">
							<label for="note_salle">Note</label>
							<select name="note" id="note_salle" class="form-control" required="required">
								<option>1</option>
								<option>2</option>
								<option>3</option>
								<option>4</option>
								<option>5</option>
							</select>
						</div>
						<div class="form-group">
							<input type="submit" name="<?= $name_submit ?>" value="<?= $submit_value ?>" class="btn btn-default">
						</div>
					</form>
					<?php } else { ?>
						<p><a href="<?= $url_page_encours ?>">Connectez vous</a> pour déposer un commentaire</p>
					<?php } ?>
				</div>
				<div class="col-sm-4">
					<h3 id="liste-commentaires">Commentaires</h3>
					<?= $commentaires ?>
				</div>
				<div class="col-sm-4">
					<p class="pull-right retour-catalogue" ><span class="glyphicon glyphicon-share-alt"></span><a href="index.php"> Retour vers le catalogue</a></p>
				</div>
			</div>			
		</div>
	</section>
<!-- Fin corps de page-->
<?php
include("inc/footer.inc.php");