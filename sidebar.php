<?php if ( is_active_sidebar( 'sidebar' ) ) : ?>

	<aside id="sidebar">

		<section class="widget">
			<ul>
			<?php dynamic_sidebar( 'sidebar' ); ?>
			</ul>
		</section>

	</aside>

<?php endif; ?>

<!-- /#sidebar -->

