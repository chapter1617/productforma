<?php get_header(); ?>	

<?php //if (function_exists('qt_custom_breadcrumbs')) qt_custom_breadcrumbs(); ?>
		
		<?php // the loop ?>
		<?php if (have_posts()) : ?>
		
			<?php while (have_posts()) : the_post(); ?>
	
				<?php get_template_part( 'inc/loop' , 'index'); ?>
	
			<?php endwhile; ?>
							
			<?php get_template_part( 'inc/pagination'); ?>
		
		<?php else : ?>
	
			<p><?php _e( 'Sorry, nothing found.', 'themify' ); ?></p>
	
		<?php endif; ?>			

<?php get_footer(); ?>