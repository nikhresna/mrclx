<?php 

ob_start();

get_header();

$args = array(
	'numberposts' => 5,
	'offset' => 0,
	'category' => 0,
	'orderby' => 'post_date',
	'order' => 'DESC',
	'include' => '',
	'exclude' => '',
	'meta_key' => '',
	'meta_value' =>'',
	'post_type' => 'post',
	'post_status' => 'publish',
	'suppress_filters' => true
);

?>
<div class="not-found">
	<h1>404</h1>
	<p>not found</p>
</div>
<div class="recent-posts">	
<?php

// $recent_posts = wp_get_recent_posts( $args, ARRAY_A );
// foreach( $recent_posts as $recent ){
// 	echo '<li><a href="' . get_permalink($recent["ID"]) . '">' .   $recent["post_title"].'</a> </li> ';
// }

?>
</div>
<?php

wp_reset_query();

get_footer();

$content = ob_get_clean();
echo $content;