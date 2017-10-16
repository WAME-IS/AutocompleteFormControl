/*!
 * jQuery Autocomplete Select2
 * Original author: @kaliver
 * Licensed under the MIT license
 */

;(function ( $, window, document, undefined ) {

    var autocompleteSelect2 = "autocompleteSelect2",
        defaults = {
            minimumInputLength: 1,
            limit: 10
        };

    // The actual plugin constructor
    var AutocompleteSelect2 = function ( element, options ) {
        this.element = element;

        this.options = $.extend( {}, defaults, options) ;

        this._defaults = defaults;
        this._name = autocompleteSelect2;

        this.init();
    };

    AutocompleteSelect2.prototype = {

        init: function() {
            var elm = $(this.element);
            var pluginOptions = this.options;
            var dataOptions = elm.data( "options" );
            
            elm.select2({
                ajax: {
                   url: function (params) {
                       var url = $(this).data( "source" );
                       
                       return url;
                   },
                   dataType: "json",
                   delay: 250,
                   clear: true,
                   data: function (params) {
                       console.log(params);

                       var query = {
                           columns: dataOptions.columns,
                           phrase: params.term,
                           select: dataOptions.select,
                           limit: pluginOptions.limit,
                           offset: params.page * pluginOptions.limit
                       };
                       
                       return query;
                   },
                   processResults: function (data, params) {
                       var results = [];
                       
                       $.each(data.items, function (index, value) {
                           value.text = value.value;
                           delete value.value;
                           
                           results[index] = value;
                       });
                       
                       params.page = params.page || 1;
                       
                       var pagination = {
                           "more": (params.page * 2) < data.results
                       };
                       
                       return {
                           results,
                           pagination
                       };
                   },
                   cache: true
                },
                minimumInputLength: this.options.minimumInputLength
            }); 
        }
    };

    $.fn[autocompleteSelect2] = function ( options ) {
        return this.each(function () {
            if (!$.data(this, "plugin_" + autocompleteSelect2)) {
                $.data(this, "plugin_" + autocompleteSelect2,
                new AutocompleteSelect2( this, options ));
            }
        });
    };

})( jQuery, window, document );