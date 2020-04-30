<?php require_once 'dashboard-header.php'; /* Template Name: Forms Dashboard */ ?>

				
<main role="main">
	<div class="row">
		<div class="medium-12 columns">
		<?php if (have_posts()): while (have_posts()) : the_post(); ?>
		
			<?php the_content(); ?>
		
		<?php endwhile; endif; ?>
		
		<h2>Printable Forms</h2>
		
		<div class="row">
			<?php
				global $post;
				$args = array(
					'numberposts'     => -1,
					'orderby'         => 'title',
					'order'           => 'ASC',
					'post_type'       => 'forms',
					'post_status'     => 'publish' 
				);
				$myposts = get_posts( $args );
				$current_header = '';
				foreach( $myposts as $post ) :  setup_postdata($post); 
				
					$file_path = get_field('file');
					$screenshot = get_field('screenshot');
					
			?>
				
				<div class="medium-3 columns end">
					<a href="<?php echo $file_path['url']; ?>" target="blank" class="form-download">
						<img src="<?php echo $screenshot['url']; ?>" alt="">
						<h4><?php the_title(); ?></h4>
						<p><?php the_field('description'); ?></p>
					</a>
				</div>
				
			<?php endforeach; ?>
			</div>
			
			<h2>Digital Forms</h2>
			
			<div class="row">
				<div class="medium-3 columns end">
					<a href="<?php bloginfo('url'); ?>/dashboard/performance-review" class="form-download">
						<img src="" alt="">
						<h4>Performance Review</h4>
						<p></p>
					</a>
				</div>
			</div>
		
		</div>
	</div>
</main>

<?php require_once 'dashboard-footer.php'; ?>