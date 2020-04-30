<?php acf_form_head(); $module = 'out_list'; require_once 'dashboard-header.php'; /* Template Name: Out List */ ?>

<main role="main">
	<div class="row">
		
		<?php if (have_posts()): while (have_posts()) : the_post(); ?>
		
			<?php
			
				// if "new" is present in query string, then display new post form
				if( isset( $_GET['new'] ) ) :
				
					acf_form(array(
						'post_id'		=> 'new_post',
						'post_title'	=> false,
						'post_content'	=> false,
						'new_post'		=> array(
							'post_type'		=> 'outlist',
							'post_status'	=> 'publish'
						)
					));
				
				// Otherwise display recent list
				else :
			?>
			
			
			<p><a href="<?php the_permalink(); ?>?new=yes" class="button">Add New List</a></p>
			
			<table id="outlist">
				<tr>
					<th width="25%">Date</th>
					<th width="75%">By the Numbers</th>
				</tr>
				
				<!-- posts summary -->			
				<?php
					$the_query = new WP_Query( 
						array(
							'post_type'   => 'outlist',
							'numberposts' => 5,
							'post_status' => 'publish',
							'meta_key'    => 'list_date',
							'orderby'     => 'meta_value',
							'order'       => 'ASC',
						) 
					);

					if ( $the_query->have_posts() ) : while ( $the_query->have_posts() ) : $the_query->the_post();
					
						if( have_rows('out_list') ): while ( have_rows('out_list') ) : the_row();
						    
						    $reasons = get_sub_field('reason');
						    $reason[] = implode(',', $reasons);
						    
						endwhile; endif;
						
						$reason = array_count_values($reason);
						
						$vacation_count = $appt_count = $clients_count = $sick_count = $wfh_count = '';
						$vacation_count = isset( $reason['Vacation'] ) ? $reason['Vacation'] : '0';
						$appt_count     = isset( $reason['Appointment'] ) ? $reason['Appointment'] : '0';
						$clients_count  = isset( $reason['Out With Clients'] ) ? $reason['Out With Clients'] : '0';
						$sick_count     = isset( $reason['Sick'] ) ? $reason['Sick'] : '0';
						$wfh_count      = isset( $reason['Work From Home'] ) ? $reason['Work From Home'] : '0';
						
						$list_date = get_field('list_date');
						$list_date = date("l, F j, Y", strtotime($list_date));
					    
				?>
				
					<tr>
						<td><a href="<?php the_permalink(); ?>"><?php echo $list_date; ?></a></td>
						<td>
							<div class="row">
								<div class="medium-2 columns"><strong>Vacation</strong><br /><?php echo $vacation_count; ?></div>
								<div class="medium-2 columns"><strong>Appt.</strong><br /><?php echo $appt_count; ?></div>
								<div class="medium-2 columns"><strong>Clients</strong><br /><?php echo $clients_count; ?></div>
								<div class="medium-2 columns"><strong>Sick</strong><br /><?php echo $sick_count; ?></div>
								<div class="medium-2 columns"><strong>WFH</strong><br /><?php echo $wfh_count; ?></div>
								<div class="medium-2 columns end"><a href="<?php the_permalink(); ?>" class="button full-width">Edit</a></div>
							</div>
						</td>
					</tr>
						
				<?php endwhile; else :?>
				
					<h3>Nothing to see here. Please <a href="<?php bloginfo('url'); ?>/dashboard/out-list/?new=yes">add</a> a new Out List.</h3>
				
				<?php endif; ?>
				
			</table> <!-- #outlist -->
			
		<?php endif; endwhile; endif; ?>
			
	</div> <!-- .row -->
</main> <!-- /main -->

<?php require_once 'dashboard-footer.php'; ?>