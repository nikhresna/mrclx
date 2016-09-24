<?php 

$img_class = "article-title";
$title_class = "";
$overlay = "";

if(has_post_thumbnail()) {
  $img_class = "article-list-image";
  $title_class = "article-link";
  $overlay = "<div class='article-image-overlay'></div>";
}

ob_start();

get_header();

if (have_posts()) {
  if (is_category()) {
    ?> 
    <div class="category-title">
      <h3><?php echo $wp_query->get_queried_object()->name ?></h3> 
    </div>
    <?php
  }

  ?><ul class="article-list"><?php

  while (have_posts()) {

    the_post();
      ?>
      <li class="article-list-item">
        <div class="<?php echo $img_class ?>" style="background-image: url(<?php the_post_thumbnail_url('full', array('class' => 'article-image')); ?>);">
          <?php echo $overlay ?>
          <h2 class="<?php echo $title_class ?>"><?php echo strtoupper(get_the_title()); ?></h2>
        </div>
        <div class="article-wrapper">
          <div class="article-content">
            <?php the_content(); ?>
          </div>
        </div>
      </li>
      <?php
      
  }

} else {

  echo 'no post';

}

get_footer();

$content = ob_get_clean();
echo $content;