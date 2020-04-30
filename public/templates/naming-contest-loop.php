<?php
	global $post;

	$args = array(
		'posts_per_page'  => 200,
		'orderby'         => 'post_date',
		'order'           => 'DESC',
		'post_type'       => 'naming-contest',
		'post_status'     => 'publish',
		'paged'			  => $paged,
		'post_parent' 	  => 0
	);
	$loop = new WP_Query( $args );
	
	if( $loop->have_posts() ): while ( $loop->have_posts() ) : $loop->the_post();
?>

	<tr class="candidate-row">
		<td><?php the_title(); ?></td>
		<td><?php the_field('entry'); ?></td>
	</tr>

<?php endwhile; else: ?>

	<tr class="candidate-row">
		<td colspan="3"><h4 style="text-align: center">Sorry, no results. Please try again.</h4></td>
	</tr>

<?php endif; wp_reset_postdata(); ?>