$(document).ready(function(){
	$('body').jKit();

	$('td.instruction').hide();
	$('#showinstructions').on( 'change', function(){
		if ($(this).is(':checked')){
			$('td.instruction').show();
		} else {
			$('td.instruction').hide();
		}
	});

	$('#files').on( 'change', function(){
		if (!window.location.origin) window.location.origin = window.location.protocol+"//"+window.location.host;
		window.location = window.location.origin + window.location.pathname + '?file=' + $(this).val();
	});

	prettyPrint(); // Google Code Prettify
});