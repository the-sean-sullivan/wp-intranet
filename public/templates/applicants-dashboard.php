<?php $module = 'applicants'; require_once 'dashboard-header.php'; /* Template Name: Applicants Dashboard */ ?>

<main role="main">

	<?php if (have_posts()): while (have_posts()) : the_post(); ?>

		<div class="grid-x">
			<!-- <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

				<?php the_content(); ?>

			</article> -->

			<?php if( isset($_GET['delete'] ) ) : ?>
				<div class="success">Applicant successfully deleted.</div>
			<?php endif; ?>
		</div> <!-- .row -->

		<?php

			// For filters
			$meta_key   = '';
			$meta_value = '';

			if( isset( $_POST['position'] ) ) :
				session_unset();
				$meta_key   = 'job_applying_for';
				$meta_value = $_POST['position'];
			endif;

			if( isset( $_POST['department'] ) ) :
				session_unset();
				$meta_key   = 'job_department';
				$meta_value = $_POST['department'];
			endif;

			if( isset( $_POST['status'] ) ) :
				session_unset();
				$meta_key   = 'status';
				$meta_value = $_POST['status'];
			endif;

			// Create sessions to carry over to detail page.
			$_SESSION['meta_key'] = $meta_key;
			$_SESSION['meta_value'] = $meta_value;

			// If sessions, then grab from detail page and keep filters.
			if ( isset( $_SESSION['key'] ) ) :
				$meta_key = $_SESSION['key'];
				$meta_value = $_SESSION['value'];
			endif;

		?>

		<input type="hidden" name="candidate_meta_key" value="<?php echo $meta_key; ?>">
		<input type="hidden" name="candidate_meta_value" value="<?php echo $meta_value; ?>">

		<div id="applicant-filters">
			<div class="grid-container fluid">
				<div class="grid-x grid-margin-x align-center">
					<div class="medium-12 large-8 cell">
						<div class="grid-x grid-margin-x">
							<div class="medium-4 cell">
								<form action="" method="post" id="position-form">
									<div class="dropdown-filters">
										<select name="position" onchange="this.form.submit()" id="position-dropdown">
											<option value="">All Positions</option>
											<?php
												$content = array(
													'post_type'   => 'careers',
													'post_status' => 'publish',
													'number'      => 100
											    );

												// Use the REST API to get the jobs from live site
												$jobs = SRS_Intranet_Public::rest_api_get( 'careers?per_page=100' );

												$i = 0;
												usort($jobs, function($a, $b) { return $b['content']['active'] <=> $a['content']['active']; });
												foreach( $jobs as $job ) :
													$job_id     = $job['post_id'];
													$job_title  = $job['title']['rendered'];
													$job_status = $job['content']['active'];

													if ( $job_title !== 'Auto Draft' ) :
														$selected = ( $job_title == $meta_value ) ? 'selected'  : '';
														$draft_color = ( $job_status == false ) ? 'class="draft draft-' . $i++ . '"' : '';
														$option = '<option value="' . $job_title . '" ' . $selected . ' ' . $draft_color . '>';
														$option .= $job_title;
														$option .= ($job_status == false) ? ' (Inactive)' : '';
														$option .= '</option>';
														echo $option;
													endif;

												endforeach;
											?>
										</select>
									</div> <!-- .dropdown-filters -->
								</form> <!-- /form -->
							</div> <!-- .cell -->
							<div class="medium-4 cell">
								<form action="" method="post" id="department-form">
									<div class="dropdown-filters">
										<select name="department" onchange="this.form.submit()" id="department-dropdown">
											<option value="">All Departments</option>
										 	<?php
												$departments = get_terms('applicant-dept');

												foreach ($departments as $department) {

													// $dept_id = $department->slug;
													$dept_name = $department->name;

													$selected = ( $dept_name == $meta_value ) ? 'selected' : '';
													$option = '<option value="' . $dept_name . '" ' . $selected . '>';
													$option .= $dept_name;
													$option .= '</option>';

													echo $option;
												}
										 	?>
										</select>
									</div> <!-- .dropdown-filters -->
								</form> <!-- /form -->
							</div> <!-- .cell -->
							<div class="medium-4 cell">
								<form action="" method="post" id="status-form">
									<div class="dropdown-filters">
										<select name="status" onchange="this.form.submit()" id="status-dropdown">
											<option value="">All Status</option>
										 	<?php
												$statuses = get_terms('applicant-status');

												foreach ($statuses as $status) {

													// $status_id = $status->slug;
													$status_name = $status->name;

													$selected = ( $status_name == $meta_value ) ? 'selected' : '';
													$option = '<option value="' . $status_name . '" ' . $selected . '>';
													$option .= $status_name;
													$option .= '</option>';

													echo $option;
												}
										 	?>
										</select>
									</div> <!-- .dropdown-filters -->
								</form> <!-- /form -->
							</div> <!-- .cell -->
						</div>
					</div>
				</div> <!-- #row -->
			</div>
		</div> <!-- #applicant-filters -->

		<div id="loading">
			<img src="<?php echo SRS_URL; ?>/public/images/ripple.svg" alt="" />
		</div>

		<div class="grid-container fluid dark-bg">
			<div class="grid-x align-center">
				<div class="medium-12 large-8 cell">
					<table id="applicants" class="table-style">
						<tr>
							<th width="30%" id="position-col">Position <i class="fa fa-sort fa-fw"></i></th>
							<th width="30%" id="name-col">Name <i class="fa fa-sort fa-fw"></i></th>
							<th width="20%" id="date-col">Date Applied <i class="fa fa-sort fa-fw"></i></th>
							<th width="20%" id="status-col">Candidate Status <i class="fa fa-sort fa-fw"></i></th>
						</tr>

						<!-- posts summary -->
						<?php require SRS_FILE_PATH . '/public/templates/applicants-loop.php'; ?>

					</table> <!-- #applicants -->
				</div>
			</div>
		</div> <!-- .row -->

	<?php endwhile; endif; ?>

	<input type="hidden" name="candidate_page_count" value="<?php echo $loop->max_num_pages; ?>">

</main> <!-- /main -->

<?php require_once 'dashboard-footer.php'; ?>
