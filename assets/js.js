$('input.autocomplete').autocomplete({
	source: function( request, response ) {
		var phrase = request.term;
		var source = this.element.data('source');
		var options = this.element.data('options');
		var columns = options.columns;
		var select = options.select;

		$.ajax({
			url: source,
			type: 'get',
			dataType: 'json',
			data: { 
				columns : columns,
				phrase : phrase,
				select : select
			},
			success: function( res ) {
				if (res.length === 0) {
					return {
						label: 'No results found',
						value: ''
					};
				} else {
					response($.map(res, function(item) {
						return {
							label: item.title,
							value: item.id
						};
					}));
				}
			}
		});
	},
	minLength: 3,
	select: function( event, ui ) {
		var label = ui.item.label;
		var el = $(this);

		el.hide();
		el.before('<div class="well well-sm autocomplete-value">' + label + '<a class="autocomplete-remove btn btn-xs btn-link pull-right" href="#" data-control="' + el.attr('id') + '"><span class="fa fa-times-circle"></span></a></div>');
	},
	open: function() {
		$( this ).removeClass( "ui-corner-all" ).addClass( "ui-corner-top" );
	},
	close: function() {
		$( this ).removeClass( "ui-corner-top" ).addClass( "ui-corner-all" );
	}
});

$('form').delegate('.autocomplete-remove', 'click', function() {
	var control = $(this).data('control');

	$(this).closest('.autocomplete-value').remove();
	$('#' + control).val('').show();
	
	return false;
});