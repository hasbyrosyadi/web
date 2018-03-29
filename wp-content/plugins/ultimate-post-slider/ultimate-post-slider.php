<?php
/*
Plugin Name: Ultimate Post Slider Widget
Description: A Post Slider Widget built around what you need to do, with lots of functions and more to come.
Author: Pieter Ferreira
Version: 2.0.0
License: GPLv2
*/

// Register Style
function ups_admin_css() {

	wp_register_style( 'ups_admin_css', plugins_url('/css/ups-admin.css', __FILE__), false, false, 'all' );
	wp_enqueue_style( 'ups_admin_css' );

}
add_action( 'admin_enqueue_scripts', 'ups_admin_css' );

// Register Script
function ups_admin_js() {

	wp_register_script('ups_admin_js', plugins_url('/js/ups-admin.min.js', __FILE__), array('jquery'), null, true);
	wp_enqueue_script( 'ups_admin_js' );

}
add_action( 'admin_enqueue_scripts', 'ups_admin_js' );


add_action( 'wp_enqueue_scripts', 'ultimate_post_slider_widget_styles' );

function ultimate_post_slider_widget_styles() {
	wp_register_style( 'ultimate-post-slider', plugins_url( 'ultimate-post-slider', __FILE__) , array() , false, false);
	wp_enqueue_style( 'ultimate-post-slider' );
	
	wp_register_style( 'ups_bxslider-css', plugins_url( '/third-party/jquery.bxslider/jquery.bxslider.css', __FILE__) , array() , false, false);
	wp_enqueue_style( 'ups_bxslider-css' );
	
}
	
function ups_slider_script() {        
		wp_register_script( 'ups_slider_script', plugins_url( '/third-party/jquery.bxslider/jquery.bxslider-rahisified.js', __FILE__), array(), false, false);
		wp_enqueue_script( 'ups_slider_script' );
		
		wp_register_script( 'ups_bxslider_ease', plugins_url( '/third-party/jquery.bxslider/plugins/jquery.easing.1.3.js', __FILE__), array(), false, false);
		wp_enqueue_script( 'ups_bxslider_ease' );
	
    }
add_action( 'wp_footer', 'ups_slider_script' );

function ups_slider_inline() {
    if( wp_script_is( 'jquery', 'done' ) ) {
    ?>
<!-- bxSlider Javascript file -->
  <script>
    $(document).ready(function(){
      // code highlighter
      hljs.initHighlightingOnLoad();
    });
  </script>
    <?php
    }
}
add_action( 'wp_footer', 'ups_slider_inline' );



