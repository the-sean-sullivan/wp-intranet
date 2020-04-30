<?php $module = 'people'; include_once 'dashboard-header.php'; /* Template Name: People Dashboard */ ?>

<main role="main">
	<div class="grid-container full">
		<div class="grid-x align-center blue-bg padding-sm">
			<div class="medium-10 large-8 cell">
				<form role="search" method="post" id="search" action="<?php bloginfo('url'); ?>">
					<p><input type="text" name="s" id="s" value="" placeholder="Looking for someone?"></p>
				</form> <!-- /form -->
			</div>
		</div>

		<div class="grid-x align-center">
			<div class="medium-10 large-8 cell">
			
			<?php if (have_posts()): while (have_posts()) : the_post(); ?>

				<div class="grid-x grid-margin-x the-people">
					
					<!-- posts summary -->
					<?php require 'people-loop.php'; ?>

					<?php
						// Additional Contacts
						$contacts = get_field('contacts');

						$current_header = '';

						if ( $contacts ) : 

							// Order by Type
							usort($contacts, function($a, $b) { return $a['name_sort'] <=> $b['name_sort']; }); // Sort
							foreach ( $contacts as $contact ) :

								if ( $contact['contact_type'] != $current_header ) :
							        $current_header = $contact['contact_type'];
							        echo '<h3 class="department-header medium-12 cell people-filter">' . $current_header . '</h3>';
							    endif;

							    // Get each employee for each department
							    $contact_info = $contact['contacts_info'];
					?>

								<div class="medium-4 cell people-filter">
									<div class="person">
										<img src="<?php echo $contact['photo']; ?>" alt="">
										<div class="person-info match">
											<h5><?php echo $contact['name']; ?></h5>
											<div class="email-ext">
												<?php
													echo $contact_info['email'] ? '<object><a href="mailto:' . $contact_info['email'] . '" class="email">' . $contact_info['email'] . '</a></object>' : '';
													echo $contact_info['phone_number_extension'] ? '<div class="ext">DID #: <strong>' . $contact_info['phone_number_extension'] . '</strong></div>' : ''; 
												?>
											</div>
											<?php echo $contact_info['personal_cell_phone'] ? '<div class="phone">' . $contact_info['personal_cell_phone'] . '</div>' : ''; ?>
											<div class="nickname" style="display: none;"><?php echo $contact_info['nickname']; ?></div>
										</div>
									</div>
								</div>
							
					<?php endforeach; endif; ?>
				
				</div> <!-- .row -->
		
			<?php endwhile; endif; ?>
			
			</div> <!-- .columns -->
		</div> <!-- .row -->
	</div>
</main> <!-- /main -->

<?php include_once 'dashboard-footer.php'; ?>