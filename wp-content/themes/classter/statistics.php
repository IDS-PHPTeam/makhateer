<?php  /* Template Name: Statistics Page */


get_header();


$language_code = get_locale();

$labels = array();

if ($language_code == "ar") {
	$labels["statistics"] = "إحصاءات";
	$labels["polls"] = "استطلاعات الرأي";
} else {
	$labels["statistics"] = "Statistics";
	$labels["polls"] = "Polls";
}

?>

<style>
	.ids-tabs {
		text-align: center;
		margin-top: 70px;
	}

	.ids-tablinks {
		background-color: transparent;
		color: #acb4b6 !important;
		font-size: 20px;
		border-radius: 31px;
		padding-right: 30px;
		padding-left: 30px;
		margin-right: 5px;
		margin-left: 5px;
		text-decoration: none;
		cursor: pointer;
	}

	@media (max-width: 460px) {
		#ids_statistics_dropdown__div {
			margin-right: 15px !important;
			margin-left: 15px !important;
		}
	}


	@media (max-width: 345px) {
		.ids-tablinks {
			padding-top: 10px;
			cursor: pointer;
			display: block;
			padding-bottom: 10px;
			margin-bottom: 10px;
		}
	}

	.ids-tablinks:hover {
		text-decoration: none;
	}

	.ids-tablinks.ids-active {
		border: 1px solid #dcdfe0;
	}

	.tabcontent {
		transition: all 0.5s linear;
	}

	.tabcontent:not(.active) {
		height: 0px;
		opacity: 0;
	}
</style>
<!-- Tabs Start -->

<div class="ids-tabs">
	<a class="ids-tablinks ids-active" onclick="openTab(event, 'statistics')">
		<?php
		echo $labels["statistics"];
		?>
	</a>

	<a class="ids-tablinks" onclick="openTab(event, 'polls')">
		<?php
		echo $labels["polls"];
		?>
	</a>
</div>

<!-- Tabs End -->

<?php

// Custom Post Type Statistics Start.

$posts_args = array(
	'post_type'      => 'statistic',
	'posts_per_page' => -1,
	'post_status'    => 'publish',
	'orderby'        => 'date',
	'order'          => 'DESC',
);

$posts = get_posts($posts_args);

if (count($posts)) {

?>
	<div id="statistics" class="tabcontent active">

		<div id="ids_statistics_dropdown__div">
			<select id='titles_dropdown'>

				<?php
				foreach ($posts as $statistic_post) {
				?>
					<option value='<?php echo esc_attr($statistic_post->ID); ?>'><?php echo esc_html($statistic_post->post_title); ?></option>
				<?php
				}
				?>

			</select>
		</div>


		<?php
		wp_enqueue_script(
			'statistics_script',
			get_template_directory_uri() . '/js/statistics-dropdown.js',
			array('jquery'),
			'1.0.1',
			true
		);

		$counters = get_theme_option("blog_counters");
		$allow_editor = get_theme_option("allow_editor") == 'yes';
		$page_style = get_custom_option('single_style');

		// Show the first dropdown statistic (not the page body / leftover loop post).
		global $post;
		$post = $posts[0];
		setup_postdata($post);

		if (get_theme_option('use_ajax_views_counter') == 'no') {
			setPostViews(get_the_ID());
		}

		WPBMap::addAllMappedShortcodes();

		?>
		<div id="ids_statistic_body">
			<?php
			showPostLayout(
				array(
					'layout' => esc_attr($page_style),
					'thumb_size' => esc_attr($page_style),
					'thumb_crop' => false,
					'sidebar' => !in_array(get_custom_option('show_sidebar_main'), array('none', 'fullwidth')),
					'content' => true
				)
			);
			?>
		</div>

	</div>
<?php
	wp_reset_postdata();
}

?>

<div id="polls" class="tabcontent">

	<?php
	global $wpdb;
	$query_polls = $wpdb->prepare(
		"SELECT id FROM {$wpdb->prefix}ids_polls ORDER BY id DESC LIMIT %d",
		1
	);
	$result_polls = $wpdb->get_results($query_polls);
	if (!empty($result_polls[0]->id)) {
		$poll_id = (int) $result_polls[0]->id;
		$poll_lang = (get_locale() === 'ar') ? 'ar' : 'en';
		echo do_shortcode('[ids_poll id="' . $poll_id . '" lang="' . $poll_lang . '"]');
	}
	?>
</div>


<script>
	function openTab(event, tabName) {
		var i, tabcontent, tablinks;
		tabcontent = document.getElementsByClassName("tabcontent");
		for (i = 0; i < tabcontent.length; i++) {
			tabcontent[i].style.height = "0px";
			tabcontent[i].style.opacity = "0";
		}
		tablinks = document.getElementsByClassName("ids-tablinks");
		for (i = 0; i < tablinks.length; i++) {
			tablinks[i].className = tablinks[i].className.replace(" ids-active", "");
		}
		document.getElementById(tabName).style.height = "auto";
		document.getElementById(tabName).style.opacity = "1";
		if (tabName == "polls") {
			setTimeout(() => {
				jQuery("#polls .vc_chart ").vcLineChart();
			});
		}
		event.currentTarget.className += " ids-active";
	}
</script>
<?php

// Custom Post Type Statistics End.


get_footer();