class ups_widget extends WP_Widget {

function __construct() {
parent::__construct(
// Base ID of the widget
'ups_widget', 

// Widget name that appears in UI
__('Ultimate Post Slider Widget', 'ups_widget_domain'), 

// Widget description
array( 'description' => __( 'Display your posts the way you want to!, sticky or not.', 'ups_widget_domain' ), ) 
);
}

// Creating widget front-end
public function widget( $args, $instance ) {

global $post;
	$current_post_id =  $post->ID;

if ( ! empty( $title ) )
echo $args['before_title'] . $title . $args['after_title'];
 	$title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title'], $instance, $this->id_base);
	$cssid = $instance['cssid'];
	$cssclass = $instance['cssclass'];
    $sticky = $instance['sticky'];
    $from_cat = empty($instance['from_cat']) ? '' : explode(',', $instance['from_cat']);
    $order = $instance['order'];
    $orderby = $instance['orderby'];
    $custom_fields = $instance['custom_fields'];
    $some_generic = $instance['some_generic'];
    $date_format = $instance['date_format'];
    ###General
	$mode = $instance['mode'];
	$infiniteLoop = $instance['infiniteLoop'];
	$speed = $instance['speed'];
	$randomStart = $instance['randomStart'];
	$adaptiveHeight = $instance['adaptiveHeight'];
	$adaptiveHeightSpeed = $instance['adaptiveHeightSpeed'];
	###BP
	$bp1 = $instance['bp1'];
	$bp2 = $instance['bp2'];
	$bp3 = $instance['bp3'];
	###Pager
	$pager = $instance['pager'];
	$pagerType = $instance['pagerType'];
	###Controls
	$controls = $instance['controls'];
	$nextText = $instance['nextText'];
	$prevText = $instance['prevText'];
	$autoControls = $instance['autoControls'];
	$startText = $instance['startText'];
	$stopText = $instance['stopText'];
	$autoControlsCombine = $instance['autoControlsCombine'];
	###Auto
	$auto = $instance['auto'];
	$pause = $instance['pause'];
	$autoStart = $instance['autoStart'];
	$autoDirection = $instance['autoDirection'];
	$autoHover = $instance['autoHover'];
	$autoDelay = $instance['autoDelay'];
	###Carousel
	$moveSlides = $instance['moveSlides'];


      // Ultimate Sticky posts Query
      
      if ($sticky == 'only') {
        $sticky_query = $args = array( 
        'posts_per_page' => $instance['num'], 
        'post__in' => get_option( 'sticky_posts' ),
        'category__in' => $from_cat,
        'orderby' => $instance['orderby'],
		'order' => $instance['order'],
        'ignore_sticky_posts' => 1  
         ); 
      } elseif ($sticky == 'hide') {
      $sticky_query = $args = array( 
        'posts_per_page' => $instance['num'], 
        'post__not_in' => get_option( 'sticky_posts' ),
        'category__in' => $from_cat,
        'orderby' => $instance['orderby'],
		'order' => $instance['order']
         );
      } 
      else { 
        $sticky_query = $args = array( 
        'posts_per_page' => $instance['num'],
        'category__in' => $from_cat,
        'order' => $order,
        'orderby' => $orderby,
        'ignore_sticky_posts' => 1
        
      );
      } 
if(!function_exists('excerpt')) {
    function excerpt($num) {
		$limit = $num+0;
		$show_excerpt = explode(' ', get_the_excerpt(), $limit);
		array_pop($show_excerpt);
		$show_excerpt = implode(" ",$show_excerpt)." ... "."";
		echo "<p>".$show_excerpt."</p>";
	}
}
// This is where you run the code and display the output
   			
			$query = new WP_Query( $args );
?>
			<div id="<?php echo $instance["cssid"] ?>" class="<?php echo $instance["cssclass"]  ?>">
			
			<?php
			if ( $title ) {
        		echo "<h2>" . $title . "</h2>";
      		}
   				$featured = new WP_Query($args); 
   				?>
   				<ul class="ups-bxslider" data-call="bxslider" data-options="{ 
					mode: '<?php echo $mode; ?>',
					infiniteLoop: <?php echo $infiniteLoop; ?>,
					speed: <?php echo $speed; ?>,
					randomStart: <?php echo $randomStart; ?>,
					adaptiveHeight: <?php echo $adaptiveHeight; ?>,
					adaptiveHeightSpeed: <?php echo $adaptiveHeightSpeed; ?>,
					pager: <?php echo $pager; ?>,
					pagerType: '<?php echo $pagerType; ?>',
					controls: <?php echo $controls; ?>,
					nextText: '<?php echo $nextText; ?>',
					prevText: '<?php echo $prevText; ?>',
					autoControls: <?php echo $autoControls; ?>,
					startText: '<?php echo $startText; ?>',
					stopText: '<?php echo $stopText; ?>',
					autoControlsCombine: <?php echo $autoControlsCombine; ?>,
					auto: <?php echo $auto; ?>,
					pause: <?php echo $pause; ?>,
					autoStart: <?php echo $autoStart; ?>,
					autoDirection: '<?php echo $autoDirection; ?>',
					autoHover: <?php echo $autoHover; ?>,
					autoDelay: <?php echo $autoDelay; ?>,
					moveSlides: <?php echo $moveSlides; ?>
   				}" 
   				data-breaks="[{screen:0, slides:<?php echo $bp1; ?>, pager:false},{screen:460, slides:<?php echo $bp2; ?>},{screen:768, slides:<?php echo $bp3; ?>}]" >
	   				<?php
					if ($featured->have_posts ()): 
						while($featured->have_posts()): $featured->the_post(); ?>
							<li class="featured_individual">
								<div class="ups_container">
									<?php if (current_theme_supports('post-thumbnails') && $instance['show_thumbnail'] && has_post_thumbnail()) : ?>
										<div class="ups_figure">
											<div class="ups_image">
												<a href="<?php the_permalink(); ?>"><?php the_post_thumbnail($instance['thumb_size']); ?>
												<div class="ups_overlay"></div>
												</a>
											</div>
										</div>
									<?php endif; ?>
									<div class="ups_body">
										<?php if ( isset( $instance['show_title'] ) ) : ?>
											<div class="ups_title">
												<h3>
													<?php if ( isset( $instance['link_title'] ) ) : ?>
														<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
													<?php else: ?>
														<?php the_title(); ?>
													<?php endif; ?>
												</h3>
											</div>
										<?php endif; ?>
										<?php if ( $instance['show_excerpt'] ) : ?>
											<div class="ups_excerpt"><?php excerpt($instance["excerpt_length"]); ?></div>
										<?php endif; ?>
	
										<?php if ( isset( $instance['show_category'] ) ) : ?>
											<div class="ups_category"><?php the_category(','); ?></div>
										<?php endif; ?>
											
										<?php if ( isset( $instance['show_readmore'] ) ) : ?>
											<div class="ups_rm"><a href="<?php the_permalink(); ?>"><?php echo $instance[ 'readmore_text' ]; ?></a>
												</div>
										<?php endif; ?>

										<?php 
											if ($instance['show_date'] === 'hide') {
						        			?>
						        			<?php
						   					}
						   					else if ($instance['show_date'] === 'created') {
						   					?>
						   						<p><?php the_time($date_format);?></p>
						   					<?php
						    				}
						    				
						    				else if ($instance['show_date'] === 'modified') {
						   					?>
						   						<p><?php the_modified_date($date_format);?></p>
						   					<?php
						    				}
						    			?>

						    			<?php if ($custom_fields) : ?>
							              	<?php $custom_field_name = explode(',', $custom_fields); ?>
							              	<div class="ups_custom_fields">
							                
								                <?php foreach ($custom_field_name as $name) :
								                	$name = trim($name);
								                  	$custom_field_values = get_post_meta($post->ID, $name, true);
								                  	if ($custom_field_values) : ?>
								                    	<div class="ups_field ups_field_<?php echo $name; ?>">
									                      	<?php
									                      		if (!is_array($custom_field_values)) {
									                        			echo $custom_field_values;
									                      		} else {
									                        		$last_value = end($custom_field_values);
									                        		foreach ($custom_field_values as $value) {
									                         			echo $value;
									                          			if ($value != $last_value) echo ', ';
									                        		}
									                      		}
									                      	?>
								                    	</div>
								                  	<?php endif;
								                endforeach; ?>
							               		<?php if ($some_generic) : ?>
								                    <div class="ups_generic">
								                    	<?php echo $some_generic ?>
								                    </div>
							                    <?php endif; ?>
							              	</div>
							            <?php endif; ?>

									</div>
								</div>
							</li>
						<?php
						endwhile; 
					endif;
					wp_reset_query();
					?>
				</ul>
			</div>
			<?php

}
		
