<?php
	acf_form_head();
	$page_object = get_queried_object();
	$page_id     = get_queried_object_id();
	$is_child = Srs_Intranet_Public::is_child( $page_id );
	if ( !empty( $is_child ) ) $module = 'comment_box';
	require_once 'dashboard-header.php'; /* Template Name: Single Submission */
?>

<main role="main">
	<?php if (have_posts()): while (have_posts()) : the_post(); ?>

		<!-- <?php echo Srs_Intranet_Public::set_post_views( $post->ID ) . ' Views'; ?> -->

		<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

			<div id="main-info" style="width: 59.9%">

				<div id="position-title">
					<p><a href="<?php bloginfo('url');?>/dashboard/comment-form-dashboard">&laquo; Back to Submissions</a></p>

					<label>Submitter</label>
					<h2><?php the_title(); ?></h2>
				</div> <!-- #position-title -->

				<div class="grid-x grid-margin-x">
					<div class="medium-3 cell">
						<label>Date Submitted</label>
						<?php the_time('n/j/Y'); ?>
					</div>
					<div class="medium-3 cell">
						<label>Comment Type</label>
						<?php the_field('comment_type'); ?>
					</div>
					<div class="medium-3 cell">
						<label>Comment Topic</label>
						<?php the_field('comment_topic'); ?>
					</div>
                    <div class="medium-3 cell">
                        <label>Department</label>
                        <?php the_field('department'); ?>
                    </div>
				</div>


				<label>Comments</label>
				<?php the_field('comment');?>

			</div> <!-- #main-info -->

			<div id="more-info-panel" class="visible">
				<div class="padding-correct">
					<a href="#" id="slide">
						<span id="open-panel"><i class="fa fa-chevron-left fa-fw"></i></span>
						<span id="close-panel"><i class="fa fa-times fa-fw"></i></span>
					</a>

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
								<input name="submit" type="submit" id="submit" tabindex="5" value="Add Comment" class="blue" />
								<?php comment_id_fields(); ?>
							</p>
							<?php do_action('comment_form', $post->ID); ?>
						</form>

					<?php endif; ?>

					<hr />

					<?php
						// "Delete applicant (Just makes the status to draft)
						if( isset( $_GET['delete'] ) ) :
							$status = 'draft'; // Allows to keep but not show on FE
							// $status = 'yes'; // This will perm delete
							$post = array( 'ID' => get_the_ID(), 'post_status' => $status );
							wp_update_post($post);
							wp_redirect( get_site_url() . '/dashboard/comment-form-dashboard/?delete=yes' ); exit;
						endif;
					?>
					<div id="delete-link">
						<p><a href="?delete=yes" onclick="javascript:if(!confirm('Are you sure you want to move this item to trash?')) return false;">Delete Submission</a></p>
					</div> <!-- #delete-link -->

				</div> <!-- .padding-correct -->
			</div> <!-- #more-info-panel -->

		</article> <!-- /article -->

	<?php endwhile; endif; ?>

</main> <!-- /main -->

<?php require_once 'dashboard-footer.php'; ?>
