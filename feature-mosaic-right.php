<?php
/*
Template Name: Feature - Mosaic Right Caption, No Author */
?>
<?php get_header(); ?>
	<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); 
		$page = get_queried_object();
		$page_name = $page->post_name; 
		 ?>
	
	<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/assets/stylesheets/features/<?php echo $page_name; ?>.css">
	<div id="feature-head">
	
		<div class="intro-container row">
		<?php
if (is_page( 'Past in the Present' ) ){
echo '<div class="eight columns offset-by-six mobile-twelve">';
}else{ 
echo '<div class="six columns offset-by-six mobile-twelve">';
}
?>
		<div class="feature-intro">
		<?php if(has_post_thumbnail()): ?>
		<div class="nonbackground"><?php $image = wp_get_attachment_url( get_post_thumbnail_id() ); ?>
		<img src="<?php echo $image; ?>"></div> <?php endif; ?>
			<div class="textblock">
				<?php if ( get_post_meta($post->ID, 'fancy_title', true) ) : ?><div class="fancytitle"><?php echo get_post_meta($post->ID, 'fancy_title', true); ?></div><?php endif; ?>
				<h3 class="featuretitle"><?php the_title(); ?></h3>
				<h4 class="tagline"><?php if ( get_post_meta($post->ID, 'ecpt_tagline', true) ) : ?>  <?php echo get_post_meta($post->ID, 'ecpt_tagline', true); ?><?php endif; ?></h4>
				<p class="othercredits"><?php if ( get_post_meta($post->ID, 'ecpt_other_credits', true) ) : ?>  <?php echo get_post_meta($post->ID, 'ecpt_other_credits', true); ?><?php endif; ?></p>
			</div>
			<div class="introcopy">	
				<div class="pullquote"><?php if ( get_post_meta($post->ID, 'ecpt_pull_quote', true) ) : ?>  <?php echo get_post_meta($post->ID, 'ecpt_pull_quote', true); ?><?php endif; ?></div>
			</div><!--End intro copy-->
		</div><!--end feature-intro-->
		</div><!--end columns offset-->
		</div><!--end intro-containter-->
		
	</div><!--end feature-head-->
	<div class="headerbreak"></div>
	
	<div id="container-mid">
			<ul id="mosaic" class="clearfix">	
		<!-- Set argument to pull image attachments -->
			<?php $mosaic_args = array(
					'post_type' => 'attachment',
					'numberposts' => -1,
					'post_status' => null,
					'post_parent' => $post->ID
					); 
				$mosaic_attachments = get_posts($mosaic_args);
					if ($mosaic_attachments) {
						foreach ($mosaic_attachments as $mosaic_attachment) {
							$mosaic_link = wp_get_attachment_image_src($mosaic_attachment->ID, 'full', false);
							$mosaic_caption = $mosaic_attachment->post_excerpt;
							$mosaic_description = $mosaic_attachment->post_excerpt;
							$mosaic_dimensions = $mosaic_attachment->menu_order;
							echo $mosaic_description;
							echo '<li class="item size-' . $mosaic_dimensions;
							echo '">
									<a href="' . $mosaic_link[0];
							echo '" class="lightbox">
										<img src="' . $mosaic_link[0];
							echo '" title="' . $mosaic_caption;
							echo '" /></a></li>';		
		                    }
		                } ?>

		</ul><!--End #mosaic -->
	<div id="feature" class="row">
	    
		<div class="postmaterial">
			
		<?php the_content(); ?>
		
		</div><!--End postmaterial -->
	
	<?php endwhile; ?> <?php endif; ?>
<?php if (is_page( 'Past in the Present' ) ) { ?>
<script type="text/javascript" src="<?php bloginfo('template_url'); ?>/assets/javascripts/jquery.photomosaic.js"></script>
			<script>
    var $q = jQuery.noConflict();
			$q(function(){

      var $mosaic = $q('#mosaic');
     $q(document).ready(function(){
        $mosaic.photoMosaic({
        	input : 'html',
        	columns : 3,
        	modal_name : 'lightbox',
        	external_links : true,
        	random : true
        });
    });
	$q("a.popup").fancybox({
		openEffect	: 'fade',
		closeEffect	: 'fade',
		helpers : {
		title: {
			type: 'inside',
		},
        overlay : {
            css : {
                'background' : 'rgba(43, 42, 46, 0.7)',
            }
        }
    }
	});
    });
			</script>

<?php } ?>			
		<script type="text/javascript" src="<?php bloginfo('template_url'); ?>/assets/javascripts/jquery.photomosaic.js"></script>
			<script>
    var $q = jQuery.noConflict();
			$q(function(){

      var $mosaic = $q('#mosaic');
     $q(document).ready(function(){
        $mosaic.photoMosaic({
        	input : 'html',
        	columns : 6,
        	modal_name : 'lightbox',
        	external_links : true,
        	random : true
        });
    });
	$q("a.popup").fancybox({
		openEffect	: 'fade',
		closeEffect	: 'fade',
		helpers : {
		title: {
			type: 'inside',
		},
        overlay : {
            css : {
                'background' : 'rgba(43, 42, 46, 0.7)',
            }
        }
    }
	});
    });
			</script>

<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery-backstretch/2.0.4/jquery.backstretch.min.js"></script>
<?php
if (is_page( 'In Plain Air' ) ){
echo '<script>
  jQuery.backstretch("http://krieger.jhu.edu/magazine/wp-content/themes/asmagflex/assets/images/v12n1/plain-air_JHU6240_a.jpg");
</script>';
}elseif (is_page('Past in the Present') ){ 
echo '<script>
  jQuery.backstretch("http://krieger.jhu.edu/magazine/wp-content/themes/asmagflex/assets/images/v12n1/retrospective.jpg");
</script>';
}
?>
<style>
	/* Medium screens */
@media only screen and (max-width: 1279px) and (min-width: 768px) { .feature-intro {
margin: 0px;
padding: 5px;
}
}

/* Small screens */
@media only screen and (max-width: 767px) { .feature-intro {
margin: 0px;
padding: 5px;
} }

</style>

<?php locate_template('parts/footer_feature.php', true, false);				
 get_footer(); ?>