<!DOCTYPE html>
<!--[if lt IE 7]>      <html <?php language_attributes(); ?> class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html <?php language_attributes(); ?> class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html <?php language_attributes(); ?> class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html <?php language_attributes(); ?> class="no-js"> <!--<![endif]-->
    <head>
		<!-- Global site tag (gtag.js) - Google Analytics -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=UA-1400355-58"></script>
        <script>
          window.dataLayer = window.dataLayer || [];
          function gtag(){dataLayer.push(arguments);}
          gtag('js', new Date());

          gtag('config', 'UA-1400355-58');
        </script>

        <meta charset="<?php bloginfo('charset'); ?>">
		<link href="//www.google-analytics.com" rel="dns-prefetch">
        <link href="<?php echo get_template_directory_uri(); ?>/images/icons/favicon.ico" rel="shortcut icon">
        <link href="<?php echo get_template_directory_uri(); ?>/images/icons/touch.png" rel="apple-touch-icon-precomposed">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<?php wp_head(); ?>
		<script src="https://use.typekit.net/fet6hoa.js"></script>
		<script>try{Typekit.load({ async: true });}catch(e){}</script>
    </head>
    <body <?php body_class(); ?>>

    	<?php
    		// Checks user permissions
    		if( !empty( $module ) ) $allow = Srs_Intranet_Public::user_permissions( $module );
    		else $allow = 1;

    		// Check if user is logged
			if ( !is_user_logged_in() && !is_page('login') ) :
				$_SESSION['redirect'] = esc_url($_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
				wp_redirect( get_site_url() . '/login' ); exit;
			endif;

			// Check if user has permissions
			if ( !is_page( array( 'login', 'insufficient-permissions', 'dashboard' ) ) && $allow == 0 ) :
				wp_redirect( get_site_url() . '/insufficient-permissions' ); exit;
			endif;

			if ( !is_page( array('login', 'insufficient-permissions' ) ) ) :
		?>

    	<div class="grid-x">
			<div class="medium-12 cell">
				<header id="intranet-header">

					<div class="slideout-menu">
						<div class="menu-toggle">
							<span class="close-menu slideout-menu-toggle"><i class="fa fa-times fa-fw"></i></span>
							<span class="open-menu toggle-button slideout-menu-toggle"><i class="fa fa-bars fa-fw"></i></span>
						</div> <!-- .menu-toggle -->

						<?php
							// Manual links
							$pre_links = array(
								array(
									'title' => 'Dashboard',
									'url' => get_bloginfo( 'url' ) . '/dashboard/',
								),
							);

							// Display menu
							echo Srs_Intranet_Public::main_menu( $pre_links );
						?>

						<a href="<?php echo wp_logout_url(); ?>" class="logout" title="Logout">
							Logout
							<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"><path fill="#FFF" d="M16 9V5l8 7-8 7v-4H8V9h8zM0 2v20h14v-2H2V4h12V2H0z"/></svg>
						</a>
					</div> <!-- .slideout-menu -->

				</header>
			</div> <!-- .cell -->
		</div> <!-- .row -->

		<?php
			endif;

			$post_type = get_post_type( $post->ID );
			$specific_page = ( $post_type !== 'applicants' && $post_type !== 'vendors' && $post_type !== 'people' && $post_type !== 'comment-form' ) ? true : false;
		?>

		<div class="grid-x <?php echo $specific_page ? 'title-wrap' : ''; ?> align-center">
			<div class="medium-12 large-8 cell">
				<?php
					if( is_single() ) :

						echo ( $specific_page ) ? '<h1>' . $post_type . '</h1>' : '';

					else :

						if( !is_page( array( 'login', 'insufficient-permissions') ) ) :

							$page_title = get_the_title();
							$icon = preg_replace('/\W+/', '-', strtolower( $page_title ));
							$icon_path = ABSPATH . '/wp-content/plugins/srs-intranet/public/images/icons';

							$file_exists = $icon_path . '/icon-' . $icon . '.svg';

							if ( !file_exists( $file_exists ) ) :
								$icon = str_replace('_', '-', $module);
								$file_exists = $icon_path . '/icon-' . $icon . '.svg';
							endif;

							$icon_url = SRS_URL . '/public/images/icons/icon-' . $icon . '.svg';
				?>

						<h1>
							<?php
								echo ( file_exists( $file_exists ) ) ? '<div class="icon"><img src="' . $icon_url . '" alt="' . $page_title . '"></div>' : '';
								echo $page_title;
							?>
						</h1>

				<?php endif; endif; ?>
			</div> <!-- .cell -->
		</div> <!-- .row -->
