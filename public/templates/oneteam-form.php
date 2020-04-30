<?php $module = 'comment_box'; require_once 'dashboard-header.php'; /* Template Name: OneTeam Suggestion Box - Form */ ?>

<main role="main">

	<div class="text-block blue-bg">
		<div class="grid-container fluid">
			<div class="grid-x align-center">
				<div class="medium-12 large-8 cell">

					<?php
						if (have_posts()): while (have_posts()) : the_post();
							the_content();
						endwhile; endif;
					?>

				</div>
			</div>
		</div>
	</div>

	<div class="grid-container fluid dark-bg">
		<div class="grid-x align-center">
			<div class="medium-12 large-8 cell">

				<?php if( current_user_can('editor') || current_user_can('administrator') ) : ?>
					<a href="<?php bloginfo('url'); ?>/dashboard/comment-form-dashboard/" class="button" style="position: absolute; right: 15vw; top: 65px;">View Submissions</a>
				<?php
					endif;

					$user = wp_get_current_user();

					if ($_SERVER['REQUEST_METHOD'] == 'POST') :

						$comment_type  = $_POST['comment_type'] ?? '';
						$comment_topic = $_POST['comment_topic'] ?? '';
						$comments      = $_POST['comments'] ?? '';
						$department    = $_POST['department'] ?? '';
						$anon		   = $_POST['anon'] ?? '';

						if ( empty($comment_type) || empty($comment_topic) || empty($comments) || empty($department) ) :

							echo '<div class="fail">Please fill out all required fields.</div>';

						else :

							if ( $anon == 1 ) :
								$title = 'Anonymous';
							else :
								$title = $user->display_name;
							endif;

							// Add the content of the form to $post as an array
							$post = array(
								'post_title'  => $title,
								'post_type'   => 'comment-form',
								'post_author' => 53,
								'post_status' => 'publish'
							);
							$postID = wp_insert_post($post);

							// Update custom fields
							if ( $postID ) :

								update_field('field_5d76972b41235', $comment_type, $postID);
								update_field('field_5d76975c41236', $comment_topic, $postID);
								update_field('field_5d76978e41237', $comments, $postID);
								update_field('field_5d7697a941238', $department, $postID);

							endif;


							// Send HTML email
							$headers = "MIME-Version: 1.0" . "\r\n";
							$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
							$headers .= 'From: <from@email.com>' . "\r\n";

							$message = '<p>' . $title . ' has submitted a new #OneTeam comment. Please <a href="' . get_bloginfo('url') . '/dashboard/comment-form-dashboard" target="_blank">visit the dashboard</a> to view.</p>';

							// Get people to email
							$editors = get_users( array( 'role__in' => array( 'administrator', 'editor' ) ) );
							foreach ($editors as $editor) :
								if ( $editor->user_login !== 'user_to_not_email' )
									$emails[] = $editor->user_email;
							endforeach;

							// Do the mail
							$mail = mail(implode(',', $emails), 'New #OneTeam Comment Submission', $message, $headers);

							// If mail is successful, be happy.
							if ( $mail ) echo '<div class="success">Your suggestion has successfully been submitted.</div>';

						endif;

					endif;

				?>
				<div class="text-block">
					<form action="" method="post">
						<div class="grid-x grid-margin-x grid-margin-y">
							<div class="medium-4 cell">
								<label for="comment_type">Comment Type <span>*</span></label>
								<div class="dropdown-filters">
									<select name="comment_type" id="comment_type">
										<option value="">Please Select</option>
										<option value="Complaint">Complaint</option>
										<option value="Praise">Praise (for teammate or process)</option>
										<option value="Question">Question</option>
										<option value="Suggestion">Suggestion</option>
									</select>
								</div>
							</div>
							<div class="medium-4 cell">
								<label for="comment_topic">Comment Topic <span>*</span></label>
								<div class="dropdown-filters">
									<select name="comment_topic" id="comment_topic">
										<option value="">Please Select</option>
										<option value="Mindsets">Mindsets</option>
										<option value="Onboarding">Onboarding</option>
										<option value="Performance Reviews">Performance Reviews</option>
										<option value="Workflow">Workflow</option>
										<option value="Other">Other</option>
									</select>
								</div>
							</div>
							<div class="medium-4 cell">
								<label for="comment_topic">Department <span>*</span></label>
								<div class="dropdown-filters">
									<select name="department" id="department">
										<option value="">Please Select</option>
										<option value="Accounting">Accounting</option>
										<option value="Admin">Admin</option>
										<option value="Client Service">Client Service</option>
										<option value="Creative">Creative</option>
										<option value="Emerging Solutions">Emerging Solutions</option>
										<option value="Media">Media</option>
										<option value="Public Relations">Public Relations</option>
										<option value="Strategy">Strategy</option>
										<option value="Web">Web</option>
									</select>
								</div>
							</div>
							<div class="medium-12 cell">
								<label for="comments">Comments <span>*</span></label>
								<textarea name="comments" id="comments" cols="30" rows="10" placeholder="Note: This is not the place to address individual performance concerns. Please discuss those 1:1 with your teammate and / or his or her supervisor."></textarea>
							</div>
						</div>

						<div class="grid-x grid-padding-x align-right align-middle text-right">
							<div class="medium-12 cell">
								<p><small>This inbox is monitored weekly by the OneTeam leads.<br /> All input will be addressed, and comments can be submitted anonymously if desired.</small></p>
							</div>
							<div class="medium-9 cell">
								<input type="checkbox" id="anon" name="anon" value="1">
								<label for="anon">You’re currently logged in as <?php echo $user->display_name; ?>. Submit anonymously?</label>
							</div>
							<div class="medium-3 cell">
								<p class="text-center">
									<input type="submit" value="Submit Comment" class="blue full-width">
								</p>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>

</main> <!-- /main -->

<?php require_once 'dashboard-footer.php'; ?>
