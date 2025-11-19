<?php
$post_data['post_views']++;
$avg_author = 0;
$avg_users  = 0;
$show_title = get_custom_option('show_post_title')=='yes' && (get_custom_option('show_post_title_on_quotes')=='yes' || !in_array($post_data['post_format'], array('aside', 'chat', 'status', 'link', 'quote')));

startWrapper(array(
'<div class="itemscope" itemscope itemtype="'.themerex_get_protocol().'://schema.org/Article">',
'	<section class="'.join(' ', get_post_class('post post_format_'.$post_data['post_format'].' post'.esc_attr($opt['post_class']).(get_custom_option("show_post_author") == 'yes' || get_custom_option("show_post_related") == 'yes' || get_custom_option("show_post_comments") == 'yes' ? ' hrShadow' : ' no_margin'))).'">',
'		<article class="post_content">'
));

			if (!$post_data['post_protected']) {
				if (!empty($opt['dedicated'])) {
					echo balanceTags($opt['dedicated']);
				} else if (get_custom_option('show_featured_image')=='yes' && $post_data['post_thumb']) {
					?>
					<div class="sc_section post_thumb thumb">
						<?php echo balanceTags($post_data['post_thumb']); ?>
					</div>
					<?php
				}
			}

			if ($show_title) {
			?>
			<h1 itemprop="<?php echo esc_attr('name'); ?>" class="post_title entry-title"><?php echo esc_html($post_data['post_title']); ?></h1>
			<?php
			}

			if ( get_custom_option('show_post_info')=='yes') {
				?>
				<div class="post_info infoPost">
					<?php if ($post_data['post_edit_enable'] || $post_data['post_delete_enable']) { ?>
						<span class="frontend_editor_buttons">
									<?php if ($post_data['post_edit_enable']) { ?>
										<span class="squareButton sc_button_style_global global medium"><a id="frontend_editor_icon_edit" title="<?php _e('Edit post', 'themerex'); ?>" href="#"><?php _e('Edit', 'themerex'); ?></a></span>
									<?php } ?>
							<?php if ($post_data['post_delete_enable']) { ?>
								<span class="squareButton sc_button_style_global global medium"><a id="frontend_editor_icon_delete" title="<?php _e('Delete post', 'themerex'); ?>" href="#"><?php _e('Delete', 'themerex'); ?></a></span>
							<?php } ?>
								</span>
					<?php } ?>
					<span class="authorPost" itemprop="author"><a href="<?php echo esc_url($post_data['post_author_url']); ?>" class="post_author fn" rel="author"><?php echo esc_html($post_data['post_author']); ?></a></span>

					<span class="datePost"><a href="<?php echo esc_url($post_data['post_link']); ?>" class="post_date" itemprop="datePublished" content="<?php echo esc_attr(get_the_date('Y-m-d')); ?>"><?php echo esc_html($post_data['post_date']); ?></a></span>

					<span class="likePost icon-heart"><?php echo balanceTags($post_data['post_likes']); ?></span>

					<span class="commentPost"><a class="icon-comment-1" title="<?php echo sprintf(__('Comments - %s', 'themerex'), $post_data['post_comments']); ?>" href="<?php echo esc_url($post_data['post_comments_link']); ?>"><?php echo esc_html($post_data['post_comments']); ?></a></span>

				</div>
			<?php
			}

			startWrapper('<div class="post_text_area" itemprop="articleBody">');

			// Post content
			if ($post_data['post_protected']) {
				echo balanceTags($post_data['post_excerpt']);
				echo get_the_password_form();
			} else {
				echo sc_gap_wrapper($post_data['post_content']);
				wp_link_pages( array(
					'before' => '<div class="nav_pages_parts"><span class="pages">' . __( 'Pages:', 'themerex' ) . '</span>',
					'after' => '</div>',
					'link_before' => '<span class="page_num">',
					'link_after' => '</span>'
				) );
						$value11 = get_field( "status" );
					
					if( $value11 ) {
					    $lang = get_locale();
					    if($lang=='ar'){
					    ?>
					    
					    <span class="post_status">الحالة : <?php echo $value11;?>
					   </span>
					    
					    
					    <?php 
					    }else{
					        
					        ?>
					        <span class="post_status">Status : <?php echo $value11;?> 
					       </span>
					        <?php 
					    }
					        
					}
					$value = get_field( "file" );
					$pdfs = get_post_meta(get_the_ID(), 'attachements_pdf', true);
					$single_pdf= get_post_meta(get_the_ID(), 'file', true);

					
					    $lang = get_locale();
						if(!empty($pdfs) || $single_pdf) { 
					    	if($lang=='ar'){
					    ?>
							<h2 class="post_attach_header"> <b>الملفات المرفقة</b></h2>
						<?php
						} else { ?>
						<h2 class="post_attach_header"> <b>Attachments</b></h2>
						<?php } 
					
					 ?>
						<?php 
					
						echo '<ul class="pdf-lists">';
						if(is_singular('bids')) {
						foreach ($pdfs as $pdf) {
							$title = get_the_title($pdf);
							$pdf_icon = '<img src="'. site_url().'/wp-content/themes/classter/assets/images/icons/pdf.svg" class="w-auto mt-2" alt="calendar" style="height: 20px">';
							
							
							echo '<li>' . $pdf_icon . '<a target="_blank" href="' . get_permalink($pdf) . '">' . $title . '</a> </li>';
						}	
					} else {
						$title = get_the_title($single_pdf);
						$pdf_icon = '<img src="'. site_url().'/wp-content/themes/classter/assets/images/icons/pdf.svg" class="w-auto mt-2" alt="calendar" style="height: 20px">';
						
						
						echo '<li>' . $pdf_icon . '<a target="_blank" href="' . get_permalink($single_pdf) . '">' . $title . '</a> </li>';
					

					}					
						echo '</ul>';
						}
						?>

						<?php
						
					        
					
						$value1 = get_field( "phone" );
				//	print_r($value1);
					if( $value1 ) {
					    $lang = get_locale();
					    if($lang=='ar'){
					    ?>
					    
					    <span class="post_phone"><img src="https://makhateer.ids-demo.me/wp-content/uploads/2021/03/mokhtar-call.png" width="25px" height="25px" style="float: right; " /><?php echo  $value1; ?></span>
					    
					    
					    <?php 
					    }else{
					        
					        ?>
					        <span class="post_phone"><img src="https://makhateer.ids-demo.me/wp-content/uploads/2021/03/mokhtar-call.png" width="25px" height="25px"  style="float: right; " /><?php echo  $value1; ?></span>
					        <?php 
					    }
					        
					}
					
				?>

				<div class="tagsWrap">

				<?php if ($post_data['post_categories_links']!='' && get_custom_option('show_post_tags')=='yes' && !isset($_GET['post_type']) ) { 
					    $lang = get_locale();
					    if($lang=='ar'){
					        
					    ?>
						<span class="post_cats"><?php _e('التصنيفات', 'themerex'); ?> <?php echo balanceTags($post_data['post_categories_links']); ?></span>
					<?php 
					    }else if($lang=='en_US'){
					        ?>
					        						<span class="post_cats"><?php _e('Categories:', 'themerex'); ?> <?php echo balanceTags($post_data['post_categories_links']); ?></span>
					        
					        <?php }
					} ?>

					<?php if ( get_custom_option('show_post_counters')=='yes') { ?>
					<div class="postSharing">
						<?php
						$postinfo_buttons = array('comments', 'views', 'likes', 'share', 'markup');
						require(themerex_get_file_dir('/templates/page-part-postinfo.php'));
						?>
					</div>
					<?php } 
					
					
					
					?>
					<?php if ( get_custom_option('show_post_tags')=='yes' && $post_data['post_tags_links'] != '') { ?>
					<div class="post_tags">
						<?php _e('Tags:', 'themerex'); ?>
						<?php echo balanceTags($post_data['post_tags_links']); ?>
					</div>
					<?php } ?>
				</div>
				<?php
			}
			stopWrapper(3);
			?>

	<?php
	if (!$post_data['post_protected']) {
		if ($post_data['post_edit_enable']) {
			require(themerex_get_file_dir('/templates/page-part-editor-area.php'));
		}
		require(themerex_get_file_dir('/templates/page-part-author-info.php'));
		require(themerex_get_file_dir('/templates/page-part-related-posts.php'));
		require(themerex_get_file_dir('/templates/page-part-comments.php'));
	}
	?>

<?php stopWrapper(); ?><!-- </div> --><!-- .itemscope -->

<?php require(themerex_get_file_dir('/templates/page-part-views-counter.php')); ?>
