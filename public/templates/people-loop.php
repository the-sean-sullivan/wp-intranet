<?php
	// Get People
	$peoples = SRS_Intranet_Public::rest_api_get( 'people?per_page=100' );

	// Get Departments
	$departments = SRS_Intranet_Public::rest_api_get( 'department-people' );

	$current_header = '';

	// Order by department
	foreach( $departments as $department ) :

		if ( $department['count'] >= 1 ) :

			if ( $department['name'] != $current_header ) :
		        $current_header = $department['name'];
		        echo '<h3 class="department-header medium-12 cell people-filter">' . $current_header . '</h3>';
		    endif;

		    // Get each employee for each department
		    usort($peoples, function($a, $b) { return $a['content']['name_sort'] <=> $b['content']['name_sort']; }); // Sort
			foreach( $peoples as $people ) :
				$block_info = $people['content']['_raw'];
				$custom_fields = $people['content']['custom_fields'];

				if ( $department['id'] == $block_info['department'] ) :
?>

			<div class="medium-4 cell people-filter">
				<div class="person">
					<?php
						$position_type = $custom_fields['position_type'];
						if ( $position_type['value'] !== 'full' && !empty( $position_type ) ) :
					?>
					<div class="position-type"><?php echo $position_type['label']; ?></div>
					<?php
						endif;

						$people_img = $people['content']['photo'] ?? SRS_URL . '/public/images/no-photo.jpg';
					?>
					<img src="<?php echo $people_img; ?>" alt="">
					<div class="person-info match">
						<h5><?php echo $people['title']['rendered']; ?></h5>
						<div class="title"><?php echo $block_info['job_title']; ?></div>
						<div class="email-ext">
							<object><a href="mailto:<?php echo $custom_fields['email']; ?>" class="email"><?php echo $custom_fields['email']; ?></a></object>
							<?php echo $custom_fields['phone_number_extension'] ? '<div class="ext">DID #: <strong>' . $custom_fields['phone_number_extension'] . '</strong></div>' : ''; ?>
						</div> <!-- .row -->
						<div class="phone"><?php echo $custom_fields['personal_cell_phone']; ?></div>
						<div class="nickname" style="display: none;"><?php echo $custom_fields['nickname']; ?></div>
					</div> <!-- .person-info -->
				</div>
			</div> <!-- .columns -->

	<?php endif; endforeach; endif; endforeach;?>
