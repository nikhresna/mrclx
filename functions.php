<?php 
// register menu
add_action('init', 'register_menus');

function register_menus() {
	register_nav_menus(array(
		'primary_menu' => 'Primary Menu',
		'footer_menu' => 'Footer Menu',
		'footer_links' => 'Footer Links',
		'logout_menu' => 'Logout Links',
	));
}


// Add customizer settings
add_action('customize_register', 'tm_customizer_regist');

function tm_customizer_regist($wp_customize) {

	// Navbar customizer
	$wp_customize->add_section('navbar_mod' , array(
		'title'      => __('Navbar Mod', 'twellve_miracle'),
		'priority'   => 30,
	));
	
	$wp_customize->add_setting('header_logo' , array(
		'default'     => '',
		'transport'   => 'refresh',
	));
	$wp_customize->add_control(
		new WP_Customize_Media_Control($wp_customize, 'image_control', array(
			'label' => __('Navbar logo', 'twellve_miracle'),
			'section' => 'navbar_mod',
			'settings' => 'header_logo',
			'mime_type' => 'image',
		))
	);
}

add_support();
function add_support() {
	add_theme_support('post-formats', array('aside', 'gallery', 'image', 'quote', 'status', 'video', 'chat'));
	
	add_post_type_support('post', 'post-formats');

	// enable post-thumbnail
	add_theme_support('post-thumbnails');
}

// remove support
show_admin_bar(false);
remove_action('wp_head', 'print_emoji_detection_script', 7);
remove_action('wp_print_styles', 'print_emoji_styles');
remove_action('wp_head', 'wp_generator');
remove_action('wp_head', 'wlwmanifest_link');
remove_action ('wp_head', 'rsd_link');
remove_action('wp_head', 'rest_output_link_wp_head', 10);
remove_action('wp_head', 'wp_oembed_add_discovery_links', 10);
remove_action('wp_head', 'wp_resource_hints', 2);

// add support
function enqueue_style() {
		wp_enqueue_script( 'jquery' );
    wp_enqueue_style('theme-css', get_stylesheet_uri());

    wp_enqueue_script('prism-js', get_template_directory_uri() . '/_includes/vendor/prism/prism.js', array(), '1.0.0', true);

    wp_enqueue_script('typeahead-js', get_template_directory_uri() . '/_includes/vendor/typeahead/typeahead.js', array(), '1.0.0', true);

		wp_enqueue_script( 'hogan-js' , get_template_directory_uri() . '/_includes/vendor/hogan/hogan.js', array(), '', true );

    wp_enqueue_script('theme-js', get_template_directory_uri() . '/_includes/js/script.js', array(), '1.0.0', true);
    wp_enqueue_script('search-js', get_template_directory_uri() . '/_includes/js/search.js', array(), '1.0.0', true);

		$wp_typeahead_vars = array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) );
		wp_localize_script( 'search-js', 'wp_typeahead', $wp_typeahead_vars );

    wp_deregister_script( 'wp-embed' );

}
// add_action('wp_ajax_nopriv_ajax_search', 'ajax_search');
// add_action('wp_ajax_ajax_search', 'ajax_search');


add_action('wp_enqueue_scripts', 'enqueue_style');

add_filter('get_search_form', 'custom_search_form');


add_filter('wp_calculate_image_srcset_meta', '__return_null');

add_filter('post_thumbnail_html', 'remove_thumbnail_dimensions', 10, 3);
function remove_thumbnail_dimensions($html, $post_id, $post_image_id) {
    $html = preg_replace('/(width|height)=\"\d*\"\s/', "", $html);
    return $html;
}

add_filter('comment_form_default_fields', 'comment_field');

function comment_field($fields) {
	return '';
}

if (is_singular()) wp_enqueue_script('comment-reply', false, '', false, true);



add_filter('avatar_defaults', 'new_default_avatar');

function new_default_avatar($avatar_defaults) {
	$image = wp_get_attachment_image_src(196);
  $avatar_defaults[$image[0]] = 'mrcl';
	return $avatar_defaults;
}


// edit profile page
remove_action('admin_color_scheme_picker', 'admin_color_scheme_picker');


// login page
function login_stylesheet() {
	wp_enqueue_style('login-style', get_template_directory_uri() . '/login.css');
}
function admin_stylesheet() {
	wp_enqueue_style('admin-style', get_template_directory_uri() . '/admin.css');
}
add_action('login_enqueue_scripts', 'login_stylesheet');
// add_action('admin_enqueue_scripts', 'admin_stylesheet');

function my_login_logo() { ?>
    <style type="text/css">
        #login h1 a, .login h1 a {
            background-image: url(<?php echo get_stylesheet_directory_uri(); ?>/_includes/img/100x100.png);
            padding-bottom: 30px;
        }
    </style>
<?php }
add_action('login_enqueue_scripts', 'my_login_logo');

remove_action('login_form', 'wsl_render_auth_widget_in_wp_login_form');
add_action('login_footer', 'wsl_render_auth_widget_in_wp_login_form');

add_filter('login_url', 'my_login_page', 10, 3);
function my_login_page($login_url, $redirect, $force_reauth) {
    return home_url('/login/?redirect_to=' . $redirect);
}

// comment vote

// function that renders html
				// <label class="screen-reader-text hidden" for="s">' . __('Search for:') . '</label>
