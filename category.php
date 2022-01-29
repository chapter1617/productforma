<?php 
get_header(); 
include_once('inc/template-parts/banner-with-breadcrumbs.php');
if($_SESSION['region'] == 'ca-fr') {
	$product_categories_title 	= 'catégories de produits';
	$all_products				= 'Tous les produits';
	$back_to_top				= 'Retour au sommet';
	$sub_categories_title		= 'Sous-catégories';
	$similar_categories_title	= 'Catégories similaires';
}
elseif($_SESSION['region'] == 'us-sp') {
	$product_categories_title 	= 'Categorías de Producto';
	$all_products				= 'Todos los productos';
	$back_to_top				= 'Volver arriba';
	$sub_categories_title		= 'Subcategorías';
	$similar_categories_title	= 'Categorías similares';
}
else {
	$product_categories_title 	= 'Product Categories';
	$all_products				= 'All Products';
	$back_to_top				= 'Back to Top';
	$sub_categories_title		= 'Sub-Categories';
	$similar_categories_title	= 'Similar Categories';
}
echo '<div class="open-sidebar"><span onclick="openNav()"><i class="fas fa-bars"></i> Filter Products</span></div>';
?>
<div class="content-container category-grid">
    <main class="product-grid-container">
        <?php
		$brand = $_GET['brand'];		
		if(!empty($brand)){
			foreach  ($categoryProducts as $category) {
				$brands = get_the_terms($category->ID, 'brands');
				$brand_region = get_field('region', 'term_'.$brands[0]->term_id);
				if($brands && in_array_r(strtoupper($_SESSION['region']), $brand_region)) {
					set_query_var('pID', absint($category->ID));
					$pID = $category->ID;
					if ( $brands && ! is_wp_error( $brands ) ) :
						$draught_links = array();
						foreach ( $brands as $term ) {
							$draught_links[] = $term->slug;
						}											 
						$on_draught = join( ", ", $draught_links );
						if ( $on_draught == $brand){
							get_template_part( 'inc/template-parts/product', 'grid', $pID);
						}						
					endif;					
				}			
			}
		} else {
			if($categoryProducts) {
				foreach($categoryProducts as $cp) {
					$brands = get_the_terms($cp->ID, 'brands');
					$brand_region = get_field('region', 'term_'.$brands[0]->term_id);
					if($brands && in_array_r(strtoupper($_SESSION['region']), $brand_region)) {
						set_query_var('pID', absint($cp->ID));
						$pID = $cp->ID;
						get_template_part( 'inc/template-parts/product', 'grid', $pID);
					} else {
						$_SESSION['region'] = "ca-en";
						$_SESSION['region_default'] = "ca-en";
					}
				}
				echo '<div class="back-to-top"><a href="#header"><i class="fad fa-arrow-alt-circle-up"></i> '.$back_to_top.'</a></div>';
			}
		}
		?>
    </main>
    <aside class="right-sidebar" id="sidebar-nav">
        <a href="javascript:void(0)" class="closebtn" onclick="closeNav()"><i class="fas fa-arrow-from-left"></i> Close
            Menu</a>
        <?php 
			// if parent category, display children
			if($cats[0]->parent == 0) {
				$childCats = $currentCatID;
				$catContainerTitle = $sub_categories_title;
			}
			else {
				$childCats = $cats[0]->parent;
				$catContainerTitle = $similar_categories_title;
			}
			$sideBarCats = get_categories( array (
				'hide_empty'	=> true,
				'child_of'		=> $childCats,
				'orderby'		=> 'name',
				'order'			=> 'ASC',
			)); 
		?>
        <div class="sub-categories-container">
            <h3 style="text-transform: uppercase;"><?php echo $catContainerTitle; ?></h3>
            <ul class="sub-categories">
                <?php
				if($cats[0]->parent > 0) { 
					if($_SESSION['region'] == 'ca-fr') {
						$translated_cat_name = get_field('french_category_name', 'category_'.$childCats);
					} elseif($_SESSION['region'] == 'us-sp') {
						$translated_cat_name = get_field('spanish_category_name', 'category_'.$childCats);
					} else {
						$translated_cat_name = get_cat_name($childCats);
					}
					if($translated_cat_name) {
						$translated_cat_name = $translated_cat_name;
					} else {
						$translated_cat_name = get_cat_name($childCats);
					}
					echo '<li><a href="'.get_category_link($childCats).'">'.$translated_cat_name.'</a>';
					echo '<ul class="sub-children-categories">';
					foreach ($sideBarCats as $sbc) {
						if($sbc->term_id == $currentCatID) {

						}
						else {
							if($_SESSION['region'] == 'ca-fr') {
								$translated_cat_name = get_field('french_category_name', 'category_'.$sbc->term_id);
							} elseif($_SESSION['region'] == 'us-sp') {
								$translated_cat_name = get_field('spanish_category_name', 'category_'.$sbc->term_id);
							} else {
								$translated_cat_name = $sbc->name;
							}
							if($translated_cat_name) {
								$translated_cat_name = $translated_cat_name;
							} else {
								$translated_cat_name = $sbc->name;
							}
							echo '<li><a href="'.get_category_link($sbc->term_id).'">'.$translated_cat_name.'</a></li>';
						}
					}
					echo '</ul>';
					echo '<li>';
				}
				else {
					foreach ($sideBarCats as $sbc) {
						if($_SESSION['region'] == 'ca-fr') {
							$translated_cat_name = get_field('french_category_name', 'category_'.$sbc->term_id);
						} elseif($_SESSION['region'] == 'us-sp') {
							$translated_cat_name = get_field('spanish_category_name', 'category_'.$sbc->term_id);
						} else {
							$translated_cat_name = $sbc->name;
						}
						if($translated_cat_name) {
							$translated_cat_name = $translated_cat_name;
						} else {
							$translated_cat_name = $sbc->name;
						}
						echo '<li><a href="'.get_category_link($sbc->term_id).'">'.$translated_cat_name.'</a></li>';
					}
				}
				?>
            </ul>
            <a class="back-to-all-products" href="<?php echo get_bloginfo('url'); ?>/products"><i
                    class="fad fa-sign-out-alt"></i> <?php echo $all_products; ?></a>
        </div>
        <div class="category-brands-containers">
            <?php 
			// foreach($categoryProducts as $cp) {
			// 	$brands = get_the_terms($cp->ID, 'brands');
			// 	foreach($brands as $brs) {
			// 		$brsa[] = array ('brand_name' => $brs->name);	
			// 	}

			// }
			// get a list of available taxonomies for a post type
			?>
        </div>
    </aside>

</div>
<?php
get_footer(); 
?>