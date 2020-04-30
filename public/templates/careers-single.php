<?php get_header(); /* Template Name: Careers - Single (Public Site) */ ?>

<?php get_sidebar(); ?>

<main role="main">

	<?php if (have_posts()): while (have_posts()) : the_post(); ?>

		<!-- article -->
		<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

			<!-- post thumbnail -->
			<?php if ( has_post_thumbnail()) : // Check if Thumbnail exists ?>
				<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
					<?php the_post_thumbnail(); // Fullsize image for the single post ?>
				</a>
			<?php endif; ?>
			<!-- /post thumbnail -->

			<h1><?php the_field('job_headline'); ?></h1>

			<span class="date"><?php the_time('F j, Y'); ?></span>

			<?php the_field('job_description'); ?>
			<?php the_field('equal_opportunity'); ?>

			<?php if ( get_field('online_form') ) : ?>

				<form id="new-applicant" enctype="multipart/form-data" method="post" action="<?php echo SRS_URL . '/public/templates/careers-post-applicant.php'; ?>">

					<?php

						$field = get_field_object('form_fields');
						$value = $field['value'];
						$choices = $field['choices'];

						if( $value ) : foreach( $value as $v ) :

					?>
							<label for="<?php echo $v; ?>"><?php echo $choices[ $v ]; ?></label>

							<?php if ( $v == 'email_add' ) : ?>
								<p><input type="email" name="<?php echo $v; ?>" value="" /></p>
							<?php elseif ( $v == 'website' ) : ?>
								<p><input type="url" name="<?php echo $v; ?>" value="" /></p>
							<?php elseif ( $v == 'comments' ) : ?>
								<p><textarea name="<?php echo $v; ?>" cols="30" rows="3"></textarea></p>
							<?php elseif ( $v == 'resume' ) : ?>
								<p><input type="file" name="files" id="files" /></p>
							<?php else : ?>
								<p><input type="text" name="<?php echo $v; ?>" /></p>
							<?php endif; ?>

					<?php endforeach; endif; ?>

					<input type="hidden" name="job_title" value="<?php the_title(); ?>" />
					<input type="hidden" name="active" value="<?php the_field('job_active'); ?>" />
					<input type="hidden" name="job_number" value="<?php the_field('job_number'); ?>" />
					<input type="hidden" name="status" value="new" />

					<?php $group = get_field('job_group'); ?>
					<input type="hidden" name="group" value="<?php echo $group->slug; ?>" />

					<p><input type="submit" value="Submit" /></p>

				</form>

			<?php endif; ?>

		</article> <!-- /article -->

	<?php endwhile; endif; ?>

</main> <!-- /main -->

<?php get_footer(); ?>
