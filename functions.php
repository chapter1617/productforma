<?php

function theme_supports_setup_functions() {
  
  add_theme_support( 'post-thumbnails');
  add_theme_support( 'custom-logo');

  register_nav_menus( array(
    'main-nav' => __( 'Main Navigation', 'themify' ),
  ));
}
add_action( 'after_setup_theme', 'theme_supports_setup_functions' );

function news_change_post_label() {
    global $menu;
    global $submenu;
    $menu[5][0] = 'News';
    $submenu['edit.php'][5][0] = 'News';
    $submenu['edit.php'][10][0] = 'Add News';
    $submenu['edit.php'][16][0] = 'News Tags';
}
function news_change_post_object() {
    global $wp_post_types;
    $labels = &$wp_post_types['post']->labels;
    $labels->name = 'News';
    $labels->singular_name = 'News';
    $labels->add_new = 'Add News';
    $labels->add_new_item = 'Add News';
    $labels->edit_item = 'Edit News';
    $labels->new_item = 'News';
    $labels->view_item = 'View News';
    $labels->search_items = 'Search News';
    $labels->not_found = 'No News found';
    $labels->not_found_in_trash = 'No News found in Trash';
    $labels->all_items = 'All News';
    $labels->menu_name = 'News';
    $labels->name_admin_bar = 'News';
}
add_action( 'admin_menu', 'news_change_post_label' );
add_action( 'init', 'news_change_post_object' );


/**
* Start a new Session
*/
function register_my_session(){
  if( ! session_id() ) {
    session_start();
   // echo $_SESSION['region'];
	//$_SESSION['region'] = '';
	//if(($_SESSION['region'] == NULL || !isset($_SESSION['region'])))
	if(($_SESSION['region'] == NULL || !isset($_SESSION['region'])))
	{
		$_SESSION['region_default'] = false;
		$_SESSION['region'] = $_SESSION['region'];
    } 
	else 
	{
		$_SESSION['region_default'] = false;
		$_SESSION['region'] = $_SESSION['region'];
    }
  }
}
add_action('init', 'register_my_session');

// Determine Menu Items to Show
function translate_menu($items, $args) {
    if( $args->menu == 'main-menu' ){
      $burl       = get_bloginfo('url');
      $pre_html   = '<li class="menu-item menu-item-type-post_type_archive menu-item-object-products"><a class="elementor-item" href="';
      $post_html  = '</a></li>';
      if($_SESSION['region'] == 'ca-fr') {
        $products   = $pre_html.$burl.'/products">Produits'.$post_html;
        $news       = $pre_html.get_permalink(9300).'">Nouvelles'.$post_html;
        $resources  = $pre_html.$burl.'/resources">SDS/TDS Ressources'.$post_html;
        $faqs       = $pre_html.$burl.'/faqs">FAQs'.$post_html;
        $brands     = $pre_html.get_permalink(13461).'">Marques'.$post_html;
        $about      = $pre_html.get_permalink(14819).'">À propos de nous'.$post_html;
      }
      elseif($_SESSION['region'] == 'us-sp') {
        $products   = $pre_html.$burl.'/products">Productos'.$post_html;
        $news       = $pre_html.get_permalink(9300).'">Noticias'.$post_html;
        $resources  = $pre_html.$burl.'/resources">SDS/TDS Recursos'.$post_html;
        $faqs       = $pre_html.$burl.'/faqs">FAQs'.$post_html;
        $brands     = $pre_html.get_permalink(13454).'">Marcas'.$post_html;
        $about      = $pre_html.get_permalink(14825).'">Sobre nosotros'.$post_html;
      }
      elseif($_SESSION['region'] == 'us-en') {
        $products   = $pre_html.$burl.'/products">Products'.$post_html;
        $news       = $pre_html.get_permalink(9300).'">News'.$post_html;
        $resources  = $pre_html.$burl.'/resources">SDS/TDS Resources'.$post_html;
        $faqs       = $pre_html.$burl.'/faqs">FAQs'.$post_html;
        $brands     = $pre_html.get_permalink(13472).'">Brands'.$post_html;
        $about      = $pre_html.get_permalink(9303).'">About Us'.$post_html;
      }
      else {
        $products   = $pre_html.$burl.'/products">Products'.$post_html;
        $news       = $pre_html.get_permalink(9300).'">News'.$post_html;
        $resources  = $pre_html.$burl.'/resources">SDS/TDS Resources'.$post_html;
        $faqs       = $pre_html.$burl.'/faqs">FAQs'.$post_html;
        $brands     = $pre_html.get_permalink(9459).'">Brands'.$post_html;
        $about      = $pre_html.get_permalink(9303).'">About Us'.$post_html;
      }

      $items = $products . $news . $resources . $faqs . $brands . $about/* . $items*/;
    }

    return $items;
}
add_filter( 'wp_nav_menu_items', 'translate_menu', 10, 2 );


