<?php
/**
 * The template for displaying the blog index
 *
 * This template is shown when the blog index is selected as the static front
 * page option.
 *
 * @package luigi
 */

get_header(); ?>

<div id="content" class="site-content">
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

			<header>
				<h1 class="entry-title">
					<?php single_post_title(); ?>
				</h1>
			</header>

			<?php
				while ( have_posts() ) : the_post();
					get_template_part( 'content' );
				endwhile;

				the_posts_navigation(
					array(
						'prev_text' => esc_html__( '&larr; Older posts', 'lugi' ),
						'next_text' => esc_html__( 'Newer posts &rarr;', 'lugi' ),
					)
				);
			?>

		</main><!-- #main -->
	</div><!-- #primary -->
	<?php get_sidebar(); ?>
</div><!-- #content -->

<?php
get_footer();
