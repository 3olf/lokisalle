$(document).ready(function(){
	/********** ALL PAGES - DATEPICKER **********/
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

	/********** PAGE PRODUIT - VIGNETTES AUTRES PRODUITS **********/
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

	/********** ALL PAGES - POSITION FOOTER **********/
	var windowH = $(window).height();
	var bodyH = $("body").height();
	var footer = $("footer");
	var section = $("section");
	if(bodyH < windowH)
	{
		var sectionH = bodyH;
		section.css("height", sectionH+2+"px");
	}

});