<?php
if (userConnectedAdmin()) {
  $menu_swap = "<li ".active(URL.'boutique.php')."><a href='".URL."boutique.php'>Boutique</a></li>
                <li ".active(URL.'panier.php')."><a href='".URL."panier.php'>Panier</a></li>
                <li ".active(URL.'membre.php')." ><a href='".URL."'>Gestion membre</a></li>
                <li ".active(URL.'admin/gestion_boutique.php')." ><a href='".URL."admin/gestion_boutique.php'>Gestion boutique</a></li>
                <li ".active(URL.'admin/gestion_commandes.php')." ><a href='".URL."admin/gestion_commandes.php'>Gestion commande</a></li>
                <li ".active(URL.'profil.php')." ><a href='".URL."profil.php'>Mon compte</a></li>
                <li><a href='".URL."connexion.php?action=deconnexion'>Deconnexion</a></li>";                
}
elseif(userConnected()) 
{
  $menu_swap = "<li ".active(URL.'fiche_produit.php')."><a href='".URL."fiche_produit.php'>Nos produits</a></li>
                <li ".active(URL.'panier.php')."><a href='".URL."panier.php'>Panier</a></li>
                <li ".active(URL.'profil.php')." ><a href='".URL."profil.php'>Mon compte</a></li>
                <li><a href='".URL."connexion.php?action=deconnexion'>Deconnexion</a></li>";

}
else 
{
  $menu_swap = "<li ".active(URL.'fiche_produit.php')."><a href='".URL."fiche_produit.php'>Nos produits</a></li>
                <li ".active(URL.'connexion.php')." ><a href='".URL."connexion.php'>Connexion</a></li>
                <li ".active(URL.'inscription.php')." ><a href='".URL."inscription.php'>Inscription</a></li>";
}
?>
    <nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#" title="Accueil"><span class="glyphicon glyphicon-home"></span></a>
        </div>
        <div id="navbar" class="collapse navbar-collapse">
          <ul class="nav navbar-nav">
            <?= $menu_swap ?>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </nav>