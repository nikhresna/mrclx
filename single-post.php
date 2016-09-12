<?php

ob_start();

get_header();

if (have_posts()) :
  while (have_posts()) :
    the_post();
  		
      ?>
      <li class="article-list-item">
        <div class="article-list-cat">
          <?php
            echo the_category(',');
          ?>
        </div>
        <div class="article-list-image" style="background-image: url(<?php the_post_thumbnail_url('full', array('class' => 'article-image')); ?>);">
          <?php // the_post_thumbnail('full', array('class' => 'article-image')); ?>
          <div class="article-image-overlay"></div>
          <span class="article-link"><?php the_title(); ?></span>
        </div>

        <div class="article-content">
        	<?php 
	        	the_content();

	        	$form_args = array(
						  'name_submit'       => 'submit',
						  'title_reply'       => __( 'Leave a Reply' ),
						  'title_reply_to'    => __( 'Leave a Reply to %s' ),
						  'cancel_reply_link' => __( 'Cancel Reply' ),
						  'label_submit'      => __( 'Comment' ),
						  'format'            => 'xhtml',

						  'comment_field' =>  '<p class="comment-form-comment"><textarea id="comment" name="comment" cols="45" rows="8" aria-required="true">' .
						    '</textarea></p>',

						  'must_log_in' => '<p class="must-log-in">' .
						    sprintf(
						      __( 'You must be <a href="%s">logged in</a> to post a comment.' ),
						      wp_login_url( apply_filters( 'the_permalink', get_permalink() ) )
						    ) . '</p>',

						  'logged_in_as' => '',

						  'comment_notes_before' => '',

						  'comment_notes_after' => '',

						  'fields' => apply_filters( 'comment_form_default_fields', $fields ),
						);
	        	comment_form($form_args);

	        	?>
	        	<div class="comments-list">
		        	<?php comments_template(); ?>
	        	</div>
        </div>
      </li>
      <?php
      
      // the_content();
  endwhile;
endif;

get_footer();

$content = ob_get_clean();
echo $content;