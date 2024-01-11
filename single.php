<?php
/**
 * Single Page
 *
 * @package Zdev
 * @since 1.0.0
 */

get_header();
?>

<main>
	<?php
	if ( have_posts() ) :
		while ( have_posts() ) :
			the_post();
			echo esc_html( get_the_title() );
		endwhile;
	endif;
	?>
</main>

<?php
get_footer();
