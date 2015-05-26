<?php
/*****************1.0 SECURITY AND PERFORMANCE FUNCTIONS*****************************/
	// 1.1 Prevent login errors - attacker prevention
		add_filter('login_errors', create_function('$a', "return null;"));
	
	// 1.2 Block malicious queries - Based on http://perishablepress.com/press/2009/12/22/protect-wordpress-against-malicious-url-requests/
		global $user_ID;
		
		if($user_ID) {
		  if(!current_user_can('level_10')) {
		    if (strlen($_SERVER['REQUEST_URI']) > 255 ||
		      strpos($_SERVER['REQUEST_URI'], "eval(") ||
		      strpos($_SERVER['REQUEST_URI'], "CONCAT") ||
		      strpos($_SERVER['REQUEST_URI'], "UNION+SELECT") ||
		      strpos($_SERVER['REQUEST_URI'], "base64")) {
		        @header("HTTP/1.1 414 Request-URI Too Long");
			@header("Status: 414 Request-URI Too Long");
			@header("Connection: Close");
			@exit;
		    }
		  }
		}
	// 1.3 remove junk from head
		remove_action('wp_head', 'rsd_link');
		remove_action('wp_head', 'wp_generator');
		remove_action('wp_head', 'feed_links', 2);
		remove_action('wp_head', 'index_rel_link');
		remove_action('wp_head', 'wlwmanifest_link');
		remove_action('wp_head', 'feed_links_extra', 3);
		remove_action('wp_head', 'start_post_rel_link', 10, 0);
		remove_action('wp_head', 'parent_post_rel_link', 10, 0);
		remove_action('wp_head', 'adjacent_posts_rel_link', 10, 0);
	
		//remove version info from head and feeds
		    function complete_version_removal() {
		    	return '';
		    }
		    add_filter('the_generator', 'complete_version_removal');
			

//add menu support
	add_theme_support( 'menus' );

//register menus
	function asmag_register_my_menus() {
  		register_nav_menus(
    		array( 'subpage-menu' => __( 'Subpage Menu' ), 'footer-menu' => __('Footer Menu'), 'issue-menu'=> __('Issue Menu'))
  		);
	}
	
// initiate register menus
	add_action( 'init', 'asmag_register_my_menus' );

//register thumbnail/featured image support
	add_theme_support( 'post-thumbnails' );

// name of the thumbnail, width, height, crop mode
	add_image_size( 'exclusive', 220, 110, true );
	add_image_size( 'homethumb', 60, 70, true );
	add_image_size( 'alumni', 150, 130, true );
	add_image_size( 'filterthumb', 235, 195, true);
	add_image_size( 'filterthumbbig', 520, 280, true);

//pagination function
	function asmag_pagination($prev = '�', $next = '�') {
    	global $wp_query, $wp_rewrite;
    	$wp_query->query_vars['paged'] > 1 ? $current = $wp_query->query_vars['paged'] : $current = 1;
    	$pagination = array(
    	    'base' => @add_query_arg('paged','%#%'),
    	    'format' => '',
    	    'total' => $wp_query->max_num_pages,
    	    'current' => $current,
    	    'prev_text' => __($prev),
    	    'next_text' => __($next),
    	    'type' => 'plain'
		);		
    	if( $wp_rewrite->using_permalinks() )
    	    $pagination['base'] = user_trailingslashit( trailingslashit( remove_query_arg( 's', get_pagenum_link( 1 ) ) ) . 'page/%#%/', 'paged' );
		
    	if( !empty($wp_query->query_vars['s']) )
    	    $pagination['add_args'] = array( 's' => get_query_var( 's' ) );
		
    	echo paginate_links( $pagination );
	};		

//register sidebars
	if ( function_exists('register_sidebar') )
		register_sidebar(array(
			'name'          => 'Homepage Sidebar',
			'id'            => 'homepage-sb',
			'description'   => '',
			'before_widget' => '<div id="homepage-widget" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h2 class="widgettitle">',
			'after_title'   => '</h2>' 
			));	
//Add Theme Options Page
	if(is_admin()){	
		require_once('assets/functions/asmag-theme-settings-basic.php');
	}
	
	//Collect current theme option values
		function asmag_get_global_options(){
			$asmag_option = array();
			$asmag_option 	= get_option('asmag_options');
		return $asmag_option;
		}
	
	//Function to call theme options in theme files 
		$asmag_option = asmag_get_global_options();
		
