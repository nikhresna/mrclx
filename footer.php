<?php ob_start() ?>

	<div id="footer">
		<div class="footer">
			<div class="footer-nav">
				<h3 class="footer-heading">Menu</h3>
				<?php
					// call primary menu
					if( has_nav_menu('footer_menu') ) {
						wp_nav_menu( array(
							'theme_location' 	=> 'footer_menu',
							'container' 		=> 'ul',
							'container_class' 	=> 'menu',
							'items_wrap' 		=> '<ul id="%1$s" class="%2$s">%3$s</ul>' ) 
						); 
					} else {
						echo '<li><a href="#">No menu assigned!</a></li>';
					}
				?>
			</div>
			<div class="footer-categories">
				<h3 class="footer-heading">Categories</h3>
				<ul>
					<?php 
							$args = array(
				        'depth'               => 0,
				        'hide_empty'          => 0,
				        'hide_title_if_empty' => false,
				        'hierarchical'        => true,
				        'order'               => 'ASC',
				        'orderby'             => 'name',
				        'separator'           => '<br />',
				        'show_count'          => 0,
				        'show_option_all'     => '',
				        'show_option_none'    => __( 'No categories' ),
				        'style'               => 'list',
				        'taxonomy'            => 'category',
				        'title_li'            => '',
				        'use_desc_for_title'  => 1,
					    );
					    wp_list_categories( $args );
					?>
				</ul>
			</div>
			<div class="footer-links">
				<h3 class="footer-heading">Other Links</h3>
				<?php
					if( has_nav_menu('footer_links') ) {
						wp_nav_menu( array(
							'theme_location' 	=> 'footer_links',
							'container' 		=> 'ul',
							'container_class' 	=> 'menu',
							'items_wrap' 		=> '<ul id="%1$s" class="%2$s">%3$s</ul>' ) 
						); 
					} else {
						echo '<li><a href="#">No menu assigned!</a></li>';
					}
				?>
			</div>
		</div>

		<div class="footer-copyright">
			<span>Nikko Khresna</span>
		</div>
	</div>
	<!-- <script src="https://code.jquery.com/jquery-3.1.0.slim.min.js" integrity="sha256-cRpWjoSOw5KcyIOaZNo4i6fZ9tKPhYYb6i5T9RSVJG8=" crossorigin="anonymous"></script> -->
	<script src="<?php echo get_stylesheet_directory_uri().'/_includes/js/script.js' ?>" type="text/javascript" async></script>
	<script src="<?php echo get_stylesheet_directory_uri().'/_includes/js/comment-reply.js' ?>" type="text/javascript" async></script>
	<script src="<?php echo get_stylesheet_directory_uri().'/_includes/vendor/prism/prism.js' ?>" type="text/javascript" async></script>
</body>

<?php 
	$content = ob_get_clean();
	echo $content;
?>