<?php $module = 'vendor_database'; require_once 'dashboard-header.php'; /* Template Name: Vendors Dashboard */ ?>

<main role="main">

	<?php if (have_posts()): while (have_posts()) : the_post(); ?>

		<div class="row">
			<!-- <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

				<?php the_content(); ?>

			</article> -->

			<a href="<?php bloginfo('url');?>/dashboard/vendor-database/vendors-edit/?add=doit" class="button">Add Vendor</a>
			<a href="#lightbox-data" class="your-lightbox button">View Your Lightboxes</a>

			<?php if( isset($_GET['delete'] ) ) : ?>
				<div class="success">Vendor successfully deleted.</div>
			<?php endif; ?>

		</div> <!-- .row -->

		<?php

			// For filters
			$meta_key   = '';
			$meta_value = '';
			$lb_name = '';
			$lb_db_name = '';
			$lb_values = '';

			if( isset( $_POST['vendor_cats'] ) ) :
				session_unset();
				$meta_key   = 'vendor-cat';
				$meta_value = $_POST['vendor_cats'];
			endif;

			if( isset( $_POST['capabilities'] ) ) :
				session_unset();
				$meta_key   = 'vendor-tag';
				$meta_value = $_POST['capabilities'];
			endif;

			// Create sessions to carry over to detail page.
			$_SESSION['meta_key'] = $meta_key;
			$_SESSION['meta_value'] = $meta_value;

			// If sessions, then grab from detail page and keep filters.
			if ( isset( $_SESSION['key'] ) ) :
				$meta_key = $_SESSION['key'];
				$meta_value = $_SESSION['value'];
			endif;

			if ( isset( $_GET['reset'] ) )
				session_unset();

			if ( isset( $_SESSION['name'] ) ) :
				$lb_name = $_SESSION['name'];
				$lb_db_name = $_SESSION['db_name'];
				$lb_values = $_SESSION['values'];
			endif;

		?>

		<div id="applicant-filters">
			<div class="row">
				<div class="medium-4 columns">
					<form role="search" method="post" id="search" action="<?php bloginfo('url'); ?>">
						<label for="position-dropdown">Search</label>
						<input type="text" name="s" id="s" value="">
					</form> <!-- /form -->
				</div> <!-- .columns -->
				<div class="medium-4 columns">
					<form action="" method="post">
						<label for="vendor-cats-dropdown">Vendor Type</label>
						<div class="dropdown-filters">
							<select name="vendor_cats" onchange="this.form.submit()" id="vendor-cats-dropdown">
								<option value="">All</option>
							 	<?php
									$vendor_cats = get_terms('vendor-cat');

									foreach ($vendor_cats as $vendor_cat) :
										$selected = ( $vendor_cat->term_id == $meta_value ) ? 'selected' : '';
										$option = '<option value="' . $vendor_cat->term_id . '" ' . $selected . '>';
										$option .= $vendor_cat->name;
										$option .= '</option>';

										echo $option;
									endforeach;
							 	?>
							</select>
						</div> <!-- .dropdown-filters -->
					</form> <!-- /form -->
				</div> <!-- .columns -->
				<div class="medium-4 columns">
					<form action="" method="post">
						<label for="status-dropdown">Capabilities</label>
						<div class="dropdown-filters">
							<select name="capabilities" onchange="this.form.submit()" id="capabilities-dropdown">
								<option value="">All</option>
							 	<?php
									$vendor_tags = get_terms('vendor-tag');

									foreach ($vendor_tags as $vendor_tag) :
										$selected = ( $vendor_tag->term_id == $meta_value ) ? 'selected' : '';
										$option = '<option value="' . $vendor_tag->term_id . '" ' . $selected . '>';
										$option .= $vendor_tag->name;
										$option .= '</option>';

										echo $option;
									endforeach;
							 	?>
							</select>
						</div> <!-- .dropdown-filters -->
					</form> <!-- /form -->
				</div> <!-- .columns -->
			</div> <!-- #row -->
		</div> <!-- #applicant-filters -->

		<div id="loading">
			<img src="<?php echo SRS_URL; ?>/public/images/ripple.svg" alt="" />
		</div>

		<div class="row">
			<div class="medium-12 columns">
				<div class="row">
					<div class="medium-4 medium-offset-8 columns">
						<div id="view-icons">
							<div class="row">
								<div class="medium-5 columns">
									<?php $the_sort = ( isset( $_GET['sort'] ) ) ? '&sort=' . $_GET["sort"] : ''; ?>
									<label>View</label>
									<a href="<?php the_permalink(); ?>/?view=grid<?php echo $the_sort; ?>" title="View as grid"><i class="fa fa-th-large fa-fw"></i></a>
									<a href="<?php the_permalink(); ?>/?view=list<?php echo $the_sort; ?>" title="View as list"><i class="fa fa-list fa-fw"></i></a>
								</div>
								<div class="medium-7 columns">
									<?php $the_view = ( isset( $_GET['view'] ) ) ? '&view=' . $_GET["view"] : ''; ?>
									<label>Sort</label>
									<a href="<?php the_permalink(); ?>/?sort=abc_a<?php echo $the_view; ?>" title="Sort by alpha, ascending"><i class="fa fa-sort-alpha-asc fa-fw"></i></a>
									<a href="<?php the_permalink(); ?>/?sort=abc_d<?php echo $the_view; ?>" title="Sort by alpha, descending"><i class="fa fa-sort-alpha-desc fa-fw"></i></a>
									<a href="<?php the_permalink(); ?>/?sort=date_a<?php echo $the_view; ?>" title="Sort by date, ascending"><i class="fa fa-sort-numeric-asc fa-fw"></i></a>
									<a href="<?php the_permalink(); ?>/?sort=date_d<?php echo $the_view; ?>" title="Sort by date, descending"><i class="fa fa-sort-numeric-desc fa-fw"></i></a>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="the-vendors" id="<?php echo ( $_GET['view'] == 'list' ) ? 'vendors-list' : 'vendors-grid'; ?>">

					<!-- posts summary -->
					<?php require 'vendors-loop.php'; ?>

				</div> <!-- #masonry -->
			</div>
		</div> <!-- .row -->

	<?php endwhile; endif; ?>

