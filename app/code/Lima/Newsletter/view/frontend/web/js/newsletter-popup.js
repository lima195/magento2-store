define(
	[
		'underscore',
		'jquery',
		'mage/cookies'
	],
	function (_, $) {
		'use strict';
		return function (options) {

			var cookie = $.cookie(options.cookieName);
			var $popup = $(options.elementId);
			var $doNotShowAgain = $(options.elementDoNotShowAgain);

			// Show Popup
			if(!cookie && (options.enabled == 1)) {
				var timeToShow = !isNaN(options.timeToShow) ? (parseInt(options.timeToShow)*1000) : 3000;
				_.delay(function(){ showPopup() }, timeToShow);
			}

			// Close Popup Button
			$(options.elementClose).on('click', function(){ closePopup(); });

			// Do not show again Button
			function doNotShowAgain() {
				// If 'do not show again' button is checked we set cookie val
				if($doNotShowAgain.is(":checked")) {
					$.mage.cookies.set(options.cookieName, true);
				}
			}
			$($doNotShowAgain).change(function() {
				$(options.elementDoNotShowAgainHidden).val(0);
				if($(this).is(':checked')) {
					$(options.elementDoNotShowAgainHidden).val(1);
				}
			});

			// Popup Functions
			function showPopup() {
				$popup.css('visibility', 'visible');
				$popup.css('opacity', '1');
			}
			function closePopup() {
				$popup.css('visibility', 'hidden');
				$popup.css('opacity', '0');

				doNotShowAgain();
			}
		}
	}
);