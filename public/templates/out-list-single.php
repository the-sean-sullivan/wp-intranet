<?php acf_form_head(); $module = 'out_list'; require_once 'dashboard-header.php'; /* Template Name: Out List - Single */ ?>

<main role="main">
	<div class="row">
		
		<?php while ( have_posts() ) : the_post(); 
		
			// Setup edit form
			acf_form( 
				array(
					'post_id'    => get_the_ID(), 
					'post_title' => false
				) 
			); 
				
			endwhile; 
		?>
	
	</div> <!-- .row -->
</main> <!-- /main -->

<?php require_once 'dashboard-footer.php'; ?>
