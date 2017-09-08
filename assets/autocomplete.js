(function($) {
    $.fn.wameAutocomplete = function(options) {
        var defaultOptions = {
            source: null,
            columns: null,
            select: null,
            minLength: 3,
            fieldValue: 'id',
            fieldLabel: 'title',
        };
      
        var options = $.extend(defaultOptions, options);
        
        return this.each(function() {
            var $obj = $(this);
            
            $.widget.bridge('uiautocomplete', $.ui.autocomplete);

            $obj.uiautocomplete({
                appendTo: $obj.closest('div'),
                source: function( request, response ) {
                    var phrase = request.term;
                    var source = options.source || $obj.data('source');
                    var dataOptions = $obj.data('options');
                    var columns = options.columns || dataOptions.columns;
                    var select = options.select || dataOptions.select;
                    var fieldValue = dataOptions.fieldValue ? dataOptions.fieldValue : options.fieldValue;
                    var fieldLabel = dataOptions.fieldLabel ? dataOptions.fieldLabel : options.fieldLabel;

                    if ($obj.closest('.with-loader').length) {
                        $obj.closest('.with-loader').addClass('active');
                    }

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
                                        label: item[fieldLabel],
                                        value: item[fieldValue]
                                    };
                                }));
                            }
                        },
                        complete: function() {
                            if ($obj.closest('.with-loader.active').length) {
                                $obj.closest('.with-loader').removeClass('active');
                            }
                        }
                    });
                },
                minLength: options.minLength,
                select: function( event, ui ) {
                    var value = ui.item.value;
                    var label = ui.item.label;

                    $obj.attr('value', value).hide();
                    $obj.before('<div class="'+$obj.attr('class')+' autocomplete-value">' + label + '<a class="autocomplete-remove btn btn-xs btn-link pull-right" href="#" data-control="' + $obj.attr('id') + '"><span class="fa fa-times-circle"></span></a></div>');
                },
                open: function() {
                    $( this ).removeClass( "ui-corner-all" ).addClass( "ui-corner-top" );
                },
                close: function() {
                    $( this ).removeClass( "ui-corner-top" ).addClass( "ui-corner-all" );
                }
            });
            
            $(document).on('click', '.autocomplete-remove', function(e) {
                e.preventDefault();

                if ($obj.data('source')) {
                    $obj.val('').show().focus();
                    $(this).parents('.autocomplete-value').remove();
                }
            });
        });
    };
})(jQuery);