<?php
/**
 * Template Name: login
 */

ob_start();

get_header('login');
?>
	<body <?php echo body_class(); ?>>
		
	<link href="https://fonts.googleapis.com/css?family=Montserrat|Lato" rel="stylesheet">
	<link href="<?php echo get_stylesheet_directory_uri().'/style.css' ?>" type="text/css" rel="stylesheet">
<?php

if (!is_user_logged_in()) {

	do_action( 'wordpress_social_login' );

} else {

	$red = $_GET["redirect_to"];

	if (strpos($red, get_home_url()) !== 0) {

		header('Location: '.get_home_url());

	} else {

		header('Location: '.$red);

	}

}

?>
	</body>
<?php

wp_footer();
// get_footer();

$content = ob_get_clean();
echo $content;