<?php ob_start() ?>
	<meta charset="utf-8">
	<title><?php wp_title('|',true,'right'); ?><?php bloginfo('name'); ?></title>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="description" content="Nikko Khresna">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="icon" href="<?php echo get_stylesheet_directory_uri().'/_includes/img/icon.png' ?>" type="image/x-icon"/>
	<link rel="shortcut icon" href="<?php echo get_stylesheet_directory_uri().'/_includes/img/icon.png' ?>" type="image/x-icon"/>
	<?php wp_head() ?>
	<?php wsl_add_javascripts(); ?>
	<link href="https://fonts.googleapis.com/css?family=Montserrat|Lato" rel="stylesheet">
	<!-- <link href="<?php echo get_stylesheet_directory_uri().'/style.css' ?>" type="text/css" rel="stylesheet"> -->
</head>
<body <?php echo body_class(); ?>>
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
	</div>

<?php 
	$content = ob_get_clean();
	echo $content;
?>