// Widget Backend 
public function form( $instance ) {

$instance = wp_parse_args( (array) $instance, array(
        'title' => __('Ultimate Post Slider', 'ups'),
        'excerpt_length' => '15',
        'date_format' => 'd M Y',
        'readmore_text' => 'Continue Reading...',
        'cssid' => 'your-ID-class',
        'cssclass' => 'your-CLASS',
        'num' => '5',
        'custom_fields' => '',
        'order' => 'DESC',
        'orderby' => 'date',
        'show_title' => true,
        'mode' => 'horizontal',
        'infiniteLoop' => 'true',
        'speed' => '500',
        'randomStart' => 'false',
        'adaptiveHeight' => 'true',
        'adaptiveHeightSpeed' => '800',
        'bp1' => '1',
        'bp2' => '2',
        'bp3' => '3',
        'pager' => 'true',
        'pagerType' => 'full',
        'controls' => 'true',
        'nextText' => 'Next',
        'prevText' => 'Prev',
        'autoControls' => 'false',
        'startText' => 'Start',
        'stopText' => 'Stop',
        'autoControlsCombine' => 'false',
        'auto' => 'true',
        'pause' => '6000',
        'autoStart' => 'true',
        'autoDirection' => 'next',
        'autoHover' => 'false',
        'autoDelay' => '0',
        'moveSlides' => '1'
        
        
      ) );
$title = $instance[ 'title' ];
$show_title = $instance[ 'title' ];
$link_title = $instance[ 'link_title' ];
$show_excerpt = $instance[ 'show_excerpt' ];
$excerpt_length = $instance[ 'excerpt_length' ];

$show_date = $instance[ 'show_date' ];
$date_format = $instance[ 'date_format' ];

$show_category = $instance[ 'show_category' ];
$show_readmore = $instance[ 'show_readmore' ];
$readmore_text = $instance[ 'readmore_text' ];
$num = $instance[ 'num' ];
$custom_fields = strip_tags($instance['custom_fields']);
$some_generic = $instance[ 'some_generic' ];
$cssid = $instance[ 'cssid' ];
$cssclass = $instance[ 'cssclass' ];
$sticky = $instance['sticky'];
$from_cat = $instance['from_cat'];
$order = $instance['order'];
$orderby = $instance['orderby'];
$thumb_size = $instance['thumb_size'];
$show_thumbnail = $instance['show_thumbnail'];

###General
$mode = $instance['mode'];
$infiniteLoop = $instance['infiniteLoop'];
$speed = $instance['speed'];
$randomStart = $instance['randomStart'];
$adaptiveHeight = $instance['adaptiveHeight'];
$adaptiveHeightSpeed = $instance['adaptiveHeightSpeed'];
###BP
	$bp1 = $instance['bp1'];
	$bp2 = $instance['bp2'];
	$bp3 = $instance['bp3'];
###Pager
$pager = $instance['pager'];
$pagerType = $instance['pagerType'];
###Controls
$controls = $instance['controls'];
$nextText = $instance['nextText'];
$prevText = $instance['prevText'];
$autoControls = $instance['autoControls'];
$startText = $instance['startText'];
$stopText = $instance['stopText'];
$autoControlsCombine = $instance['autoControlsCombine'];
###Auto
$auto = $instance['auto'];
$pause = $instance['pause'];
$autoStart = $instance['autoStart'];
$autoDirection = $instance['autoDirection'];
$autoHover = $instance['autoHover'];
$autoDelay = $instance['autoDelay'];
###Carousel
$moveSlides = $instance['moveSlides'];


// Let's turn $types, $cats, and $tags into an array if they are set
      if (!empty($from_cat)) $from_cat = explode(',', $from_cat);

      // Count number of categories for select box sizing
      $cat_list = get_categories( 'hide_empty=1' );
      if ($cat_list) {
        foreach ($cat_list as $cat) {
          $cat_ar[] = $cat;
        }
        $c = count($cat_ar);
        if($c > 6) { $c = 6; }
      } else {
        $c = 3;
      }

     



// Widget admin form
?>
<div class="ups-tabs">
	<a class="ups-tb active" data-toggle="general-post-opt"><?php _e('General', 'ups'); ?></a>
	<a class="ups-tb" data-toggle="ordering-post-opt"><?php _e('Ordering / Display', 'ups'); ?></a>
	<a class="ups-tb" data-toggle="sliding-post-opt"><?php _e('Sliding', 'ups'); ?></a>
	<a class="ups-tb" data-toggle="formatting-post-opt"><?php _e('Formatting', 'ups'); ?></a>
</div>
      
      
<div class="ups-tb-item general-post-opt">
<!**************************************************** Post Display Options ****************************************************************************>
<div class="ups-seg"><h1 class="ups_seg">Post Display Options</h1></div>
<!**************************************************** End Post Display Options *************************************************************************>
<p>
<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
</p>

<p>
<input type="checkbox" class="show_title" id="<?php echo $this->get_field_id("show_title"); ?>" name="<?php echo $this->get_field_name("show_title"); ?>"<?php checked( (bool) $instance["show_title"], true ); ?> />
<label for="<?php echo $this->get_field_id( 'show_title' ); ?>"><?php _e( 'Show Titles:' ); ?></label> 
</p>

<p>
<input type="checkbox" class="link_title" id="<?php echo $this->get_field_id("link_title"); ?>" name="<?php echo $this->get_field_name("link_title"); ?>"<?php checked( (bool) $instance["link_title"], true ); ?> />
<label for="<?php echo $this->get_field_id( 'link_title' ); ?>"><?php _e( 'Link Titles:' ); ?></label> 
</p>

<p>
<input type="checkbox" class="show_excerpt" id="<?php echo $this->get_field_id("show_excerpt"); ?>" name="<?php echo $this->get_field_name("show_excerpt"); ?>"<?php checked( (bool) $instance["show_excerpt"], true ); ?> />
<label for="<?php echo $this->get_field_id("show_excerpt"); ?>"><?php _e( 'Show post excerpt' ); ?></label>
</p>

<p>
<label for="<?php echo $this->get_field_id("excerpt_length"); ?>"><?php _e( 'Excerpt length' ); ?></label>
<input style="text-align: center;" type="text" id="<?php echo $this->get_field_id("excerpt_length"); ?>" name="<?php echo $this->get_field_name("excerpt_length"); ?>" value="<?php echo $instance["excerpt_length"]; ?>" size="3" />
</p>

<p>
	<label for="<?php echo $this->get_field_id('show_date'); ?>"><?php _e('Show Date', 'ups'); ?>:</label>
 		<select name="<?php echo $this->get_field_name('show_date'); ?>" id="<?php echo $this->get_field_id('show_date'); ?>" class="widefat">
            <option value="hide"<?php if( $show_date == 'hide') echo ' selected'; ?>><?php _e('Hide Date', 'ups'); ?></option>
            <option value="created"<?php if( $show_date == 'created') echo ' selected'; ?>><?php _e('Show Created Date', 'ups'); ?></option>
            <option value="modified"<?php if( $show_date == 'modified') echo ' selected'; ?>><?php _e('Show Modified Date', 'ups'); ?></option>
		</select>
</p>

<p>
<label for="<?php echo $this->get_field_id("date_format"); ?>"><?php _e( 'Date Format' ); ?></label>
<input style="text-align: center;" type="text" id="<?php echo $this->get_field_id("date_format"); ?>" name="<?php echo $this->get_field_name("date_format"); ?>" value="<?php echo $instance["date_format"]; ?>" size="9" />
</p>

<p>
<input type="checkbox" class="show_category" id="<?php echo $this->get_field_id("show_category"); ?>" name="<?php echo $this->get_field_name("show_category"); ?>"<?php checked( (bool) $instance["show_category"], true ); ?> />
<label for="<?php echo $this->get_field_id( 'show_category' ); ?>"><?php _e( 'Show Category:' ); ?></label> 
</p>

<p>
<input type="checkbox" class="show_readmore" id="<?php echo $this->get_field_id("show_readmore"); ?>" name="<?php echo $this->get_field_name("show_readmore"); ?>"<?php checked( (bool) $instance["show_readmore"], true ); ?> />
<label for="<?php echo $this->get_field_id( 'show_readmore' ); ?>"><?php _e( 'Show Readmore:' ); ?></label> 
</p>

<p>
<label for="<?php echo $this->get_field_id( 'readmore_text' ); ?>"><?php _e( 'Custom Readmore Text' ); ?></label> 
<input class="readmore_text" id="<?php echo $this->get_field_id( 'readmore_text' ); ?>" name="<?php echo $this->get_field_name( 'readmore_text' ); ?>" type="text" value="<?php echo esc_attr( $readmore_text ); ?>" />
</p>

<p>
<label for="<?php echo $this->get_field_id( 'num' ); ?>"><?php _e( 'Number of posts to show:' ); ?></label> 
<input class="num" id="<?php echo $this->get_field_id( 'num' ); ?>" name="<?php echo $this->get_field_name( 'num' ); ?>" type="text" value="<?php echo esc_attr( $num ); ?>" />
</p>


<?php if ( function_exists('the_post_thumbnail') && current_theme_supports( 'post-thumbnails' ) ) : ?>
<?php $sizes = get_intermediate_image_sizes(); ?>
<p>
<input class="checkbox" id="<?php echo $this->get_field_id( 'show_thumbnail' ); ?>" name="<?php echo $this->get_field_name( 'show_thumbnail' ); ?>" type="checkbox" <?php checked( (bool) $show_thumbnail, true ); ?> />
<label for="<?php echo $this->get_field_id( 'show_thumbnail' ); ?>"><?php _e( 'Show thumbnail', 'ups' ); ?></label>
</p>
<p<?php if (!$show_thumbnail) echo ' style="display:none;"'; ?>>
<select id="<?php echo $this->get_field_id('thumb_size'); ?>" name="<?php echo $this->get_field_name('thumb_size'); ?>" class="widefat">
<?php foreach ($sizes as $size) : ?>
<option value="<?php echo $size; ?>"<?php if ($thumb_size == $size) echo ' selected'; ?>><?php echo $size; ?></option>
<?php endforeach; ?>
<option value="full"<?php if ($thumb_size == $size) echo ' selected'; ?>><?php _e('full'); ?></option>
</select>
</p>
<?php endif; ?>

<p>
<label for="<?php echo $this->get_field_id( 'custom_fields' ); ?>"><?php _e( 'Show custom fields (comma separated)', 'ups' ); ?>:</label>
<input class="widefat" id="<?php echo $this->get_field_id( 'custom_fields' ); ?>" name="<?php echo $this->get_field_name( 'custom_fields' ); ?>" type="text" value="<?php echo $custom_fields; ?>" />
</p>

<p>
<label for="<?php echo $this->get_field_id( 'some_generic' ); ?>"><?php _e( 'Something Generic under the Custom Fields', 'ups' ); ?>:</label>
<input class="widefat" id="<?php echo $this->get_field_id( 'some_generic' ); ?>" name="<?php echo $this->get_field_name( 'some_generic' ); ?>" type="text" value="<?php echo $some_generic; ?>" />
</p>

</div>
<div class="ups-tb-item ups-hidden-part ordering-post-opt">
<!**************************************************** Post Ordering Options ****************************************************************************>
<div class="ups-seg"><h1 class="ups_seg">Post Ordering Options</h1></div>
<!**************************************************** End Post Ordering Options *************************************************************************>
<p>
<label for="<?php echo $this->get_field_id('sticky'); ?>"><?php _e( 'Posts To Display', 'ups' ); ?>:</label>
<select name="<?php echo $this->get_field_name('sticky'); ?>" id="<?php echo $this->get_field_id('sticky'); ?>" class="widefat">
<option value="show"<?php if( $sticky === 'show') echo ' selected'; ?>><?php _e('Show All Posts', 'ups'); ?></option>
<option value="hide"<?php if( $sticky == 'hide') echo ' selected'; ?>><?php _e('Hide Sticky Posts', 'ups'); ?></option>
<option value="only"<?php if( $sticky == 'only') echo ' selected'; ?>><?php _e('Show Only Sticky Posts', 'ups'); ?></option>
</select>
</p>

<p>
<label for="<?php echo $this->get_field_id('from_cat'); ?>"><?php _e( 'Show From Categories', 'ups' ); ?>:</label>
<select name="<?php echo $this->get_field_name('from_cat'); ?>[]" id="<?php echo $this->get_field_id('from_cat'); ?>" class="widefat" style="height: auto;" size="<?php echo $c ?>" multiple>
<option value="" <?php if (empty($from_cat)) echo 'selected="selected"'; ?>><?php _e('&ndash; Show All &ndash;') ?></option>
<?php
$categories = get_categories( 'hide_empty=0' );
foreach ($categories as $category ) { ?>
<option value="<?php echo $category->term_id; ?>" <?php if(is_array($from_cat) && in_array($category->term_id, $from_cat)) echo 'selected="selected"'; ?>><?php echo $category->cat_name;?></option>
<?php } ?>
</select>
</p>
        
        <p>
          <label for="<?php echo $this->get_field_id('orderby'); ?>"><?php _e('Order by', 'ups'); ?>:</label>
          <select name="<?php echo $this->get_field_name('orderby'); ?>" id="<?php echo $this->get_field_id('orderby'); ?>" class="widefat">
            <option value="date"<?php if( $orderby == 'date') echo ' selected'; ?>><?php _e('Published Date', 'ups'); ?></option>
            <option value="title"<?php if( $orderby == 'title') echo ' selected'; ?>><?php _e('Title', 'ups'); ?></option>
            <option value="comment_count"<?php if( $orderby == 'comment_count') echo ' selected'; ?>><?php _e('Comment Count', 'ups'); ?></option>
            <option value="rand"<?php if( $orderby == 'rand') echo ' selected'; ?>><?php _e('Random'); ?></option>
          </select>
        </p>

        <p>
          <label for="<?php echo $this->get_field_id('order'); ?>"><?php _e('Order', 'ups'); ?>:</label>
          <select name="<?php echo $this->get_field_name('order'); ?>" id="<?php echo $this->get_field_id('order'); ?>" class="widefat">
            <option value="DESC"<?php if( $order == 'DESC') echo ' selected'; ?>><?php _e('Descending', 'ups'); ?></option>
            <option value="ASC"<?php if( $order == 'ASC') echo ' selected'; ?>><?php _e('Ascending', 'ups'); ?></option>
          </select>
        </p>
</div>
<!**************************************************** Featured Post Options ****************************************************************************>
<div class="ups-tb-item ups-hidden-part sliding-post-opt">
<div class="ups-seg"><h1 class="ups_seg">General</h1></div>
<!********** mode ***********>
<p>
<label for="<?php echo $this->get_field_id('mode'); ?>"><?php _e( 'Mode', 'ups' ); ?>:</label>
<select name="<?php echo $this->get_field_name('mode'); ?>" id="<?php echo $this->get_field_id('mode'); ?>" class="widefat">
<option value="horizontal"<?php if( $mode === 'horizontal') echo ' selected'; ?>><?php _e('Horizontal', 'ups'); ?></option>
<option value="vertical"<?php if( $mode == 'vertical') echo ' selected'; ?>><?php _e('Vertical', 'ups'); ?></option>
<option value="fade"<?php if( $mode == 'fade') echo ' selected'; ?>><?php _e('Fade', 'ups'); ?></option>
</select>
</p>
<!********** infiniteLoop ***********>
<p>
<label for="<?php echo $this->get_field_id('infiniteLoop'); ?>"><?php _e( 'Infinite Loop', 'ups' ); ?>:</label>
<select name="<?php echo $this->get_field_name('infiniteLoop'); ?>" id="<?php echo $this->get_field_id('infiniteLoop'); ?>" class="widefat">
<option value="true"<?php if( $infiniteLoop === 'true') echo ' selected'; ?>><?php _e('Yes', 'ups'); ?></option>
<option value="false"<?php if( $infiniteLoop == 'false') echo ' selected'; ?>><?php _e('No', 'ups'); ?></option>
</select>
</p>
<!********** speed ***********>
<p>
<label for="<?php echo $this->get_field_id( 'speed' ); ?>"><?php _e( 'Speed In ms:' ); ?></label> 
<input class="speed" id="<?php echo $this->get_field_id( 'speed' ); ?>" name="<?php echo $this->get_field_name( 'speed' ); ?>" type="text" value="<?php echo esc_attr( $speed ); ?>" />
</p>
<!********** randomStart ***********>
<p>
<label for="<?php echo $this->get_field_id('randomStart'); ?>"><?php _e( 'Random Start', 'ups' ); ?>:</label>
<select name="<?php echo $this->get_field_name('randomStart'); ?>" id="<?php echo $this->get_field_id('randomStart'); ?>" class="widefat">
<option value="true"<?php if( $randomStart === 'true') echo ' selected'; ?>><?php _e('Yes', 'ups'); ?></option>
<option value="false"<?php if( $randomStart == 'false') echo ' selected'; ?>><?php _e('No', 'ups'); ?></option>
</select>
</p>
<!********** adaptiveHeight ***********>
<p>
<label for="<?php echo $this->get_field_id('adaptiveHeight'); ?>"><?php _e( 'Adaptive Height', 'ups' ); ?>:</label>
<select name="<?php echo $this->get_field_name('adaptiveHeight'); ?>" id="<?php echo $this->get_field_id('adaptiveHeight'); ?>" class="widefat">
<option value="true"<?php if( $adaptiveHeight === 'true') echo ' selected'; ?>><?php _e('Yes', 'ups'); ?></option>
<option value="false"<?php if( $adaptiveHeight == 'false') echo ' selected'; ?>><?php _e('No', 'ups'); ?></option>
</select>
</p>
<!********** adaptiveHeightSpeed ***********>
<p>
<label for="<?php echo $this->get_field_id( 'adaptiveHeightSpeed' ); ?>"><?php _e( 'Adaptive Height Speed in ms:' ); ?></label> 
<input class="adaptiveHeightSpeed" id="<?php echo $this->get_field_id( 'adaptiveHeightSpeed' ); ?>" name="<?php echo $this->get_field_name( 'adaptiveHeightSpeed' ); ?>" type="text" value="<?php echo esc_attr( $adaptiveHeightSpeed ); ?>" />
</p>
<!********** End General ***********>
<div class="ups-seg"><h1 class="ups_seg">Breakpoint</h1></div>
<!********** Breakpoint 1 ***********>
<p>
<label for="<?php echo $this->get_field_id( 'bp1' ); ?>"><?php _e( 'Break Point 1 - Mobile (No. of slides):' ); ?></label> 
<input class="bp1" id="<?php echo $this->get_field_id( 'bp1' ); ?>" name="<?php echo $this->get_field_name( 'bp1' ); ?>" type="text" value="<?php echo esc_attr( $bp1 ); ?>" />
</p>
<!********** Breakpoint 2 ***********>
<p>
<label for="<?php echo $this->get_field_id( 'bp2' ); ?>"><?php _e( 'Break Point 2 - Tablet (No. of slides):' ); ?></label> 
<input class="bp2" id="<?php echo $this->get_field_id( 'bp2' ); ?>" name="<?php echo $this->get_field_name( 'bp2' ); ?>" type="text" value="<?php echo esc_attr( $bp2 ); ?>" />
</p>
<!********** Breakpoint 3 ***********>
<p>
<label for="<?php echo $this->get_field_id( 'bp3' ); ?>"><?php _e( 'Break Point 3 - Large Displays (No. of slides):' ); ?></label> 
<input class="bp3" id="<?php echo $this->get_field_id( 'bp3' ); ?>" name="<?php echo $this->get_field_name( 'bp3' ); ?>" type="text" value="<?php echo esc_attr( $bp3 ); ?>" />
</p>
<!********** mode ***********>
<div class="ups-seg"><h1 class="ups_seg">Pager</h1></div>
<!********** pager ***********>
<p>
<label for="<?php echo $this->get_field_id('pager'); ?>"><?php _e( 'Show Pager', 'ups' ); ?>:</label>
<select name="<?php echo $this->get_field_name('pager'); ?>" id="<?php echo $this->get_field_id('pager'); ?>" class="widefat">
<option value="true"<?php if( $pager === 'true') echo ' selected'; ?>><?php _e('Show Pager', 'ups'); ?></option>
<option value="false"<?php if( $pager == 'false') echo ' selected'; ?>><?php _e('Hide Pager', 'ups'); ?></option>
</select>
</p>
<!********** pagerType ***********>
<p>
<label for="<?php echo $this->get_field_id('pagerType'); ?>"><?php _e( 'Pager Type', 'ups' ); ?>:</label>
<select name="<?php echo $this->get_field_name('pagerType'); ?>" id="<?php echo $this->get_field_id('pagerType'); ?>" class="widefat">
<option value="full"<?php if( $pagerType === 'full') echo ' selected'; ?>><?php _e('Full', 'ups'); ?></option>
<option value="short"<?php if( $pagerType == 'short') echo ' selected'; ?>><?php _e('Short', 'ups'); ?></option>
</select>
</p>
<!********** End Pager ***********>
<div class="ups-seg"><h1 class="ups_seg">Controls</h1></div>
<!********** controls ***********>
<p>
<label for="<?php echo $this->get_field_id('controls'); ?>"><?php _e( 'Controls', 'ups' ); ?>:</label>
<select name="<?php echo $this->get_field_name('controls'); ?>" id="<?php echo $this->get_field_id('controls'); ?>" class="widefat">
<option value="true"<?php if( $controls === 'true') echo ' selected'; ?>><?php _e('Show Controls', 'ups'); ?></option>
<option value="false"<?php if( $controls == 'false') echo ' selected'; ?>><?php _e('Hide Controls', 'ups'); ?></option>
</select>
</p>
<!********** nextText ***********>
<p>
<label for="<?php echo $this->get_field_id( 'nextText' ); ?>"><?php _e( 'Next Text:' ); ?></label> 
<input class="nextText" id="<?php echo $this->get_field_id( 'nextText' ); ?>" name="<?php echo $this->get_field_name( 'nextText' ); ?>" type="text" value="<?php echo esc_attr( $nextText ); ?>" />
</p>
<!********** prevText ***********>
<p>
<label for="<?php echo $this->get_field_id( 'prevText' ); ?>"><?php _e( 'Prev Text:' ); ?></label> 
<input class="prevText" id="<?php echo $this->get_field_id( 'prevText' ); ?>" name="<?php echo $this->get_field_name( 'prevText' ); ?>" type="text" value="<?php echo esc_attr( $prevText ); ?>" />
</p>
<!********** autoControls ***********>
<p>
<label for="<?php echo $this->get_field_id('autoControls'); ?>"><?php _e( 'Auto Controls', 'ups' ); ?>:</label>
<select name="<?php echo $this->get_field_name('autoControls'); ?>" id="<?php echo $this->get_field_id('autoControls'); ?>" class="widefat">
<option value="true"<?php if( $autoControls === 'true') echo ' selected'; ?>><?php _e('Yes', 'ups'); ?></option>
<option value="false"<?php if( $autoControls == 'false') echo ' selected'; ?>><?php _e('No', 'ups'); ?></option>
</select>
</p>
<!********** startText ***********>
<p>
<label for="<?php echo $this->get_field_id( 'startText' ); ?>"><?php _e( 'Start Text:' ); ?></label> 
<input class="startText" id="<?php echo $this->get_field_id( 'startText' ); ?>" name="<?php echo $this->get_field_name( 'startText' ); ?>" type="text" value="<?php echo esc_attr( $startText ); ?>" />
</p>
<!********** stopText ***********>
<p>
<label for="<?php echo $this->get_field_id( 'stopText' ); ?>"><?php _e( 'Stop Text:' ); ?></label> 
<input class="stopText" id="<?php echo $this->get_field_id( 'stopText' ); ?>" name="<?php echo $this->get_field_name( 'stopText' ); ?>" type="text" value="<?php echo esc_attr( $stopText ); ?>" />
</p>
<!********** autoControlsCombine ***********>
<p>
<label for="<?php echo $this->get_field_id('autoControlsCombine'); ?>"><?php _e( 'Auto Controls Combine', 'ups' ); ?>:</label>
<select name="<?php echo $this->get_field_name('autoControlsCombine'); ?>" id="<?php echo $this->get_field_id('autoControlsCombine'); ?>" class="widefat">
<option value="true"<?php if( $autoControlsCombine === 'true') echo ' selected'; ?>><?php _e('Yes', 'ups'); ?></option>
<option value="false"<?php if( $autoControlsCombine == 'false') echo ' selected'; ?>><?php _e('No', 'ups'); ?></option>
</select>
</p>
<!********** End Controls ***********>
<div class="ups-seg"><h1 class="ups_seg">Auto</h1></div>
<!********** auto ***********>
<p>
<label for="<?php echo $this->get_field_id('auto'); ?>"><?php _e( 'Auto Start Transition on load', 'ups' ); ?>:</label>
<select name="<?php echo $this->get_field_name('auto'); ?>" id="<?php echo $this->get_field_id('auto'); ?>" class="widefat">
<option value="true"<?php if( $auto === 'true') echo ' selected'; ?>><?php _e('Yes', 'ups'); ?></option>
<option value="false"<?php if( $auto == 'false') echo ' selected'; ?>><?php _e('No', 'ups'); ?></option>
</select>
</p>
<!********** pause ***********>
<p>
<label for="<?php echo $this->get_field_id( 'pause' ); ?>"><?php _e( 'Pause Delay in ms:' ); ?></label> 
<input class="pause" id="<?php echo $this->get_field_id( 'pause' ); ?>" name="<?php echo $this->get_field_name( 'pause' ); ?>" type="text" value="<?php echo esc_attr( $pause ); ?>" />
</p>
<!********** autoStart ***********>
<p>
<label for="<?php echo $this->get_field_id('autoStart'); ?>"><?php _e( 'Auto Start show on load', 'ups' ); ?>:</label>
<select name="<?php echo $this->get_field_name('autoStart'); ?>" id="<?php echo $this->get_field_id('autoStart'); ?>" class="widefat">
<option value="true"<?php if( $autoStart === 'true') echo ' selected'; ?>><?php _e('Yes', 'ups'); ?></option>
<option value="false"<?php if( $autoStart == 'false') echo ' selected'; ?>><?php _e('No', 'ups'); ?></option>
</select>
</p>
<!********** autoDirection ***********>
<p>
<label for="<?php echo $this->get_field_id('autoDirection'); ?>"><?php _e( 'Auto Direction', 'ups' ); ?>:</label>
<select name="<?php echo $this->get_field_name('autoDirection'); ?>" id="<?php echo $this->get_field_id('autoDirection'); ?>" class="widefat">
<option value="next"<?php if( $autoDirection === 'next') echo ' selected'; ?>><?php _e('Next', 'ups'); ?></option>
<option value="prev"<?php if( $autoDirection == 'prev') echo ' selected'; ?>><?php _e('Prev', 'ups'); ?></option>
</select>
</p>
<!********** autoHover ***********>
<p>
<label for="<?php echo $this->get_field_id('autoHover'); ?>"><?php _e( 'Show will pause when mouse hovers over slider', 'ups' ); ?>:</label>
<select name="<?php echo $this->get_field_name('autoHover'); ?>" id="<?php echo $this->get_field_id('autoHover'); ?>" class="widefat">
<option value="true"<?php if( $autoHover === 'true') echo ' selected'; ?>><?php _e('Yes', 'ups'); ?></option>
<option value="false"<?php if( $autoHover == 'false') echo ' selected'; ?>><?php _e('No', 'ups'); ?></option>
</select>
</p>
<!********** autoDelay ***********>
<p>
<label for="<?php echo $this->get_field_id( 'autoDelay' ); ?>"><?php _e( 'Time (in ms) auto show should wait before starting:' ); ?></label> 
<input class="autoDelay" id="<?php echo $this->get_field_id( 'autoDelay' ); ?>" name="<?php echo $this->get_field_name( 'autoDelay' ); ?>" type="text" value="<?php echo esc_attr( $autoDelay ); ?>" />
</p>
<!********** End Auto ***********>
<div class="ups-seg"><h1 class="ups_seg">Carousel</h1></div>
<!********** moveSlides ***********>
<p>
<label for="<?php echo $this->get_field_id( 'moveSlides' ); ?>"><?php _e( 'Number of slides to move:' ); ?></label> 
<input class="moveSlides" id="<?php echo $this->get_field_id( 'moveSlides' ); ?>" name="<?php echo $this->get_field_name( 'moveSlides' ); ?>" type="text" value="<?php echo esc_attr( $moveSlides ); ?>" />
</p>
</div>

<div class="ups-tb-item ups-hidden-part formatting-post-opt">
<!**************************************************** Post Formatting Options ****************************************************************************>
<div class="ups-seg"><h1 class="ups_seg">Post Formatting Options</h1></div>
<!**************************************************** End Post Formatting Options *************************************************************************>
<p>
<label for="<?php echo $this->get_field_id( 'cssid' ); ?>"><?php _e( 'CSS ID:' ); ?></label> 
<input class="classid" id="<?php echo $this->get_field_id( 'cssid' ); ?>" name="<?php echo $this->get_field_name( 'cssid' ); ?>" type="text" value="<?php echo esc_attr( $cssid ); ?>" />
</p>

<p>
<label for="<?php echo $this->get_field_id( 'cssclass' ); ?>"><?php _e( 'CSS Class:' ); ?></label> 
<input class="classhere" id="<?php echo $this->get_field_id( 'cssclass' ); ?>" name="<?php echo $this->get_field_name( 'cssclass' ); ?>" type="text" value="<?php echo esc_attr( $cssclass ); ?>" />
</p>
</div>
<!**************************************************** End Featured Post Options ****************************************************************************>
<div class="rating_card_usp"><a href="https://wordpress.org/plugins/ultimate-post-slider" target="blank">If you like this widget, please take a moment to rate it here, or simply give us some feedback</a></div>
<!**************************************************** End ALL Options *************************************************************************>
<?php 
}
	
