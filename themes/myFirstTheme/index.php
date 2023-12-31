<?php
get_header();
pageBanner(array(
    'title' => 'Welcome to our blog!',
    'subtitle' => 'Keep up with our latest news.'
));
?>


<div class="container container--narrow page-section">
<?php 
	while(have_posts()) {
		the_post(); ?>
		<div class="post-item">
			<h2 class="headline headline--medium headline--post-tile"><a href="<?php the_permalink(); ?>"></a><?php the_title(); ?></h2>

			<div class="metabox">
				<p>Posted by <?php the_author_posts_link(); ?> on <?php the_time('y-j-n'); ?> in <?php echo get_the_category_list(', '); ?></p>
			</div>

			<div class="generic-content">
				<?php the_excerpt(); ?>
				<p><a class="btn btn--blue" href="<?php the_permalink(); ?>">Continue reading &raquo;</a></p>
			</div>
		</div>
	<?php }

		echo paginate_links();

?>
</div>
<?php
get_footer();
?>