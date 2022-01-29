<!doctype html>

<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="profile" href="http://gmpg.org/xfn/11">


<?php
// Page Title
$wName 		= get_bloginfo('name');
$wDescrip	= get_bloginfo('description');
?>

<title><?php if (is_home() || is_front_page()) { echo $wName .' - '. $wDescrip; } else { echo wp_title('-', true, 'right') .$wName. ' - ' . $wDescrip; } ?></title>

<!-- wp_header -->
<?php wp_head(); ?>

</head>

<body <?php isset($class)? body_class($class): ''; ?>>

	<header>
		<a id="logo" href="<?php echo get_bloginfo('url'); ?>"><img src="<?php echo get_template_directory_uri(); ?>/assets/images/pro-form-products-logo-250px.png"></a>
		<section>
			<a class="flag" href="#"><img src="<?php echo get_template_directory_uri(); ?>/assets/images/canada-flag.png"></a>
			<a class="btn distributor-btn" href="#"><i class="fas fa-street-view"></i> Find a Distributor</a>
			<a class="btn contact-btn" href="<?php echo get_permalink(2); ?>"><i class="far fa-comments"></i> Contact Us</a>
		</section>
		<nav>
			<a href="#">Products</a>
			<a href="https://leuschner.ca/proform/resources/">Resources</a>
			<a href="https://leuschner.ca/proform/faqs/">FAQs</a>
			<a href="<?php //echo get_permalink( get_option( 'page_for_posts' ) ); ?>">News</a>
			<a href="<?php //echo get_permalink(9303); ?>">About Us</a>
			<a href="#">Other Brands</a>
		</nav>
		<div id="search">
			<input type="search" placeholder="Search Products, Resources, etc.">
		</div>
	</header>