// Admin Dashboard Restrictions for Admins > 1
add_action( 'admin_menu', 'remove_menus', 999 );
function remove_menus(){
  $adminID = get_current_user_id();
  if($adminID !== 1) {
    remove_menu_page( 'index.php' );                  //Users
    remove_menu_page( 'tools.php' );                  //Tools
    remove_menu_page( 'profile.php' );
  }
}

function admin_default_page() {
   return 'wp-admin/edit.php?post_type=products';
}
add_filter('login_redirect', 'admin_default_page'); 


// Re-order Admin Menu
function reorder_admin_menu( $__return_true ) {
    return array(
         'index.php', // Dashboard
         'edit.php?post_type=products',
         'separator1', // --Space--
         'edit.php', // Posts
         'separator2', // --Space--
         'edit.php?post_type=resources',
         'edit.php?post_type=faqs',
         'edit.php?post_type=page', // Pages 
         'edit.php?post_type=representatives',
         'edit.php?post_type=distributors',
         'upload.php', // Media
         'separator3', // --Space--
         'admin.php?page=acf-options-website-options',
         'separator4', // --Space--
         'themes.php', // Appearance
         'separator5', // --Space--
         'edit-comments.php', // Comments 
         'users.php', // Users
         'plugins.php', // Plugins
         'tools.php', // Tools
         'options-general.php', // Settings
   );
}
add_filter( 'custom_menu_order', 'reorder_admin_menu' );
add_filter( 'menu_order', 'reorder_admin_menu' );


// Image Sizes
add_image_size('admin-thumbnail', 200, 200);
add_image_size('Product Photos', 380, 600);


// Enqueue CSS
function site_enqueue_scripts() {

  function css_scripts_version( $filename ) {
      $filename = get_template_directory() . $filename;
      if ( is_file( $filename ) ) {
          return filemtime( $filename );
      }

      return false;
  }
  wp_enqueue_style( 'slick-styles', '//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css' );
  wp_enqueue_style( 'slick-styles', '//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick-theme.css' );
  wp_enqueue_style( 'theme-style', get_stylesheet_directory_uri() . '/style.css', css_scripts_version( '/style.css' ), 'all' ); 
  wp_enqueue_style( 'tablets', get_template_directory_uri() . '/assets/css/1024.css', array( 'theme-style' ), css_scripts_version( '/style.css' ), 'screen and (max-width: 1024px)' );
  wp_enqueue_style( 'mobile', get_template_directory_uri() . '/assets/css/767.css', array( 'theme-style', 'tablets' ), css_scripts_version( '/style.css' ), 'screen and (max-width: 767px)' );
  // wp_enqueue_style( 'carousel', get_template_directory_uri() . '/assets/css/touchcarousel.css', array(), '1.3', 'all'  );
  // wp_enqueue_style( 'carouselskin', get_template_directory_uri() . '/assets/css/minimal-light-skin.css', array('carousel'), '1.3', 'all'  );

  // JS
  // wp_enqueue_script( 'touchcarousel', get_template_directory_uri() . '/assets/js/jquery.touchcarousel-1.3.min.js', array('jquery'), '1.3', true );
  wp_enqueue_script( 'theme-general-scripts', get_template_directory_uri() . '/assets/js/theme.script.js', array( 'jquery' ), '1.0', true );
  wp_enqueue_script( 'fontawesome', get_template_directory_uri() . '/assets/js/all.min.js', null, '5.11.2', true );

  // Google Fonts

  wp_enqueue_style( 'google-fonts', 'https://fonts.googleapis.com/css?family=Roboto|Roboto+Condensed&display=swap', false ); 

  wp_register_style( 'canadian-specific-styles', get_stylesheet_directory_uri() . '/assets/css/canadian-specific-styles.css' );
  wp_register_style( 'us-specific-styles', get_stylesheet_directory_uri() . '/assets/css/us-specific-styles.css' );

  if($_SESSION['region'] == 'ca-en' or $_SESSION['region'] == 'ca-fr') {
    wp_enqueue_style('canadian-specific-styles');
  } else {
    wp_enqueue_style('us-specific-styles');
  }

  /* Clean WordPress header */
  remove_action( 'wp_head', 'wp_generator' );
  remove_action( 'wp_head', 'rsd_link' );
  remove_action( 'wp_head', 'wlwmanifest_link' );
  remove_action( 'wp_head', 'wp_shortlink_wp_head', 10, 0 );
  remove_action( 'wp_head', 'feed_links', 2 );
  remove_action( 'wp_head', 'feed_links_extra', 3 );
  remove_action( 'wp_head', 'start_post_rel_link', 10, 0 );
  remove_action( 'wp_head', 'parent_post_rel_link', 10, 0 );
  remove_action( 'wp_head', 'index_rel_link' );
  remove_action( 'wp_head', 'adjacent_posts_rel_link', 10, 0 );
  remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head, 10, 0' );
  remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
  remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
  remove_action( 'wp_print_styles', 'print_emoji_styles' );
  remove_action( 'admin_print_styles', 'print_emoji_styles' );

  wp_register_script( "theme-script", get_template_directory_uri() . '/assets/js/theme.script.js', array('jquery') );
  //wp_register_script( "product-ajax-filter", get_template_directory_uri() . '/assets/js/products-ajax-filter.js', array('jquery') );
  wp_localize_script( 'theme-script', 'ajax_obj', array( 'url' => admin_url( 'admin-ajax.php' )));
  wp_enqueue_script( 'jquery' );
  wp_enqueue_script( 'theme-script' );
  //wp_enqueue_script( 'product-ajax-filter' );
  
  add_action( 'init', 'my_script_enqueuer' );
}
add_action( 'wp_enqueue_scripts', 'site_enqueue_scripts' );


