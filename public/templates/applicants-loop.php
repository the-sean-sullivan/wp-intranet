<?php
	global $post;

	$args = array(
		'posts_per_page'  => 100,
		'orderby'         => 'post_date',
		'order'           => 'DESC',
		'post_type'       => 'applicants',
		'post_status'     => 'publish',
		'meta_key'		  => $meta_key,
		'meta_value'	  => $meta_value,
		'paged'			  => $paged,
		'post_parent' 	  => 0
	);
	$loop = new WP_Query( $args );
	if( $loop->have_posts() ): while ( $loop->have_posts() ) : $loop->the_post();

		if ( get_field('job_applying_for') )
			$the_job = get_field('job_applying_for');
		else
			$the_job = 'No position, from old app.';

		$post_views = Srs_Intranet_Public::set_post_views( $post->ID );
?>

	<tr class="candidate-row <?php echo ( $post_views == 0 ) ? 'unread' : ''; ?>" data-href="<?php the_permalink(); ?>" views="<?php echo $post_views; ?>">
		<td><?php echo $the_job; echo ( $the_job == 'General Submission') ? ' - ' . strip_tags ( get_the_term_list( $post->ID, 'applicant-dept' ) ) : ''; ?></td>
		<td><?php the_title(); ?></td>
		<td><?php the_time('m/d/Y'); ?></td>
		<td>
			<?php
				echo strip_tags ( get_the_term_list( $post->ID, 'applicant-status' ) );
				echo ( get_field('notes') || get_comments_number( $post->ID ) ) ? '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"><path d="M20 2H4a2 2 0 0 0-2 2v18l4-4h14a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2zM8 14H6v-2h2v2zm0-3H6V9h2v2zm0-3H6V6h2v2zm7 6h-5v-2h5v2zm3-3h-8V9h8v2zm0-3h-8V6h8v2z" fill="#9bdaeb"/></svg>' : '';
			?>
		</td>
	</tr>

<?php endwhile; else: ?>

	<tr class="candidate-row">
		<td colspan="6"><h4 style="text-align: center">Sorry, no results. Please try again.</h4></td>
	</tr>

<?php endif; wp_reset_postdata(); ?>
