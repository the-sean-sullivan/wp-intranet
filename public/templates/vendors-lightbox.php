<?php include( $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php'); ?>


<html lang="en">
	<head>
		<?php wp_head(); ?>
	</head>
	<body>
		
		<div class="success" style="display: none;">Added to lightbox</div>
		<div class="fail" style="display: none;">No.</div>
		
		<div class="row">
			<div class="medium-12 columns">
				<h2>Your Lightboxes</h2>
				
				<?php
					$data = $_POST['lightbox'];
					$amount = count($data);
				?>
				<p><?php echo $amount; ?> vendor(s) have been selected.</p>
		
				<div class="row">
					<div class="medium-6 columns">
						<h4>Create new lightbox</h4>
						<form action="" id="new-lightbox-form">
							<?php
								$current_user = wp_get_current_user();
								
								$user_id = $current_user->ID;
								echo '<input type="hidden" name="user_id" value="' . $user_id . '">';
								
								
								foreach( $data as $result ) :
									echo '<input type="hidden" name="lightbox_value[]" value="' . $result . '">';
								endforeach;
							?>
							
							<p>
								<label>Lightbox Name</label>
								<input type="text" name="lightbox_name" value="" placeholder="No spaces or special characters">
							</p>
							<p><input type="submit" class="submit-lightbox" value="Add New Lightbox"></p>
						</form>
					</div>
					<div class="medium-6 columns">
						<h4>Add to lightbox</h4>
						<form action="" id="update-lightbox-form">
							<input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
							<?php
							
								foreach( $data as $update_result ) :
									echo '<input type="hidden" name="lightbox_value[]" value="' . $update_result . '">';
								endforeach;
							
								global $wpdb;
								$current_lightboxes = $wpdb->get_results(
									$wpdb->prepare("SELECT * FROM $wpdb->usermeta WHERE user_id = %d AND meta_key LIKE '%%lightbox_%%'", $user_id)
								);
							?>
							
							<p>
								<label>Lightbox Name</label>
								<select name="lightbox_name" class="staff-survey-select" style="width: 100%;">
								<?php
									$lightbox_name = '';
									foreach ( $current_lightboxes as $current ) :
										
										$meta_key = $current->meta_key;
										
										$nice_name = str_replace('lightbox_', '', $meta_key);
										
										if ($nice_name != $lightbox_name) :
									        echo '<option value="' . $meta_key . '">' . $nice_name . '</option>';
									        $lightbox_name = $nice_name;
									    endif;
									endforeach;
								?>
								</select>
							</p>
							
							<p><input type="submit" class="submit-lightbox" value="Update Lightbox"></p>
						</form>
					</div>
				</div>
			</div>
		</div>
	</body>
	
	<script>
		jQuery(document).ready(function($){
			
			$('.submit-lightbox').on('click', function(e) {
				
				var formID = $(this).closest('form').attr('id');
				var data = $('#' + formID).serialize();
				
		        e.preventDefault();
		        $.ajax({
		            type: 'POST',
		            url: '<?php echo admin_url( "admin-ajax.php" ); ?>',
		            data: {
			            action : 'add_lightbox',
			            data   : data
			        },
		            success: function (data) {
			            $('.success').fadeIn().delay(5000).fadeOut();
		            },
		            error: function () {
		            	$('.fail').fadeIn().delay(5000).fadeOut();
		            }
		        });
		    });
		    
		});
	</script>
	
	<?php //wp_footer(); ?>
	
</html>
	
