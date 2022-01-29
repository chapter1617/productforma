<?php
get_header();
include_once('inc/template-parts/banner-with-breadcrumbs.php');
// Translations
if($_SESSION['region'] == 'ca-fr') {
	$filter_by_brand 	= 'Filtrer par marque';
	$filter_by_cat		= 'Filtrer par catégorie';
	$term_region		= 'french_category_name';
	$filter_products	= 'Filtrer les produits';
}
elseif($_SESSION['region'] == 'us-sp') {
	$filter_by_brand 	= 'Filtrar por marca';
	$filter_by_cat		= 'Filtrar por categoría';
	$term_region		= 'spanish_category_name';
	$filter_products	= 'Filtrar productos';
}
else {
	$filter_by_brand 	= 'Navigate Brands';
	$filter_by_cat		= 'Filter By Category';
	$term_region		= 'english_name_us_en';
	$filter_products	= 'Filter Products';
}
echo '<div class="open-sidebar"><span onclick="openNav()"><i class="fas fa-bars"></i> Filter Products</span></div>';
echo '<div class="content-container category-grid">';
	echo '<main class="product-grid-container">';
		if($_SESSION['region'] == 'ca-en' or $_SESSION['region'] == 'ca-fr') {
			$brands = array(18, 20, 25, 23);
		} else {
			$brands = array(19,21,22,26,24);
		}
		foreach($brands as $brand) {
			$products = get_posts( array (
				'post_type'		=> 'products',
				'post_status'	=> 'publish',
				'numberposts'	=> -1,
				'orderby'		=> 'title',
				'order'			=> 'ASC',
				'tax_query' => array(
		            array(
		                'taxonomy' 	=> 'brands',
		                'field' 	=> 'term_id',
		                'terms' 	=> $brand,
		            )
		        ),
		        'meta_query' => array(
		        	array(
		        		'key' 			=> 'discontinued_product',
		        		'value'			=> 0,
		        		// 'meta_compare'	=> '='
		        	)
		        )
			));
			$brand_logo = get_field('brand_logo', 'brands_'.$brand);
			$brand_term = get_term($brand);
			echo '<span id="'.$brand_term->name.'"></span>';
			echo '<div class="product-brand">';
				echo '<a href="'.get_term_link($brand, 'brands').'"><img src="'.$brand_logo['url'].'"></a>';
			echo '</div>';
			foreach($products as $product) {
				set_query_var('pID', absint($product->ID));
				$pID = $product->ID;
				get_template_part( 'inc/template-parts/product', 'grid', $pID);
				// Collect Product IDs
				$product_ids[] = $product->ID;
			}
			echo '<div class="back-to-top"><a href="#header"><i class="fad fa-arrow-alt-circle-up"></i> Back to Top</a></div>';
		}
		echo '</main>';
		echo '<aside class="right-sidebar" id="sidebar-nav">'; ?>
<a href="javascript:void(0)" class="closebtn" onclick="closeNav()"><i class="fas fa-arrow-from-left"></i> Close Menu</a>
<?php echo '<div class="sub-categories-container">';
				echo '<h3 style="text-transform: uppercase;">'.$filter_by_brand.'</h3>';
				echo '<div class="product-brands-list">';
				foreach($brands as $brand) {
					$brand_term = get_term($brand);
					echo '<div class="product-brand">';
						echo '<a href="'.get_term_link($brand, 'brands').'">'.$brand_term->name.'</a>';
					echo '</div>';
				}
				echo '</div>';
				echo '<h3 style="text-transform: uppercase;">'.$filter_by_cat.'</h3>';
				// Get list of related product categories based on products showcased
				$product_cat_ids = array();
				foreach($product_ids as $pid) {
					$product_cat = get_the_category($pid);
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

						echo '<li class="cat-item cat-item-28"><a href="' . get_term_link( $term_key ) . '">' . $term_name . '</a></li>';

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
								echo '<li class="cat-item cat-item-28"><a href="' . get_term_link( $child_key ) . '">' . $child_name . '</a></li>';
							endforeach;
							echo '</ul>';
						}
					endforeach;

				echo '</ul>';
			echo '</div>';
		echo '</aside>';
	echo '</div>';
echo '</div>'; 
get_footer();