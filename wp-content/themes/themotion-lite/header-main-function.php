<?php
/**
 * TheMotion main header functions ( for lite version )
 *
 * @package themotion
 */

/**
 * Display the page header.
 */
function themotion_page_header() {

	$themotion_page_header = get_the_post_thumbnail_url();
	?>

	<header class="page-main-header" 
	<?php
	if ( ! empty( $themotion_page_header ) ) {
		echo 'style="background-image:url(' . esc_url( $themotion_page_header ) . ')"'; }
?>
 >
		<div class="container">
			<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
		</div>
	</header><!-- .entry-header -->
<?php }
?>
