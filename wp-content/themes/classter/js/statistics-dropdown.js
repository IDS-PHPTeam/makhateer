jQuery(function ($) {
	var $dropdown = $('#titles_dropdown');
	if (!$dropdown.length) {
		return;
	}

	function getStatisticArea() {
		var $area = $('#ids_statistic_body .post_text_area').first();
		if ($area.length) {
			return $area;
		}
		return $('#ids_statistic_body').first();
	}

	function reinitCharts($scope) {
		if ($.fn.vcRoundChart) {
			$scope.find('.vc_round-chart').each(function () {
				$(this).vcRoundChart();
			});
		}
		if ($.fn.vcLineChart) {
			$scope.find('.vc_line-chart').each(function () {
				$(this).vcLineChart();
			});
		}
	}

	$dropdown.on('change', function () {
		var id = $(this).val();
		var $area = getStatisticArea();

		if (!id || !$area.length || typeof ids_ajaxurl === 'undefined') {
			return;
		}

		$area.stop(true, true).animate({ opacity: 0.3 }, 300);

		$.post(ids_ajaxurl, {
			action: 'ids_get_statistic_by_id',
			id: id
		})
			.done(function (response) {
				if (!response || response.success !== true || typeof response.data === 'undefined') {
					$area.animate({ opacity: 1 }, 300);
					return;
				}

				// Replace the whole statistic content (not only the first .vc_row),
				// otherwise previous sibling rows remain visible after switching.
				$area.html(response.data);
				reinitCharts($area);
				$area.animate({ opacity: 1 }, 300);
			})
			.fail(function () {
				$area.animate({ opacity: 1 }, 300);
			});
	});
});
