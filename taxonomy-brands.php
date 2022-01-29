<?php
get_header();
include_once('inc/template-parts/banner-with-breadcrumbs.php');
if($_SESSION['region'] == 'ca-fr') {
	$product_categories_title 	= 'catégories de produits';
	$all_products				= 'Tous les produits';
	$back_to_top				= 'Retour au sommet';
	$term_region				= 'french_category_name';
}
elseif($_SESSION['region'] == 'us-sp') {
	$product_categories_title 	= 'Categorías de Producto';
	$all_products				= 'Todos los productos';
	$back_to_top				= 'Volver arriba';
	$term_region				= 'spanish_category_name';
}
else {
	$product_categories_title 	= 'Product Categories';
	$all_products				= 'All Products';
	$back_to_top				= 'Back to Top';
	$term_region				= 'english_name_us_en';
}
echo '<div class="open-sidebar"><span onclick="openNav()"><i class="fas fa-bars"></i> Filter Products</span></div>';
echo '<div class="content-container category-grid">';	
	echo '<main class="product-grid-container">';
		$brand_product_ids = array();
		$brand = array(get_queried_object()->term_id);
		$brand_slug = get_queried_object()->slug;
		$brand_logo = get_field('brand_logo', 'brands_'.get_queried_object()->term_id);
		echo '<div class="product-brand">';
			echo '<a href="#"><img src="'.$brand_logo['url'].'"></a>';
		echo '</div>';
		$products = get_posts( array (
			'post_type'		=> 'products',
			'post_status'	=> 'publish',
			'numberposts'	=> -1,
			'orderby'		=> 'title',
			'order'			=> 'ASC',
			'tax_query' => array(
	            array(
	                'taxonomy' => 'brands',
	                'field' => 'term_id',
	                'terms' => $brand,
	            )
	        ),
	        'meta_query' => array(
	        	array(
	        		'key' 			=> 'discontinued_product',
	        		'value'			=> 0,
	        		// 'meta_compare'	=> '='
	        	)
	        ),
		));
		foreach($products as $product) {
			set_query_var('pID', absint($product->ID));
			$pID = $product->ID;
			get_template_part( 'inc/template-parts/product', 'grid', $pID);
			// Store Product IDs in array
			$brand_product_ids[] = $product->ID;
		}
		echo '<div class="back-to-top"><a href="#header"><i class="fad fa-arrow-alt-circle-up"></i> '.$back_to_top.'</a></div>';
		echo '</main>';
		echo '<aside class="right-sidebar" id="sidebar-nav">'; ?>
<a href="javascript:void(0)" class="closebtn" onclick="closeNav()"><i class="fas fa-arrow-from-left"></i> Close Menu</a>
<?php echo '<div class="sub-categories-container">';
				echo '<h3 style="text-transform: uppercase;">'.single_term_title( '', false ).' <br>'.$product_categories_title.'</h3>';
				// Get list of related product categories based on products showcased
				$product_cat_ids = array();
				foreach($brand_product_ids as $bpid) {
					$product_cat = get_the_category($bpid);
					foreach($product_cat as $pcat) {
						$product_cat_ids[] = $pcat->term_id;	
					}
				}
				$product_cat_ids = array_unique($product_cat_ids);
				// Get list of all categories IDs
				$cat_ids = get_terms(
			        array(
			            'taxonomy' => 'category',
			            'fields'   => 'ids',
			            'get'      => 'all',
			        )
			    );
			    // Remove the categories related to the current product IDs, setup an exclusion list to pass to the below function
				$exclude_cats = array_diff($cat_ids, $product_cat_ids);
				
				echo '<ul class="sub-categories">';
					$args = array(
						'taxonomy' => 'category',
						'hide_empty' => true,
						'exclude'	=> $exclude_cats,
						'parent' => 0,
						'fields' => 'id=>name'
					);
					$terms = get_terms($args);

					foreach ($terms as $term_key => $term_value):
						$term_name = get_field($term_region, 'term_' . $term_key) ? get_field($term_region, 'term_' . $term_key) : $term_value;

						echo '<li class="cat-item cat-item-28"><a href="' . get_term_link( $term_key ) . '?brand='.$brand_slug. '">' . $term_name . '</a></li>';

						$child_args = array(
							'taxonomy' => 'category',
							'hide_empty' => true,
							'exclude'	=> $exclude_cats,
							'parent' => $term_key,
							'fields' => 'id=>name'
						);
						$child_terms = get_terms($child_args);

						if ($child_terms) {
							echo '<ul class="children">';
							foreach ($child_terms as $child_key => $child_value):
								$child_name = get_field($term_region, 'term_' . $child_key) ? get_field($term_region, 'term_' . $child_key) : $child_value;

								//var_dump($child);
								echo '<li class="cat-item cat-item-28"><a href="' . get_term_link( $child_key ) . '?brand='.$brand_slug. '">' . $child_name . '</a></li>';
							endforeach;
							echo '</ul>';
						}
					endforeach;

				echo '</ul>';

				echo '<a class="back-to-all-products" href="'.get_bloginfo('url').'/products"><i class="fad fa-sign-out-alt"></i> '.$all_products.'</a>';
			echo '</div>';
		echo '</aside>';
	echo '</div>';
echo '</div>';
get_footer();