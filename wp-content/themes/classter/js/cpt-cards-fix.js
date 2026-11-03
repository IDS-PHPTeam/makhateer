/**
 * CPT archives: 3-column CSS grid (not isotope), taller cards for Read More.
 */
(function ($) {
	function fixCptCards() {
		if (!$('body.ids-cpt-cards').length) {
			return;
		}

		var $masonry = $('body.ids-cpt-cards section.masonry');
		if (!$masonry.length) {
			return;
		}

		if ($masonry.data('isotope')) {
			try {
				$masonry.isotope('destroy');
			} catch (e) {
				/* ignore */
			}
		}

		$masonry
			.removeClass('isotope isotopeNOanim inited')
			.addClass('cptCards')
			.css({
				height: 'auto',
				minHeight: 0,
				position: 'relative',
				overflow: 'visible',
				display: ''
			});

		var $wrap = $masonry.closest('.masonryWrap');
		if ($wrap.length) {
			$wrap.css({
				height: 'auto',
				minHeight: 0,
				overflow: 'visible'
			});
		}

		$masonry.find('.isotopeElement').each(function () {
			this.style.removeProperty('position');
			this.style.removeProperty('left');
			this.style.removeProperty('top');
			this.style.removeProperty('right');
			this.style.removeProperty('transform');
			this.style.removeProperty('width');
			this.style.removeProperty('height');
			this.style.opacity = '1';
			this.style.setProperty('float', 'none', 'important');
			this.style.setProperty('display', 'flex', 'important');
			this.style.setProperty('flex-direction', 'column', 'important');
			this.style.setProperty('height', '100%', 'important');
		});

		$masonry.find('.isotopePadding').each(function () {
			this.style.removeProperty('height');
			this.style.setProperty('min-height', '260px', 'important');
			this.style.setProperty('height', '100%', 'important');
			this.style.setProperty('position', 'relative', 'important');
		});

		$masonry.find('.post_wrap').each(function () {
			this.style.setProperty('height', '230px', 'important');
			this.style.setProperty('min-height', '230px', 'important');
			this.style.setProperty('padding-bottom', '48px', 'important');
			this.style.setProperty('position', 'relative', 'important');
			this.style.setProperty('overflow', 'hidden', 'important');
			this.style.setProperty('box-sizing', 'border-box', 'important');
		});

		$masonry.find('.post_wrap .post_file').each(function () {
			this.style.setProperty('position', 'absolute', 'important');
			this.style.setProperty('bottom', '12px', 'important');
			this.style.setProperty('top', 'auto', 'important');
			this.style.setProperty('float', 'none', 'important');
			this.style.setProperty('z-index', '2', 'important');
		});
	}

	$(fixCptCards);
	$(window).on('load', fixCptCards);
	setTimeout(fixCptCards, 500);
	setTimeout(fixCptCards, 1500);
	setTimeout(fixCptCards, 3000);
	$(window).on('resize', function () {
		setTimeout(fixCptCards, 200);
	});
})(jQuery);
