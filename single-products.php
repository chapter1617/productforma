<?php get_header(); 

if (have_posts()) :
	while (have_posts()) : the_post();
	include_once('inc/template-parts/banner-with-breadcrumbs.php');

	$post_id = get_the_ID();
	$variations 		= get_field('product_details');
	$product_region 	= get_field('product_region');
	$descriptionsGroup 	= get_field('descriptions');
	if($product_region) :
		if(in_array('CA-EN', $product_region)) {
			if($_SESSION['region'] == 'ca-fr') {
					$product_name = get_field('product_names_group')['ca-fr'.'_product_name'];
				if($descriptionsGroup['description_ca_fr']) {
					$description = str_replace('<li>', '<li><span class="kit-contains">', $descriptionsGroup['description_ca_fr']);
				}
			} elseif($_SESSION['region'] == 'us-sp') {
				$product_name = '';
				$description = '';
			} else {
				$product_name = get_field('product_names_group')['ca-en'.'_product_name'];
				if($descriptionsGroup['description_ca_en']) {
					$description = str_replace('<li>', '<li><span class="kit-contains">', $descriptionsGroup['description_ca_en']);
				}
			}
		} elseif(in_array('US-EN', $product_region)) {
			if($_SESSION['region'] == 'ca-fr') {
				$product_name = '';
				$description = '';
			} elseif($_SESSION['region'] == 'us-sp') {
				$product_name = get_field('product_names_group')['us-sp'.'_product_name'];
				if($descriptionsGroup['description_us_sp']) {
					$description = str_replace('<li>', '<li><span class="kit-contains">', $descriptionsGroup['description_us_sp']);
				}
			} else {
				$product_name = get_field('product_names_group')['us-en'.'_product_name'];
				if($descriptionsGroup['description_us_en']) {
					$description = str_replace('<li>', '<li><span class="kit-contains">', $descriptionsGroup['description_us_en']);
				}
			}
		} else {
			$product_name = get_field('product_names_group')['us-en'.'_product_name'];
			if($descriptionsGroup['description_us_en']) {
				$description = str_replace('<li>', '<li><span class="kit-contains">', $descriptionsGroup['description_us_en']);
			}
		}
		$description = str_replace(':', ':</span><span class="kit-text">', $description);
		$description = str_replace('.</li>', '.</span></li>', $description);
	endif;
	$sold_separately 	= get_field('sold_separately');
	$product_features 	= get_field('product_features')['features_'.str_replace('-', '_', $_SESSION['region'])];
	$product_brands 	= get_the_terms(get_the_ID(), 'brands');
	$region_lower		= str_replace('-', '_', $_SESSION['region']);
	$regions = array();
	foreach($product_brands as $product_brand) {
		$region_availabilities = get_field('region', 'brands_'.$product_brand->term_id);
		foreach($region_availabilities as $ra) {
			$regions[] = strtolower($ra['value']);
		}
	}
