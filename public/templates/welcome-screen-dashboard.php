<?php acf_form_head(); $module = 'welcome_screen'; require_once 'dashboard-header.php'; /* Template Name: Welcome Screen */ ?>

<main role="main">
    <div class="row">
        <div class="medium-8 columns">
            <div class="location-title"><a href="<?php the_permalink(); ?>welcome-view">The Welcome Screen</a></div>
        </div> <!-- .columns -->
        <div class="medium-4 columns ">
            <div class="nav-icons">
                <a href="<?php the_permalink(); ?>welcome-view"><i class="fa fa-eye fa-2x fa-fw"></i><br />view</a>
                <a href="<?php the_permalink(); ?>welcome-view/?edit=welcome-screen"><i class="fa fa-edit fa-2x fa-fw"></i><br />edit</a>
            </div> <!-- .nav-icons -->
        </div> <!-- .columns -->
    </div> <!-- .row -->
</main> <!-- /main -->

<?php require_once 'dashboard-footer.php'; ?>