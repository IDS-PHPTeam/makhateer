<?php
$show_title = !in_array($post_data['post_format'], array('aside', 'chat', 'status', 'link', 'quote'));
$columns = max(1, min(4, (int) themerex_substr($opt['style'], -1)));
if (isset($_GET['post_type']) && $_GET['post_type'] == 'mokhtar') {
?>

	<style>
		.post_wrap {
			display: grid;
			grid-gap: 0px;
			grid-template:
				'image title title title'
				'image phone phone phone';
			grid-template-columns: max-content;
		}

		.ids-phone-img-div {
			grid-area: image;
		}

		.ids-phone-title {
			grid-area: title;
		}

		.post_phone {
			grid-area: phone;
		}

		html:lang(ar) .masonry article .ids-phone-img-div img {
			margin-top: 7%;
			width: 60px;
			margin-left: 25px;
			width: 60px;
			height: 60px;
		}

		html:lang(en) .masonry article .ids-phone-img-div img {
			margin-top: 7%;
			width: 60px;
			margin-right: 25px;
			width: 60px;
			height: 60px;
		}

		html:lang(ar) .masonry {
			margin-right: 30px !important;
			margin-left: 30px !important;
			width: auto !important;
		}

		html:lang(en) .masonry {
			margin-right: 30px !important;
			margin-left: 30px !important;
			width: auto !important;
		}

		.masonry article {
			margin: 1px 0 0 0 !important;
		}

		.ids-phone-title {
			font-size: 15px;
			margin-bottom: 10px;
			font-weight: normal !important;
			color: #808080 !important;
		}

		section {
			height: auto !important;
		}

		.ids-phone a {
			text-decoration: none;
		}

		article.isotopeElement {
			width: 20% !important;
		}

		.masonry.masonry-colums-4 .isotopePadding.bg_post.bg_post .post_wrap {
			padding: 20px 5px 20px !important;
		}

		.masonry .isotopePadding {
			margin: 0px 1px !important;
		}

		.ids-phone-img-title {
			font-size: 19px;
			font-weight: bold;
			color: #808080 !important;
		}

		.masonry.masonry-colums-4 .isotopePadding.bg_post.bg_post .post_wrap {
			padding: 20px 25px 0px;
		}

		/*.masonry.masonry-colums-4 .isotopePadding.bg_post.bg_post .post_wrap {
    height: 180px !important;
}*/
	</style>
<?php
}
if (isset($_GET['post_type']) && $_GET['post_type'] == 'bids') {
?>

	<style>
		/*@media (min-width: 600px){
.masonry.masonry-colums-4 .isotopePadding.bg_post.bg_post .post_wrap {
    height: 260px !important;
}
}*/
		.post_status {
			bottom: 40px !important;
			position: absolute !important;
		}

		article.isotopeElement {
			width: 33% !important;
		}

		h4,
		.h4 {
			font-size: 20px !important;

line-height:25px;
		}
	</style>
<?php
}
?>
<article class="isotopeElement <?php echo $_GET["post_type"] == "mokhtar" ? "ids-phone" : "ids-custom-post-type"; ?> <?php
																														echo 'post_format_' . esc_attr($post_data['post_format'])
																															. ($opt['number'] % 2 == 0 ? ' even' : ' odd')
																															. ($opt['number'] == 0 ? ' first' : '')
																															. ($opt['number'] == $opt['posts_on_page'] ? ' last' : '')
																															. ($opt['add_view_more'] ? ' viewmore' : '')
																															. ($opt['filters'] != ''
																																? ' flt_' . join(' flt_', $opt['filters'] == 'categories' ? $post_data['post_categories_ids'] : $post_data['post_tags_ids'])
																																: '');
																														?>">
	<div class="isotopePadding bg_post">
		<?php

		if (isset($_GET["post_type"]) && $_GET["post_type"] == "mokhtar") {
		?>
			<div class="ids-phone-wrap-post_wrap">
			<?php
		}
		if ($post_data['post_audio']) {
			echo balanceTags($post_data['post_audio']);
		} else if ($post_data['post_thumb'] || $post_data['post_gallery'] || $post_data['post_video']) { ?>

				<?php if ($post_data['post_video'] || $post_data['post_gallery']) { ?>
					<div class="sc_section post_thumb thumb">
					<?php } else { ?>
						<div class="thumb hoverIncrease hoverTwo" <?php echo ($post_data['post_url_target'] ? 'data-target="' . $post_data['post_url_target'] . '" ' : ''); ?>data-link="<?php echo esc_url($post_data['post_link']); ?>" data-image="<?php echo esc_url($post_data['post_attachment']); ?>" data-title="<?php echo esc_attr($post_data['post_title']); ?>">
						<?php }
					if ($post_data['post_video']) {
						echo getVideoFrame($post_data['post_video'], $post_data['post_thumb'], false);
					} else if ($post_data['post_thumb'] && ($post_data['post_format'] != 'gallery' || !$post_data['post_gallery'] || get_custom_option('gallery_instead_image') == 'no')) {
						if ($post_data['post_format'] == 'link' && $post_data['post_url'] != '')
							echo '<a href="' . esc_url($post_data['post_url']) . '"' . ($post_data['post_url_target'] ? ' target="' . esc_attr($post_data['post_url_target']) . '"' : '') . '>' . balanceTags($post_data['post_thumb']) . '</a>';
						else if ($post_data['post_link'] != '')
							echo balanceTags($post_data['post_thumb']);
						else
							echo balanceTags($post_data['post_thumb']);
					} else if ($post_data['post_gallery']) {
						echo balanceTags($post_data['post_gallery']);
					}
						?>
						</div>
					<?php
				}

				if (in_array($post_data['post_format'], array('quote', 'link', 'chat', 'status', 'aside'))) { ?>
						<div class="post_wrap_part">
						<?php
					} else { ?>
							<div class="post_wrap">
							<?php }
						if (isset($_GET["post_type"]) && $_GET["post_type"] == "mokhtar") {
							?>
								<?php
							}
							if ($show_title) {
								if (!isset($opt['links']) || $opt['links']) {
									if (isset($_GET["post_type"]) && ( $_GET["post_type"] == "laws" ||  $_GET["post_type"] == "decrees" ||  $_GET["post_type"] == "diary" ||  $_GET["post_type"] == "decisions")) {
							$lang = get_locale();
						  if ($lang != 'en_US') {
								?>
								<img src="https://mokhtar-fund.gov.lb/wp-content/uploads/2021/03/web-.png" width="25px" height="25px" style="float: right; padding-left:10px;" />
								<?php
						  }else{
						      ?>
						      <img src="https://mokhtar-fund.gov.lb/wp-content/uploads/2021/03/web-.png" width="25px" height="25px" style="float: left; padding-right:10px;" />

						      <?php
						  }?>
								
										<h4><a href="<?php echo esc_url($post_data['post_link']); ?>"><?php echo esc_html($post_data['post_title']); ?></a></h4><?php
																																							} else if (isset($_GET["post_type"]) && $_GET["post_type"] == "mokhtar" && get_field("photo")) {

																																								?>

										<div class="ids-phone-img-div">
											<img src=" <?php echo get_field("photo"); ?>" />
										</div>

										<div class="ids-phone-title">
											<?php echo esc_html($post_data['post_title']); ?>

										</div>

									<?php



																																							} else {
									?><h4><a href="<?php echo esc_url($post_data['post_link']); ?>"><?php echo esc_html($post_data['post_title']); ?></a></h4><?php
																																							}
																																						} else {
																																								?><h4><?php echo esc_html($post_data['post_title']); ?></h4><?php
																																																						}
																																																					}
																																																					if ($_GET["post_type"] != "mokhtar") {
																																																						$lang = get_locale();
																																																						if ($lang == 'en_US') {
																																																							?>
									<span class="datePost"><a href="<?php echo esc_url($post_data['post_link']); ?>" class="post_date" itemprop="datePublished" content="<?php echo esc_attr(get_the_date('Y-m-d')); ?>"><?php echo esc_html($post_data['post_date']); ?></a></span>
								<?php  } else { ?>
									<date>
										<?php
																																																							$postdate_d = get_the_date('d');
																																																							$postdate_d2 = get_the_date('D');
																																																							$postdate_m = get_the_date('M');
																																																							$postdate_y = get_the_date('Y');

																																																							echo single_post_arabic_date($postdate_d, $postdate_d2, $postdate_m, $postdate_y);
										?>
									</date>
								<?php }
																																																					}
																																																					if ($post_data['post_excerpt'] && $_GET["post_type"]!="bids") {
								?>
								<div class="post_format_wrap post<?php echo esc_attr(themerex_strtoproper($post_data['post_format'])); ?>">
									<?php echo in_array($post_data['post_format'], array('quote', 'link', 'chat')) ? balanceTags($post_data['post_excerpt']) : getShortString($post_data['post_excerpt'], isset($opt['descr']) ? $opt['descr'] : get_custom_option('post_excerpt_maxlength' . ($columns > 1 ? '_masonry' : ''))); ?>
								</div>
								<?php
																																																					}
																																																					if($post_data['post_excerpt'] && $_GET["post_type"]=="bids"){
																																																					    ?>
								<div class="post_format_wrap post<?php echo esc_attr(themerex_strtoproper($post_data['post_format'])); ?>">
																																																					       <?php  echo wp_trim_words( $post_data['post_excerpt'], 14, "..."); ?>
								</div>
																																																					   <?php
																																																					}
																																																					$value11 = get_field("status");

																																																					if ($value11) {
																																																						$lang = get_locale();
																																																						if ($lang == 'ar') {
								?>

									<span class="post_status"><b>الحالة : <?php echo $value11; ?> </b></span>


								<?php
																																																						} else {

								?>
									<span class="post_status"><b>Status : <?php echo $value11; ?> </b></span>
								<?php
																																																						}
																																																					}


																										$value = get_field("file");
																																																					$pdfs = get_post_meta(get_the_ID(), 'attachements_pdf', true);
																																																					$single_pdf= get_post_meta(get_the_ID(), 'file', true);

																																																					if (!empty($pdfs) || $single_pdf) {
																																																						$lang = get_locale();
																																																						if ($lang == 'ar') {
								?>

									<span class="post_file"><a href="<?php echo esc_url($post_data['post_link']); ?> ">اقرأ المزيد</a></span>


								<?php
																																																						} else {

								?>
									<span class="post_file"><a href="<?php echo esc_url($post_data['post_link']); ?> ">Read More</a></span>
								<?php
																																																						}
																																																					}
																																																					$value1 = get_field("phone");
																																																					//	print_r($value1);
																																																					if ($value1) {
																																																						$lang = get_locale();
																																																						if ($lang == 'ar') {
								?>

									<div class="post_phone"><a href="tel:+961<?php echo $value1; ?>"><img src="https://mokhtar-fund.gov.lb/wp-content/uploads/2021/03/mokhtar-call.png" width="25px" height="25px" style="float: right;margin-left: 10px;" /><?php echo  $value1; ?></a></div>


								<?php
																																																						} else {

								?>
									<div class="post_phone"><a href="tel:+961<?php echo $value1; ?>"><img src="https://mokhtar-fund.gov.lb/wp-content/uploads/2021/03/mokhtar-call.png" width="25px" height="25px" style="float: left;margin-right: 10px;transform: scaleX(-1);" /><?php echo  $value1; ?></a></div>
							<?php
																																																						}
																																																					}


							?>

							<?php if (!isset($opt['info']) || $opt['info']) {
								if (get_custom_option('show_post_info') == 'yes' || in_shortcode_blogger(true)) { ?>
									<div class="bog_post_info infoPost">
										<span class="datePost"><a href="<?php echo esc_url($post_data['post_link']); ?>" class="post_date" itemprop="datePublished" content="<?php echo esc_attr(get_the_date('Y-m-d')); ?>"><?php echo esc_html($post_data['post_date']); ?></a></span>
										<?php if (!in_array($post_data['post_format'], array('status', 'link'))) { ?>
											<span class="commentPost"><a class="icon-comment-1" title="<?php echo sprintf(__('Comments - %s', 'themerex'), $post_data['post_comments']); ?>" href="<?php echo esc_url($post_data['post_comments_link']); ?>"><?php echo esc_html($post_data['post_comments']); ?></a></span>
											<span class="likePost"><a class="icon-heart" title="<?php echo sprintf(__('Likes - %s', 'themerex'), $post_data['post_likes']); ?>" href="<?php echo esc_url($post_data['post_link']); ?>"><?php echo esc_html($post_data['post_likes']); ?></a></span>
										<?php } ?>
									</div>
							<?php
								}
							} ?>

							<?php
							if (isset($_GET["post_type"]) && $_GET["post_type"] == "mokhtar") {
							?>
							</div>

						<?php
							}
						?>


						</div>

					</div>

</article>