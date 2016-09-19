<?php
if ( post_password_required() )
  return;
?>
 
<div id="comments" class="comments-area">
 
  <?php if ( have_comments() ) : ?>
    <h2 class="comments-title">
      <?php
        printf( _nx( 'One comment"', '%1$s comments', get_comments_number(), 'comments title'),
            number_format_i18n( get_comments_number() ) );
      ?>
    </h2>

    <ul class="comment-list">
      <?php
        wp_list_comments( array(
          'style'       => 'ul',
          'short_ping'  => true,
          'avatar_size' => 40,
          'callback' => 'comment_callback',
 	     ) );
      ?>
    </ul><!-- .comment-list -->

    <?php
      // Are there comments to navigate through?
      if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) :
    ?>
    <nav class="navigation comment-navigation" role="navigation">
      <h1 class="screen-reader-text section-heading"><?php _e( 'Comment navigation' ); ?></h1>
      <div class="nav-previous"><?php previous_comments_link( __( '&larr; Older Comments' ) ); ?></div>
      <div class="nav-next"><?php next_comments_link( __( 'Newer Comments &rarr;' ) ); ?></div>
    </nav><!-- .comment-navigation -->
    <?php endif; // Check for comment navigation ?>

    <?php if ( ! comments_open() && get_comments_number() ) : ?>
    <p class="no-comments"><?php _e( 'Comments are closed.' ); ?></p>
    <?php endif; ?>

  <?php endif; // have_comments() ?>
 
</div><!-- #comments -->