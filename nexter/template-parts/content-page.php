<?php
/**
 * Template part for displaying page content in page.php
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Nexter
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<!--<header class="entry-header">
		<?php //the_title( '<h1 class="entry-title">', '</h1>' ); ?>
	</header>--><!-- .entry-header -->

	<?php if(nexter_get_option('page-hide-fea-image') != 'on') {
		nexter_post_thumbnail();
	}else if(nexter_get_option('page-hide-fea-image') == 'on') { ?>
		<div class="nxt-hidden"><?php nexter_post_thumbnail(); ?></div>
	<?php } ?>

	<div class="entry-content">
		<?php
		the_content();

		wp_link_pages( array(
			'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'nexter' ),
			'after'  => '</div>',
		) );
		?>
	</div><!-- .entry-content -->

	<?php if ( get_edit_post_link() ) : ?>
		<footer class="entry-footer">
			<?php
			edit_post_link(
				sprintf(
					wp_kses(
						/* translators: %s: Name of current post. Only visible to screen readers */
						__( 'Edit <span class="screen-reader-text">%s</span>', 'nexter' ),
						array(
							'span' => array(
								'class' => array(),
							),
						)
					),
					get_the_title()
				),
				'<span class="edit-link">',
				'</span>'
			);
			?>
		</footer><!-- .entry-footer -->
	<?php endif; ?>
</article><!-- #post-<?php the_ID(); ?> -->
