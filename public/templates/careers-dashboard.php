<?php $module = 'careers'; require_once 'dashboard-header.php'; /* Template Name: Careers Dashboard */ ?>

<?php $current_url = get_permalink(); ?>
				
<main role="main">
	<div class="row">
		<?php if (have_posts()): while (have_posts()) : the_post(); ?>
		
				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	
					<?php the_content(); ?>
					
					<p><a href="<?php echo $current_url; ?>careers-edit/?add=doit" class="button">Add Job</a></p>
	
				</article> <!-- /article -->
				
				<h2>Active Jobs</h2>
				
				<ul class="job-block">
				
					<!-- posts summary -->			
					<?php
						global $post;
						$args = array(
							'numberposts'     => 0,
							'offset'          => 0,
							'orderby'         => 'title',
							'order'           => 'ASC',
							'post_type'       => 'careers',
							'post_status'     => 'publish' 
						);
						$myposts = get_posts( $args );
						foreach( $myposts as $post ) :  setup_postdata($post); 
					?>

						<li>			
							<a href="<?php echo $current_url; ?>careers-edit/?edit=<?php echo the_ID(); ?>" class="job match">
								<h4><?php the_title(); ?></h4>
								<span><?php the_time('m.d.Y'); ?></span>
								<p><?php the_field('job_excerpt'); ?></p>
							</a>
						</li> <!-- .columns -->
						
					<?php endforeach; ?>
					
				</ul>
				
				<hr />
				
				<h2>Inactive Jobs</h2>
				
				<ul class="job-block">
				
					<!-- posts summary -->			
					<?php
						global $post;
						$args = array(
							'numberposts'     => 0,
							'offset'          => 0,
							'orderby'         => 'title',
							'order'           => 'ASC',
							'post_type'       => 'careers',
							'post_status'     => 'draft'
						);
						$myposts = get_posts( $args );
						foreach( $myposts as $post ) :  setup_postdata($post); 
					?>

						<li>			
							<a href="<?php echo $current_url; ?>careers-edit/?edit=<?php echo the_ID(); ?>" class="job match">
								<h4><?php the_title(); ?></h4>
								<span><?php the_time('m.d.Y'); ?></span>
								<p><?php the_field('job_excerpt'); ?></p>
							</a>
						</li> <!-- .columns -->
						
					<?php endforeach; ?>
					
				</ul>
	
		<?php endwhile; endif; ?>

	</div> <!-- .row -->
</main> <!-- /main -->

<?php require_once 'dashboard-footer.php'; ?>