<?php

$shortcode = new Shortcode_Class();
class Shortcode_Class
{
    private $addScript = false;

    public function __construct()
    {
        add_shortcode('owl_carousel_grid', array($this, 'owl_carousel_function1'));
    }
   

    public function owl_carousel_function1( $attr, $content )
    {   
        $this->addScript = true;
        ob_start();
    $cat = get_categories(array('taxonomy'=>'category'));
?>

<div class="row bids">
         
<?php
            global $post;
            $query = new WP_Query(array(
                'post_type' => 'bids',
                'orderby' => 'date',
                'order' => 'DESC',
                'posts_per_page' => 3

                 
            )); 
            while ( $query->have_posts() ) : 
            $query->the_post();
            $language = pll_current_language();
        ?>
                <article class="bids-article isotopeElement ids-custom-post-type post_format_standard odd isotopeElementShow" style="width: 350; position: absolute; left: 0px; top: 0px; opacity: 1;">
                    <div class="isotopePadding bg_post">
                        <div class="post_wrap bids_content">
                            <h4><a href="<?php the_permalink(); ?>"><?php echo the_title(); ?></a></h4>
                            <date>
										<?php
                                        if($language == 'ar') {
											$postdate_d = get_the_date('d');
						 				    $postdate_d2 = get_the_date('D');
											$postdate_m = get_the_date('M');
											$postdate_y = get_the_date('Y');
                                            echo single_post_arabic_date($postdate_d, $postdate_d2, $postdate_m, $postdate_y);
                                        } else {
                                            echo get_the_date();
                                        }

										
										?>
							</date>
                                                                                                                                                                                                                        
                                                                                                                                                                                                                                
                            <div class="post_format_wrap postStandard">
                                <?php echo wp_trim_words( get_the_content(), 14, "...");   ?>
                            </div>

                            <?php
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

                                <span class="post_file"><a href="<?php the_permalink(); ?> ">اقرأ المزيد</a></span>


                                <?php
                                    } else {

                                ?>
                                <span class="post_file"><a href="<?php the_permalink(); ?> ">Read More</a></span>
                                <?php
                                    }
                                }
                            ?>                                                                                                                                       	
                        </div>
                    </div>
                </article>
        <?php 
            wp_reset_postdata();
            endwhile; 
        ?>
              
</div>
<?php
        $html = ob_get_clean();
        return $html;

    } 
}
