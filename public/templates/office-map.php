<?php require_once 'dashboard-header.php'; /* Template Name: Office Map */ ?>

<main role="main">
	<div class="grid-container fluid">
		<div class="grid-x grid-padding-y align-center padding-sm">
			<div class="medium-10 cell">
				<div id="agency-map">
					<img src="<?php echo SRS_URL; ?>/public/images/office-map.png" alt="Office Map">

					<?php
						// Get People
						$peoples = SRS_Intranet_Public::rest_api_get( 'people?per_page=100' );

						foreach( $peoples as $people ) :
							$block_info = $people['content']['_raw'];
							$custom_fields = $people['content']['custom_fields'];
							$office_num = $custom_fields['office_map'] ?? '';
							$person = $office_num == 24 ? 'Front Desk' : $people['title']['rendered'];

							if ( $office_num ) :
					?>

							<div id="office-<?php echo $office_num; ?>" class="dept-<?php echo $block_info['department']; ?> office-box">
								<?php echo $person; ?>
							</div>

					<?php
							endif;
						endforeach;
					?>
				</div> <!-- #agency-map -->
			</div>
		</div>
	</div> <!-- .row -->
</main> <!-- /main -->

<?php require_once 'dashboard-footer.php'; ?>
