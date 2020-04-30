<?php $module = 'naming_contest'; require_once 'dashboard-header.php'; /* Template Name: Naming Contest - Dashboard */ ?>

<?php
	$user = get_current_user_id();

	if ( $user == 17 || $user == 9 || current_user_can('administrator') ) :
?>

<main role="main">

	<div id="loading">
		<img src="<?php echo SRS_URL; ?>/public/images/ripple.svg" alt="" />
	</div>

	<div class="grid-container fluid dark-bg">

		<a href="<?php bloginfo('url'); ?>/dashboard/naming-contest/" class="button view-entries" style="position: absolute; right: 15vw; top: 65px;">Contest Entry Form</a>

		<div class="grid-x align-center">
			<div class="medium-12 large-8 cell">
				<table id="applicants" class="table-style responsive">
					<tr>
						<th width="50%" id="position-col">Name</th>
						<th width="50%" id="name-col">Entry</th>
					</tr>

					<!-- posts summary -->
					<?php require SRS_FILE_PATH . '/public/templates/naming-contest-loop.php'; ?>

				</table> <!-- #applicants -->
			</div>
		</div>
	</div> <!-- .row -->

</main> <!-- /main -->

<?php

	else :

		wp_redirect( site_url('/dashboard/naming-contest') );
		exit();

	endif;
?>

<?php require_once 'dashboard-footer.php'; ?>