</main> <!-- /main -->

<div id="lightbox-data" style="display: none;">
	<?php
		$user_id = get_current_user_id();

		global $wpdb;
		$current_lightboxes = $wpdb->get_results(
			$wpdb->prepare("SELECT * FROM $wpdb->usermeta WHERE user_id = %d AND meta_key LIKE '%%lightbox_%%' GROUP BY meta_key", $user_id)
		);

		$lightbox_name = '';
		foreach ( $current_lightboxes as $current ) :

			$meta_key = $current->meta_key;

			echo '<form action="' . get_the_permalink() . '" method="post" class="show-lightboxes" id="' . $meta_key . '">';

				$nice_name = str_replace('lightbox_', '', $meta_key);

				echo '<input type="hidden" name="lb_name" value="' . $nice_name . '" />';
				echo '<input type="hidden" name="lb_db_name" value="' . $meta_key . '" />';

				if ($nice_name != $lightbox_name) :
			        echo '<h4 id="' . $meta_key . '">
			    			<a href="" class="show-lightbox">' . $nice_name . '</a>
			    			<a href="" class="delete-box"><i class="fa fa-minus-circle"></i></a>
			    		</h4>';
			        $lightbox_name = $nice_name;
			    endif;

			    $lb_results = $wpdb->get_results(
					$wpdb->prepare("SELECT * FROM $wpdb->usermeta WHERE meta_key = %s AND user_id = %d", $meta_key, $user_id)
				);

				foreach ( $lb_results as $lb_result ) :
			    	echo '<input type="hidden" name="lb_values[]" value="' . $lb_result->meta_value . '" />';
			   	endforeach;

		    echo '</form>';

		endforeach;
	?>
</div>

<?php $the_view = ( isset( $_GET['view'] ) ) ? $_GET['view'] : ''; ?>