?>	
<div class="product-container">
	<?php 
		// if($_SESSION['region'] && in_array($_SESSION['region'], $regions)) :
		// elseif ( in_array( strtoupper($_SESSION['region']), get_field('product_availability') ) ):
		// Translations
		if($_SESSION['region'] == 'ca-fr') {
			$feature_title = 'traits';
			$related_products_title = 'Produits connexes';
			$how_to_video = 'Vidéo explicative';
		}
		elseif($_SESSION['region'] == 'us-sp') {
			$feature_title = 'Caracteristicas';
			$related_products_title = 'Productos relacionados';
			$how_to_video = 'Video instructivo';	
		}
		else {
			$feature_title = 'Features';
			$related_products_title = 'Related Products';
			$how_to_video = 'How-to Video';
		}
	?>
		<aside class="product-info">
			<?php 
				// Get first variation
				$imageID = $variations[0]['image']['ID'];
				$image = wp_get_attachment_image_src( $imageID, 'Product Photos' );
				$mainImg = get_field('main_image');

				if( $mainImg ) {
					echo wp_get_attachment_image( $mainImg['ID'], 'Product Photos');
				} elseif( $image ) {
					if( $image[2] <= 600 ) {
						$width = 'max-width:320px';
						$height = 'auto';
					} elseif ( $image[2] >= 600 ) {
						$width = 'max-width:auto';
						$height = 'max-height:600px';	
					}

					echo '<img class="product-image" src="'.$image[0].'" style="'.$width.'; '.$height.';">';
				} else {
					echo '<i class="fal fa-images no-product-image"></i>';
				}

				if($sold_separately) {
					$product_sold_separately = get_field('product_sold_separately');
					$sold_separately_custom_text = get_field('sold_separately_custom_text');
					if($product_sold_separately) {
				?>
						<section id="sold_separately" class="sold-separately">
							<i class="fad fa-exclamation-circle"></i>
							
							<a href="<?php echo get_permalink($product_sold_separately[0]->ID); ?>">
								<?php
									if($sold_separately_custom_text) {
										if($sold_separately_custom_text[strtolower($_SESSION['region']).'_sold_separately_custom_text']) {
											echo $sold_separately_custom_text['ca-en_sold_separately_custom_text'];
										}
										else {
											echo $product_sold_separately[0]->post_title . ' sold separately.';
										}
									}
									else { 
										echo $product_sold_separately[0]->post_title . ' sold separately.';
									}
								?>
							</a>
						</section>
				<?php
					} 

				}
				echo '<h1 class="mobile-product-name" id="product_name">'.$product_name.'</h1>';
				// Cats defined in banner template part
				echo '<div class="small-screen-category-bubble"><h5 style="background-color:'.$category_banner_color.';">'.$product_main_cat.'</h5></div>';
				if($product_features) :
				?>
					<section id="product-features">
						<h3><?php echo $feature_title; ?></h3>
						<div id="product_features">
							<?php echo $product_features; ?>
						</div>
					</section>
				<?php endif; ?>
		</aside>
		<main class="product-info">
			<?php $faqs = get_field('related-faqs'); ?>
			<div class="product-info-left">
				<?php
					// Cats defined in banner template part
					echo '<h5 class="large-screen-category-bubble" style="background-color:'.$category_banner_color.';">'.$product_main_cat.'</h5>';
				?>
				<h1 id="product_name"><?php echo $product_name; ?></h1>
				<p><div class="product-description"><?php echo $description; ?></div></p>
				<?php 
					if( count($variations) > 0 ) { ?>
						<section class="product-variations">
							<h3>Product Variations</h3>
							<hr>
							<?php for ( $i = 0; $i < count( $variations ); $i++ ) { ?>
								<div class="variation-data">
									<div class="variation_btn" data-variation_id="<?php echo $variations[$i]['part_number'] ?>">
										<h4>
											<?php 
											if( $variations[$i]['product_name_extension']['product_name_extension_'.$_SESSION['region']] ){
												echo $variations[$i]['part_number'].' '.$variations[$i]['product_name_extension']['product_name_extension_'.$_SESSION['region']];
											} else {
												echo $variations[$i]['part_number'];
											} 
											?>
					
										</h4>
										<table class="variation-details-table">
											<tr>
											<?php 
												if($variations[$i]['product_size']['product_size_'.$region_lower]) {
													// Check if it only contains a space...
													if (strlen($variations[$i]['product_size']['product_size_'.$region_lower]) > 0 && strlen(trim($variations[$i]['product_size']['product_size_'.$region_lower])) > 0) {
														echo '<td class="product-variation-size">'.$variations[$i]['product_size']['product_size_'.$region_lower].'</td>';
													}
												}
												if($variations[$i]['cs_qt']) {
													echo '<td class="product-variation-case-quantity">'.$variations[$i]['cs_qt'].'/case</td>';
												} 
												if($variations[$i]['timings']['open_time']) {
													echo '<td class="product-variation-timing">Open Time: '.$variations[$i]['timings']['open_time'].'</td>';
												}
												if($variations[$i]['colors']) {
													if ($variations[$i]['colors']['color_'.$region_lower]) {
														echo '<td class="product-variation-color">'.$variations[$i]['colors']['color_'.$region_lower].'</td>';
													}
												}
												if($variations[$i]['hardener']) {
													echo '<td class="product-variation-hardener">Hardener: '.$variations[$i]['hardener'].'</td>';
												}
											?>
											</tr>
										</table>
									</div>
									<?php
										$attached_resources = $variations[$i]['part_number_related_resources'];
										if( $attached_resources ) {
											foreach($attached_resources as $sds) {
												$sds_url = get_field('resource_file_'.str_replace('-', '_', $_SESSION['region']), $sds->ID); 
												$type = get_the_terms($sds->ID, 'type');
												if($type) {
													foreach($type as $t) {
														$file_type = $t->name;
														if($file_type == 'Technical Data Sheet') {
															$file_type = 'TDS';
														}
														elseif($file_type == 'Product Data Sheet') {
															$file_type = 'PDS';
														}
														else {
															$file_type = 'SDS';
														}
													}
												}
												else {
													$file_type = 'SDS';
												}
											?>
												<a class="sds-tds" target="_blank" href="<?php echo $sds_url['url'] ?>">
													<i class="fad fa-file-pdf"></i>
													<span><?php //echo get_the_title($sds->ID) ?><?php echo $file_type; ?></span>
												</a>
											<?php }
										}
									?>
								</div>
							<?php } ?>
						</section>
					<?php }

					// Resources Specific to Selected Variation
					foreach($variations as $resources):
						if($resources['part_number_related_resources']) :
							foreach($resources['part_number_related_resources'] as $resource): 					
								$resource_url = get_field( 'resource_file_'.str_replace('-', '_', $_SESSION['region']), $resource->ID );
								$resource_title = get_the_title( $resource->ID ); ?>
								<input type="hidden" id="resource_<?php echo $resource->ID ?>" data-resource_title="<?php echo $resource_title ?>" data-resources_url="<?php echo $resource_url['url'] ?>" />
										<?php endforeach;
						endif;
					endforeach; 

					// Resources Common to all Variations
						$common_resources = get_field('related_resources');
						if($common_resources) :
							echo '<h3>General Resources</h3><hr>';
							echo '<div class="common-resources">';
							foreach($common_resources as $cr) :
								$cr_url = get_field( 'resource_file_'.str_replace('-', '_', $_SESSION['region']), $cr->ID );
								$type = get_the_terms($cr->ID, 'type');
								if($type) {
									foreach($type as $t) {
										$file_type = $t->name;
									}
								}
								else {
									$file_type = 'Safety Data Sheet';
								}
							?>
								<a class="sds-tds" target="_blank" href="<?php echo $cr_url['url'] ?>"><i class="fad fa-file-pdf"></i><span><?php echo get_the_title($cr->ID) ?><br><?php echo $file_type; ?></span></a>
							<?php
							endforeach;
							echo '</div>';
						endif;
						

						$videos = get_field('videos_repeater');

						if($videos) {
							echo '<h3>Videos</h3><hr>';
							echo '<div class="product_videos">';
							foreach ($videos as $video) {
								echo '<a class="show_product_video" href="'.$video['videos_group']['video_youtube'].'">';
								echo '<i class="fab fa-youtube"></i>';
								echo '<span>'.$video['videos_group']['video_label'].'</span>';
								echo '</a>';		
							}
							echo '</div>';
						}
						
						// if($faqs) {
						// 	echo '<div>';
						// 	echo '<a href="#product-faq">';
						// 	echo '<i class="fad fa-question-circle"></i>';
						// 	if($numFAQs = 1) {
						// 		echo '<span>FAQ</span>';
						// 	}
						// 	else {
						// 		echo '<span>FAQs</span>';	
						// 	}
						// 	echo '</a>';
						// 	echo '</div>';
						// }

						if($faqs) {
						echo '<section id="product-faq" class="product-faq">';
						$numFAQs = count($faqs);
						if($numFAQs = 1) {
							echo '<h3>Product FAQ</h3><hr>';
						}
						else {
							echo '<h3>Product FAQs</h3><hr>';	
						}
						foreach($faqs as $faq) {
							echo '<div class="product-faq-item">';
							echo '<h5>'.get_the_title($faq->ID).'</h5>';
							$getA = get_field('answer', $faq->ID);
							echo '<p>'.$getA.'</p>';
							echo '</div>';
						}
					}
					?>
			</div>
		</main>
