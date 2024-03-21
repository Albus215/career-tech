<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package career_theme
 */

get_header();
?>

	<main id="primary" class="site-main">
		<h1>Content</h1>
		<?php the_content(); ?>
	</main><!-- #main -->

<?php
get_footer();
