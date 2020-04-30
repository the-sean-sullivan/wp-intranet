<?php $module = 'comment_box'; require_once 'dashboard-header.php'; /* Template Name: OneTeam Suggestion Box - Dashboard */ ?>

<main role="main">

	<?php if( current_user_can('editor') || current_user_can('administrator') ) : ?>

		<?php if( isset($_GET['delete'] ) ) : ?>
			<div class="success">Suggestion successfully deleted.</div>
		<?php endif; ?>

		<div id="loading">
			<img src="<?php echo SRS_URL; ?>/public/images/ripple.svg" alt="" />
		</div>

		<div class="grid-container fluid dark-bg">
			<div class="grid-x align-center">
				<div class="medium-12 large-8 cell">
					<table id="applicants" class="table-style responsive">
						<tr>
							<th width="33%" id="position-col">Name <i class="fa fa-sort fa-fw"></i></th>
							<th width="33%" id="name-col">Comment Type <i class="fa fa-sort fa-fw"></i></th>
							<th width="33%" id="date-col">Date Submitted <i class="fa fa-sort fa-fw"></i></th>
						</tr>

						<!-- posts summary -->
						<?php require SRS_FILE_PATH . '/public/templates/comment-loop.php'; ?>

					</table> <!-- #applicants -->
				</div>
			</div>
		</div> <!-- .row -->

		<input type="hidden" name="candidate_page_count" value="<?php echo $loop->max_num_pages; ?>">
	<?php
		else :

			wp_redirect( site_url( '/dashboard/comment-suggestion-box/' ) ); exit;

		endif;
	?>

</main> <!-- /main -->

<?php require_once 'dashboard-footer.php'; ?>
