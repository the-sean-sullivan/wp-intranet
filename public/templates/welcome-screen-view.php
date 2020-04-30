<?php acf_form_head(); $module = 'welcome_screen'; require_once 'dashboard-header.php'; /* Template Name: Welcome Screen - View */ ?>

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

		<?php if ( isset($_GET['edit']) ) : ?>

			<div class="row">
				<h3>Edit/Add</h3>

				<?php
					// Setup edit form
					acf_form(
						array(
							'post_id'    => get_the_ID(),
							'post_title' => false
						)
					);
				?>

				<div class="medium-6 columns">
					<a href="<?php echo site_url('/dashboard/welcome-screen/'); ?>">&laquo; Back to Welcome Screen Dashboard</a>
				</div> <!-- .columns -->
				<div class="medium-6 columns" style="text-align: right;">
					<a href="<?php the_permalink(); ?>">View Welcome Screen &raquo;</a>
				</div> <!-- .columns -->
			</div> <!-- .row -->

		<?php else : ?>

			<div class="welcome" style="background-image: url('<?php echo SRS_URL; ?>/public/images/pattern.jpg');">
				<div class="arrow" style="background-image: url('<?php echo SRS_URL; ?>/public/images/arrow.png');">
					Welcome
				</div>

				<?php if( get_field('visitors') ) : ?>
					<?php $count = count( get_field('visitors') ); ?>
					<div class="row visitors">
						<div id="welcome-slider<?php echo ( $count <= 1 ) ? '-off' : ''; ?>">

							<?php while( has_sub_field('visitors') ) : if ( get_sub_field('active') ): ?>

								<div>
									<h1><?php the_sub_field('visitor_group_name'); ?></h1>
									<p><?php the_sub_field('individuals'); ?></p>
								</div>

							<?php endif;  endwhile; ?>

						</div> <!-- #welcome-slider -->
					</div> <!-- .row -->
				<?php endif; ?>

				<a href="<?php echo site_url('/dashboard/welcome-screen/'); ?>" class="close-screen"></a>
			</div> <!-- .welcome -->

		<?php endif; ?>

	<?php endwhile; endif; ?>

<?php require_once 'dashboard-footer.php'; ?>
