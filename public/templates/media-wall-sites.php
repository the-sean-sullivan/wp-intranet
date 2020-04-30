<?php acf_form_head(); $module = 'media_wall'; require_once 'dashboard-header.php'; /* Template Name: Media Wall - Sites */ ?>

<?php
	if (have_posts()) : while (have_posts()) : the_post();

		if ( isset($_GET['edit']) ) :
?>

	<div class="grid-container">
		<div class="grid-x">
			<div class="cell">
				<?php
					echo '<h3>Edit/Add Sites</h3>';

					// Setup edit form
					acf_form(
						array(
							'post_id'    => get_the_ID(),
							'post_title' => false
						)
					);
				?>
			</div>
			<div class="medium-6 cell">
				<p><a href="<?php echo site_url('/dashboard/media-wall/'); ?>">&laquo; Back to Media Wall Dashboard</a></p>
			</div> <!-- .columns -->
			<div class="medium-6 cell" style="text-align: right;">
				<p><a href="<?php the_permalink(); ?>">View Media Wall &raquo;</a></p>
			</div> <!-- .columns -->
		</div> <!-- .row -->
	</div>

	<?php

		else :

			$this_url = $_SERVER['REQUEST_URI'];
			header("Refresh: 60; URL = $this_url");

			echo '<a href="' . site_url('/dashboard/media-wall/') . '">';

				if( $slug = 'things-we-made' )
					echo '<img src="' . SRS_URL . '/public/images/ribbon-made.png" alt="Things We\'ve Made" class="ribbon" />';
				elseif( $slug = 'things-we-love' )
					echo '<img src="' . SRS_URL . '/public/images/ribbon-love.png" alt="Things We Love" class="ribbon" />';

			echo '</a>';

			$rows = get_field( 'sites', get_the_ID() );

			if( $rows ) :

				shuffle( $rows );

				$i = '';
				foreach( $rows as $row ) :
					$i++;

					if( $i > 1 )
						break;

					if( $row['site_active'] == 1 ) :
	?>

		<iframe src="<?php echo $row['site_url']; ?>" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" id="the-sites"></iframe>

	<?php endif; endforeach; endif; endif; ?>

<?php endwhile; endif; ?>

<?php require_once 'dashboard-footer.php'; ?>
