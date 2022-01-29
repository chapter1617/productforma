<?php 
get_header(); 
include_once('inc/template-parts/banner-with-breadcrumbs.php');
?>
<div class="content-container category-grid">
<?php
echo '<main class="product-grid-container">';
if($categoryProducts) {
	foreach($categoryProducts as $cp) {
		$variations = get_field('product_details', $cp->ID);
		echo '<div class="product-grid-item">';
		echo '<a href="'.get_permalink($cp->ID).'">';
			echo '<div class="image-wrapper">';
			// Get first variation
			$imageID = $variations[0]['image']['ID'];
			$image = wp_get_attachment_image_src( $imageID, 'medium' );
			// echo var_dump($image);
			$mainImg = get_field('main_image', $cp->ID);
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
				echo '<h4>'.$cp->post_title.'</h4>';
			echo '</div>';
			echo '<div class="part-number-wrapper">';
				echo '<div class="part-number-label">Part #</div>';
				echo '<div class="product-part-numbers">';
					echo '<div class="main-part-number">'.$variations[0]['part_number'].'</div>';
					$cv = count($variations);
					if($cv > 1) {
						$cvv = $cv - 1;
						echo '<div class="additional-part-numbers">';
						echo '+ ' .$cvv.' Options';
						echo '</div>';
					}
				echo '</div>';
			echo '</div>';
		echo '</a>';
		echo '</div>';
	}
}
?>
	</main>
	<aside class="right-sidebar">
		<?php 
			
			?>
			(Do Something Here)
	</aside>
</div>
<?php
get_footer(); 
?>