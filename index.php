<?php 

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
        <div class="article-list-cat">
          <?php
            echo the_category(', ');
          ?>
        </div>
        <a href="<?php the_permalink(); ?>" class="article-list-image" style="background-image: url(<?php the_post_thumbnail_url('full', array('class' => 'article-image')); ?>);">
          <div class="article-image-overlay"></div>
          <h2 class="article-link"><?php the_title(); ?></h2>
          <div class="article-list-cat mobile">
            <?php
              echo the_category(', ');
            ?>
          </div>
        </a>
        <div class="the_excerpt">
          <?php the_excerpt(); ?>
          <a href="<?php the_permalink(); ?>" class="read-more">Full Reading</a>
        </div>
      </li>
      <?php
      
      // the_content();
  }
  
  ?>
    </ul>
      <div class="pagination">
        <div class="pagination-links">
          <?php
            if( get_previous_posts_link() ) : echo '<span class="newer">' . get_previous_posts_link( 'Newer articles', 0 ) . '</span>'; endif;
            if( get_next_posts_link() ) : echo '<span class="older">' . get_next_posts_link( 'Older articles', 0 ) . '</span>'; endif;
            if( !get_next_posts_link() ) : echo '<span class="more-readings">Need more readings?<br>Check out Other Links on the bottom of the page</span>'; endif;
          ?>
        </div>
      </div>
  <?php

} else {

  echo 'no post';

}

get_footer();

$content = ob_get_clean();
echo $content;