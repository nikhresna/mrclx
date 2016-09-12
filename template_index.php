<?php
/**
 * Template Name: Index
 */
ob_start();

get_header();



get_footer();

$content = ob_get_clean();
echo $content;