<!-- 	<?php // else: ?>
		// load notice
		<a id="back_to_products" class="back_to_products" href="#" style="display: none;"></a>
		<script> 
			setTimeout(function() {
				jQuery('#back_to_products').click();
			}, 2000)
		</script>
	<?php // endif; ?> -->
</div>
<?php 
	$relatedProducts = get_field('related_products');
	if($relatedProducts) {
		$relProCount = sizeof($relatedProducts);	
	}
	else {
		$relProCount = 0;
	}
	if($relatedProducts) {
		echo '<section class="related-products-container">';
		echo '<h2 id="related-products-title">'.$related_products_title.'</h2>';
		if($relProCount > 20) {
			echo '<div id="relatedProducts" class="related-products touchcarousel minimal-light-skin">';
			echo '<ul class="touchcarousel-container">';
		}
		else {
			echo '<div id="relatedProducts" class="related-products">';
		}
		foreach ($relatedProducts as $rp) {
			if($relProCount > 20) {
				echo '<li class="touchcarousel-item">';
			}
			$flexBasis = 100/$relProCount;
			echo '<a class="related-product-item item-block" href="'.get_permalink($rp->ID).'" style="width:calc('.$flexBasis.'% - 20px);margin: 0 10px;">';
			echo '<div class="related-products-img-wrapper">';
			$variations = get_field('product_details', $rp->ID);
			if($variations) {
				$imageID = $variations[0]['image']['ID'];
				$image = wp_get_attachment_image_src( $imageID, 'full' );
				$mainImg = get_field('main_image', $rp->ID);
				if($image) {
					if($image[2] <= 600) {
						$width = 'max-width:150px';
						$height = 'auto';
					}
					elseif ($image[2] >= 600 ) {
						$width = 'max-width:auto';
						$height = 'max-height:150px';	
					}
					echo '<img src="'.$image[0].'" style="'.$width.'; '.$height.';">';
				}
				elseif($mainImg) {
					echo wp_get_attachment_image( $mainImg['ID'], 'full');
				}
				else {
					echo '<i class="fal fa-images"></i>';
				}
				
			}
			echo '</div>';
			$rp_translated_titles = get_field('product_names_group',$rp->ID);
			if($_SESSION['region'] == 'ca-en') {
				echo '<h4>'. $rp_translated_titles['ca-en_product_name'].'</h4>';
			}
			elseif($_SESSION['region'] == 'ca-fr') {
				echo '<h4>'. $rp_translated_titles['ca-fr_product_name'].'</h4>';
			}
			elseif($_SESSION['region'] == 'us-en') {
				echo '<h4>'. $rp_translated_titles['us-en_product_name'].'</h4>';
			}
			elseif($_SESSION['region'] == 'us-sp') {
				echo '<h4>'. $rp_translated_titles['us-sp_product_name'].'</h4>';
			}
			else {
				echo '<h4>'.$rp->post_title.'</h4>';
			}
			if($variations) {
				echo '<span>'.$variations[0]['part_number'].'</span>';
			}
			echo '</a>';
			if($relProCount > 20) {
				echo '</li>';
			}
		}
		if($relProCount > 20) {
			echo '</ul>';
		}
		echo '</div>';
		echo '</section>';
	}
	endwhile;
