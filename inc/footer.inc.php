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
				$spanCapacite.html($thisValue);
			});


		});
	</script>
	</body>
</html>