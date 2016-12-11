	<footer>
		<p class="small pull-left hidden-xs">Réalisé dans le cadre de la formation <a href="http://www.wf3.fr/" rel="no-follow" target="_blank">Webforce3</a> avec <a href="http://eprojet.fr/" rel="no-follow" target="_blank">eprojet.fr</a></p>
		<p class="pull-right small"><a href="https://www.linkedin.com/in/gachetthomas" rel="no-follow" target="_blank">Thomas Gachet</a>&nbsp;&nbsp;-&nbsp;&nbsp;<a href="https://www.linkedin.com/in/florent-mallet-03804910b/en" rel="no-follow" target="_blank">Florent Mallet</a></p>
	</footer>
	<script src="<?=URL?>js/jquery-3.1.1.js"></script>
	<script src="<?=URL?>js/bootstrap.js"></script>
	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
	<script src="<?=URL?>js/jquery-ui-timepicker-addon.js"></script>		  	
	<script type="text/javascript">
		$(document).ready(function(){
			$( ".datepicker" ).datetimepicker({ dateFormat: 'dd-mm-yy' });

			$spanCapacite=$('#capaciteFiltre');
			$spanPrix=$('#prixFiltre');

			$('#prix').on('input change', function(){
				$thisValue=$(this).val();
				$spanPrix.html($thisValue);
			});

			$('#capacite').on('input change', function(){
				$thisValue=$(this).val();
				if($thisValue > 0)
				{
					$spanCapacite.html($thisValue);
				}
				else
				{
					$spanCapacite.html("toutes");
				}
			});

			// hover des vignettes autres produits sur page produit
			var pdtsComplement = $(".produits-complementaires");

			pdtsComplement.hover(function(){
				var $this = $(this);
				var tempH = $this.height();
				var tempW = $this.width();
				var $findH = $this.find(".hover-produits-complementaires");				
				var $findh4 = $this.find("h4");				
				$findH.height(tempH);
				$findH.width(tempW);				
				$findH.toggleClass("hideHover");
				$findh4.css("line-height", tempH+"px");
			});

		});
	</script>
	</body>
</html>