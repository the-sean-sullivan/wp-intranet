<?php

	function update_post_status_acf( $post_id ) {
		if ( is_admin() || get_post_type($post_id) != 'people' )
    		return $post_id;

		$args = array(
			'ID'          => $post_id,
			'post_status' => 'pending'
		);
		wp_update_post($args);
		return $post_id;
	}
	add_filter('acf/pre_save_post' , 'update_post_status_acf', 10, 1 );

	acf_form_head();

	require_once 'dashboard-header.php'; /* Template Name: PTO Request */

?>

<main role="main" class="dark-bg">
	<div class="grid-container fluid">
		<div class="grid-x align-center padding-md">
			<div class="medium-11 large-8 cell">

			<?php
				if (have_posts()): while (have_posts()) : the_post();

					// Get posts information
					$current_user = wp_get_current_user();
					$display_name = $current_user->display_name;
					$user_email   = $current_user->user_email;
					$user_slug    = Srs_Intranet_Public::user_to_slug( $display_name );
					$user_page_id = Srs_Intranet_Public::get_post_id( $user_slug, 'people' );
					$employee 	  = SRS_Intranet_Public::rest_api_get( 'people?slug=' . $user_slug ); // REST API
					$curr_page_id = $post->ID;

					// Get supervisor info things
					$supervisor = $employee[0]['content']['custom_fields']['supervisors_name'][0]['ID'];
					$is_supervisor = $employee[0]['content']['custom_fields']['is_a_supervisor'];

					// If user ID returns 0, add '-2' to the end of the slug. Most likely the issue.
					if ( $user_page_id == 0 )
						$user_page_id = Srs_Intranet_Public::get_post_id($user_slug . '-2', 'people');

					// Email stuff
					if ( isset( $_GET['vac-req'] ) ) :

						$vac_req = $_GET['vac-req'];

						$supers_info = SRS_Intranet_Public::rest_api_get( 'people/' . $supervisor ); // REST API
						if ( $supers_info ) :

							$to = $supers_info['content']['custom_fields']['email'];

							if ( $vac_req == 'new' ) :
								$subject = 'PTO request from ' . $display_name;
								$body = '<p>' . $display_name . ' has requested PTO time.</p>';
							else :
								$subject = 'PTO request update from ' . $display_name;
								$body = '<p>' . $display_name . ' has updated their requested PTO time.</p>';
							endif;

							$body .= '<p>Please <a href="' . get_bloginfo('url') . '/dashboard/pto-request/">click here</a> to see their request.</p>';

							$headers = array(
								'Content-Type: text/html; charset=UTF-8',
								'From: ' . $user_email,
							);

							mail($to, $subject, $body, implode('\r\n', $headers) );

						endif;

					endif;


					// Show request form
					if ( isset( $_GET['request'] ) ) :
				?>
						<h3>Request PTO</h3>

				<?php
						$request_type = $_GET['request'];
						$new_post = '';

						if ( $request_type == 'new' ) :
							$post_id = 'new_post';
							$return = 'new';
							$new_post = array(
								'post_title'	=> 'PTO Request',
								'post_type'		=> 'people',
								'post_status'	=> 'pending',
								'post_parent'	=> $user_page_id
							);

						elseif ( $request_type == 'edit' ) :
							$post_id = $_GET['page-id'];
							$return  = 'update';
						elseif ( $request_type == 'delete' ) :
							wp_delete_post( $_GET['page-id'] );
							wp_redirect(get_bloginfo('url') . '/dashboard/pto-request/'); exit;
						endif;

						// Display form
						acf_form(
							array(
								'post_id'            => $post_id,
								'post_title'         => false,
								'post_content'       => false,
								'field_groups'       => array('group_57bf471b05dcd'), // Grabs the specific PTO ACF
								'new_post'           => $new_post,
								'submit_value'       => __('Send Request'),
								'html_submit_button' => '<input type="submit" class="button blue" value="%s" />',
								'return'             => get_bloginfo('url') . '/dashboard/pto-request/?vac-req=' . $return
							)
						);

						echo '<p class="text-center" style="padding-top: 25px;"><a href="' . get_the_permalink() . '" style="color: #CCC">&laquo; Nevermind, I don\'t want to take any PTO</a></p>';

					// Show dashboard
					else :

				?>

				<div class="grid-x grid-margin-x">
					<div class="medium-6 cell"><a href="<?php the_permalink(); ?>/?request=new" class="button blue">Request PTO</a></div>

					<?php if ( $is_supervisor == true ) : ?>
					<div class="medium-6 cell">
						<ul class="tabs">
							<li class="tab-link current" data-tab="tab-1">My Requests</li>
							<li class="tab-link" data-tab="tab-2">Team Requests</li>
						</ul>
					</div>
					<?php endif; ?>
				</div>

				<!-- Show your requests -->
				<div id="tab-1" class="tab-content current">
					<div class="grid-x">
						<div class="medium-12 cell">
							<table class="table-style">
								<tr></tr>
								<?php

									if ( $user_page_id !== 0 ) :

										$my_query = new WP_Query(
											array(
												'post_type'   => 'people',
												'numberposts' => -1,
												'post_status' => 'any',
												'post_parent' => $user_page_id
											)
										);

										if ( $my_query->have_posts() ) : while ( $my_query->have_posts() ) : $my_query->the_post();

											$status = get_post_status( get_the_ID() );
								?>

								<tr>
									<td style="width: 73%">
										<div style="display: flex; flex-wrap: wrap;">
								<?php
									$amt = array();
									while ( have_rows('dates_requested') ) : the_row();

										$field = get_sub_field_object('field_57bf55c7b1af6');
										$value = get_sub_field('type');
										$label = $field['choices'][ $value ];
								?>
									    <div class="pto-date">
									        <?php
									        	echo get_sub_field('dates');
									        	echo '<span>' . $label . '</span>';
									       	?>
								        </div>

								<?php
								        $amt[] = $value == 'full' ? 1 : 0.5;

								    endwhile;
								?>
									    	<div class="pto-date">
									    		Days off: <?php echo array_sum($amt); ?>
									    	</div>
										</div>
								    </td>
									<td style="width: 15%">

								<?php if ( $status !== 'publish' ) : ?>
								    	<div class="medium-text-right">
								        	<a href="<?php echo get_the_permalink( $curr_page_id ); ?>?request=edit&page-id=<?php echo get_the_ID(); ?>">Edit</a> |
								        	<a href="<?php echo get_the_permalink( $curr_page_id ); ?>?request=delete&page-id=<?php echo get_the_ID(); ?>">Delete</a>
								        </div>
								<?php endif; ?>

								    </td>

								<?php
									if ( $status == 'pending' )
										$button_class = 'pending';
									elseif ( $status == 'publish' )
										$button_class = 'approved';
									elseif ( $status == 'draft' )
										$button_class = 'denied red';
								?>

									<td style="width: 12%">
										<a class="button <?php echo $button_class; ?> full-width">
											<?php
												if ( $status == 'pending' )
													echo '<i class="fa fa-question fa-fw"></i> Pending';
												elseif ( $status == 'draft' )
													echo '<i class="fa fa-times fa-fw"></i> Denied';
												else
													echo '<i class="fa fa-check fa-fw"></i> Approved';
											?>
										</a>
									</td>
								</tr>

						<?php endwhile; ?>

							</table>

						<?php else : ?>

							<h3>No PTO on record.</h3>

						<?php endif; endif; ?>
						</div>
					</div> <!-- .row -->
				</div> <!-- #tab-1 -->

				<?php
					// If is a supervisor
					if ( $is_supervisor == true ) :

						// Send emails
						if ( isset( $_GET['status'] ) ) :

							$request_status = $_GET['status'];
							$emp_id         = $_GET['emp-id'];
							$request_id     = $_GET['request-id'];
							$emp_name       = get_the_title( $emp_id );

							if ( $request_status == 'approved' )
								$post_status = 'publish';
							else
								$post_status = 'draft';

							$vac_status = array(
								'ID'          => $request_id,
								'post_status' => $post_status
							);
							wp_update_post($vac_status);

							// Takes name and retrieves email
							$emp_email = Srs_Intranet_Public::name_to_email( $emp_name );

							$pamela = '';
							if ( $request_status == 'approved' )
								$pamela = 'CC: me@seanrsullivan.com';

								// $pamela = 'CC: pamela.brady@srsadv.com';

							$to = $emp_email;
							$subject = 'PTO request has been ' . $request_status;

							$body = '<p>' . $emp_name . ',</p>';
							$body .= '<p>Your most recent PTO request has been ' . $request_status . '.</p>';
							if ( $request_status == 'denied' )
								$body .= '<p>Please see ' . $current_user->first_name . ' for any questions.</p>';

							$headers = array(
								'Content-Type: text/html; charset=UTF-8',
								'From: ' . $user_email,
								$pamela
							);

							mail($to, $subject, $body, implode('\r\n', $headers) );

						endif;

				?>
				<div id="tab-2" class="tab-content">
					<div class="grid-x">
						<div class="medium-12 cell">
							<table class="table-style">
								<tr></tr>
								<?php

									$employees = SRS_Intranet_Public::rest_api_get( 'people/super/' . $user_slug ); // REST API

									foreach ( $employees['super_info'] as $employee ) :

										$page = get_page_by_path( $employee['post_name'], OBJECT, 'people' );
										$parent_id = $page->ID;

										$filter_query = new WP_Query(
											array(
												'post_type'   => 'people',
												'numberposts' => -1,
												'post_status' => 'any',
												'post_parent' => $parent_id
											)
										);

										if ( $filter_query->have_posts() ) : while ( $filter_query->have_posts() ) : $filter_query->the_post();

											$status = get_post_status( get_the_ID() );
								?>
								<tr>
									<td style="width: 85%">
										<?php echo '<h5>' . get_the_title( $parent_id ) . '</h5>'; ?>

										<div style="display: flex; flex-wrap: wrap;">
										<?php
											$amt = array();
											while ( have_rows('dates_requested') ) :
												the_row();

												$field = get_sub_field_object('field_57bf55c7b1af6');
												$value = get_sub_field('type');
												$label = $field['choices'][ $value ];
										?>

										       <div class="pto-date">
											        <?php
											        	echo get_sub_field('dates');
											        	echo '<span>' . $label . '</span>';
											       	?>
										        </div>

										<?php
								        $amt[] = $value == 'full' ? 1 : 0.5;

										    endwhile;
										?>
									    	<div class="pto-date">
									    		Days off: <?php echo array_sum($amt); ?>
									    	</div>
										</div>
								    </td>
									<td style="width: 15%">
										<?php
											// If new request (pending), show options to approve or deny.
											if ( $status == 'pending' ) :
										?>

											<div id="status">
												<ul>
													<li><a href="" class="pending button"><i class="fa fa-question fa-fw"></i> Pending</a>
														<ul>
															<li><a href="<?php the_permalink( $curr_page_id ); ?>?status=approved&emp-id=<?php echo $parent_id; ?>&request-id=<?php the_ID(); ?>" class="button approved"><i class="fa fa-check fa-fw"></i> Approved</a></li>
															<li><a href="<?php the_permalink( $curr_page_id ); ?>?status=denied&emp-id=<?php echo $parent_id; ?>&request-id=<?php the_ID(); ?>" class="button denied"><i class="fa fa-times fa-fw"></i> Denied</a></li>
														</ul>
													</li>
												</ul>
											</div>

										<?php

											// If approve or denied, show appropriate button.
											else :

												if ( $status == 'draft' ) :
													$button_class = 'denied';
													$button_text = '<i class="fa fa-times fa-fw"></i> Denied';
												elseif ( $status == 'publish' ) :
													$button_class = 'approved';
													$button_text = '<i class="fa fa-check fa-fw"></i> Approved';
												endif;

										?>

											<a class="button <?php echo $button_class; ?> full-width">
												<?php echo $button_text; ?>
											</a>

										<?php endif; ?>

									</td>
								</tr>

								<?php endwhile; else : ?>

								<h3 class="text-center">No PTO requests.</h3>

								<?php endif; endforeach; ?>

							</table>
						</div>
					</div> <!-- .row -->
				</div> <!-- #tab-2 -->

			<?php endif; endif; endwhile; endif; ?>

			</div> <!-- .columns -->
		</div> <!-- .row -->
	</div>
</main> <!-- /main -->

<?php require_once 'dashboard-footer.php'; ?>