//Change Excerpt Length -- Add to functions.php
function asmag_new_excerpt_length($length) {
	return 10; //Change word count
}
add_filter('excerpt_length', 'asmag_new_excerpt_length');

//Add iframe shortcode
if ( !function_exists( 'iframe_embed_shortcode' ) ) {
	function iframe_embed_shortcode($atts, $content = null) {
		extract(shortcode_atts(array(
			'width' => '100%',
			'height' => '480',
			'src' => '',
			'frameborder' => '0',
			'scrolling' => 'no',
			'marginheight' => '0',
			'marginwidth' => '0',
			'allowtransparency' => 'true',
			'id' => '',
			'same_height_as' => ''
		), $atts));
		$src_cut = substr($src, 0, 35);
		if(strpos($src_cut, 'maps.google' )){
			$google_map_fix = '&output=embed';
		}else{
			$google_map_fix = '';
		}
		$return = '';
		if( $id != '' ){
			$id_text = 'id="'.$id.'" ';
		}else{
			$id_text = '';
		}
		$return .= '<div class="video-container"><iframe '.$id_text.' width="'.$width.'" height="'.$height.'" src="'.$src.$google_map_fix.'" frameborder="'.$frameborder.'" scrolling="'.$scrolling.'" marginheight="'.$marginheight.'" marginwidth="'.$marginwidth.'" allowtransparency="'.$allowtransparency.'" webkitAllowFullScreen allowFullScreen  wmode="transparent"></iframe></div>';
		// &amp;output=embed
		return $return;
	}
	add_shortcode('iframe', 'iframe_embed_shortcode');
}

//Add Volume/Issue Taxonomy
function create_my_taxonomies() {
	register_taxonomy('volume', array( 'post', 'page', 'accordion' ), array( 'hierarchical' => true, 'label' => 'Volume/Issue', 'query_var' => true, 'rewrite' => true));
} 

add_action('init', 'create_my_taxonomies', 0);

//Conditional for Taxonomy
	function asmag_in_taxonomy($tax, $term, $_post = NULL) {
		// if neither tax nor term are specified, return false
		if ( !$tax || !$term ) { return FALSE; }
		// if post parameter is given, get it, otherwise use $GLOBALS to get post
		if ( $_post ) {
		$_post = get_post( $_post );
		} else {
		$_post =& $GLOBALS['post'];
		}
		// if no post return false
		if ( !$_post ) { return FALSE; }
		// check whether post matches term belongin to tax
		$return = is_object_in_term( $_post->ID, $tax, $term );
		// if error returned, then return false
		if ( is_wp_error( $return ) ) { return FALSE; }
	return $return;
	}
//Remove height and width attributes from image inserts
function myprefix_image_downsize( $value = false, $id, $size ) {
    if ( !wp_attachment_is_image($id) )
        return false;

    $img_url = wp_get_attachment_url($id);
    $is_intermediate = false;
    $img_url_basename = wp_basename($img_url);

    // try for a new style intermediate size
    if ( $intermediate = image_get_intermediate_size($id, $size) ) {
        $img_url = str_replace($img_url_basename, $intermediate['file'], $img_url);
        $is_intermediate = true;
    }
    elseif ( $size == 'thumbnail' ) {
        // Fall back to the old thumbnail
        if ( ($thumb_file = wp_get_attachment_thumb_file($id)) && $info = getimagesize($thumb_file) ) {
            $img_url = str_replace($img_url_basename, wp_basename($thumb_file), $img_url);
            $is_intermediate = true;
        }
    }

    // We have the actual image size, but might need to further constrain it if content_width is narrower
    if ( $img_url) {
        return array( $img_url, 0, 0, $is_intermediate );
    }
    return false;
}

add_filter( 'image_downsize', 'myprefix_image_downsize', 1, 3 );



