<?php

/* Template Name: Main Search Results */

global $post;

// Retrieve applicable query parameters.
$search_query = isset( $_GET['searchwp'] ) ? sanitize_text_field( $_GET['searchwp'] ) : null;
$search_page  = isset( $_GET['swppg'] ) ? absint( $_GET['swppg'] ) : 1;

// Perform the search.
$search_results    = [];
$search_pagination = '';
if ( ! empty( $search_query ) && class_exists( '\\SearchWP\\Query' ) ) {
	$searchwp_query = new \SearchWP\Query( $search_query, [
		'engine' => 'default', // The Engine name.
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
	if($_SESSION['region'] == 'ca-fr') {
    	$trans_placeholder = 'Rechercher des produits, des ressources, etc.';
    } elseif ( $_SESSION['region'] == 'us-sp' ) {
    	$trans_placeholder = 'Buscar productos, recursos, etc.';
    } else {
    	$trans_placeholder = 'Search Products, Resources, etc.';
    }
?>
<div id="primary" class="content-container">
	<div class="above-content-spacer"></div>
	<!-- BEGIN Supplemental Engine Search form -->
	<form role="search" method="get" class="search-form supplemental-search-form"
	action="<?php echo site_url( 'search-results/' ); ?>">
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

<div class="resource-container content-container search-results-container product-search-grid-container">
	<?php if ( ! empty( $search_query ) && ! empty( $search_results ) ) : ?>
		<?php 
			if($_SESSION['region'] == 'ca-en' or $_SESSION['region'] == 'ca-fr') {
				$brands = array(18, 20, 25, 23);
			} else {
				$brands = array(19,21,22,26,24);
			}
			// echo '<pre>';
			// var_dump($search_results);
			// echo '</pre>';
			$i = 0;
			foreach ( $search_results as $search_result ) :
				$i++;
				$post = $search_result;
				$post_type = $search_result->post_type;
				switch($post_type) {
					case 'products':
						if($i == 1) {
							// echo '<div class="searc-container"><h4>Products:</h4>';
							if($_SESSION['region'] == 'ca-fr') {
						    	$trans_title = 'Recherche de produit ';
						    } elseif ( $_SESSION['region'] == 'us-sp' ) {
						    	$trans_title = 'Búsqueda de Producto ';
						    } else {
						    	$trans_title = 'Product Search ';
						    }
							printf( __( '<h4 class="product-search-title"><span>'.$trans_title.':</span> %s</h4>' ), esc_html( $search_query ) );
						}
						$product_brands = get_the_terms($search_result->ID, 'brands');
						$product_brand = $product_brands[0]->term_id;
						if(in_array($product_brand, $brands)) :
							$variations = get_field('product_details', $search_result->ID);
							echo '<div class="product-grid-item">';
								echo '<a href="'.get_permalink($search_result->ID).'">';
									echo '<div class="image-wrapper">';
										// Get first variation
										$imageID = $variations[0]['image']['ID'];
										$image = wp_get_attachment_image_src( $imageID, 'medium' );
										$mainImg = get_field('main_image', $search_result->ID);
										if($mainImg) {
											echo wp_get_attachment_image( $mainImg['ID'], 'medium');
										}
										elseif($image) {
											if($image[2] <= 600) {
												$width = 'max-width:380px';
												$height = 'auto';
											}
											elseif ($image[2] >= 600 ) {
												$width = 'max-width:auto';
												$height = 'max-height:300px';	
											}
											echo '<img class="product-image" src="'.$image[0].'">';
										}
										else {
											echo '<i class="fal fa-images"></i>';
										}
									echo '</div>';
									echo '<div class="title-wrapper">';
										$product_name = get_field('product_names_group', $search_result->ID);
										if($_SESSION['region'] == 'ca-en') {
											echo '<h4>'.$product_name['ca-en_product_name'].'</h4>';
											$part_number = 'Part #';
											$options = 'Options';
										}
										elseif($_SESSION['region'] == 'ca-fr') {
											echo '<h4>'.$product_name['ca-fr_product_name'].'</h4>';
											$part_number = 'Partie #';
											$options = 'Options';
										}
										elseif($_SESSION['region'] == 'us-sp') {
											echo '<h4>'.$product_name['us-sp_product_name'].'</h4>';
											$part_number = 'Parte #';
											$options = 'Opciones';
										}
										elseif($_SESSION['region'] == 'us-en') {
											echo '<h4>'.$product_name['us-en_product_name'].'</h4>';
											$part_number = 'Part #';
											$options = 'Options';
										}
										else {
											echo '<h4>'.$search_result->post_title.'</h4>';
											$part_number = 'Part #';
											$options = 'Options';	
										}
										
									echo '</div>';
									echo '<div class="part-number-wrapper">';
										echo '<div class="part-number-label">'.$part_number.'</div>';
										echo '<div class="product-part-numbers">';
											echo '<div class="main-part-number">'.$variations[0]['part_number'].'</div>';
											$cv = count($variations);
											if($cv > 1) {
												$cvv = $cv - 1;
												echo '<div class="additional-part-numbers">';
												echo '+ ' .$cvv.' '.$options;
												echo '</div>';
											}
										echo '</div>';
									echo '</div>';
								echo '</a>';
							echo '</div>';
							if($i == 1) {
								// echo '</div>';
							}
						endif;
					break;
					case 'resources':
						if($i == 1) {
							$title = get_field('field_5f0d187d2d8aa', 'option');
							if($_SESSION['region'] == 'ca-fr') {
						    	$trans_title = $title['resource_search_results_page_title_fr'];
						    } elseif ( $_SESSION['region'] == 'us-sp' ) {
						    	$trans_title = $title['resource_search_results_page_title_sp'];
						    } else {
						    	$trans_title = $title['resource_search_results_page_title_en'];
						    }
							printf( __( '<h4><span>'.$trans_title.':</span> %s</h4>' ), esc_html( $search_query ) );
						}
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
				        	echo '<div class="resource-file-grid-item">';
				        	echo '<a target="_blank" href="'.$resource_file_ca_en['url'].'">';
			        		echo '<i class="fad fa-file-pdf"></i><span><b>';
			        		the_title();
			        		echo '</b></span><br>';
			        		echo '<span class="resource-result-lang">(English)<span>';
			        		echo '</a>';
			        		echo '</div>';
			        	}
			        	if($resource_file_ca_fr) {
				        	echo '<div class="resource-file-grid-item">';
				        	echo '<a target="_blank" href="'.$resource_file_ca_fr['url'].'">';
			        		echo '<i class="fad fa-file-pdf"></i><span><b>';
			        		the_title();
			        		echo '</b></span><br>';
			        		echo '<span class="resource-result-lang">(Français)<span>';
			        		echo '</a>';
			        		echo '</div>';
			        	}
			        	if($resource_file_us_en) {
				        	echo '<div class="resource-file-grid-item">';
				        	echo '<a target="_blank" href="'.$resource_file_us_en['url'].'">';
			        		echo '<i class="fad fa-file-pdf"></i><span><b>';
			        		the_title();
			        		echo '</b></span><br>';
			        		echo '<span class="resource-result-lang">(English)<span>';
			        		echo '</a>';
			        		echo '</div>';
			        	}
			        	if($resource_file_us_sp) {
				        	echo '<div class="resource-file-grid-item">';
				        	echo '<a target="_blank" href="'.$resource_file_us_sp['url'].'">';
			        		echo '<i class="fad fa-file-pdf"></i><span><b>';
			        		the_title();
			        		echo '</b></span><br>';
			        		echo '<span class="resource-result-lang">(Española)<span>';
			        		echo '</a>';
			        		echo '</div>';
			        	}

					break;
					case 'distributors':
						if($i == 1) {
							$title = get_field('field_5f0d2907d6c9d', 'option');
							if($_SESSION['region'] == 'ca-fr') {
						    	$trans_title = $title['distributors_page_title_fr'];
						    } elseif ( $_SESSION['region'] == 'us-sp' ) {
						    	$trans_title = $title['distributors_page_title_sp'];
						    } else {
						    	$trans_title = $title['distributors_page_title_en'];
						    }
							printf( __( '<h4><span>'.$trans_title.':</span> %s</h4>' ), esc_html( $search_query ) );
						}
						$region = get_field('region', $search_result->ID);
						// var_dump($region);
						if($region) :
							if($_SESSION['region'] == 'ca-en' or $_SESSION['region'] == 'ca-fr') {
								if(in_array('usa', $region)) {
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
					break;
					case 'representatives':
						if($i == 1) {
							if($_SESSION['region'] == 'ca-fr') {
						    	$trans_title = 'Recherche de représentant';
						    } elseif ( $_SESSION['region'] == 'us-sp' ) {
						    	$trans_title = 'Búsqueda representativa';
						    } else {
						    	$trans_title = 'Representative Search';
						    }
							printf( __( '<h4><span>'.$trans_title.':</span> %s</h4>' ), esc_html( $search_query ) );
						}
						$company 	= get_field('company');
						$region 	= get_field('region');
						$country 	= get_field('country'); 
						echo '<a class="representative-search-result-item" href="'.get_permalink($search_result->ID).'">';
							echo '<i class="fas fa-user-tie"></i>';
							echo '<h4 class="rep-name">'.get_the_title().'</h4>';
							if($company) {
								echo '<span class="rep-company">'.$company.'</span>';
							}
							echo '<span class="rep-region-country">';
								if($region) {
									echo $region;
									if($country) {
										echo ', '.$country;
									}
								} elseif ($country) {
									echo $country;
								}	
							echo '</span>';
						echo '</a>';
					break;
					case 'faqs':
						if($i == 1) {
							if($_SESSION['region'] == 'ca-fr') {
						    	$trans_title = 'FAQ terme de recherche';
						    } elseif ( $_SESSION['region'] == 'us-sp' ) {
						    	$trans_title = 'Preguntas frecuentes Término de búsqueda';
						    } else {
						    	$trans_title = 'FAQ Search Term';
						    }
							printf( __( '<h4><span>'.$trans_title.':</span> %s</h4>' ), esc_html( $search_query ) );
						}
						$region = get_field('region', $search_result->ID);
						if($region) :
							if( in_array_r(strtoupper($_SESSION['region']), $region) ) {
								echo '<a class="search-result distributor-search-result" href="'.get_permalink($search_result->ID).'">';
								echo '<div class="result-header"><h2 class="result-title">'.get_the_title().'</h2></div>';
								echo '</a>';
							}
						endif;
					break;
				} // end switch
				// wp_reset_postdata();
			endforeach; 
		?>
</div>
<div class="content-container">
		<?php if ( $searchwp_query->max_num_pages > 1 ) : ?>
			<div class="navigation pagination" role="navigation">
				<h2 class="screen-reader-text">Results navigation</h2>
				<div class="nav-links"><?php echo wp_kses_post( $search_pagination ); ?></div>
			</div>
		<?php endif;
		elseif ( ! empty( $search_query ) ) :
		if($_SESSION['region'] == 'ca-en') {
			echo '<p>No results found, please search again.<br>';
			// echo 'If you are searching for a distributor, try searching your province.</p>';
		}
		elseif($_SESSION['region'] == 'ca-fr') {
			echo '<p>Aucun résultat trouvé, veuillez effectuer une nouvelle recherche.<br>';
			// echo 'Si vous recherchez un distributeur, essayez de rechercher votre province.</p>';
		}
		elseif($_SESSION['region'] == 'us-en') {
			echo '<p>No results found, please search again.<br>';
			// echo 'If you are searching for a distributor, try searching your state.</p>';
		}
		elseif($_SESSION['region'] == 'us-sp') {
			echo '<p>No se encontraron resultados, busque de nuevo.<br>';
			// echo 'Si está buscando un distribuidor, intente buscar en su estado</p>';
		}
		else {
			echo '<p>No results found, please search again.<br>';
			// echo 'If you are searching for a distributor, try searching your state/province.</p>';
		}
		
	endif; ?>
</div>
<?php get_footer(); ?>