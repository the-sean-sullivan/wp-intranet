<?php $module = 'media_wall'; require_once 'dashboard-header.php'; /* Template Name: Media Wall Dashboard */ ?>

<?php get_sidebar(); ?>
				
<main role="main">
	
	<div class="grid-container">
		<div class="grid-x">
			<div class="medium-8 cell">
				<div class="location-title"><a href="<?php the_permalink(); ?>things-we-made">Things we made...</a></div>
			</div> <!-- .columns -->
			<div class="medium-4 cell">
				<div class="nav-icons">
					<a href="<?php the_permalink(); ?>things-we-made"><i class="fa fa-eye fa-2x fa-fw"></i><br />view</a>
					<a href="<?php the_permalink(); ?>things-we-made/?edit=things-we-made"><i class="fa fa-edit fa-2x fa-fw"></i><br />edit</a>
				</div> <!-- .nav-icons -->
			</div> <!-- .columns -->
		</div> <!-- .row -->
	</div>

	<hr />

	<div class="grid-container">
		<div class="grid-x">
			<div class="medium-8 cell">
				<div class="location-title"><a href="<?php the_permalink(); ?>things-we-love">Things we love...</a></div>
			</div> <!-- .columns -->
			<div class="medium-4 cell ">
				<div class="nav-icons">
					<a href="<?php the_permalink(); ?>things-we-love"><i class="fa fa-eye fa-2x fa-fw"></i><br />view</a>
					<a href="<?php the_permalink(); ?>things-we-love/?edit=things-we-love"><i class="fa fa-edit fa-2x fa-fw"></i><br />edit</a>
				</div> <!-- .nav-icons -->
			</div> <!-- .columns -->
		</div> <!-- .row -->
	</div>
	
</main> <!-- /main -->

<?php require_once 'dashboard-footer.php'; ?>