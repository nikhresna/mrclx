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

		if(is_single()) {

	    wp_enqueue_script('comment-vote-js', get_template_directory_uri() . '/_includes/js/comment-vote.js', array(), '1.0.0', true);
	    wp_enqueue_script('comment-reply-js', get_template_directory_uri() . '/_includes/js/comment-reply.js', array(), '1.0.0', true);
			$comment_vote_vars = array( 'ajaxurl' => admin_url( 'admin-ajax.php' ), 'status' => is_user_logged_in() );
			wp_localize_script( 'comment-vote-js', 'comment_vote', $comment_vote_vars );

		}
    wp_deregister_script( 'wp-embed' );

}

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
    	<?php printf(__('%1$s at %2$s'), get_comment_date('M j Y, G i'),  get_comment_time()); ?><?php edit_comment_link(__(' - Edit '), '  ', ''); ?>
    	<?php if (current_user_can('edit_comment')) {
				$url = esc_url(wp_nonce_url(get_home_url()."/wp-admin/comment.php?action=deletecomment&p=$comment->comment_post_ID&c=$comment->comment_ID", "delete-comment_$comment->comment_ID"));
				// echo "<a href='$url' class='delete:the-comment-list:comment-$comment->comment_ID delete'>" . __('- Delete') . "</a> ";
				// echo  wp_delete_comment($comment->comment_ID);
				?>
					<div class="comment-ui">
						<?php
						comment_reply_link(array_merge($args, array(
		            'add_below' => 'div-comment',
		            'depth'     => $depth,
		            'reply_text' => 'Reply',
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
		<div class="sharer">
			<h4>Sharing is caring via:</h4>
			<ul class="share">
				<li class="share-item">
					<a href="https://www.facebook.com/sharer.php?u=<?php the_permalink(); ?>" title="Facebook">
						<svg height="512" preserveAspectRatio="xMidYMid" viewBox="0 0 512 512" width="512" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><defs><style>
			      .cls-1 {
			        fill: #5f9ea0;
			      }

			      .cls-2 {
			        fill: #000;
			        opacity: 0.3;
			      }

			      .cls-2, .cls-3 {
			        fill-rule: evenodd;
			      }

			      .cls-3 {
			        fill: #fff;
			      }
			    </style></defs><g><circle class="cls-1" cx="256" cy="256" r="256"/><path class="cls-2" d="M273.546,383.891 L184.498,501.881 C98.749,476.988 31.561,408.455 8.566,321.916 L255.647,137.497 L315.000,171.000 L271.000,213.000 L307.000,257.000 L271.000,298.000 L273.546,383.891 Z"/><path class="cls-3" d="M273.573,384.004 L273.998,257.630 L307.831,257.630 L312.497,215.268 L273.998,215.268 C273.484,179.977 274.917,173.987 286.831,170.552 C301.280,171.052 315.997,170.552 315.997,170.552 L315.997,132.896 C276.970,120.034 227.862,127.714 230.833,215.268 L197.001,215.268 L197.001,257.630 L229.666,257.630 L229.512,383.990 L273.573,384.004 Z"/></g></svg>
					</a>
				</li>
				<li class="share-item">
					<a href="whatsapp://send?text=<?php the_permalink(); ?>" data-action="share/whatsapp/share" title="Whatsapp">
						<svg height="512" preserveAspectRatio="xMidYMid" viewBox="0 0 512 512" width="512" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><defs>
						</defs><g><circle class="cls-1" cx="256" cy="256" r="256"/><path class="cls-2" d="M0.603,273.716 C9.492,403.755 115.491,507.116 246.634,511.832 L362.465,327.715 C362.465,327.715 410.983,232.173 341.000,166.000 C279.606,107.948 189.626,148.102 189.626,148.102 L0.603,273.716 Z"/><path class="cls-3" d="M257.684,382.728 C234.071,382.728 211.960,376.322 193.004,365.160 C193.004,365.160 155.845,376.500 137.233,382.180 C124.071,386.197 130.608,374.227 130.608,374.227 L148.480,321.121 C136.980,302.054 130.367,279.728 130.367,255.864 C130.367,185.799 187.369,129.000 257.684,129.000 C327.998,129.000 385.000,185.799 385.000,255.864 C385.000,325.929 327.998,382.728 257.684,382.728 ZM258.121,149.054 C199.646,149.054 152.243,196.289 152.243,254.556 C152.243,278.588 160.307,300.743 173.885,318.482 L158.293,360.058 L200.914,343.347 C217.411,353.923 237.047,360.058 258.121,360.058 C316.596,360.058 363.999,312.823 363.999,254.556 C363.999,196.289 316.596,149.054 258.121,149.054 ZM222.209,295.227 C164.059,237.476 188.271,209.807 209.891,195.284 C217.507,194.792 223.213,193.697 225.179,196.896 C228.127,202.558 236.155,222.743 238.857,229.143 C240.699,233.503 227.838,240.835 227.593,245.266 C227.109,253.997 249.827,282.153 275.870,288.799 C281.766,290.030 288.056,270.123 294.376,271.870 C301.501,273.839 320.105,282.867 325.756,285.574 C329.051,287.153 324.031,302.019 321.733,306.534 C308.466,332.599 252.264,325.075 222.209,295.227 Z"/></g></svg>
					</a>
				</li>
				<li class="share-item">
					<a href="https://twitter.com/home?status=Check%20this%20article%20<?php the_permalink(); ?>" title="Twitter">
						<svg height="512" preserveAspectRatio="xMidYMid" viewBox="0 0 512 512" width="512" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><defs>
						</defs><g><circle class="cls-1" cx="256" cy="256" r="256"/><path class="cls-2" d="M277.000,140.000 L313.000,188.000 L406.000,170.000 L330.817,265.461 L348.537,306.768 L210.575,507.982 C90.859,486.544 -0.000,381.883 -0.000,256.000 C-0.000,238.197 1.817,220.819 5.276,204.041 L120.350,139.197 C120.350,139.197 135.087,182.752 179.000,215.000 L277.000,140.000 Z"/><path class="cls-3" d="M413.000,159.421 L379.196,169.544 C379.196,169.544 401.949,150.680 405.886,131.976 C386.112,144.105 367.305,148.082 363.205,148.240 C355.075,124.813 251.536,100.712 251.536,205.989 C226.244,212.956 153.127,183.662 120.325,139.091 C118.224,144.304 87.865,189.520 139.411,226.628 C133.900,226.557 127.207,222.765 112.195,219.394 C112.195,272.459 162.599,283.713 162.599,283.713 C162.599,283.713 149.648,286.009 134.552,284.448 C152.070,329.378 192.922,330.094 192.922,330.094 C192.922,330.094 170.999,356.040 100.000,355.602 C208.518,423.648 376.908,369.446 384.547,192.735 C406.022,175.346 413.000,159.421 413.000,159.421 Z"/></g></svg>
					</a>
				</li>
				<li class="share-item">
					<a href="#" title="BBM">
						<svg height="512" preserveAspectRatio="xMidYMid" viewBox="0 0 512 512" width="512" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><defs>
						</defs><g><circle class="cls-1" cx="256" cy="256" r="256"/><path class="cls-2" d="M357.710,382.666 L279.371,510.948 C271.674,511.644 263.878,512.000 256.000,512.000 C114.615,512.000 -0.000,397.385 -0.000,256.000 C-0.000,245.947 0.580,236.029 1.707,226.277 L155.929,133.475 L325.000,133.475 L351.000,159.475 L364.000,159.475 L379.000,181.000 L378.000,312.000 L350.000,355.000 L357.710,382.666 Z"/><path class="cls-3" d="M346.259,334.507 L358.469,384.000 L309.688,333.675 C309.688,333.675 220.026,333.793 214.937,333.675 C205.419,333.453 194.989,322.859 194.989,322.859 C194.989,322.859 156.530,370.507 153.432,373.609 C150.333,376.710 141.244,374.708 143.458,366.953 C145.671,359.199 154.877,323.949 158.419,309.548 C142.925,304.230 130.990,283.630 130.990,272.109 C130.990,260.588 131.822,194.100 131.822,173.938 C131.822,152.668 151.354,129.844 171.717,129.844 C190.088,129.844 318.831,129.012 318.831,129.012 C318.831,129.012 341.921,129.144 352.908,153.139 C373.463,155.951 381.998,177.594 381.998,184.753 C381.998,191.913 381.998,280.148 381.998,296.236 C381.998,317.875 359.438,334.346 346.259,334.507 ZM343.765,170.610 C343.765,158.600 329.851,143.155 315.506,143.155 C301.161,143.155 182.691,143.867 170.886,143.987 C160.807,144.090 146.828,159.962 145.990,170.950 C145.981,170.879 145.968,171.026 145.951,171.442 C145.958,171.280 145.977,171.114 145.990,170.950 C146.152,172.211 145.120,247.856 145.120,272.109 C145.120,288.433 160.483,296.792 169.223,297.900 C173.675,298.465 174.094,304.597 173.379,307.052 C171.113,314.833 165.068,336.170 165.068,336.170 C165.068,336.170 191.597,299.769 194.989,299.564 C198.381,299.359 305.036,298.732 318.831,298.732 C332.625,298.732 343.765,285.521 343.765,272.941 C343.765,260.362 343.765,182.620 343.765,170.610 ZM375.349,186.417 C374.693,171.211 359.664,163.478 356.993,162.665 C357.888,164.182 357.857,164.541 357.895,166.450 C357.903,192.071 357.895,260.346 357.895,275.437 C357.895,291.658 340.425,313.707 317.168,313.707 C293.912,313.707 201.638,313.707 201.638,313.707 C201.638,313.707 200.009,315.950 199.145,317.035 C199.737,318.049 207.410,327.851 222.417,327.851 C237.424,327.851 313.844,327.851 313.844,327.851 L348.664,366.346 C348.664,366.346 339.236,333.904 338.778,331.179 C338.291,328.279 342.934,327.851 342.934,327.851 C355.056,327.851 375.349,317.210 375.349,297.068 C375.349,276.926 375.892,199.012 375.349,186.417 ZM299.714,223.023 L275.611,223.023 L280.598,199.729 L301.376,199.729 C301.376,199.729 312.044,202.235 311.350,209.712 C310.413,219.816 299.714,223.023 299.714,223.023 ZM304.701,245.487 C304.093,254.464 293.065,257.134 293.065,257.134 L268.962,257.134 L273.949,234.671 L295.558,234.671 C295.558,234.671 305.309,236.509 304.701,245.487 ZM255.663,203.057 L234.053,203.057 L239.871,179.762 L258.988,179.762 C258.988,179.762 272.173,181.429 270.624,191.409 C269.075,201.389 255.663,203.057 255.663,203.057 ZM263.975,223.856 C263.431,233.916 249.845,236.335 249.845,236.335 L228.235,236.335 L233.222,213.040 L254.001,213.040 C254.001,213.040 264.518,213.795 263.975,223.856 ZM257.325,261.294 C255.152,270.674 244.027,270.445 244.027,270.445 L221.586,270.445 L226.573,247.982 L249.014,247.982 C249.014,247.982 259.499,251.913 257.325,261.294 ZM214.106,203.057 L189.171,203.057 L194.158,179.762 L214.937,179.762 C214.937,179.762 225.715,181.529 224.910,189.745 C224.110,197.922 214.106,203.057 214.106,203.057 ZM218.261,223.856 C216.631,233.780 205.794,236.335 205.794,236.335 L183.353,236.335 L188.340,213.040 L209.119,213.040 C209.119,213.040 219.745,214.819 218.261,223.856 Z"/></g></svg>

						</svg>
					</a>
				</li>
				<li class="share-item">
					<a href="http://line.me/R/msg/text/?<?php the_title(); ?>%0D%0A<?php the_permalink(); ?>" title="LINE">
						<svg height="512" preserveAspectRatio="xMidYMid" viewBox="0 0 512 512" width="512" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><defs>
						</defs><g><circle class="cls-1" cx="256" cy="256" r="256"/><path class="cls-2" d="M258.269,511.990 C257.513,511.997 256.757,512.000 256.000,512.000 C114.615,512.000 -0.000,397.385 -0.000,256.000 C-0.000,253.956 0.024,251.918 0.072,249.886 L179.317,151.742 C179.317,151.742 270.412,132.913 318.000,165.000 C366.529,197.721 371.569,281.370 371.569,281.370 L258.269,511.990 Z"/><path class="cls-3" d="M382.484,248.004 C360.193,352.920 237.555,379.997 237.555,379.997 C237.555,379.997 254.490,340.507 240.220,337.457 C164.996,321.375 133.897,286.701 128.542,239.484 C121.582,178.112 184.822,132.993 255.158,132.993 C347.045,132.993 393.248,197.342 382.484,248.004 ZM204.367,251.910 C199.084,251.910 184.450,251.910 184.450,251.910 C184.450,251.910 184.450,213.914 184.450,207.894 C184.450,201.873 170.935,200.861 170.935,207.894 C170.935,213.575 170.935,247.149 170.935,259.837 C170.935,264.682 175.366,264.689 175.366,264.689 C175.366,264.689 200.181,264.689 204.367,264.689 C209.946,264.689 209.649,251.910 204.367,251.910 ZM227.840,207.894 C227.840,200.988 215.037,201.304 215.037,207.894 C215.037,214.484 215.037,253.095 215.037,259.010 C215.037,266.695 227.840,266.811 227.840,259.010 C227.840,251.208 227.840,214.799 227.840,207.894 ZM287.591,208.604 C287.591,199.754 274.788,201.119 274.788,207.184 C274.788,213.249 274.788,239.131 274.788,239.131 C274.788,239.131 252.948,209.027 249.180,204.344 C245.412,199.661 237.799,200.198 237.799,207.894 C237.799,215.590 237.799,252.431 237.799,259.010 C237.799,267.137 250.603,267.253 250.603,259.010 C250.603,250.766 250.603,228.482 250.603,228.482 C250.603,228.482 271.717,257.050 275.499,261.849 C279.281,266.649 287.591,265.087 287.591,259.010 C287.591,252.932 287.591,217.454 287.591,208.604 ZM330.271,202.924 C321.886,202.924 301.107,202.214 301.107,202.214 C301.107,202.214 296.839,203.295 296.839,207.894 C296.839,214.705 296.839,250.440 296.839,259.010 C296.839,262.713 299.483,264.689 301.818,264.689 C304.153,264.689 323.139,264.689 330.271,264.689 C337.403,264.689 336.965,251.910 328.848,251.910 C320.732,251.910 311.065,251.910 311.065,251.910 L310.354,239.841 C310.354,239.841 324.376,239.841 329.559,239.841 C334.743,239.841 338.692,226.352 328.848,226.352 C319.005,226.352 310.354,226.352 310.354,226.352 L310.354,215.703 C310.354,215.703 326.113,215.703 330.982,215.703 C335.851,215.703 338.655,202.924 330.271,202.924 Z"/></g></svg>
					</a>
				</li>
			</ul>
		</div>
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
      'post_status' => array( 'publish' ),
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


// typeahead
add_action( 'wp_ajax_nopriv_ajax_search', 'ajax_search' );
add_action( 'wp_ajax_ajax_search', 'ajax_search' );

function ajax_search() {
	if ( isset( $_REQUEST['fn'] ) && 'get_ajax_search' == $_REQUEST['fn'] ) {
		$search_query = new WP_Query( array(
			's' => $_REQUEST['terms'],
			'posts_per_page' => 10,
			'no_found_rows' => true,
		) );

		$results = array( );
		if ( $search_query->get_posts() ) {
			foreach ( $search_query->get_posts() as $the_post ) {
				$title = get_the_title( $the_post->ID );
				$results[] = array(
					'value' => $title,
					'url' => get_permalink( $the_post->ID ),
					'tokens' => explode( ' ', $title ),
				);
			}
		}

		wp_reset_postdata();
		echo json_encode( $results );
	}
	die();
}

// vote up
add_action( 'wp_ajax_comment_vote_up', 'comment_vote_up' );
add_action( 'wp_ajax_nopriv_comment_vote_up', 'comment_vote_up' );

function comment_vote_up() {
	$id = $_POST['data']['id'];
	$old_user_meta = get_user_meta( get_current_user_id(), 'voted_comment', $id );
	$old_value = get_comment_meta( $id, 'vote', true );
	if(in_array($id, $old_user_meta)) {
		$key = array_search($id, $old_user_meta);
	  unset($old_user_meta[$key]);
		update_user_meta( get_current_user_id(), 'voted_comment', $old_user_meta );
	  update_comment_meta( $id, 'vote', $old_value - 1 );
		wp_send_json_success( get_comment_meta( $id, 'vote', true ) );
	}
	array_push($old_user_meta, $id);
	update_user_meta( get_current_user_id(), 'voted_comment', $old_user_meta );
  update_comment_meta( $id, 'vote', $old_value + 1 );
  wp_send_json_success( get_comment_meta( $id, 'vote', true ) );
}

function meta_og() {
  global $post;
  echo '<meta property="og:site_name" content="http://mrcl.xyz/"/>';
  echo '<meta property="og:type" content="article"/>';
  echo '<meta property="og:url" content="' . get_permalink() . '"/>';
  echo '<meta property="og:title" content="' . get_the_title() . '"/>';
  if ( is_singular()) { //if it is not a post or a page
	  if(has_post_thumbnail( $post->ID )) { //the post does not have featured image, use a default image
	    $thumbnail_src = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'medium' );
	    echo '<meta property="og:image" content="' . esc_attr( $thumbnail_src[0] ) . '"/>';
	  } else {
	    $default_image="http://example.com/image.jpg"; //replace this with a default image on your server or an image in your media library
	    echo '<meta property="og:image" content="' . $default_image . '"/>';
	  }
	}
}