function admin_style() {
  wp_enqueue_style('admin-styles', get_template_directory_uri().'/assets/css/admin.css');
}
add_action('admin_enqueue_scripts', 'admin_style');

// Add Region css class to body tag
function add_body_classes($classes) {
    if($_SESSION['region'] == 'us-en') {
      $classes[] = 'region-us-en';  
    }
    elseif($_SESSION['region'] == 'us-sp') {
      $classes[] = 'region-us-sp';
    }
    elseif($_SESSION['region'] == 'ca-en') {
      $classes[] = 'region-ca-en';
    }
    elseif($_SESSION['region'] == 'ca-fr') {
      $classes[] = 'region-ca-fr';
    }
    else {
      $classes[] = 'region-us-en';
    }
    return $classes;
}

add_filter('body_class', 'add_body_classes');


///////////////////////////////////////
// Register Widgets
///////////////////////////////////////
add_action( 'widgets_init', 'my_register_sidebars' );

function my_register_sidebars() {
  
  register_sidebar(array(
    'name' => 'Sidebar',
    'id' => 'sidebar',
    'description' => 'Default sidebar',
    'before_widget' => '<div id="%1$s" class="widget %2$s">',
    'after_widget' => '</div>',
    'before_title' => '<h4 class="widgettitle">',
    'after_title' => '</h4>',
  ));
}

function my_acf_init() {
  acf_update_setting('google_api_key', 'AIzaSyCmMukqAXbpghklG-azfYf836slpcsbquI');
}
add_action('acf/init', 'my_acf_init');

// Copy Product Number to Admin Field
add_action('admin_footer', function () {
?>
<script type="text/javascript">
  $('#acf-field_5d54a3f45e019').on('keyup', e => {
    $('#acf-field_5d64a2f090ea9').val(parseFloat(e.target.value));
  })
</script>
<?php
});

// Use for multi-dimensional arrays
function in_array_r($needle, $haystack, $strict = false) {
    foreach ($haystack as $item) {
        if (($strict ? $item === $needle : $item == $needle) || (is_array($item) && in_array_r($needle, $item, $strict))) {
            return true;
        }
    }
    return false;
}

/**
* Handle Regions on AJAX Request
*/
function handle_region_on_click() {
  global $post;

  $_SESSION['region'] = $_REQUEST['region'];
  $_SESSION['region_default'] = '';
  echo $_SESSION['region'];
  die();
}
add_action("wp_ajax_handle_region_on_click", "handle_region_on_click");
add_action("wp_ajax_nopriv_handle_region_on_click", "handle_region_on_click");


