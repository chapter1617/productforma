<?php wp_footer(); ?>
<section id="upper-footer-container">
	<div id="upper-footer">
		<div class="footer-address">
			<h3>Pro Form Products Ltd.</h3>
			<p>604 McGeachie Drive<br>Milton, Ontario<br>L9T 3Y5</p>
		</div>
		<div class="footer-contact">
			<h3>Contact Us</h3>
			<p>Phone: <a href="tel:9058784990">(905)878-4990</a><br>Fax.: (905)878-1189</p>
			<p>Toll Free: <a href="tel:18003877981">1-800-387-7981</a></p>
			<p><a href="mailto:info@proformproducts.com">inf@proformproducts.com</a></p>
		</div>
		<div class="footer-social">
			<a href="#"><i class="fab fa-facebook"></i></a>
			<a href="#"><i class="fab fa-twitter"></i></a>
			<a href="#"><i class="fab fa-instagram"></i></a>
		</div>
	</div>
</section>
<section id="lower-footer">
	&copy; <?php echo date('Y'); ?> Pro Form Products Ltd. All Rights Reserved.
	<?php 
	// If the current user can manage options(ie. an admin)
	if( current_user_can( 'manage_options' ) ) 
	    // Print the saved global 
	    // printf( '- <strong>Current template:</strong> %s', get_page_template() ); 
	?>
</section>
</div> <!-- /.container (css grid) -->
</body>
</html>