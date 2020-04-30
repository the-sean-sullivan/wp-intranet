<?php
	acf_form_head();
	$page_object = get_queried_object();
	$page_id     = get_queried_object_id();
	$is_child = Srs_Intranet_Public::is_child( $page_id );
	if ( !empty( $is_child ) ) $module = 'applicants';
	require_once 'dashboard-header.php'; /* Template Name: Single Applicant */
?>

<main role="main">
	<?php if (have_posts()): while (have_posts()) : the_post(); ?>

		<!-- <?php echo Srs_Intranet_Public::set_post_views( $post->ID ) . ' Views'; ?> -->

		<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

			<div id="main-info" style="width: 59.9%">

				<div id="position-title">
					<?php
						if ( $_SESSION['meta_key'] ) :
							$_SESSION['key'] = $_SESSION['meta_key'];
							$_SESSION['value'] = $_SESSION['meta_value'];
						endif;

						$the_job = get_field('job_applying_for');
					?>

					<p><a href="<?php bloginfo('url');?>/dashboard/applicants">&laquo; Back to Candidates</a></p>

					<label>Position</label>
					<h2><?php echo $the_job; echo ( $the_job == 'General Submission') ? ' - ' . strip_tags ( get_the_term_list( $post->ID, 'applicant-dept' ) ) : ''; ?></h2>
					<?php $job_num = get_field('job_number'); ?>
					<?php if( $job_num ) : ?><label>Job ID # <?php echo $job_num; ?></label><?php endif; ?>

					<?php //if ( strpos($the_job, 'Intern') !== false || strpos($the_job, 'Internship') !== false || strpos($the_job, 'Internships') !== false ) : ?>
						<!-- <label>Department</label> -->
						<?php //echo strip_tags ( get_the_term_list( $post->ID, 'applicant-dept' ) ); ?>
					<?php //endif; ?>
				</div> <!-- #position-title -->

				<label>Candidate</label>
				<h4><?php the_title(); ?></h4>

				<p><a href="mailto:<?php the_field('email_add'); ?>"><?php the_field('email_add'); ?></a></p>
				<p><?php print preg_replace('~.*(\d{3})[^\d]{0,7}(\d{3})[^\d]{0,7}(\d{4}).*~', '($1) $2-$3', get_field('phone_num')). "\n"; ?></p>

				<p>
					<label>Date Applied</label>
					<?php the_time('n/j/Y'); ?>
				</p>

				<?php
					if( get_field('resume') ) :
						the_field('resume');
					endif;

					$website = get_field('website');
					if( $website ) :

						if ( strpos($website, 'https://' ) !== false ) : // If https URL, leave
							$website = $website;
						else : // If http:// or none, add http://
							$website = str_replace('http://', '', $website);
							$website = 'http://' . $website;
						endif;

						echo '<a href="' . $website . '" target="_blank" class="button">View Site</a>';
					endif;

					$text = get_field('comments');
					if( get_field('comments') ) :
						if ( get_the_time('Ymd') < '20160613' )
							echo '<p style="margin: 0.8em 0;"><em>Please Note: Formatting may not display correctly here due to this applicant being from the previous application.</em></p>';

						echo '<div class="comments">';
							echo make_clickable($text);
						echo '</div>';
					endif;
				?>

			</div> <!-- #main-info -->

			<div id="more-info-panel" class="visible">
				<div class="padding-correct">
					<a href="#" id="slide">
						<span id="open-panel"><i class="fa fa-chevron-left fa-fw"></i></span>
						<span id="close-panel"><i class="fa fa-times fa-fw"></i></span>
					</a>

					<?php
						// Setup edit form
						acf_form(
							array(
								'post_id'    => get_the_ID(),
								'post_title' => true,
								'html_submit_button'	=> '<input type="submit" class="acf-button button button-primary button-large blue" value="%s" />',
								'fields'     => array(
									'acf-field_56e2f0d572291' => 'field_56e2f0d572291', // Status
									// 'acf-field_56e6f9344db7f' => 'field_56e6f9344db7f'  // Notes
								)
							)
						);
					?>

					<hr />

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

						if ( get_field('notes') ) :
							echo '<h4>Past Notes</h4>';
							the_field('notes');
						endif;

					?>

					<div id="modified">
						Last modified<br />
						<?php the_modified_date('n/j/Y'); ?> at <?php the_modified_date('g:i a'); ?>
					</div> <!-- #modified -->

					<hr />

					<?php
						// "Delete applicant (Just makes the status to draft)
						if( isset( $_GET['delete'] ) ) :
							$status = 'draft'; // Allows to keep but not show on FE
							// $status = 'yes'; // This will perm delete
							$post = array( 'ID' => get_the_ID(), 'post_status' => $status );
							wp_update_post($post);
							wp_redirect( get_site_url() . '/dashboard/applicants/?delete=yes' ); exit;
						endif;
					?>
					<div id="delete-link">
						<a href="?delete=yes" onclick="javascript:if(!confirm('Are you sure you want to move this item to trash?')) return false;">Delete Candidate</a>
					</div> <!-- #delete-link -->

				</div> <!-- .padding-correct -->
			</div> <!-- #more-info-panel -->

		</article> <!-- /article -->

	<?php endwhile; endif; ?>

</main> <!-- /main -->

<?php require_once 'dashboard-footer.php'; ?>
