<?php
/*
Template Name: Page contact
*/

get_header();
?>
<div class="container">


<?php if (have_posts()) : ?>

	<?php while (have_posts()) : the_post(); ?>
    <div class="row">
      <h1 class="col-xs-12"><?php the_title(); ?></h1>
    </div>
    <div class="row">
      <div class="cols-xs-12 col-md-6">
          <?php echo do_shortcode('[wpforms id="136" title="false" description="false"]'); ?>
      </div>
      <div class="cols-xs-12 col-md-6">
          <?php the_content(); ?>
      </div>
    </div>

	<?php endwhile; ?>
	<?php else : ?>
<?php endif; ?>
</div>
<?php
get_footer();

 ?>