function get_the_volume($post) {
			wp_reset_query();
			$post = get_queried_object_id();
			$terms = get_the_terms($post, 'volume');
			$asmag_option = asmag_get_global_options();
			if(is_array($terms)) {
				$term_slugs = array();
				foreach( $terms as $term) {
					if($term->slug != 'feature') {
						$term_slugs[] = $term->slug;
					}
					$volume = implode('', $term_slugs); } 
				} else { $volume = $terms->slug; }
			if(isset($_GET['volume'])) {
				$volume = $_GET['volume'];
			}
			if ($volume == null) { 
			$volume = $asmag_option['asmag_current_issue']; } 
	return $volume;
}

function get_the_volume_name($post) {
	$post = get_queried_object_id();
	$terms = get_the_terms($post, 'volume');
	$asmag_option = asmag_get_global_options();
	
		if(is_array($terms)) {
			$term_names = array();
			foreach( $terms as $term) { 
				if($term->name != 'Feature') {
					$term_names[] = $term->name;
				}
			 } 
			 $volume_name = implode('', $term_names);
		} 
		
		else { $volume_name = $terms->name; }
		
		if(isset($_GET['volume'])) {
			$new_volume = $_GET['volume'];
			$new_volume_data = get_term_by('slug', $new_volume, 'volume');
			$volume_name = $new_volume_data->name;
		}

		if ($volume_name == null) { 
			$new_volume = $asmag_option['asmag_current_issue']; 
			$new_volume_data = get_term_by('slug', $new_volume, 'volume');
			$volume_name = $new_volume_data->name;
		} 
	
	return $volume_name;
}
	//***9.1 Menu Walker to add Foundation CSS classes
		class foundation_navigation extends Walker_Nav_Menu
		{
		      function start_el(&$output, $item, $depth = 0, $args = array(), $current_object_id = 0)
		      {
					global $wp_query;
					$indent = ( $depth ) ? str_repeat( "", $depth ) : '';
					
					$class_names = $value = '';
					
					// If the item has children, add the dropdown class for bootstrap
					if ( $args->has_children ) {
						$class_names = "has-flyout ";
					}
					$classes = empty( $item->classes ) ? array() : (array) $item->classes;
					
					$class_names .= join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item ) );
					$class_names = ' class="'. esc_attr( $class_names ) . ' page-id-' . esc_attr( $item->object_id ) .'"';
		           
					$output .= $indent . '<li id="menu-item-'. $item->ID . '"' . $value . $class_names .'>';
		           
		
		           	$attributes  = ! empty( $item->attr_title ) ? ' title="'  . esc_attr( $item->attr_title ) .'"' : '';
		           	$attributes .= ! empty( $item->target )     ? ' target="' . esc_attr( $item->target     ) .'"' : '';
		           	$attributes .= ! empty( $item->xfn )        ? ' rel="'    . esc_attr( $item->xfn        ) .'"' : '';
		           	$attributes .= ! empty( $item->url )        ? ' href="'   . esc_attr( $item->url        ) .'"' : '';
		           	// if the item has children add these two attributes to the anchor tag
		           	if ( $args->has_children ) {
						$attributes .= 'data-toggle="dropdown"';
					}
		
		            $item_output = $args->before;
		            $item_output .= '<a'. $attributes .'>';
		            $item_output .= $args->link_before .apply_filters( 'the_title', $item->title, $item->ID );
		            $item_output .= $args->link_after;
		            $item_output .= '</a>';
		            $item_output .= $args->after;
		
		            $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
		            }
		            
		function start_lvl( &$output, $depth = 0, $args = array() ) {
			$output .= "\n<ul class=\"flyout up\">\n";
		}
		            
		      	function display_element( $element, &$children_elements, $max_depth, $depth=0, $args, &$output )
		      	    {
		      	        $id_field = $this->db_fields['id'];
		      	        if ( is_object( $args[0] ) ) {
		      	            $args[0]->has_children = ! empty( $children_elements[$element->$id_field] );
		      	        }
		      	        return parent::display_element( $element, $children_elements, $max_depth, $depth, $args, $output );
		      	    }
		      	
		            
		}
		
		// Add a class to the wp_page_menu fallback
		function foundation_page_menu_class($ulclass) {
			return preg_replace('/<ul>/', '<ul class="nav-bar">', $ulclass, 1);
		}
		
		add_filter('wp_page_menu','foundation_page_menu_class');

	//***9.2 Menu Walker to create a dropdown menu for mobile devices	
		class mobile_select_menu extends Walker_Nav_Menu{
		    function start_lvl(&$output, $depth = 0, $args = array() ){
		      $indent = str_repeat("\t", $depth); // don't output children opening tag (`<ul>`)
		    }
		
		    function end_lvl(&$output, $depth = 0, $args = array()){
		      $indent = str_repeat("\t", $depth); // don't output children closing tag
		    }
		
		    function start_el(&$output, $item, $depth = 0, $args = array(), $current_object_id = 0){
		      // add spacing to the title based on the depth
		      $item->title = str_repeat("&nbsp;", $depth * 4).$item->title;
		
			//deleted '&' on $output; TG 8-13-2014
		      parent::start_el($output, $item, $depth, $args);
		
		      // no point redefining this method too, we just replace the li tag...
		      $output = str_replace('<li', '<option value="'. esc_attr( $item->url        ) .'"', $output);
		    }
		
		    function end_el(&$output, $item, $depth = 0, $args = array() ){
		      $output .= "</option>\n"; // replace closing </li> with the option tag
		    }
		}
		
