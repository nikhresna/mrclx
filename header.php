<?php ob_start() ?>
	<meta charset="utf-8">
	<title><?php wp_title('|',true,'right'); ?><?php bloginfo('name'); ?></title>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="icon" href="<?php echo get_stylesheet_directory_uri().'/_includes/img/icon.png' ?>" type="image/x-icon"/>
	<link rel="shortcut icon" href="<?php echo get_stylesheet_directory_uri().'/_includes/img/icon.png' ?>" type="image/x-icon"/>
	<!-- <link href="https://fonts.googleapis.com/css?family=Fjord+One|Lato" rel="stylesheet"> -->
	<link href="<?php echo get_stylesheet_directory_uri().'/style.css' ?>" type="text/css" rel="stylesheet">
</head>
<body class="<?php echo body_class(); ?>">
	<div id="header">
		<nav id="nav">
			<img src="<?php echo get_stylesheet_directory_uri(); ?>/_includes/img/logo.png" class="logo">
			<?php
				// call primary menu
				if( has_nav_menu('primary_menu') ) {
					wp_nav_menu( array(
						'theme_location' 	=> 'primary_menu',
						'container' 		=> 'ul',
						'container_class' 	=> 'menu',
						'items_wrap' 		=> '<ul id="%1$s" class="%2$s">%3$s</ul>' ) 
					); 
				} else {
					echo '<li><a href="#">No menu assigned!</a></li>';
				}
			?>
		</nav>
		<?php // get_search_form( true ); ?>
		<ul class="helper-nav">
			<?php if (is_user_logged_in()){ ?>
				<li>
					<a href="<?php echo wp_logout_url(get_permalink()); ?>">Logout</a>
				</li>
			<?php } else { ?>
				<li>
					<a href="<?php echo wp_login_url(get_permalink()); ?>">Login</a>
				</li>
			<?php } ?>
		</ul>
	</div>

<?php 
	$content = ob_get_clean();
	echo $content;
?>