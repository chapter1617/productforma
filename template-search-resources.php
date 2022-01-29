<?php

/* Template Name: Resource Search Results */

global $post;

// Retrieve applicable query parameters.
$search_query = isset( $_GET['searchwp'] ) ? sanitize_text_field( $_GET['searchwp'] ) : null;
$search_page  = isset( $_GET['swppg'] ) ? absint( $_GET['swppg'] ) : 1;

// Perform the search.
$search_results    = [];
$search_pagination = '';
if ( ! empty( $search_query ) && class_exists( '\\SearchWP\\Query' ) ) {
	$searchwp_query = new \SearchWP\Query( $search_query, [
		'engine' => 'resources', // The Engine name.
		'fields' => 'all',          // Load proper native objects of each result.
		'page'   => $search_page,
	] );

	$search_results = $searchwp_query->get_results();

	$search_pagination = paginate_links( array(
		'format'  => '?swppg=%#%',
		'current' => $search_page,
		'total'   => $searchwp_query->max_num_pages,
	) );
}

get_header(); 

if($_SESSION['region'] == 'ca-fr') {
	$title = '// Résultats de recherche';
} elseif ($_SESSION['region'] == 'us-sp') {
	$title = '// Resultados de la búsqueda';
} else {
	$title = '// Search Results';
}
?>

<div class="short-banner">
	<div class="banner-content">
		<h1><?php echo $title; ?></h1>
	</div>
</div>
<?php 
	$placeholder = get_field('field_5f0d0d377ae7d', 'option');
	$title = get_field('field_5f0d187d2d8aa', 'option');
	if($_SESSION['region'] == 'ca-fr') {
    	$trans_placeholder = $placeholder['search_placeholder_text_fr'];
    	$trans_title = $title['resource_search_results_page_title_fr'];
    } elseif ( $_SESSION['region'] == 'us-sp' ) {
    	$trans_placeholder = $placeholder['search_placeholder_text_sp'];
    	$trans_title = $title['resource_search_results_page_title_sp'];
    } else {
    	$trans_placeholder = $placeholder['search_placeholder_text_en'];
    	$trans_title = $title['resource_search_results_page_title_en'];
    }
?>
<div id="primary" class="content-container">
	<div class="above-content-spacer"></div>
	<!-- BEGIN Supplemental Engine Search form -->
	<form role="search" method="get" class="search-form supplemental-search-form"
	action="<?php echo site_url( 'resource-search-results/' ); ?>">
		<label>
			<span class="screen-reader-text">
			<?php echo _x( 'Search for:', 'label' ) ?>
			</span>
			<input type="search" class="search-field"
			name="searchwp"
			placeholder="<?php echo esc_attr_x( $trans_placeholder, 'placeholder' ) ?>"
			value="<?php echo isset( $_GET['searchwp'] ) ? esc_attr( $_GET['searchwp'] ) : '' ?>"
			title="<?php echo esc_attr_x( 'Search for:', 'label' ) ?>" />
		</label>
		<input type="submit" class="search-submit supplemental-search-submit"
			value="<?php echo esc_attr_x( '&#xf002;', 'submit button' ) ?>" />
	</form>
	<!-- END Supplemental Engine Search form -->
</div>

<div class="resource-container content-container search-results-container">
	<?php if ( ! empty( $search_query ) && ! empty( $search_results ) ) : ?>
		<h4><?php printf( __( '<span>'.$trans_title.':</span> %s' ), esc_html( $search_query ) ); ?></h4>
		<?php 
			$i = 0;
			foreach ( $search_results as $search_result ) :
				$i++;
				$post = $search_result;
		        if($_SESSION['region'] == 'ca-fr' or $_SESSION['region'] == 'ca-en') {
		        	$resource_file_ca_en = get_field('resource_file_ca_en');
		        	$resource_file_ca_fr = get_field('resource_file_ca_fr');
		        	// $not_available = 'That resource does not exist or is not available for your region';
		        	// $not_available = 'Cette ressource n\'existe pas ou n\'est pas disponible pour votre région';
		        } elseif ( $_SESSION['region'] == 'us-sp' or $_SESSION['region'] == 'us-en' ) {
		        	$resource_file_us_en = get_field('resource_file_us_en');
		        	$resource_file_us_sp = get_field('resource_file_us_sp');
		        	// $not_available = 'Ese recurso no existe o no está disponible para su región';
		        	// $not_available = 'That resource does not exist or is not available for your region';	
		        } else {
		        	//  do nothing
		        }
		        if($resource_file_ca_en) {
		        	echo '<div class="resource-file">';
		        	echo '<a target="_blank" href="'.$resource_file_ca_en['url'].'">';
	        		echo '<i class="fad fa-file-pdf"></i><span><b>';
	        		the_title();
	        		echo '</b></span><br>';
	        		echo '<span class="resource-result-lang">(English)<span>';
	        		echo '</a>';
	        		echo '</div>';
	        	}
	        	if($resource_file_ca_fr) {
		        	echo '<div class="resource-file">';
		        	echo '<a target="_blank" href="'.$resource_file_ca_fr['url'].'">';
	        		echo '<i class="fad fa-file-pdf"></i><span><b>';
	        		the_title();
	        		echo '</b></span><br>';
	        		echo '<span class="resource-result-lang">(Français)<span>';
	        		echo '</a>';
	        		echo '</div>';
	        	}
	        	if($resource_file_us_en) {
		        	echo '<div class="resource-file">';
		        	echo '<a target="_blank" href="'.$resource_file_us_en['url'].'">';
	        		echo '<i class="fad fa-file-pdf"></i><span><b>';
	        		the_title();
	        		echo '</b></span><br>';
	        		echo '<span class="resource-result-lang">(English)<span>';
	        		echo '</a>';
	        		echo '</div>';
	        	}
	        	if($resource_file_us_sp) {
		        	echo '<div class="resource-file">';
		        	echo '<a target="_blank" href="'.$resource_file_us_sp['url'].'">';
	        		echo '<i class="fad fa-file-pdf"></i><span><b>';
	        		the_title();
	        		echo '</b></span><br>';
	        		echo '<span class="resource-result-lang">(Española)<span>';
	        		echo '</a>';
	        		echo '</div>';
	        	}
	        	// else {
	        	// 	if($i == 1) {
	        	// 		echo $not_available;	
	        	// 	} else {

	        	// 	}
	        	// }
				wp_reset_postdata();
			endforeach; 
		?>
</div>
<div class="content-container">
		<?php if ( $searchwp_query->max_num_pages > 1 ) : ?>
			<div class="navigation pagination" role="navigation">
				<h2 class="screen-reader-text">Results navigation</h2>
				<div class="nav-links"><?php echo wp_kses_post( $search_pagination ); ?></div>
			</div>
		<?php endif; ?>
	<?php elseif ( ! empty( $search_query ) ) : ?>
		<p>No results found, please search again.</p>
	<?php endif; ?>
</div>

<?php get_footer(); ?>