/**
* Handle products in Shortcode Products
*/
function get_ajax_products_by_terms() {
  $selected_brands = isset($_POST['brands']) ? $_POST['brands'] : array();
  $selected_cats = isset($_POST['categories']) ? $_POST['categories'] : array();

  if (empty($selected_brands)) {
    $taxonomies = get_terms(array(
      'taxonomy'   => 'brands',
      'hide_empty' => true,
      'parent' => 0,
    ));
  }

  ob_start();
  require_once __DIR__ . '/inc/template-parts/ajax-shortcode-products.php';
  echo ob_get_clean();  
  die();
}
add_action("wp_ajax_get_ajax_products_by_terms", "get_ajax_products_by_terms");
add_action("wp_ajax_nopriv_get_ajax_products_by_terms", "get_ajax_products_by_terms");


// Add Settings Options Pages
if( function_exists('acf_add_options_page') ) {

    acf_add_options_page(array(
      'page_title'  => 'Pro Form Options',
      'menu_title'  => 'Pro Form Options',
      'menu_slug'   => 'pro-form-options',
      'capability'  => 'edit_posts',
      'redirect'    => false,
      'update_button'   => __('Update', 'acf'),
      'updated_message' => __("Options Updated", 'acf'),
    ));

    acf_add_options_sub_page(array(
        'page_title'  => 'Common Translations',
        'menu_title'  => 'Translations',
        'parent_slug' => 'pro-form-options',
    ));

    acf_add_options_sub_page(array(
        'page_title'  => 'Resource Translations',
        'menu_title'  => 'Resource Translations',
        'parent_slug' => 'edit.php?post_type=resources',
    ));

    acf_add_options_sub_page(array(
        'page_title'  => 'FAQ Translations',
        'menu_title'  => 'FAQ Translations',
        'parent_slug' => 'edit.php?post_type=faqs',
    ));

    acf_add_options_sub_page(array(
        'page_title'  => 'Reps Translations',
        'menu_title'  => 'Reps Translations',
        'parent_slug' => 'edit.php?post_type=representativs',
    ));

    acf_add_options_sub_page(array(
        'page_title'  => 'Distributors Translations',
        'menu_title'  => 'Distributors Translations',
        'parent_slug' => 'edit.php?post_type=distributors',
    ));
}


/**
* Get product variations
*/
function proform_get_clicked_product_variation() {
  $post_id = $_POST['post_id'];
  $variations = get_field("product_details", $post_id);
  $f_region = strtolower(str_replace('-', '_', $_SESSION['region']));
  $default_features = get_field('product_features', $post_id)['features_' . $f_region];
  $var_selected = $_POST['variation_id'];
  $product_details = array();
  $product_name = $_POST['product_name'];
  $variation_sold_separately = get_field('variation_sold_separately', $post_id);

  foreach ($variations as $variation) {
    if ($var_selected == $variation['part_number']) {
      $product_details['name'] = $product_name . ' - ' . $variation['part_number'];
      $product_details['image_src'] .= $variation['image']['url'];
      $product_details['features'] .= ($variation['features'] !== '') ? $variation['features'] : $default_features;
      $product_details['related_resources'] .= '';
      $resource_type = (strpos($resource->post_title, 'TDS') === false) ? 'Safety Data Sheet' : 'Technical Data Sheet';
      
     if($variation['variation_sold_separately']){
        $var_product_link = $variation['variation_product_sold_separately'];
        if($var_product_link) {
          $product_details['sold_separately']  = '<i class="fad fa-exclamation-circle"></i>';
          $product_details['sold_separately'] .= '<a href="' . get_permalink($var_product_link[0]->ID) . '">';
          if($variation['variation_sold_separately_custom_text'][strtolower($_SESSION['region']) . '_sold_separately_custom_text'] != '') {
            $product_details['sold_separately'] .= $variation['variation_sold_separately_custom_text'][strtolower($_SESSION['region']) . '_sold_separately_custom_text'];
          } 
          else {
            $product_details['sold_separately'] = $var_product_link[0]->post_title . ' sold separately';
          }
          $product_details['sold_separately'] .= '</a>';
        }
      }

      foreach($variation['part_number_related_resources'] as $sds) {
        $sds_url = get_field('resource_file_'.str_replace('-', '_', $_SESSION['region']), $sds->ID); 
        $type = get_the_terms($sds->ID, 'type');
              
        if($type) {
          foreach($type as $t) {
            $file_type = $t->name;
          }
        } else {
          $file_type = 'Safety Data Sheet';
        } 

        $product_details['related_resources'] .= '<div class="product_related_resources"><a target="_blank" href="'. $sds_url['url'] . '"><i class="fad fa-file-pdf"></i><span>' . get_the_title($sds->ID) . '<br>' . $file_type . '</span></a></div>';
      }
    }
  }

  echo json_encode($product_details);
  die();
}
add_action("wp_ajax_proform_get_clicked_product_variation", "proform_get_clicked_product_variation");
add_action("wp_ajax_nopriv_proform_get_clicked_product_variation", "proform_get_clicked_product_variation");


