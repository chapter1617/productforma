<?php get_header(); ?>

		<?php
		while ( have_posts() ) : the_post();
			get_template_part( 'content', 'page' );
		endwhile; // end of the loop.
		?>

<?php get_footer(); ?>