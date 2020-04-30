<?php

	// Get lightbox selected and filter out the results
	if ( isset( $_POST['lb_values'] ) ) :
		$lb_name = $_POST['lb_name'];
		$lb_values = $_POST['lb_values'];
		$lb_db_name = $_POST['lb_db_name'];

		$_SESSION['lb_name'] = $lb_name;
		$_SESSION['lb_values'] = $lb_values;
		$_SESSION['lb_db_name'] = $lb_db_name;
	endif;

	if ( $lb_name )
		echo '<h3>' . $lb_name . ' <a href="' . get_the_permalink() . '?reset=yes">Clear Results</a></h3>';

?>

<form action="" id="lightbox-form">
	<div class="row">
		<?php
			global $post;

			// Sorting
			if ( isset( $_GET['sort'] ) ) :

				$sort = $_GET['sort'];

				if ( $sort == 'abc_a' ) :
					$orderby = 'title';
					$order = 'ASC';
				elseif ( $sort == 'abc_d' ) :
					$orderby = 'title';
					$order = 'DESC';
				elseif ( $sort == 'date_a' ) :
					$orderby = 'post_date';
					$order = 'ASC';
				else :
					$orderby = 'post_date';
					$order = 'DESC';
				endif;

			else :

				$orderby = 'post_date';
				$order = 'DESC';

			endif;

			// View type ( Gets either the query string or the search function view variable )
			$view = '';
			$view = ( isset ( $_GET['view'] ) ) ? $_GET['view'] : $view;

			// For categories/tags
			$tax_query = '';
			if ( $meta_value )
				$tax_query = array( array( 'taxonomy' => $meta_key, 'terms' => $meta_value )  );

			if ( !isset( $query ) ) $query = '';

			// Get the loop
			$args1 = array(
				'posts_per_page'  => 100,
				'orderby'         => $orderby,
				'order'           => $order,
				'post_type'       => 'vendors',
				'post_status'     => 'publish',
				'paged'			  => $paged,
				's'               => $query,
				'post__in'		  => $lb_values,
				'tax_query' 	  => $tax_query
			);
			$args2 = array(
		        'post_type' => 'vendors',
		        'meta_query' => array(
		            array(
		               'key' => 'contact_name',
		               'value' => $query,
		               'compare' => 'LIKE'
		            )
		        )
			);
			$args = array_merge( $args1, $args2 );
			$loop = new WP_Query( $args );
			if( $loop->have_posts() ) : while ( $loop->have_posts() ) : $loop->the_post();

				// If low rating, lower opacity
				$low_rating = ( get_field('vendor_rating') == 2 || get_field('vendor_rating') == 1 ) ? 'style="opacity: .5"' : '';
		?>

		<div class="medium-<?php echo ( $view == 'list' ) ? '12' : '4 end'; ?> columns">
			<div class="vendor-item-wrap match">
				<?php if ( $lb_values ) : ?>
				<a href="" class="remove-lb" id="<?php echo $post->ID; ?>"><i class="fa fa-minus-circle"></i> Remove from Lightbox</a>
				<?php else : ?>
				<label><input type="checkbox" name="lightbox[]" value="<?php echo $post->ID; ?>" class="lightbox-check"> Add to Lightbox</label>
				<?php endif; ?>

				<a href="<?php the_permalink(); ?>" class="vendor-item" <?php echo $low_rating; ?> >
					<label><?php echo strip_tags ( get_the_term_list( $post->ID, 'vendor-cat', '', ', ' ) ); ?></label>

					<?php echo ( $view == 'list' ) ? '<div class="row"><div class="medium-4 columns">' : ''; ?>
					<h5><?php the_title(); ?></h5>
					<?php
						if( have_rows('visual_samples') && $view !== 'list' ): while ( have_rows('visual_samples') ) : the_row();

							if ( get_sub_field('sample_type') == 'Image') :

								$image = get_sub_field('image');
								echo '<p><img src="' . $image['url'] . '" alt="' . get_the_title() . '"></p>';

							elseif ( get_sub_field('sample_type') == 'Website') :

								$website_image = get_sub_field('website_image');
								echo '<p><img src="' . $website_image['url'] . '" alt="' . get_the_title() . '"></p>';

							else :

								echo '<div class="embed-container">' . get_sub_field('video') .'</div>';

							endif;

							break;

						endwhile; endif;

						if ( $view == 'list' ) :
							echo '</div><div class="medium-4 columns">';
								echo '<p><object><a href="mailto:' . get_field('email_address') . '">' . get_field('email_address') . '</a></object></p>';
							echo '</div><div class="medium-2 columns">';
								echo '<p>' . preg_replace('~.*(\d{3})[^\d]{0,7}(\d{3})[^\d]{0,7}(\d{4}).*~', '($1) $2-$3', get_field('phone_number')) . '</p>';
							echo '</div><div class="medium-2 columns">';
						endif;
					?>

					<object><a class="button full-width">View Vendor</a></object>

					<?php echo ( $view == 'list' ) ? '</div></div>' : ''; ?>
				</a>
			</div> <!-- .vendor-item-wrap -->
		</div> <!-- .columns -->

		<?php endwhile; else: ?>

			<h4 style="text-align: center">Sorry, no results. Please try again.</h4>

		<?php endif; wp_reset_postdata(); ?>

	</div> <!-- .row -->

	<div class="lightbox-add" style="position: fixed; left: 0; right: 0; bottom: 0; background: #FFF; text-align: center; padding: 5px 0; box-shadow: 0 0 3px #333;"><!--input type="submit" value="Add to Lightbox"-->
		<a href="<?php echo SRS_URL . '/public/templates/vendors-lightbox.php'; ?>" data-fancybox-type="ajax" class="button lightbox-add-button" style="margin: 0;">Add to Lightbox</a>
	</div>

</form>
