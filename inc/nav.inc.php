<?php

$menu_swap="";
$menu_swap_right="";

//debug($_SESSION['utilisateur']); 
if (userConnectedAdmin()) {
  $menu_swap_drop = "<li ".active(URL.'admin/gestion_membres.php')."><a href='".URL."admin/gestion_membres.php'>Gestion membres</a></li>
                <li ".active(URL.'admin/gestion_avis.php')." ><a href='".URL."admin/gestion_avis.php'>Gestion avis</a></li>
                <li ".active(URL.'admin/gestion_commandes.php')." ><a href='".URL."admin/gestion_commandes.php'>Gestion commandes</a></li>
                <li ".active(URL.'admin/gestion_salles.php')." ><a href='".URL."admin/gestion_salles.php'>Gestion salles</a></li>
                <li ".active(URL.'admin/gestion_produits.php')." ><a href='".URL."admin/gestion_produits.php'>Gestion produit</a></li>
                <li ".active(URL.'admin/statistique.php')." ><a href='".URL."admin/statistique.php'>Statistiques</a></li>";                 
}
if(userConnected()) 
{
  $menu_swap .= "<li ".active(URL.'index.php')."><a href='".URL."fiche_produit.php'>Nos produits</a></li>
                <li ".active(URL.'reservation.php')."><a href='".URL."reservation.php'>Réservation</a></li>";

  $menu_swap_right.="<li><a href='".URL."connexion.php?action=deconnexion'>Deconnexion</a></li>
                    <li ".active(URL.'profil.php')."><a href='".URL."profil.php'><span class='glyphicon glyphicon-user'></span> ".$_SESSION['utilisateur']['pseudo']."</a></li>";

}
else 
{
  $menu_swap = "<li ".active(URL.'fiche_produit.php')."><a href='".URL."fiche_produit.php'>Nos produits</a></li>
                <li ".active(URL.'connexion.php')." ><a href='".URL."connexion.php'>Connexion</a></li>
                <li ".active(URL.'inscription.php')." ><a href='".URL."inscription.php'>Inscription</a></li>";
}
//debug($menu_swap_right); 
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
          <a class="navbar-brand" href="<?php echo URL; ?>index.php" title="Accueil"><span class="glyphicon glyphicon-home"></span></a>
        </div>
        <div id="navbar" class="collapse navbar-collapse">
          <ul class="nav navbar-nav">
            <?= $menu_swap ?>
          </ul>

<?php if (userConnectedAdmin()){ ?>
          <ul class="nav navbar-nav">
            <li class="dropdown <?php if(substr($_SERVER['PHP_SELF'], 0, 17) == URL.'admin/') { echo 'active'; } ?> ">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Admin <span class="caret"></span></a>
              <ul class="dropdown-menu">
                <?= $menu_swap_drop ?>
              </ul>
            </li>
          </ul>
<?php }
if (userConnected()){ ?>
          <ul class="nav navbar-nav navbar-right">
            <?= $menu_swap_right ?>
          </ul>
<?php  
} 
?>
        </div><!--/.nav-collapse -->
      </div>
    </nav>