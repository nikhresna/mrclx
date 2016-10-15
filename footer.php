<?php ob_start() ?>

	<div id="footer">
		<div class="footer">
			<div class="footer-nav">
				<h4 class="footer-heading">MENU</h4>
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
				<h4 class="footer-heading">CATEGORIES</h4>
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
				<h4 class="footer-heading">OTHER LINKS</h4>
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
	<?php wp_footer() ?>

	<?php if (is_singular()) { ?>
		<script src="<?php echo get_stylesheet_directory_uri().'/_includes/js/comment-reply.js' ?>" type="text/javascript" async></script>
		<script src="<?php echo get_stylesheet_directory_uri().'/_includes/js/comment-vote.js' ?>" type="text/javascript" async></script>
	<?php	} ?>
</body>

<?php 
	$content = ob_get_clean();
	echo $content;
?>