<?php acf_form_head(); include 'dashboard-header.php'; /* Template Name: Main Dashboard */ ?>

<main role="main">

	<div id="dashboard-space">
		<?php if (have_posts()): while (have_posts()) : the_post(); ?>
			<section id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

				<div class="grid-container fluid">
					<div class="grid-x align-center">
						<div class="medium-10 large-8 cell">

							<div id="user-buttons">
								<!-- <a href="<?php bloginfo('url');?>/dashboard/people" class="button"><img src="<?php echo SRS_URL; ?>/public/images/icons/icon-phone.svg" alt=""> Phone List</a> -->
								<?php echo ( current_user_can('administrator') ) ? '<a href="' . get_bloginfo('url') . '/wp-admin" class="button"><img src="' . SRS_URL . '/public/images/icons/icon-wp-admin.svg" alt=""> WP Dash</a>' : ''; ?>
							</div>

							<?php
								$current_user = wp_get_current_user();
								$current_user_url = strtolower(trim(preg_replace('/[^a-zA-Z0-9]+/', '-', $current_user->display_name), '-')); // Convert their name to URL friendly
								$pid = Srs_Intranet_Public::get_post_id( $current_user_url, 'people' ); // Get the post ID of their page

								if ( $current_user->first_name == 'Sean' ) echo '<h4>Hello there, handsome.</h4>';
								elseif ( $current_user->first_name == 'Mateo' ) echo '<h4>Hello there, Mr. Digital Director.</h4>';
								elseif ( get_field('nickname', $pid ) ) echo '<h4>What\'s up, ' . get_field('nickname', $pid) . '?</h4>';
								else echo '<h4>Welcome, ' . $current_user->first_name . '.</h4>';

								the_content();
							?>

						</div>
					</div>
				</div>

			</section> <!-- /article -->

			<?php $app_perm = Srs_Intranet_Public::user_permissions( 'applicants' ); ?>

			<div class="grid-container">
				<div class="grid-x medium-up-2 large-up-5 quick-links align-center">

					<?php if ( post_type_exists( 'naming-contest' ) ) : ?>
					<div class="cell">
						<a href="<?php bloginfo('url'); ?>/dashboard/naming-contest">
							<img src="<?php echo SRS_URL; ?>/public/images/icons/icon-naming-contest.svg" alt="" class="">
							Naming Contest
						</a>
					</div>
					<?php endif; ?>

					<div class="cell">
						<a href="<?php bloginfo('url'); ?>/dashboard/comment-comment-box">
							<img src="<?php echo SRS_URL; ?>/public/images/icons/icon-comment-comment-box.svg" alt="" class="">
							OneTeam Comment Box
						</a>
					</div>

					<div class="cell">
						<a href="<?php bloginfo('url'); ?>/dashboard/office-map">
							<img src="<?php echo SRS_URL; ?>/public/images/icons/icon-office-map.svg" alt="" class="">
							Office Map
						</a>
					</div>

					<div class="cell">
						<a href="<?php bloginfo('url'); ?>/dashboard/people">
							<img src="<?php echo SRS_URL; ?>/public/images/icons/icon-agency-directory.svg" alt="" class="">
							Agency Directory
						</a>
					</div>

					<?php if ( $app_perm == 1 ) : ?>
					<div class="cell">
						<a href="<?php bloginfo('url'); ?>/dashboard/applicants">
							<img src="<?php echo SRS_URL; ?>/public/images/icons/icon-candidate-tracker.svg" alt="" class="">
							Candidate Tracker
						</a>
					</div>
					<?php endif; ?>

				</div>




				<!-- <div class="grid-x">
					<div class="medium-5 cell">
						<?php if ( post_type_exists( 'events' ) ) : ?>
							<section>
								<h4 class="more">Important Dates <i class="fa fa-chevron-down fa-fw"></i></h4>

								<div class="more-info">
									<label><?php echo date('l, F j, Y'); ?></label>

									<?php

										$today = date('Ymd');

									   	$the_query = new WP_Query(
											array(
												'post_type'   => array( 'events', 'people' ),
												'numberposts' => 15,
												'post_status' => 'publish',
												'meta_query'  => array(
											        'relation' => 'OR',
											        'event_date_clause' => array(
														'key'     => 'event_date',
														'value'   => $today,
														'compare' => '>='
											        ),
											        'birthdate_clause' => array(
														'key'     => 'birthdate',
														'value'   => $today,
														'compare' => '>='
											        ),
											        'start_date_clause' => array(
														'key'     => 'start_date',
														'value'   => $today,
														'compare' => '>='
											        ),
											    ),
											    'orderby' => array(
													'event_date_clause' => 'ASC',
													'birthdate_clause'  => 'ASC',
													'start_date_clause' => 'ASC',
											    ),
											)
										);

										if ( $the_query->have_posts() ) :

									?>

									<div id="color-keys">
										<div class="grid-x grid-padding-x">
											<div class="medium-3 cell"><div class="show-all-filter">All</div></div>
											<div class="medium-3 cell"><div class="events-filter">Events</div></div>
											<div class="medium-3 cell"><div class="meetings-filter">Meetings</div></div>
											<div class="medium-3 cell"><div class="holidays-filter">Holidays</div></div>
										</div>
									</div>

									<ul id="important-dates">

										<?php

												while ( $the_query->have_posts() ) : $the_query->the_post();

													// People Dates
													$birthdate  = get_field('birthdate');
													$birthdate  = date("F j", strtotime($birthdate));

													$start_date = get_field('start_date');
													$start_date  = date("F j", strtotime($start_date));

													// Events
													$event_date = get_field('event_date');
													$event_date = date("F j", strtotime($event_date));

													if( $event_date ) :
														$event_info = get_field('event_info');
														$event_type = get_field('event_type');
														$event_date_full = date("l, F j, Y", strtotime($event_date));

														if ( isset( $event_type->slug ) ) :
															if ($event_type->slug == 'event')
																$color = '#EAF5FB';
															elseif ($event_type->slug == 'meeting')
																$color = '#EAFBF1';
															elseif ($event_type->slug == 'holiday')
																$color = '#FCF5EB';
														endif;
										?>

													<li style="background-color: <?php echo $color; ?>" class="<?php echo $event_type->slug; ?>">


														<?php echo ( $event_info ) ? '<a class="event-info" href="#event-for-' . get_the_ID() . '">' : ''; ?>
															<strong><?php echo $event_date; ?></strong> - <?php the_title(); ?>
															<?php if( $event_info ) : ?>
																<span id="event-for-<?php the_ID(); ?>" style="display: none;">
																	<h5><?php the_title(); ?></h5>
																	<p><?php echo $event_date_full; ?></p>
																	<?php echo $event_info; ?>
																</span>
															<?php endif; ?>
														<?php echo ( $event_info ) ? '</a>' : ''; ?>

													</li>

												<?php else : ?>

													<li>

														<?php if ( $birthdate ) : ?>

															<strong><?php echo $birthdate; ?></strong> - Happy Birthday, <?php the_title(); ?>!

														<?php elseif ( $start_date ) : ?>

															<strong><?php echo $start_date; ?></strong> - Happy Work Anniversary, <?php the_title(); ?>!

														<?php endif; ?>

													</li>

												<?php endif; ?>
											</li>

									<?php endwhile; ?>

									</ul>

									<?php

										else :

											echo '<h5>Currently, no events.</h5>';

										endif;

									?>
								</div>
							</section>
						<?php endif; ?>
					</div>
					<div class="medium-7 cell">

						<?php if ( post_type_exists( 'outlist' ) ) : ?>
							<section>
								<h4>Out List</h4>

								<div id="slider">

									<?php
									   $the_query = new WP_Query(
											array(
												'post_type'   => 'outlist',
												'numberposts' => 5,
												'post_status' => 'publish',
												'meta_key'    => 'list_date',
												'orderby'     => 'meta_value',
												'order'       => 'ASC',
												'meta_query'  => array(
											        'event_date_clause' => array(
														'key'     => 'list_date',
														'value'   => $today,
														'compare' => '>='
											        )
											    )
											)
										);

										if ( $the_query->have_posts() ) : while ( $the_query->have_posts() ) : $the_query->the_post();

											$list_date = get_field('list_date');
											$list_date_full = date('l, F j, Y', strtotime($list_date));
									?>

									<div class="outlist-slide">
										<h3><?php echo $list_date_full; ?></h3>

										<?php the_field('special_message'); ?>

										<?php
											$the_reason = ''; $client = '';
											if( have_rows('out_list') ): while ( have_rows('out_list') ) : the_row();

										    	$names = get_sub_field('name');

										    	foreach ($names as $name) :
										    		$out_tax = get_sub_field('reason');
										    		$client_name = get_sub_field('clients');

										    		// Group by reason
											    	if ( $out_tax != $the_reason ) :
												        $the_reason = $out_tax;
												    	echo '<h5>' . $the_reason[0] . '</h5>';
												    endif;

												    // If client, group by client
												    if ( $client_name != $client ) :
												        $client = $client_name;
												    	echo '<h6>' . $client . '</h6>';
												    endif;
										?>

											<div class="row">
												<div class="medium-2 cell">
													<div class="photo">
														<?php $photo = get_field('photo', $name->ID); ?>
														<img src="<?php echo SRS_URL; ?>/public/images/out-list-mask.png" alt="" class="outlist-mask">
														<img src="<?php echo $photo['url']; ?>" alt="<?php echo $name->post_title; ?>">
													</div>
												</div>
												<div class="medium-3 cell">
									    			<?php echo $name->post_title; ?>
									    		</div>
									    		<div class="medium-7 cell">
									    			<?php echo ( get_sub_field('notes') ) ? get_sub_field('notes') : ''; ?>
									    		</div>
									    	</div>

									    <?php endforeach; endwhile; endif; ?>

									</div>

									<?php endwhile; endif; ?>

								</div>

								<?php if ( $the_query->have_posts() ) : ?>
								<div id="outlist-pager">
									<p>View the Out List for:</p>
									<?php
										$i = 0; while ( $the_query->have_posts() ) : $the_query->the_post();
											$list_date = get_field('list_date');
											$list_date_full = date('n/j', strtotime($list_date));
									?>
								  		<a data-slide-index="<?php echo $i; ?>" href=""><?php echo $list_date_full; ?></a>
								  	<?php $i ++; endwhile; ?>
								</div>
								<?php endif; ?>

							</section>
						<?php endif; ?>

					</div>
				</div> -->
			</div>

		<?php endwhile; endif; ?>

	</div> <!-- #dashboard-space -->
</main><!-- /main -->


<?php include 'dashboard-footer.php'; ?>
