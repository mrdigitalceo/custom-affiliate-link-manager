/**
 * Admin JavaScript for Custom Affiliate Link Manager
 */

(function($) {
	'use strict';

	$(document).ready(function() {
		// Tab switching logic
		$('.calm-tabs li').on('click', function() {
			var tabId = $(this).data('tab');
			
			$('.calm-tabs li').removeClass('active');
			$(this).addClass('active');
			
			$('.calm-tab-content').hide();
			$('#' + tabId).show();
		});

		// Copy to clipboard
		$('.calm-copy-btn').on('click', function() {
			var inputId = $(this).data('copy-target');
			var input = $('#' + inputId);
			
			input.select();
			document.execCommand('copy');
			
			var originalText = $(this).text();
			$(this).text('Copied!');
			
			var btn = $(this);
			setTimeout(function() {
				btn.text(originalText);
			}, 2000);
		});

		// Add Geo-Targeting row
		$('#calm-add-geo').on('click', function() {
			var index = $('.calm-geo-target').length;
			var html = '<div class="calm-geo-target">' +
				'<select name="calm_geo_targets[' + index + '][country]">' +
				'<option value="">Select Country</option>' +
				'<option value="US">United States</option>' +
				'<option value="GB">United Kingdom</option>' +
				'<option value="CA">Canada</option>' +
				'<option value="AU">Australia</option>' +
				'<option value="IN">India</option>' +
				'</select>' +
				'<input type="url" name="calm_geo_targets[' + index + '][url]" placeholder="Target URL">' +
				'<button type="button" class="button calm-remove-row">Remove</button>' +
				'</div>';
			$('#calm-geo-targets-container').append(html);
		});

		// Add A/B Testing row
		$('#calm-add-ab').on('click', function() {
			var index = $('.calm-ab-test').length;
			var html = '<div class="calm-ab-test">' +
				'<input type="url" name="calm_ab_testing[' + index + '][url]" placeholder="Target URL">' +
				'<input type="number" name="calm_ab_testing[' + index + '][percentage]" placeholder="%" min="1" max="100">' +
				'<button type="button" class="button calm-remove-row">Remove</button>' +
				'</div>';
			$('#calm-ab-testing-container').append(html);
		});

		// Remove row
		$(document).on('click', '.calm-remove-row', function() {
			$(this).parent().remove();
		});
	});

})(jQuery);
