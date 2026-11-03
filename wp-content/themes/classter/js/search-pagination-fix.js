/**
 * Search results: use CSS grid card layout (not isotope absolute packing)
 * and keep #pagination below the grid. Equal-height cards per row.
 */
(function ($) {
	function fixSearchCards() {
		if (!$('body.search').length) {
			return;
		}

		var $masonry = $('body.search section.masonry');
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
			.addClass('searchCards')
			.css({
				height: 'auto',
				position: 'relative',
				overflow: 'visible',
				display: ''
			});

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
			this.style.setProperty('align-self', 'stretch', 'important');
			this.style.setProperty('height', '100%', 'important');
		});

		$masonry.find('.isotopePadding').each(function () {
			this.style.removeProperty('height');
			this.style.setProperty('display', 'flex', 'important');
			this.style.setProperty('flex-direction', 'column', 'important');
			this.style.setProperty('flex', '1 1 auto', 'important');
			this.style.setProperty('height', '100%', 'important');
			this.style.setProperty('max-height', 'none', 'important');
		});

		$masonry.find('.post_wrap').each(function () {
			this.style.removeProperty('height');
			this.style.setProperty('display', 'flex', 'important');
			this.style.setProperty('flex-direction', 'column', 'important');
			this.style.setProperty('flex', '1 1 auto', 'important');
			this.style.setProperty('max-height', 'none', 'important');
		});

		$masonry.find('.post_wrap .post_file, .post_wrap .squareButton, .post_wrap .moreWrapMasonry').each(function () {
			this.style.setProperty('margin-top', 'auto', 'important');
		});

		var pag = document.getElementById('pagination');
		var wrap = document.querySelector('body.search .masonryWrap');
		if (pag && wrap && wrap.parentElement) {
			if (pag.parentElement !== wrap.parentElement || pag.previousElementSibling !== wrap) {
				wrap.parentElement.insertBefore(pag, wrap.nextSibling);
			}
		}
	}

	$(fixSearchCards);
	$(window).on('load', fixSearchCards);
	setTimeout(fixSearchCards, 500);
	setTimeout(fixSearchCards, 1500);
	setTimeout(fixSearchCards, 3000);
	$(window).on('resize', function () {
		setTimeout(fixSearchCards, 200);
	});
})(jQuery);
