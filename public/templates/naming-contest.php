<?php $module = 'naming_contest'; require_once 'dashboard-header.php'; /* Template Name: Naming Contest - Entry Form */ ?>

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

				<?php
					$user = wp_get_current_user();

					if ( $user->ID == 17 || $user->ID == 9 || current_user_can('administrator') ) :
				?>

					<a href="<?php bloginfo('url'); ?>/dashboard/naming-contest-dashboard/" class="button" style="position: absolute; right: 15vw; top: 65px;">View Contest Entries</a>

				<?php

					endif;

					// $user = wp_get_current_user();
					$title = $user->display_name;

					if ($_SERVER['REQUEST_METHOD'] == 'POST') :

						$entry  = $_POST['entry'] ?? '';

						if ( empty($entry) ) :

							echo '<div class="fail">Please fill out an entry.</div>';

						else :

							// Add the content of the form to $post as an array
							$post = array(
								'post_title'  => $title,
								'post_type'   => 'naming-contest',
								'post_author' => 53,
								'post_status' => 'publish'
							);
							$postID = wp_insert_post($post);

							// Update custom fields
							if ( $postID ) :

								update_field('field_5e271a3b01d86', $entry, $postID);

							endif;

							echo '<div class="success">Your suggestion has successfully been submitted.</div>';

						endif;

					endif;

				?>
				<div class="text-block">
					<form action="" method="post">
						<div class="grid-x grid-margin-x grid-margin-y align-center">
							<div class="medium-6 cell">
								<label for="entry">Name entry <span>*</span></label>
								<input type="text" id="entry" name="entry" style="padding: 30px 25px; border-radius: 3px;">

								<p class="text-center">
									<input type="submit" value="Submit Entry" class="blue">
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