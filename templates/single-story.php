<?php
/**
 * The template for displaying single stories
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

		<?php
		// Start the loop.
		while ( have_posts() ) : the_post();

			var_dump(get_post_meta($post->ID));

			/*
			* Contents of content.php file
			*
			* @Todo: figure out how to override with its own content-story.php file
			*/

			?>

			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<?php
					// Post thumbnail.
					twentyfifteen_post_thumbnail();
				?>

				<header class="entry-header story-header">
					<?php
						if ( is_single() ) :
							the_title( '<h1 class="entry-title story-title">', '</h1>' );
						else :
							the_title( sprintf( '<h2 class="entry-title story-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' );
						endif;

						$writer_name = get_post_meta($post->ID, 'story_writer_name', true);
						echo '<span class="writer-name">by ' . $writer_name . '</span>';

					?>
				</header><!-- .entry-header -->

				<div class="story-audio">

					<?php

					$story_audio_array = get_post_meta($post->ID, 'story_audio_info', true);

					// 0 = Soundcloud, 1 = Youtube

					// Soundcloud
					echo '<iframe width="100%" height="150" scrolling="no" frameborder="no" src="https://w.soundcloud.com/player/?url=' . $story_audio_array[0] . '&amp;auto_play=false&amp;hide_related=true&amp;show_comments=false&amp;show_user=true&amp;show_artwork=false&amp;show_reposts=false&amp;visual=false"></iframe>';

					// Youtube
					echo $story_audio_array[1];


					?>
				</div>

				<div class="entry-content">
					<?php
						/* translators: %s: Name of current post */
						the_content( sprintf(
							__( 'Continue reading %s', 'twentyfifteen' ),
							the_title( '<span class="screen-reader-text">', '</span>', false )
						) );

						wp_link_pages( array(
							'before'      => '<div class="page-links"><span class="page-links-title">' . __( 'Pages:', 'twentyfifteen' ) . '</span>',
							'after'       => '</div>',
							'link_before' => '<span>',
							'link_after'  => '</span>',
							'pagelink'    => '<span class="screen-reader-text">' . __( 'Page', 'twentyfifteen' ) . ' </span>%',
							'separator'   => '<span class="screen-reader-text">, </span>',
						) );
					?>
				</div><!-- .entry-content -->

				<?php
					// Author bio.
					if ( is_single() && get_the_author_meta( 'description' ) ) :
						get_template_part( 'author-bio' );
					endif;
				?>

				<footer class="entry-footer">
					<?php twentyfifteen_entry_meta(); ?>
					<?php edit_post_link( __( 'Edit', 'twentyfifteen' ), '<span class="edit-link">', '</span>' ); ?>
				</footer><!-- .entry-footer -->

			</article><!-- #post-## -->

			<?php

			/* End contents of content.php file */


			
			/*
			 * Include the post format-specific template for the content. If you want to
			 * use this in a child theme, then include a file called called content-___.php
			 * (where ___ is the post format) and that will be used instead.
			 */
			//get_template_part( 'content', 'story' );

			// If comments are open or we have at least one comment, load up the comment template.
			if ( comments_open() || get_comments_number() ) :
				comments_template();
			endif;

		// End the loop.
		endwhile;
		?>

		</main><!-- .site-main -->
	</div><!-- .content-area -->

<?php get_footer(); ?>
