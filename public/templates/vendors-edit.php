<?php acf_form_head(); $module = 'vendor_database'; require_once 'dashboard-header.php'; /* Template Name: Vendors - Edit */ ?>

<main role="main">
	<div class="row">
		<div class="medium-12 columns">
			<?php 
				if (have_posts()) : while (have_posts()) : the_post(); 
				
					if ( isset($_GET['edit']) ) : 
						
						$job_id = $_GET['edit'];
						
						echo '<h3>Edit Vendor</h3>';
						
						// Setup edit form
						acf_form( 
							array(
								'post_id'    => $job_id,
								'post_title' => true
							) 
						); 
			?>		
				
				<p><a href="<?php echo site_url('/dashboard/vendors/'); ?>">&laquo; Back to Vendors</a></p>
				
			<?php	
					
					elseif ( isset($_GET['add'] ) ) :
						
						echo '<h3>Add Vendor</h3>';
						
						acf_form(array(
							'post_id'		=> 'new_post',
							'post_title'	=> true,
							'post_content'	=> false,
							'new_post'		=> array(
								'post_type'		=> 'vendors',
								'post_status'	=> 'publish'
							)
						));
					
						echo '<p><a href="' . site_url('/dashboard/vendors/') . '">&laquo; Back to Vendors</a></p>';
					
					endif;
			
				endwhile; endif; 
			?>
		</div> <!-- .columns -->
	</div> <!-- .row -->
</main> <!-- /main -->

<?php require_once 'dashboard-footer.php'; ?>