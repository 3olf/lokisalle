	<footer></footer>
	<script src="<?=URL?>js/jquery-3.1.1.js"></script>
	<script src="<?=URL?>js/bootstrap.js"></script>
	<script
			  src="http://code.jquery.com/ui/1.12.0/jquery-ui.min.js"
			  integrity="sha256-eGE6blurk5sHj+rmkfsGYeKyZx3M4bG+ZlFyA7Kns7E="
			  crossorigin="anonymous"></script>	
	<script type="text/javascript">
		$(document).ready(function(){
			$( ".datepicker" ).datepicker();

			$spanCapacite=$('#capaciteFiltre');
			$spanPrix=$('#prixFiltre');

			$('#prix').on('input change', function(){
				$thisValue=$(this).val();
				$spanPrix.html($thisValue);
			});

			$('#capacite').on('input change', function(){
				$thisValue=$(this).val();
				$spanCapacite.html($thisValue);
			});


		});
	</script>
	</body>
</html>