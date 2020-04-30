<?php acf_form_head(); $module = 'vendor_database'; require_once 'dashboard-header.php'; /* Template Name: Vendors - Single */ ?>

<main role="main">
	<?php if (have_posts()): while (have_posts()) : the_post(); ?>

		<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

			<?php
				if ( isset( $_SESSION['meta_key'] ) ) :
					$_SESSION['key'] = $_SESSION['meta_key'];
					$_SESSION['value'] = $_SESSION['meta_value'];
				endif;

				if ( isset( $_SESSION['lb_name'] ) ) :
					$_SESSION['name'] = $_SESSION['lb_name'];
					$_SESSION['values'] = $_SESSION['lb_values'];
					$_SESSION['db_name'] = $_SESSION['lb_db_name'];
				endif;
			?>

			<div id="main-info">
				<div id="vendor-header">
					<div class="row">
						<div class="medium-6 columns"><p><a href="<?php bloginfo('url');?>/dashboard/vendor-database">&laquo; Back to Vendors</a></p></div>
						<div class="medium-6 columns"><p align="right"><a href="<?php bloginfo('url');?>/dashboard/vendor-database/vendors-edit/?edit=<?php the_ID(); ?>" class="button">Edit Vendor</a></p></div>
					</div> <!-- .row -->

					<div class="row">
						<div class="medium-10 medium-offset-2 columns">
							<h1>
								<?php the_title(); ?>
								<?php if ( get_field('vendor_agreement') == 1 ) : ?>
									<span class="fa-stack fa-lg">
									  	<i class="fa fa-file-text-o fa-stack-2x"></i>
									  	<i class="fa fa-check fa-stack-1x"></i>
									</span>
								<?php endif; ?>
							</h1>
						</div> <!-- .columns -->
					</div> <!-- .row -->
				</div> <!-- #vendor-header -->

				<div class="row">
					<div class="medium-2 columns">
						<div id="vendor-logo">
							<?php
								$vendor_logo = get_field('vendor_logo');
								if ( $vendor_logo )
									echo '<img src="' . $vendor_logo['url'] . '" alt="'. get_the_title() . '">';
								else
									echo '<img src="' . SRS_URL . '/public/images/vendor-logo-default.png" alt="'. get_the_title() . '">';
							?>
						</div>
					</div>
					<div class="medium-10 columns">
						<div id="vendor-type">
							<?php echo strip_tags ( get_the_term_list( $post->ID, 'vendor-cat', '', ', ' ) ); ?>
						</div> <!-- #vendor-type -->

						<div id="vendor-info">
							<div class="row small-up-1 medium-up-3 large-up-5">
								<div class="column ellipsis">
									<p><i class="fa fa-user fa-fw"></i> <?php the_field('contact_name'); ?></p>
								</div> <!-- .column -->
								<div class="column ellipsis">
									<p><i class="fa fa-phone fa-fw"></i> <?php print preg_replace('~.*(\d{3})[^\d]{0,7}(\d{3})[^\d]{0,7}(\d{4}).*~', '($1) $2-$3', get_field('phone_number')). "\n"; ?></p>
								</div> <!-- .column -->
								<div class="column ellipsis">
									<p><i class="fa fa-envelope fa-fw"></i> <a href="mailto:<?php the_field('email_address'); ?>"><?php the_field('email_address'); ?></a></p>
								</div> <!-- .column -->
								<div class="column ellipsis">
									<p><i class="fa fa-desktop fa-fw"></i> <a href="<?php the_field('website'); ?>" target="_blank"><?php the_field('website'); ?></a></p>
								</div> <!-- .column -->
								<div class="column ellipsis">
									<p>
										<i class="fa fa-map-marker fa-fw"></i>
										<?php
											$address = get_field('address');

											echo ( $address ) ? '<a class="vendor-locale fancybox.iframe" href="http://maps.google.com/?output=embed&q=' . $address['address'] . '&ll=' . $address['lat'] . ',' . $address['lng'] . '&z=17">' : '';
											the_field('vendor_city');
											echo ( $address ) ? '</a>' : '';
										?>
									</p>
								</div> <!-- .column -->
							</div> <!-- .row -->
						</div> <!-- #vendor-info -->
					</div> <!-- .columns -->
				</div> <!-- .row -->

				<?php if( get_field('represented_by') == 'company') : ?>
				<div id="repped-by">
					<div class="row">
						<div class="medium-12 large-2 columns">
							<label>Represented by:</label>
						</div> <!-- .columns -->
						<div class="medium-12 large-10 columns">
							<div id="repped-by-info">
								<div class="row">
									<div class="medium-6 large-3 columns ellipsis">
										<p><i class="fa fa-building fa-fw"></i> <?php the_field('rep_by_company_name'); ?></p>
									</div> <!-- .columns -->
									<div class="medium-6 large-3 columns ellipsis">
										<p><i class="fa fa-user fa-fw"></i> <?php the_field('rep_by_primary_contact'); ?></p>
									</div> <!-- .columns -->
									<div class="medium-6 large-3 columns ellipsis">
										<p><i class="fa fa-envelope fa-fw"></i> <a href="mailto:<?php the_field('rep_by_email_address'); ?>"><?php the_field('rep_by_email_address'); ?></a></p>
									</div> <!-- .columns -->
									<div class="medium-6 large-3 columns ellipsis">
										<p><i class="fa fa-phone fa-fw"></i> <?php print preg_replace('~.*(\d{3})[^\d]{0,7}(\d{3})[^\d]{0,7}(\d{4}).*~', '($1) $2-$3', get_field('rep_by_phone_number')). "\n"; ?></p>
									</div> <!-- .columns -->
								</div> <!-- .row -->
							</div> <!-- #repped-by-info -->
						</div> <!-- .columns -->
					</div> <!-- .row -->
				</div> <!-- #repped-by -->
				<?php endif; ?>


				<?php if( have_rows('visual_samples') ) : ?>
				<div id="vis-samples">
					<div class="row">
						<?php
							while ( have_rows('visual_samples') ) : the_row();

								echo '<div class="medium-3 columns end">';

									if ( get_sub_field('sample_type') == 'Image') :

										$image = get_sub_field('image');
										echo '<a href="' . $image['url'] . '" class="the-sample sample-link" rel="samples" title="' . get_sub_field('description'). '" style="background-image: url(' . $image["url"] . ')"></a>';

									elseif ( get_sub_field('sample_type') == 'Website' ) :

										$website_image = get_sub_field('website_image');
										echo '<a href="' . get_sub_field('website') . '" class="the-sample" target="_blank" style="background-image: url(' . $website_image["url"] . ')"></a>';

									else :

										$video = get_sub_field('video', FALSE, FALSE);
									  	$video_thumb_url = Srs_Intranet_Vendors::get_video_thumbnail_uri($video);
									  	echo '<a href="' . $video . '" class="the-sample sample-link play-video" rel="samples" title="' . get_sub_field('description'). '" style="background-image: url(' . $video_thumb_url . ')"></a>';

									endif;

								echo '</div>';

							endwhile;
						?>
					</div> <!-- .row -->
				</div> <!-- #vis-samples -->
				<?php endif; ?>

			</div> <!-- #main-info -->

			<div id="more-info-panel">
				<div class="padding-correct">
					<a href="#" id="slide">
						<span id="open-panel"><i class="fa fa-chevron-left fa-fw"></i></span>
						<span id="close-panel"><i class="fa fa-times fa-fw"></i></span>
					</a>

					<div id="vendor-rating">
						<h3>Vendor Rating</h3>
						<?php
							$vendor_rating = get_field('vendor_rating');
							if ( $vendor_rating == 1 )
								echo '<i class="fa fa-star fa-2x"></i> <i class="fa fa-star-o fa-2x"></i> <i class="fa fa-star-o fa-2x"></i> <i class="fa fa-star-o fa-2x"></i> <i class="fa fa-star-o fa-2x"></i>';
							elseif ( $vendor_rating == 2 )
								echo '<i class="fa fa-star fa-2x"></i> <i class="fa fa-star fa-2x"></i> <i class="fa fa-star-o fa-2x"></i> <i class="fa fa-star-o fa-2x"></i> <i class="fa fa-star-o fa-2x"></i>';
							elseif ( $vendor_rating == 3 )
								echo '<i class="fa fa-star fa-2x"></i> <i class="fa fa-star fa-2x"></i> <i class="fa fa-star fa-2x"></i> <i class="fa fa-star-o fa-2x"></i> <i class="fa fa-star-o fa-2x"></i>';
							elseif ( $vendor_rating == 4 )
								echo '<i class="fa fa-star fa-2x"></i> <i class="fa fa-star fa-2x"></i> <i class="fa fa-star fa-2x"></i> <i class="fa fa-star fa-2x"></i> <i class="fa fa-star-o fa-2x"></i>';
							elseif ( $vendor_rating == 5 )
								echo '<i class="fa fa-star fa-2x"></i> <i class="fa fa-star fa-2x"></i> <i class="fa fa-star fa-2x"></i> <i class="fa fa-star fa-2x"></i> <i class="fa fa-star fa-2x"></i>';
							else
								echo 'No rating for ' . get_the_title() . ' yet.';
						?>
					</div>

					<div id="vendor-comments">
						<h3>Comments</h3>
						<?php
							$comments = get_comments('post_id=' . $post->ID);
							$current_user = wp_get_current_user();
							if ( $comments ) : foreach($comments as $comment) :
								$comment_id = get_comment_ID();
								$comment_author = get_comment_author( $comment_id );
						?>

							<div class="vendor-comment" id="comment-<?php echo $comment_id; ?>">
								<?php if ( $current_user->display_name == $comment_author ) : ?>
								<div class="delete-comment" id="<?php echo $comment_id; ?>"><i class="fa fa-minus-circle"></i></div>
								<?php endif; ?>
								<div class="comment-body<?php echo ( $current_user->display_name == $comment_author ) ? ' editable' : ''; ?>" id="comment-text-<?php echo $comment_id; ?>"><?php comment_text(); ?></div>
								<div class="comment-author">- <?php echo $comment_author; ?></div>
							</div>

						<?php
							endforeach; endif;

							if ('open' == $post->comment_status) :
						?>

							<form action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" method="post" id="comment-form">
								<p><textarea name="comment" id="comment" cols="100%" rows="4" tabindex="4" placeholder="Hello, <?php echo $current_user->first_name; ?>. You can add comments here."></textarea></p>
								<p align="center">
									<input name="submit" type="submit" id="submit" tabindex="5" value="Add Comment" />
									<?php comment_id_fields(); ?>
								</p>
								<?php do_action('comment_form', $post->ID); ?>
							</form>

						<?php endif; ?>
					</div>

					<div id="modified">
						<strong>Vendor added by</strong><br />
						<?php the_author(); ?> on <?php the_time('n/j/Y'); ?>
					</div> <!-- #modified -->

					<?php
						// "Delete applicant (Just makes the status to draft)
						if( isset( $_GET['delete'] ) ) :
							$status = 'draft'; // Allows to keep but not show on FE
							// $status = 'yes'; // This will perm delete
							$post = array( 'ID' => get_the_ID(), 'post_status' => $status );
							wp_update_post($post);
							wp_redirect( get_site_url() . '/dashboard/vendors/?delete=yes' ); exit;
						endif;
					?>
					<div id="delete-link">
						<a href="?delete=yes" onclick="javascript:if(!confirm('Are you sure you want to move this vendor to trash?')) return false;">Delete Vendor</a>
					</div> <!-- #delete-link -->

				</div> <!-- .padding-correct -->
			</div> <!-- #more-info-panel -->

		</article> <!-- /article -->

	<?php endwhile; endif; ?>

</main> <!-- /main -->

<?php require_once 'dashboard-footer.php'; ?>
