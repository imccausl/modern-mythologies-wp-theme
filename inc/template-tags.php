<?php
/**
 * Custom template tags for this theme
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package Modern_Mythologies
 */

if ( ! function_exists( 'mmwordpresstheme_posted_on' ) ) :
/**
 * Prints HTML with meta information for the current post-date/time.
 */
function mmwordpresstheme_posted_on() {
	$date_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time> | ';
	if (get_the_time('U') !== get_the_modified_time('U')) {
		$date_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time> | ';
	}

    $time_string = '<time class="entry-time published updated" datetime="%1$s">%2$s</time>';
    if (get_the_time('U') !== get_the_modified_time('U')) {
        $time_string = '<time class="entry-time published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
    }

    $date_string = sprintf( $date_string,
		esc_attr( get_the_date( 'c' ) ),
		esc_html( get_the_date() ),
		esc_attr( get_the_modified_date( 'c' ) ),
		esc_html( get_the_modified_date() )
	);

	$time_string = sprintf( $time_string,
        esc_attr( get_the_time( 'g:i A T' ) ),
        esc_html( get_the_time () ),
        esc_attr( get_the_modified_time( 'g:i A T' ) ),
        esc_html( get_the_modified_time() )
    );


	$posted_on = sprintf(
		esc_html_x( '%s', 'post date', 'mmwordpresstheme' ),
		'<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $date_string . $time_string . '</a>'
	);

	echo '<span class="posted-on">' . $posted_on . '</span>'; // WPCS: XSS OK.

}
endif;

if ( ! function_exists( 'mmwordpresstheme_posted_by' ) ) :
/**
 * Prints HTML with meta information for the current author.
 */
  function mmwordpresstheme_posted_by() {
    $mm_post_id = get_queried_object_id();
    $mm_post_author_id = get_post_field( 'post_author', $mm_post_id );
    // $mm_author_name = the_author_meta( 'user_nicename', $mm_post_author_id );

    $byline = sprintf(
		  esc_html_x( 'by %s', 'post author', 'mmwordpresstheme' ),
		  '<span class="author vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( $mm_post_author_id ) ) . '">' . esc_html( get_the_author_meta( 'display_name', $mm_post_author_id ) ) . '</a></span> | '
	  );

    echo '<span class="byline"> ' . $byline . '</span>';
  }
endif;

if ( ! function_exists( 'mmwordpresstheme_entry_categories') ) :
  function mmwordpresstheme_entry_categories() {
    if ( 'post' === get_post_type() ) {
      /* translators: used between list items, there is a space after the comma */
      $categories_list = get_the_category_list( esc_html__( ' ', 'mmwordpresstheme' ) );
      if ( $categories_list && mmwordpresstheme_categorized_blog() ) {
        printf( '<span class="cat-links">' . esc_html__( '%1$s', 'mmwordpresstheme' ) . '</span>', $categories_list ); // WPCS: XSS OK.
      }
    }
  }
endif;

if ( ! function_exists ('mmwordpresstheme_entry_tags') ) :
 function mmwordpresstheme_entry_tags() {
   if ( 'post' === get_post_type() ) {
      /* translators: used between list items, there is a space after the comma */
      $tags_list = get_the_tag_list( '#', esc_html__( ' #', 'mmwordpresstheme' ) );
      if ( $tags_list ) {
        printf( '<span class="dont-display tags-links">' . esc_html__( '%1$s', 'mmwordpresstheme' ) . '</span>', $tags_list ); // WPCS: XSS OK.
      }
    }
  }
endif;

if ( ! function_exists( 'mmwordpresstheme_entry_footer' ) ) :
/**
 * Prints HTML with meta information for the categories, tags and comments.
 */
function mmwordpresstheme_entry_footer() {
	if ( ! is_single() && ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
		echo '<span class="comments-link">';
		/* translators: %s: post title */
		comments_popup_link( sprintf( wp_kses( __( 'Leave a Comment<span class="screen-reader-text"> on %s</span>', 'mmwordpresstheme' ), array( 'span' => array( 'class' => array() ) ) ), get_the_title() ) );
		echo '</span>';
	}
}
endif;

if ( ! function_exists( 'mmwordpresstheme_edit_post_link' ) ) :
    /**
     * Prints HTML with meta information for the categories, tags and comments.
     */
    function mmwordpresstheme_edit_post_link() {
        edit_post_link(
            sprintf(
            /* translators: %s: Name of current post */
                esc_html__( 'Edit %s', 'mmwordpresstheme' ),
                the_title( '<span class="screen-reader-text">"', '"</span>', false )
            ),
            '<span class="edit-link"> | ',
            '</span>'
        );
    }
endif;
/**
 * Returns true if a blog has more than 1 category.
 *
 * @return bool
 */
function mmwordpresstheme_categorized_blog() {
	if ( false === ( $all_the_cool_cats = get_transient( 'mmwordpresstheme_categories' ) ) ) {
		// Create an array of all the categories that are attached to posts.
		$all_the_cool_cats = get_categories( array(
			'fields'     => 'ids',
			'hide_empty' => 1,
			// We only need to know if there is more than one category.
			'number'     => 2,
		) );

		// Count the number of categories that are attached to the posts.
		$all_the_cool_cats = count( $all_the_cool_cats );

		set_transient( 'mmwordpresstheme_categories', $all_the_cool_cats );
	}

	if ( $all_the_cool_cats > 1 ) {
		// This blog has more than 1 category so mmwordpresstheme_categorized_blog should return true.
		return true;
	} else {
		// This blog has only 1 category so mmwordpresstheme_categorized_blog should return false.
		return false;
	}
}

/**
 * Flush out the transients used in mmwordpresstheme_categorized_blog.
 */
function mmwordpresstheme_category_transient_flusher() {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	// Like, beat it. Dig?
	delete_transient( 'mmwordpresstheme_categories' );
}
add_action( 'edit_category', 'mmwordpresstheme_category_transient_flusher' );
add_action( 'save_post',     'mmwordpresstheme_category_transient_flusher' );

/* Custom post navigation */

function mmwordpresstheme_post_navigation() {
    the_post_navigation( array(
        'next_text' => '<span class="meta-nav" aria-hidden="true">' . __( 'Next', 'mmwordpresstheme' ) . ' <i class="fa fa-chevron-right"></i></span> ' .
            '<span class="screen-reader-text">' . __( 'Next post:', 'mmwordpresstheme' ) . '</span> ' .
            '<span class="post-title">%title</span>',
        'prev_text' => '<span class="meta-nav" aria-hidden="true"><i class="fa fa-chevron-left"></i> ' . __( 'Previous', 'mmwordpresstheme' ) . '</span> ' .
            '<span class="screen-reader-text">' . __( 'Previous post:', 'mmwordpresstheme' ) . '</span> ' .
            '<span class="post-title">%title</span>',
    ) );
}
