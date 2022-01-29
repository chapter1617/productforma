<?php
get_header();
if (have_posts()) :
	while (have_posts()) : the_post();
		include_once 'inc/template-parts/banner-short.php';
?>
<div class="content-container">
	<p>Click on a specific icon to download the resource PDF.</p>
</div>
<div class="resource-container">
	<?php 
		$rs_file_ca_en = get_field('resource_file_ca_en');
		if($rs_file_ca_en) {
			echo '<div class="resource-file">';
			echo '<a href="'.$rs_file_ca_en['url'].'"><i class="fad fa-file-pdf"></i><span>'.get_the_title().'<br>Safety Data Sheet<br>Canada English</span></a>';
			echo '</div>';
		}
		$rs_file_ca_fr = get_field('resource_file_ca_fr');
		if($rs_file_ca_fr) {
			echo '<div class="resource-file">';
			echo '<a href="'.$rs_file_ca_fr['url'].'"><i class="fad fa-file-pdf"></i><span>'.get_the_title().'<br>Safety Data Sheet<br>Canada French</span></a>';
			echo '</div>';
		}
		$rs_file_us_en = get_field('resource_file_us_en');
		if($rs_file_us_en) {
			echo '<div class="resource-file">';
			echo '<a href="'.$rs_file_us_en['url'].'"><i class="fad fa-file-pdf"></i><span>'.get_the_title().'<br>Safety Data Sheet<br>US English</span></a>';
			echo '</div>';
		}
		$rs_file_us_sp = get_field('resource_file_us_sp');
		if($rs_file_us_sp) {
			echo '<div class="resource-file">';
			echo '<a href="'.$rs_file_us_sp['url'].'"><i class="fad fa-file-pdf"></i><span>'.get_the_title().'<br>Safety Data Sheet<br>US Spanish</span></a>';
			echo '</div>';
		}
	?>
</div>
<?php
	endwhile;
endif;
get_footer();
?>