<script>
	jQuery(document).ready(function($){

		$('.show-lightbox').on('click', function() {
			$(this).closest('.show-lightboxes').submit();
			return false;
		});

		// Begin Infinite Scroll
		var count = 2; var total = <?php echo $loop->max_num_pages; ?>;
		$(window).scroll(function(){
		    if  ($(window).scrollTop() == $(document).height() - $(window).height()){
		        if (count > total){ return false; } else { loadArticle(count); }
		        count++;
		    }
		});

		// AJAX to load pages
		function loadArticle(pageNumber) {
			$.ajax({
		        url  : '<?php echo admin_url( "admin-ajax.php" ); ?>',
		        type : 'POST',
		        data : 'action=infinite_scroll&loop_file=vendors_loop&page_no=' + pageNumber + '<?php echo ($meta_key) ? "&meta_key=" . $meta_key : ""; ?><?php echo ($meta_value) ? "&meta_value=" . $meta_value : ""; ?>',
		        beforeSend : function() { $('#loading').fadeIn(); },
		        success: function(html){
					$(html).hide().appendTo('#vendors').filter('.vendor-item').each(function(i) {
					    $('#loading').fadeOut();
					    $(this).fadeTo(500, 1);
					})
		        }
		    });

		    console.log(pageNumber);
		    return false;
		}

		// Live Search
		$('#s').keyup(function(ev) {
			ev.preventDefault();

			var $input = $(this).find('input[name="s"]');
		    var query = $(this).val();
		    var $content = $('.the-vendors');
		    var view = '<?php echo $the_view; ?>';

		    $.ajax({
		        url  : '<?php echo admin_url( "admin-ajax.php" ); ?>',
		        type : 'POST',
		        data : {
		            action    : 'load_search_results',
		            search    : query,
		            view      : view,
					loop_file : 'vendors-loop'

		        },
		        success: function(response) {
		        	$input.prop('disabled', false);
		            $content.html( response ).each(function(i) {
					    $(this).delay((i++) * 100).fadeTo(500, 1);
					})
				}
		    });

			return false;

		});

		// Display window to add/update lightboxes
		$('.lightbox-add-button').on('click', function(e) {
	        e.preventDefault();
	        $.ajax({
	            type: 'POST',
	            cache: false,
	            url: this.href,
	            data: $('#lightbox-form').serializeArray(),
	            success: function (data) {
		            $.fancybox(data, {
						fitToView : true,
						afterClose : function () {
				            parent.location.reload(true);
				        }
		            });
	            }
	        });
	    });


	    // Remove lightbox item from lightbox
		$('.remove-lb').on('click', function(e) {
	        e.preventDefault();
	        if(confirm("Are you sure you want to delete this item?")) {
		        var itemID = $(this).attr('id');
		        var userID = <?php echo $user_id; ?>;
		        var lbName = '<?php echo $lb_db_name; ?>';

		        $.ajax({
		            type: 'POST',
		            url: '<?php echo admin_url( "admin-ajax.php" ); ?>',
		            data: {
			            action  : 'remove_lightbox',
			            item_id : itemID,
			            user_id : userID,
			            lb_name : lbName
			        },
		            success: function (data) {
			            $('#' + itemID).closest('.medium-4').fadeOut();
		            },
		            error: function () {
		            	$('.fail').fadeIn().delay(5000).fadeOut();
		            }
		        });
		    }
	    });

	    // Delete entire lightbox
	    $('.delete-box').on('click', function(e) {
	        e.preventDefault();
	        if(confirm("Are you sure you want to delete this lightbox?")) {
		        var userID = <?php echo $user_id; ?>;
		        var formID = $(this).closest('form').attr('id');
				var data = $('#' + formID).serialize();

		        $.ajax({
		            type: 'POST',
		            url: '<?php echo admin_url( "admin-ajax.php" ); ?>',
		            data: {
			            action  : 'delete_lightbox',
			            data    : data,
			            user_id : userID
			        },
		            success: function (data) {
			            $('h4#' + formID).fadeOut();
		            },
		            error: function () {
		            	$('.fail').fadeIn().delay(5000).fadeOut();
		            }
		        });
		    }
	    });

	});
</script>

<?php require_once 'dashboard-footer.php'; ?>