function delete_magazine_transients($post_id) {
	global $post;
	if (isset($_GET['post_type'])) {		
		$post_type = $_GET['post_type'];
	}
	else {
		$post_type = $post->post_type;
	}
		$volumes = get_terms('volume');
		$issues = array();
		foreach($volumes as $volume) {
			$issues[] = $volume->slug;
		}	
	switch($post_type) {

		case 'post' :
		  	foreach ($issues as $issue) { 
		  		delete_transient('exclusives_' . $issue . '_query');
		  		delete_transient('alumni_' . $issue . '_query');
		  		delete_transient('toc_dropdown_' . $issue . '_query');
		  		delete_transient('front_' . $issue . '_query');
		  	}
		  	delete_transient('web_exclusives_query');
		break;
				
		case 'page' :
		  	foreach ($issues as $issue) { 
		  		delete_transient('features_' . $issue . '_query');
		  		delete_transient('front_features_' . $issue . '_query');
		  	}
		break;		
	}
} 
add_action('save_post','delete_magazine_transients');

	//***8.8 Create Title for <head> section
		function create_page_title() {
			if ( is_front_page() )  { 
				$page_title = bloginfo('description');
				$page_title .= print(' '); 
				$page_title .= bloginfo('name');
				$page_title .= print(' | Johns Hopkins University'); 
				} 
			
			elseif ( is_category() ) { 
				$page_title = single_cat_title();
				$page_title .= print(' | ');
				$page_title .= bloginfo('description');
				$page_title .= print(' '); 
				$page_title .= bloginfo('name');
				$page_title .= print(' | Johns Hopkins University'); 
		 
				}
		
			elseif (is_single() ) { 
				$page_title = single_post_title(); 
				$page_title .= print(' | ');
				$page_title .= bloginfo('description');
				$page_title .= print(' '); 
				$page_title .= bloginfo('name');
				$page_title .= print(' | Johns Hopkins University'); 
				}
		
			elseif (is_page() ) { 
				$page_title = single_post_title();
				$page_title .= print(' | ');
				$page_title .= bloginfo('description');
				$page_title .= print(' '); 
				$page_title .= bloginfo('name');
				$page_title .= print(' | Johns Hopkins University'); 
			}
			elseif (is_404()) {
				$page_title = print('Page Not Found'); 
				$page_title .= print(' | ');
				$page_title .= bloginfo('description');
				$page_title .= print(' '); 
				$page_title .= bloginfo('name');
				$page_title .= print(' | Johns Hopkins University'); 
			}

			else { 
				$page_title = bloginfo('description');
				$page_title .= print(' '); 
				$page_title .= bloginfo('name');
				$page_title .= print(' | Johns Hopkins University'); 
				} 
			return $page_title;
		}

function add_category_to_pages() {  
// Add tag metabox to page
register_taxonomy_for_object_type('post_tag', 'page'); 
// Add category metabox to page
register_taxonomy_for_object_type('category', 'page');  
}
add_action( 'admin_init', 'add_category_to_pages' );

add_filter( 'sbtt_button_markup', 'my_sbtt_filter' );
function my_sbtt_filter($button_markup) {
if ( !is_page('5130')) {
return "";
} else {
return $button_markup;
}
}


include_once (TEMPLATEPATH . '/assets/functions/asmag-metabox.php');
?>