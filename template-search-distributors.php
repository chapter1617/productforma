<?php

/* Template Name: Distributor Search Results */

global $post;

// Retrieve applicable query parameters.
$search_query = isset( $_GET['searchwp'] ) ? sanitize_text_field( $_GET['searchwp'] ) : null;
$search_page  = isset( $_GET['swppg'] ) ? absint( $_GET['swppg'] ) : 1;

// Perform the search.
$search_results    = [];
$search_pagination = '';
if ( ! empty( $search_query ) && class_exists( '\\SearchWP\\Query' ) ) {
	$searchwp_query = new \SearchWP\Query( $search_query, [
		'engine' => 'distributors', // The Engine name.
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
	$placeholder = get_field('field_5f0d29e1c5e4b', 'option');
	$title = get_field('field_5f0d2907d6c9d', 'option');
	if($_SESSION['region'] == 'ca-fr') {
    	$trans_placeholder = $placeholder['distributors_search_placeholder_text_fr'];
    	$trans_title = $title['distributors_page_title_fr'];
    } elseif ( $_SESSION['region'] == 'us-sp' ) {
    	$trans_placeholder = $placeholder['distributors_search_placeholder_text_sp'];
    	$trans_title = $title['distributors_page_title_sp'];
    } else {
    	$trans_placeholder = $placeholder['distributors_search_placeholder_text_en'];
    	$trans_title = $title['distributors_page_title_en'];
    }
?>
<div id="primary" class="content-container">
	<div class="above-content-spacer"></div>
	<!-- BEGIN Supplemental Engine Search form -->
	<form role="search" method="get" class="search-form supplemental-search-form"
	action="<?php echo site_url( 'distributor-search-results/' ); ?>">
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
<div class="content-container search-results-container">
	<?php if ( ! empty( $search_query ) && ! empty( $search_results ) ) : ?>
		<h4><?php printf( __( '<span>'.$trans_title.':</span> %s' ), esc_html( $search_query ) ); ?></h4>
		<?php 
			$check = 0;
			foreach ( $search_results as $search_result ) : ?>
			<?php
				$region = get_field('region', $search_result->ID);
				// var_dump($region);
				if($region) :
					if($_SESSION['region'] == 'ca-en' or $_SESSION['region'] == 'ca-fr') {
						if($region[0] == 'usa') {
							continue;
						} else {
							
						}
					} else {
						if($region == 'canada') {
							continue;
						} else {
							
						}
					}
					echo '<a class="search-result distributor-search-result" href="'.get_permalink($search_result->ID).'">';
					$post = $search_result;
					$address = get_field('address');
					$phone = get_field('phone');
					?>
					<div class="result-header"><h2 class="result-title"><?php the_title(); ?></h2></div>
					<?php echo '<ul class="distributor-contact">';
					if($address) {
						echo '<li class="distributor-result-address">'.$address.'</li>';
					}
					if($phone) {
						if(strpos($phone, '-') !== false) {
							$phone = $phone;
						} else {
							$phone = preg_replace("/^1?(\d{3})(\d{3})(\d{4})$/", "$1-$2-$3", $phone);
						}
						echo '<li class="distributor-result-phone">'.$phone.'</li>';
					}
					echo '</ul>';
					wp_reset_postdata();
					echo '</a>';
				endif;
			?>
		<?php endforeach; ?>

		<?php if ( $searchwp_query->max_num_pages > 1 ) : ?>
			<div class="navigation pagination" role="navigation">
				<h2 class="screen-reader-text">Results navigation</h2>
				<div class="nav-links"><?php echo wp_kses_post( $search_pagination ); ?></div>
			</div>
		<?php endif; ?>
	<?php elseif ( ! empty( $search_query ) ) : 
		if($_SESSION['region'] == 'ca-en') {
			echo 'No results found. Please try searching your province.';
		}
		elseif($_SESSION['region'] == 'ca-fr') {
			echo 'Aucun résultat trouvé. Veuillez essayer de rechercher votre province.';
		}
		elseif($_SESSION['region'] == 'us-en') {
			echo 'No results found. Please try searching your state.';
		}
		elseif($_SESSION['region'] == 'us-sp') {
			echo 'No se han encontrado resultados. Intente buscar su estado.';
		}
		else {
			echo 'No results found. Please try again';
		}
	endif; ?>
<?php
$title = get_field('field_5fc9687e0d90b', 'option');
    if($_SESSION['region'] == 'ca-fr') {
        $trans = $title['distributors_all_locations_title_fr'];
    } elseif ( $_SESSION['region'] == 'us-sp' ) {
        $trans = $title['distributors_all_locations_title_sp'];
    } else {
        $trans = $title['distributors_all_locations_title_en'];
    }
?>
<?php 
if($_SESSION['region'] == 'us-en' or $_SESSION['region'] == 'us-sp' or $_SESSION['region'] == 'us-int') {
	$region = 'usa';
} else {
	$region = 'canada';
}
$site_url = get_site_url('https');
$args = array (
	'post_type'		=> 'distributors',
	'post_status'	=> 'publish',
	'numberposts'	=> -1,
	'meta_key'	    => 'province_state',
	'meta_value'	=> $search_query,
	// 'meta_key'		=> 'city',
	// 'orderby'		=> 'meta_value',
	// 'order'			=> 'ASC'
);
$distributors = get_posts($args); 

if(!empty($distributors)) :

$d_cities = array();
$d_provinces = array();
$d_region = array();
foreach ($distributors as $d) {
	// $d_id   = $d->ID;
	$d_city = get_field('city', $d->ID);
	$d_prov = get_field('province_state', $d->ID);
	$d_region = get_field('region', $d->ID);
	if(is_array($d_region)) {
		$d_region = implode('', $d_region);
	} else {
		$d_region = $d_region;
	}
	$d_cities[] = $d_city;
	$d_provinces[] = $d_prov;
	$d_regions[] = $d_region;
}
$d_cities_unique = array_unique($d_cities);
sort($d_cities_unique);
$d_provinces_unique = array_unique($d_provinces);
$d_regions_unique = array_unique($d_regions);

// var_dump($d_cities_unique);

if(in_array($region, $d_regions_unique)) {
	echo '<h3>'.$d_provinces_unique[0].'</h3>';

	echo '<div class="distributor-search-cities-list-container">';

	foreach ($d_cities_unique as $cities) {
		echo '<a href="'.$site_url.'/distributor-search-results/?searchwp='.$cities.'">'.$cities.'</a>';
	}

	echo '</div>';
}
endif;
?>
<h3><?php echo $trans; ?></h3>
<!-- List of Provinces/States -->
<div class="distributor-search-provinces-states-list-container">
<?php if($region == 'usa') { 
		$states = array (
			'Alabama',
			'Arkansas',
			'Arizona',
			'California',
			'Connecticut',
			'Florida',
			'Illinois',
			'Louisiana',
			'Maryland',
			'Michigan',
			'New Hampshire',
			'New Jersey',
			'Nevada',
			'New York',
			'Ohio',
			'Oklahoma',
			'Pennsylvania',
			'Tennessee',
			'Texas'
		);
		foreach ($states as $state) {
			echo '<a href="'.$site_url.'/distributor-search-results/?searchwp='.$state.'">'.$state.'</a>';
		}
	}
	else {
		$provinces = array (
			'Alberta',
			'British Columbia',
			'Manitoba',
			'New Brunswick',
			'Newfoundland',
			'Nova Scotia',
			'Ontario',
			'Prince Edward Island',
			'Quebec',
			'Saskatchewan',
		);
		foreach ($provinces as $province) {
			echo '<a href="'.$site_url.'/distributor-search-results/?searchwp='.$province.'">'.$province.'</a>';		
		}
	}
?>
</div> <!-- /end list -->
</div>
<?php get_footer(); ?>