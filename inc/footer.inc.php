<footer>
	<p class="small pull-left hidden-xs">Réalisé dans le cadre de la formation <a href="http://www.wf3.fr/" rel="no-follow" target="_blank">Webforce3</a> avec <a href="http://eprojet.fr/" rel="no-follow" target="_blank">eprojet.fr</a></p>
	<p class="pull-right small"><a href="https://www.linkedin.com/in/gachetthomas" rel="no-follow" target="_blank">Thomas Gachet</a>&nbsp;&nbsp;-&nbsp;&nbsp;<a href="https://www.linkedin.com/in/florent-mallet-03804910b/en" rel="no-follow" target="_blank">Florent Mallet</a></p>
</footer>
<script src="<?= URL ?>js/jquery-3.1.1.js"></script>
<script src="<?= URL ?>js/bootstrap.js"></script>
<?php if( scriptActif(URL.'index.php') | scriptActif(URL.'admin/gestion_produits.php') | scriptActif(URL.'fiche_produit.php') ) { ?>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="<?= URL ?>js/jquery-ui-timepicker-addon.js"></script>
<script type="text/javascript" src="<?= URL ?>js/script.js"></script>
<?php } 
if ( scriptActif(URL.'admin/statistique.php')){ ?>
<script type="text/javascript" src="<?= URL ?>js/chart.min.js"></script>		  	
<script type="text/javascript" src="<?= URL ?>js/chart.bundle.min.js"></script>
<script type="text/javascript" src="<?= URL ?>js/graph.js"></script>	
<?php }
?>

</body>
</html>