/**
* Including Critical Files
*/
include_once 'inc/cpt/custom_post_type-products.php';
include_once 'inc/cpt/custom_post_type-resources.php';
include_once 'inc/cpt/custom_post_type-representatives.php';
include_once 'inc/cpt/custom_post_type-distributors.php';
include_once 'inc/cpt/custom_post_type-faqs.php';

include_once 'inc/taxonomy/taxonomy-brands.php';
include_once 'inc/taxonomy/taxonomy-markets.php';
include_once 'inc/taxonomy/taxonomy-types.php';
include_once 'inc/taxonomy/taxonomy-rep-regions.php';

include_once 'inc/functions/extend-product-admin-search.php';
include_once 'inc/functions/gravity-forms-functions.php';

include_once 'inc/shortcodes/shortcodes-news.php';
include_once 'inc/shortcodes/shortcodes-brands-pages.php';
include_once 'inc/shortcodes/shortcodes-resources.php';
include_once 'inc/shortcodes/shortcodes-faqs.php';
include_once 'inc/shortcodes/shortcodes-distributors.php';
include_once 'inc/shortcodes/shortcodes-header.php';
include_once 'inc/shortcodes/shortcodes-searchforms.php';
include_once 'inc/shortcodes/shortcodes-products.php';
include_once 'inc/shortcodes/shortcodes-representatives.php';
include_once 'inc/shortcodes/shortcodes-catalog.php';

// Update Brands Taxonomy Query
add_action( 'pre_get_posts', function( $query) {
    if ( !is_admin() && $query->is_main_query() && $query->is_tax( 'brands' ) ) {
        $query->set( 'posts_per_page', -1 );
        $query->set( 'orderby', 'name'  );
        $query->set( 'order', 'ASC' );
    }
    if($_SESSION['region'] == 'ca-en' or $_SESSION['region'] == 'ca-fr') {
      $brand_ids = array(18, 20, 25, 23);
    } else {
      $brand_ids = array(19, 21, 26, 24, 22);
    }
    if ( !is_admin() && $query->is_main_query() && is_post_type_archive( 'products' ) ) {
        $query->set( 'posts_per_page', -1 );
        $query->set( 'orderby', 'name'  );
        $query->set( 'order', 'ASC' );
        $tax_query[] = array(
          array (
            'taxonomy'=> 'brands',
            'field'   => 'term_id',
            'terms'   => $brand_ids,
          ),
        );
        $query->set('tax_query', array($tax_query));
    }
});

// Update Elementor Query to Filter News Posts by Region Setting (ACF Checkbox Field)
/*
add_action( 'elementor/query/news_posts', function( $query ) {
$meta_query = array();
$meta_query = $query->get( 'meta_query' );
$meta_query[] = ['key' => 'field_5e727ffb58776','value' => '','compare' => 'LIKE'];
$query->set( 'meta_query', $meta_query );

});
*/
add_action( 'elementor/query/news_posts', function( $query ) 
{	
	global $_SESSION;
	$new_meta_query = $query->get( 'meta_query' );
	
	if ( ! $new_meta_query ) {
		$new_meta_query = [];
	}
	
	$new_meta_query[] = array(
                        'key' => 'region',
                        'value' => $_SESSION['region'],
                        'compare' => 'like',
                        );  
    //Last but not least: put it back into the $query
    $query->set('meta_query',$new_meta_query); 
    //$query->set('ignore_sticky_posts',0); 
} 
);

add_action( 'elementor/query/recent_posts', function( $query ) 
{	
	global $_SESSION;
	$new_meta_query = $query->get( 'meta_query' );
	
	if ( ! $new_meta_query ) {
		$new_meta_query = [];
	}
	
	$new_meta_query[] = array(
                        'key' => 'region',
                        'value' => $_SESSION['region'],
                        'compare' => 'like',
                        );  
    //Last but not least: put it back into the $query
    $query->set('meta_query',$new_meta_query); 
    //$query->set('ignore_sticky_posts',0); 
} 
);
