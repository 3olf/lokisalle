	<footer></footer>
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

			// var hoverPdtsComplement = $(".hover-produits-complementaires");
			// var imgPdtsComplement = $(".img-produit-complentaire");	
			// var pdtsComplement = $(".produits-complementaires");

			// var tempH = Math.round(imgPdtsComplement.height());
			// var tempW = Math.round(imgPdtsComplement.width());
			// hoverPdtsComplement.height(tempH);
			// hoverPdtsComplement.width(tempW);						
					
			// pdtsComplement.each(function(){
			// 	var data;
			// 	data = $(this).attr("data-value");
			// 	$(this).on("mouseenter", function(){
			// 		console.log(data+"youpi");
			// 	});
			// 	$(this).on("mouseleave", function(){
			// 		console.log(data+"youpa");
			// 	});
			// });

		});
	</script>
	</body>
</html>