// Updating widget replacing old instances with new
public function update( $new_instance, $old_instance ) {
$instance = array();
$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
$instance['show_title'] = $new_instance['show_title'];
$instance['link_title'] = $new_instance['link_title'];
$instance['show_excerpt'] = $new_instance['show_excerpt'];
$instance['excerpt_length'] = $new_instance['excerpt_length'];


$instance['show_date'] = $new_instance['show_date'];
$instance['date_format'] = strip_tags( $new_instance['date_format']);

$instance['show_category'] = $new_instance['show_category'];
$instance['show_readmore'] = $new_instance['show_readmore'];
$instance['readmore_text'] = $new_instance['readmore_text'];
$instance['num'] = ( ! empty( $new_instance['num'] ) ) ? strip_tags( $new_instance['num'] ) : '';
$instance['custom_fields'] = strip_tags( $new_instance['custom_fields'] );
$instance['some_generic'] = $new_instance['some_generic'];

$instance['cssid'] = strip_tags( $new_instance['cssid']);
$instance['cssclass'] = strip_tags( $new_instance['cssclass']);
$instance['sticky'] = $new_instance['sticky'];
$instance['from_cat'] = (isset( $new_instance['from_cat'] )) ? implode(',', (array) $new_instance['from_cat']) : '';
$instance['order'] = $new_instance['order'];
$instance['orderby'] = $new_instance['orderby'];
$instance['show_thumbnail'] = isset( $new_instance['show_thumbnail'] );
$instance['thumb_size'] = strip_tags( $new_instance['thumb_size'] );

$instance['mode'] = $new_instance['mode'];
$instance['infiniteLoop'] = $new_instance['infiniteLoop'];
$instance['speed'] = $new_instance['speed'];
$instance['randomStart'] = $new_instance['randomStart'];
$instance['adaptiveHeight'] = $new_instance['adaptiveHeight'];
$instance['adaptiveHeightSpeed'] = $new_instance['adaptiveHeightSpeed'];

$instance['bp1'] = $new_instance['bp1'];
$instance['bp2'] = $new_instance['bp2'];
$instance['bp3'] = $new_instance['bp3'];

$instance['pager'] = $new_instance['pager'];
$instance['pagerType'] = $new_instance['pagerType'];

$instance['controls'] = $new_instance['controls'];
$instance['nextText'] = $new_instance['nextText'];
$instance['prevText'] = $new_instance['prevText'];
$instance['autoControls'] = $new_instance['autoControls'];
$instance['startText'] = $new_instance['startText'];
$instance['stopText'] = $new_instance['stopText'];
$instance['autoControlsCombine'] = $new_instance['autoControlsCombine'];

$instance['auto'] = $new_instance['auto'];
$instance['pause'] = $new_instance['pause'];
$instance['autoStart'] = $new_instance['autoStart'];
$instance['autoDirection'] = $new_instance['autoDirection'];
$instance['autoHover'] = $new_instance['autoHover'];
$instance['autoDelay'] = $new_instance['autoDelay'];

$instance['moveSlides'] = $new_instance['moveSlides'];


return $instance;
}
} // Class ups_widget ends here

// Register and load the widget
function ups_load_widget() {
	register_widget( 'ups_widget' );
}
add_action( 'widgets_init', 'ups_load_widget' );
