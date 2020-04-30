<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://seansdesign.net
 * @since      1.0.0
 *
 * @package    Srs_Intranet
 * @subpackage Srs_Intranet/admin/partials
 */
?>

<div id="up-wrap">
    <h1><?php echo esc_html(get_admin_page_title()); ?></h1>

    <?php
        $active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'user_permissions';

        global $wpdb;
        // $wpdb->hide_errors();
        $wpdb->show_errors();
    ?>

    <h2 class="nav-tab-wrapper">
        <a href="?page=srs-intranet&tab=user_permissions" class="nav-tab <?php echo $active_tab == 'user_permissions' ? 'nav-tab-active' : ''; ?>">User Permissions</a>
        <a href="?page=srs-intranet&tab=modules" class="nav-tab <?php echo $active_tab == 'modules' ? 'nav-tab-active' : ''; ?>">Modules</a>
    </h2>


    <?php if( $active_tab == 'user_permissions' ) : // User Permissions Tab?>

        <h2>User Permissions</h2>
        <p>Select which apps each user should have access to.</p>

    <?php
    	Srs_Intranet_Activator::insert_modules();

        // Updated permission table with modules (when new ones added)
        Srs_Intranet_Admin::update_permission_modules();

        // Updates permissions on form post
        if( $_SERVER['REQUEST_METHOD'] == 'POST' )
            Srs_Intranet_Admin::update_user();

        // Adds new user
        Srs_Intranet_Activator::insert_users();

        // Grab the users and post types
        $users = Srs_Intranet_Admin::get_the_users();

        // Gets active modules
        $modules = Srs_Intranet_Admin::active_modules();

        $db_table = Srs_Intranet_Activator::register_permission_table();
    ?>

        <h4>Check a box below to select all for everyone.</h4>

    <?php
        foreach ( $modules as $mod ) :
            $checked = $wpdb->get_results( "SELECT * FROM $db_table WHERE $mod[0] = 1" );
            $mod_count = count($checked);
            $users = get_users();
            $user_count = count($users);
    ?>

            <label style="padding: 0 15px 30px 0;">
                <input type="checkbox" id="<?php echo $mod[0]; ?>" class="check-all" <?php echo ( $mod_count == $user_count ) ? 'checked' : '';?>>
                <?php echo $mod[1]; ?>
            </label>

    <?php
        endforeach;

        // Loop through each user
        foreach ( $users as $user ) :
            $checks = $wpdb->get_results( $wpdb->prepare ("SELECT * FROM $db_table WHERE user_id = %d", $user->ID ) );
            $user_id = $user->ID;
    ?>
        <form action="" method="post" id="<?php echo $user_id; ?>">
            <div class="intranet-users">
                <h3><?php echo esc_html( $user->display_name ); ?></h3>
                <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
                <?php
                    // Lists all custom post types and checks to see if active for current user
                    foreach ( $modules as $module ) : foreach ( $checks as $check ) :
                        $module_nice = $module[1];
                        $module = $module[0];
            	?>
                    <label>
                        <input type="checkbox" name="module[]" value="1" <?php echo ( $check->$module == 1) ? 'checked' : ''; ?> id="<?php echo $module . '_' . $user_id; ?>" class="<?php echo $module; ?>">
                        <?php echo $module_nice; ?>
                    </label>
            	<?php endforeach; endforeach; ?>

            </div>
        </form>

    <?php endforeach; ?>

        <script>
            (function( $ ) {

                $('.check-all').on('click', function () {
                    var module = $(this).attr('id');
                    $('input:checkbox.' + module).not(this).prop('checked', this.checked);
                });

                $('input[type="checkbox"]').on('change', function(e){
                    e.preventDefault();

                    var mod_class = $(this).attr('class');

                    // if check all then update entire column, otherwise update specific user
                    if ( mod_class == 'check-all' ) {

                        var mod_id = $('.' + mod_class).attr('id'),
                            box_id = mod_id,
                            module = mod_id;

                    } else {

                        var box_id = $(this).attr('id');
                        var module = mod_class;
                        var user_id = $(this).closest( 'form' ).attr('id');

                    }

                    var data = $('#' + box_id).is(':checked') ? 1 : 0;

                    $.ajax({
                        url  : '<?php echo admin_url( "admin-ajax.php" ); ?>',
                        type : 'POST',
                        data : 'action=update_user&user_id=' + user_id + '&module=' + module + '&perm=' + data,
                        success: function(data){
                            $('#' + box_id).parent('label').addClass('updated');
                            console.log('Permissions updated.');
                        }
                    });
                    return false;
                });
            })( jQuery );
        </script>


    <?php else : // Module Tab ?>


        <h2>Modules</h2>
        <p>Select which module should be active.</p>

        <div class="updated notice is-dismissible" id="message" style="display: none"><p><strong>Module updated. Please <a href="<?php bloginfo('url'); ?>/wp-admin/admin.php?page=srs-intranet&tab=modules">refresh</a> to see the module in the admin menu.</strong></p><button class="notice-dismiss" type="button"><span class="screen-reader-text">Dismiss this notice.</span></button></div>

        <form action="" method="post">
            <?php
                // Check modules with DB table and add if needed
                Srs_Intranet_Activator::insert_modules();

                // Get the table name
                $db_table = Srs_Intranet_Activator::register_module_table();

                // Get modules from table
                $modules = $wpdb->get_results( "SELECT * FROM $db_table ORDER BY name ASC" );
                foreach ( $modules as $module ) :
                    $module_nice = $module->nice_name ?? str_replace('_', ' ', $module->name);
            ?>
            <div class="modules">
                <label>
                    <input type="checkbox" name="module[]" value="1" <?php echo ( $module->active == 1) ? 'checked' : ''; ?> id="<?php echo $module->name; ?>">
                    <?php echo $module_nice; ?>
                </label>
            </div>
            <?php endforeach; ?>
        </form>

        <script>
            (function( $ ) {
                $('input[type="checkbox"]').on('change',function(e){
                    e.preventDefault();

                    var module = $(this).attr('id');
                    var active = $('#' + module).is(':checked') ? 1 : 0;

                    $.ajax({
                        url  : '<?php echo admin_url( "admin-ajax.php" ); ?>',
                        type : 'POST',
                        data : 'action=update_modules&module=' + module + '&active=' + active,
                        success: function(data){
                            $('#message').fadeIn('fast');
                            <?php flush_rewrite_rules(); ?>
                        }
                    });
                    return false;
                });
            })( jQuery );
        </script>

    <?php endif; ?>

</div> <!-- #up-wrap -->
