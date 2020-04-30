<?php acf_form_head(); $module = 'careers'; require_once 'dashboard-header.php'; /* Template Name: Careers - Single (Edit) */ ?>

<main role="main">
	<div class="row">
	
		<?php 
			if (have_posts()) : while (have_posts()) : the_post(); 
			
				if ( isset($_GET['edit']) ) : 
					
					$job_id = $_GET['edit'];
					
					echo '<h3>Edit Job</h3>';
					
					// Activate or inactivate job
					if( isset( $_GET['active'] ) ) :
						
						if ( $_GET['active'] == 'no' ) 
							$status = 'draft';
						else
							$status = 'publish';							
						
						$post = array( 'ID' => $job_id, 'post_status' => $status );
						wp_update_post($post);
						echo '<div class="success">Job status has been updated to ' . $status . '.</div>';
						
					endif;
					
					if ( get_post_status ( $job_id ) == 'publish' )
						echo '<p><a href="?edit=' . $job_id . '&active=no" onclick="javascript:if(!confirm(\'Are you sure you want to make this job inactive?\')) return false;" class="button">Inactivate Job</a></p>';
					else
						echo '<p><a href="?edit=' . $job_id . '&active=yes" onclick="javascript:if(!confirm(\'Are you sure you want to make this job active?\')) return false;" class="button">Activate Job</a></p>';
		
					
					// Setup edit form
					acf_form( 
						array(
							'post_id'    => $job_id,
							'post_title' => true
						) 
					); 
		?>		
			<div class="row">
				<div class="medium-6 columns">
					<a href="<?php echo site_url('/dashboard/careers-dash/'); ?>">&laquo; Back to Careers Dashboard</a>
				</div> <!-- .columns -->
				<?php if ( get_post_status ( $job_id ) == 'publish' ) : ?>
				<div class="medium-6 columns" style="text-align: right;">
					<a href="<?php echo get_permalink($job_id); ?>">View Job On Site &raquo;</a>
				</div> <!-- .columns -->
				<?php endif; ?>
			</div> <!-- .row -->
			
		<?php	
				
				elseif ( isset($_GET['add'] ) ) :
					
					echo '<h3>Add Job</h3>';
					
					acf_form(array(
						'post_id'		=> 'new_post',
						'post_title'	=> true,
						'post_content'	=> false,
						'new_post'		=> array(
							'post_type'		=> 'careers',
							'post_status'	=> 'publish'
						)
					));
				
					echo '<p><a href="' . site_url('/dashboard/careers-dash/') . '">&laquo; Back to Careers Dashboard</a></p>';
				
				endif;
		
			endwhile; endif; 
		?>

	</div> <!-- .row -->
</main> <!-- /main -->

<?php require_once 'dashboard-footer.php'; ?>