function custom_search_form($form) {
	$form = '
		<form role="search" method="get" id="searchform" class="searchform" action="' . home_url('/') . '" >
			<div class="search-form-container">
				<input class="search-form" type="text" value="' . get_search_query() . '" name="s" id="s" placeholder="type to search..." />
				<span class="search-form-submit-icon">
					<input class="search-form-submit" type="submit" id="searchsubmit" value="'. esc_attr__('Search') .'" />
				</span>
			</div>
		</form>
	';

	return $form;
}

function comment_callback($comment, $args, $depth) {
  if ('div' === $args['style']) {
    $tag       = 'div';
    $add_below = 'comment';
  } else {
    $tag       = 'li';
    $add_below = 'div-comment';
  }
  ?>
  <<?php echo $tag ?> <?php comment_class(empty($args['has_children']) ? '' : 'parent') ?> id="comment-<?php comment_ID() ?>">
  <?php if ('div' != $args['style']) : ?>
    <div id="div-comment-<?php comment_ID() ?>" class="comment-body">
  <?php endif; ?>
  <div class="comment-author vcard">
    <?php if ($args['avatar_size'] != 0) echo get_avatar($comment, $args['avatar_size']); ?>
  </div>
  <?php if ($comment->comment_approved == '0') : ?>
     <em class="comment-awaiting-moderation"><?php _e('Your comment is awaiting moderation.'); ?></em>
      <br />
  <?php endif; ?>


  <div class="comment-content">
	  <?php printf(__('<span class="author-name">%s</span>'), get_comment_author_link()); ?>
    <img src="<?php echo get_stylesheet_directory_uri(); ?>/_includes/img/link.svg" class="comment-link" title="copy comment link to clipboard">
    <p class="js-notice"></p>
	  <input type="text" class="js-copy" value="<?php echo htmlspecialchars(get_comment_link($comment->comment_ID)); ?>" readonly>
	  <div class="comment-text">
		  <?php 
		  	comment_text();
      ?>
	  </div>
	  <div class="comment-meta commentmetadata">
    	<?php printf(__('%1$s at %2$s'), get_comment_date(),  get_comment_time()); ?><?php edit_comment_link(__(' - Edit '), '  ', ''); ?>
    	<?php if (current_user_can('edit_comment')) {
				$url = esc_url(wp_nonce_url(get_home_url()."/wp-admin/comment.php?action=deletecomment&p=$comment->comment_post_ID&c=$comment->comment_ID", "delete-comment_$comment->comment_ID"));
				echo "<a href='$url' class='delete:the-comment-list:comment-$comment->comment_ID delete'>" . __('- Delete') . "</a> ";
				// echo  wp_delete_comment($comment->comment_ID);

				?>
					<div class="comment-ui">
						<a href="#" class="comment-up"><img src="<?php echo get_stylesheet_directory_uri(); ?>/_includes/img/chevron-up.svg"></a>
						<span class="comment-num">0</span>
						<a href="#" class="comment-down"><img src="<?php echo get_stylesheet_directory_uri(); ?>/_includes/img/chevron-down.svg"></a>
						<?php

						comment_reply_link(array_merge($args, array(
		            'add_below' => 'div-comment',
		            'depth'     => $depth,
		            'reply_text' => '- Reply',
		            'max_depth' => $args['max_depth'],
		            'before'    => '',
		            'after'     => '',
		       )));

		        ?>
        	</div>
        <?php 
			} ?>
    </div>

  </div>

  <?php if ('div' != $args['style']) : ?>
  </div>
  <?php endif; ?>
  <?php
}

function sharer() {
	?>
		<h4>Sharing is caring via:</h4>
		<ul>
			<li>
				<a href="https://www.facebook.com/sharer.php?u=<?php the_permalink(); ?>">Facebook</a>
			</li>
			<li>
				<a href="http://line.me/R/msg/text/?<?php the_title(); ?>%0D%0A<?php the_permalink(); ?>">LINE</a>
			</li>
			<li>
				<a href="whatsapp://send?text=<?php the_permalink(); ?>" data-action="share/whatsapp/share">Whatsapp</a>
			</li>
			<li>
				<a href="https://twitter.com/home?status=Check%20this%20article%20<?php the_permalink(); ?>" data-action="share/whatsapp/share">Twitter</a>
			</li>
		</ul>
	<?php
}

function after_article($form_args) {

	comment_form($form_args);

	?>
	<div class="comments-list">
		<?php comments_template(); ?>
	</div>
	<?php
}

function article_meta() {
	?>
		<p class="article-meta">
			Written by: <?php the_author(); ?>
		</p>
	<?php
}


add_action( 'rest_api_init', function () {
	register_rest_route( 'v1', '/posts.json', array(
		'methods' => 'GET',
		'callback' => 'my_awesome_func',
	) );
} );

function my_awesome_func( $data ) {
	global $wp_query;


  $args = array(
      'post_type'      => 'post',
      'posts_per_page' => 10,
  );
  $query = new WP_Query( $args );
  $list = array();
  if ( $query->have_posts() ) : while ( $query->have_posts() ) : $query->the_post();
      $list[] = array(
          'title' => get_the_title(),
          'link' => get_permalink(),
      );
  endwhile; wp_reset_postdata(); endif;

  wp_send_json( $list );
}
