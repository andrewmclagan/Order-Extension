
var Extension;

;(function(window, document, $, undefined)
{

	'use strict';

	Extension = Extension || {
		Index: {},
	};

	// Initialize functions
	Extension.Index.init = function()
	{
		Extension.Index
			.datePicker()
		;
	};		

	// Sale price date range picker initialization
	Extension.Index.datePicker = function()
	{
		var config = {
			format: 'DD-MM-YYYY'
		};

		Extension.Index.datePicker = $('[sale-price-calendar]').daterangepicker(config);

		return this;
	};

	// Job done, lets run.
	Extension.Index.init();		

})(window, document, jQuery);
