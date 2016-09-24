<?php 
// register menu
add_action( 'init', 'register_menus' );

function register_menus() {
	register_nav_menus( array(
		'primary_menu' => 'Primary Menu',
		'footer_menu' => 'Footer Menu',
		'footer_links' => 'Footer Links',
		'logout_menu' => 'Logout Links',
	) );
}


// Add customizer settings
add_action( 'customize_register', 'tm_customizer_regist' );

function tm_customizer_regist( $wp_customize ) {

	// Navbar customizer
	$wp_customize->add_section( 'navbar_mod' , array(
		'title'      => __( 'Navbar Mod', 'twellve_miracle' ),
		'priority'   => 30,
	) );
	
	$wp_customize->add_setting( 'header_logo' , array(
		'default'     => '',
		'transport'   => 'refresh',
	) );
	$wp_customize->add_control( 
		new WP_Customize_Media_Control( $wp_customize, 'image_control', array(
			'label' => __( 'Navbar logo', 'twellve_miracle' ),
			'section' => 'navbar_mod',
			'settings' => 'header_logo',
			'mime_type' => 'image',
		) )
	);
}

add_support();
function add_support() {
	add_theme_support( 'post-formats', array( 'aside', 'gallery', 'image', 'quote', 'status', 'video', 'chat' ) );
	
	add_post_type_support( 'post', 'post-formats' );

	// enable post-thumbnail
	add_theme_support( 'post-thumbnails' );
}

add_filter( 'get_search_form', 'custom_search_form' );
function custom_search_form( $form ) {
	$form = '
		<form role="search" method="get" id="searchform" class="searchform" action="' . home_url( '/' ) . '" >
			<div class="search-form-container">
				<label class="screen-reader-text hidden" for="s">' . __( 'Search for:' ) . '</label>
				<input class="search-form" type="text" value="' . get_search_query() . '" name="s" id="s" placeholder="Ask your query" />
				<span class="search-form-submit-icon">
					<i class="fa fa-chevron-right"></i>
					<input class="search-form-submit" type="submit" id="searchsubmit" value="'. esc_attr__( 'Search' ) .'" />
				</span>
			</div>
		</form>
	';

	return $form;
}

add_filter( 'wp_calculate_image_srcset_meta', '__return_null' );

add_filter( 'post_thumbnail_html', 'remove_thumbnail_dimensions', 10, 3 );
function remove_thumbnail_dimensions( $html, $post_id, $post_image_id ) {
    $html = preg_replace( '/(width|height)=\"\d*\"\s/', "", $html );
    return $html;
}

add_filter( 'comment_form_default_fields', 'comment_field' );

function comment_field( $fields ) {
	return '';
}

if ( is_singular() ) wp_enqueue_script( 'comment-reply', false, '', false, true );

function comment_callback($comment, $args, $depth) {
  if ( 'div' === $args['style'] ) {
    $tag       = 'div';
    $add_below = 'comment';
  } else {
    $tag       = 'li';
    $add_below = 'div-comment';
  }
  ?>
  <<?php echo $tag ?> <?php comment_class( empty( $args['has_children'] ) ? '' : 'parent' ) ?> id="comment-<?php comment_ID() ?>">
  <?php if ( 'div' != $args['style'] ) : ?>
    <div id="div-comment-<?php comment_ID() ?>" class="comment-body">
  <?php endif; ?>
  <div class="comment-author vcard">
    <?php if ( $args['avatar_size'] != 0 ) echo get_avatar( $comment, $args['avatar_size'] ); ?>
  </div>
  <?php if ( $comment->comment_approved == '0' ) : ?>
     <em class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.' ); ?></em>
      <br />
  <?php endif; ?>


  <div class="comment-content">
	  <?php printf( __( '<span class="author-name">%s</span>' ), get_comment_author_link() ); ?>
    <img src="<?php echo get_stylesheet_directory_uri(); ?>/_includes/img/link.svg" class="comment-link" title="copy comment link to clipboard">
    <p class="js-notice"></p>
	  <input type="text" class="js-copy" value="<?php echo htmlspecialchars( get_comment_link( $comment->comment_ID ) ); ?>" readonly>
	  <div class="comment-text">
		  <?php 
		  	comment_text();
      ?>
	  </div>
	  <div class="comment-meta commentmetadata">
    	<?php printf( __('%1$s at %2$s'), get_comment_date(),  get_comment_time() ); ?><?php edit_comment_link( __( ' - Edit ' ), '  ', '' ); ?>
    	<?php if ( current_user_can('edit_comment') ) {
				$url = esc_url(wp_nonce_url( get_home_url()."/wp-admin/comment.php?action=deletecomment&p=$comment->comment_post_ID&c=$comment->comment_ID", "delete-comment_$comment->comment_ID" ));
				echo "<a href='$url' class='delete:the-comment-list:comment-$comment->comment_ID delete'>" . __('- Delete') . "</a> ";
				// echo  wp_delete_comment( $comment->comment_ID );
				comment_reply_link( array_merge( $args, array(
            'add_below' => 'div-comment',
            'depth'     => $depth,
            'reply_text' => '- Reply',
            'max_depth' => $args['max_depth'],
            'before'    => '',
            'after'     => '',
        ) ) );
			} ?>
    </div>

  </div>

  <?php if ( 'div' != $args['style'] ) : ?>
  </div>
  <?php endif; ?>
  <?php
}

add_filter( 'avatar_defaults', 'new_default_avatar' );

function new_default_avatar($avatar_defaults) {
	$image = wp_get_attachment_image_src(196);
  $avatar_defaults[$image[0]] = 'mrcl';
	return $avatar_defaults;
}


// edit profile page
remove_action( 'admin_color_scheme_picker', 'admin_color_scheme_picker' );


// login page
function login_stylesheet() {
	wp_enqueue_style( 'login-style', get_template_directory_uri() . '/login.css' );
}
function admin_stylesheet() {
	wp_enqueue_style( 'admin-style', get_template_directory_uri() . '/admin.css' );
}
add_action( 'login_enqueue_scripts', 'login_stylesheet' );
// add_action( 'admin_enqueue_scripts', 'admin_stylesheet' );

function my_login_logo() { ?>
    <style type="text/css">
        #login h1 a, .login h1 a {
            background-image: url(<?php echo get_stylesheet_directory_uri(); ?>/_includes/img/100x100.png);
            padding-bottom: 30px;
        }
    </style>
<?php }
add_action( 'login_enqueue_scripts', 'my_login_logo' );

remove_action('login_form', 'wsl_render_auth_widget_in_wp_login_form');
add_action('login_footer', 'wsl_render_auth_widget_in_wp_login_form');

add_filter( 'login_url', 'my_login_page', 10, 3 );
function my_login_page( $login_url, $redirect, $force_reauth ) {
    return home_url( '/login/?redirect_to=' . $redirect );
}

// sharer
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