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
            echo the_category(', ');
          ?>
        </div>
        <div class="article-list-image" style="background-image: url(<?php the_post_thumbnail_url('full', array('class' => 'article-image')); ?>);">
          <?php // the_post_thumbnail('full', array('class' => 'article-image')); ?>
          <div class="article-image-overlay"></div>
          <span class="article-link"><?php echo strtoupper(get_the_title()); ?></span>
        </div>

        <div class="article-wrapper">
          <div class="article-content">
          	<?php  
          		if (current_user_can('edit_posts' )) {
          			?>
          				<a href="<?php echo get_edit_post_link(); ?>">Edit</a>
          			<?php
          		}

  	        	the_content();

              article_meta();

              sharer();

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
                    get_home_url('', '/login')
                  ) . '</p>',

                'logged_in_as' => '',

                'comment_notes_before' => '',

                'comment_notes_after' => '<p class="comment_notes_after">* markdown supported. <a href="'. get_home_url() .'/markdown-reference">Markdown reference.</a></p>',

                'fields' => apply_filters( 'comment_form_default_fields', $fields ),
              );

              after_article($form_args);

  	        	?>
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