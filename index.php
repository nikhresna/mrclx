<?php 

ob_start();

get_header();

if (have_posts()) {
  if (is_category()) {
    var_dump(single_cat_title('', true));
    var_dump(single_term_title('', true));
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
          <?php // the_post_thumbnail('full', array('class' => 'article-image')); ?>
          <div class="article-image-overlay"></div>
          <span class="article-link"><?php the_title(); ?></span>
        </a>
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