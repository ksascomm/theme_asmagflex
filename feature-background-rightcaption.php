<?php
/*
Template Name: Feature - Background Right Caption */
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
echo '<div class="seven columns offset-by-six mobile-twelve">';
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
				<p class="credit">By&nbsp;<?php the_author(); ?></p>
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
	<img src="<?php echo get_template_directory_uri(); ?>/assets/images/v12n2/pano-baseball-web.jpg" alt="field">

	<div id="feature" class="row">
	    
		<div class="postmaterial">
			
		<?php the_content(); ?>
		
		</div><!--End postmaterial -->
	
	<?php endwhile; ?> <?php endif; ?>

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