endif;
?>

<script type="text/javascript" src="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
<script>

	var $variation_btn = document.querySelectorAll('.variation_btn');

	$variation_btn.forEach(function( item ) {
		item.addEventListener('click', function (event) {
			var post_id = '<?php echo $post_id ?>';
			var variation_id = jQuery(this).data("variation_id");

			event.preventDefault();

			//console.log(post_id, variation_id);

		    jQuery.ajax({
		        type : "post",
		        dataType : "json",
		        url : '<?php echo admin_url( 'admin-ajax.php' ); ?>',
		        data : {
		        	action: "proform_get_clicked_product_variation", 
		        	post_id: post_id, 
		        	variation_id: variation_id,
		        	product_name: '<?php echo $product_name ?>'
		        },
		        success: function(response) {
					//debugger;
					//console.log(response.sold_separately);
					var $product_name = document.getElementById('product_name');
					var $product_photo = document.querySelector('.product-info > img');
					var $sold_separately = document.getElementById('sold_separately');
					var $product_features = document.getElementById('product_features');
					var $product_related_resources_variations = document.querySelector('.product-info-docs-resources');

		            $product_name.innerHTML = response.name;

		            if ($product_photo) {
						$product_photo.removeAttribute("srcset");
						$product_photo.removeAttribute("sizes");
						$product_photo.src = response.image_src ? response.image_src : $product_photo.src;
		            }

		            if ($sold_separately) {
		            	if(response.hasOwnProperty('sold_separately')) {
		            		jQuery('#sold_separately').show();
		            	} else {
		            		jQuery('#sold_separately').hide();
		            	}

		            	$sold_separately.innerHTML = response.sold_separately;
		            }

					if ($product_features) {
						if (response.features !== '') {
							jQuery('#product-features').show();
						} else {
							jQuery('#product-features').hide();
						}

						$product_features.innerHTML = response.features;
					}

					if ($product_related_resources_variations) {
						$product_related_resources_variations.innerHTML = response.related_resources;
					}
		        },
		        error: function(error) {
		            console.log(error.responseText);
		        }
		    });
		}, false)
	});

	/**
	*	Disable normal behavior in show_product_video clicked video
	*/
	jQuery('.show_product_video').on('click', function (event) {
		event.preventDefault();
		var _videoUrl = jQuery(this).attr('href').replace('watch?v=', 'embed/');
		_videoUrl += '?feature=oembed&start&end&wmode=opaque&loop=0&controls=1&mute=0&rel=0&modestbranding=0';
		console.log(_videoUrl);

		setTimeout(function(){
			jQuery('.elementor-video-iframe').attr('src', _videoUrl);
		}, 800);
	});

	/**
	*	Handle the slck carousel js
	*/
	jQuery('#relatedProducts').slick({
	    dots: false,
	    infinite: true,
	    speed: 300,
	    slidesToShow: 5,
	    slidesToScroll: 1,
	    nextArrow: '<button type="button" class="slick-arrow slick-next"><img src="<?php echo get_stylesheet_directory_uri() ?>/assets/images/arrow-next.png" /></button>',
	    prevArrow: '<button type="button" class="slick-arrow slick-prev"><img src="<?php echo get_stylesheet_directory_uri() ?>/assets/images/arrow-back.png" /></button>',
	    responsive: [
	    {
	        breakpoint: 1024,
	        settings: {
	           slidesToShow: 3,
	           slidesToScroll: 3,
	           infinite: true
	        }
	    },
	    {
	        breakpoint: 768,
	        settings: {
	           slidesToShow: 2,
	           slidesToScroll: 2
	        }
	    },
	    {
	        breakpoint: 480,
	        settings: {
	           slidesToShow: 1,
	           slidesToScroll: 1
	        }
	    }]
   });
</script>

<?php get_footer(); ?>
