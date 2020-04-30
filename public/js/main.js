(function ($, root, undefined) {

    // Main Navigation
    var main_nav        = $('.slideout-menu'),
        main_nav_width  = $('.slideout-menu').width(),
        main_nav_height = $('.slideout-menu').height(),
        open_menu       = $('.open-menu'),
        close_menu      = $('.close-menu'),
        toggle_height   = $('.menu-toggle').height(),
        proper_offset   = (main_nav_height + 60) - 60,
        curr_domain     = window.location.hostname,
        ajax_url        = '/wp-admin/admin-ajax.php';

    // Set mobile top offset
    if ( $(window).width() < 768 ) {
        main_nav.css('top', -proper_offset);
    }

    $('.slideout-menu-toggle').on('click', function(e){
        e.preventDefault();

        // toggle open class
        main_nav.toggleClass('open');

        if ( $(window).width() >= 768 ) {

            if (main_nav.hasClass('open')) {
                main_nav.animate({ left: '0' });
                open_menu.fadeOut('slow');
                close_menu.fadeIn('slow');
            } else {
                main_nav.animate({ left: -main_nav_width }, 250);
                open_menu.fadeIn('slow');
                close_menu.fadeOut('slow');
            }

        } else {

            if (main_nav.hasClass('open')) {
                main_nav.animate({ top: '0' });
                open_menu.fadeOut('slow');
                close_menu.fadeIn('slow');
            } else {
                main_nav.animate({ top: -proper_offset }, 250);
                open_menu.fadeIn('slow');
                close_menu.fadeOut('slow');
            }
        }
    });

    // Fadeout div
    setTimeout(function() {
            $('.success, .fail, .updated').fadeOut('slow')
        }, 5000
    );


/* ==========================================================================
   Dashboard Tools
   ========================================================================== */

    // Event info popup
    $('.event-info').fancybox({
        maxWidth    : 800,
        maxHeight   : 600,
        fitToView   : true,
        autoSize    : true,
        closeClick  : false
    });

    // Event info filter
    $('.show-all-filter').on('click', function() {
        $('#important-dates li').fadeIn();
    });

    $('.events-filter').on('click', function() {
        $('.holiday, .meeting').fadeOut();
        $('.event').fadeIn();
    });

    $('.holidays-filter').on('click', function() {
        $('.event, .meeting').fadeOut();
        $('.holiday').fadeIn();
    });

    $('.meetings-filter').on('click', function() {
        $('.event, .holiday').fadeOut();
        $('.meeting').fadeIn();
    });

    // check media size & apply/remove display/hide div
    if ( $(window).width() < 768 ) {
        $('.more-info').hide();
        $('.more').click(function() {
            $(this).next(".more-info").slideToggle(200);
        });
    }

    $('.quick-links a').each(function(i) {
        $(this).css('opacity', 0);
        $(this).delay((i++) * 200).queue(function(){
            $(this).fadeTo(200, 1).dequeue();
        });
    })

//============ Applicant Scripts

    // Applicant Table Ordering
    var table = $('table#applicants');
    $('#position-col, #name-col, #date-col, #status-col').each(function(){

        var th = $(this),
            thIndex = th.index(),
            inverse = false;

        th.click(function(){

            $(table).find('td').filter(function(){

                return $(this).index() === thIndex;

            }).sortElements(function(a, b){

                return $.text([a]) > $.text([b]) ?
                    inverse ? -1 : 1
                    : inverse ? 1 : -1;

            }, function(){

                // parentNode is the element we want to move
                return this.parentNode;

            });

            inverse = !inverse;

        });

    });

    // Begin Infinite Scroll
    var count = 2,
        total = $('input[name="candidate_page_count"]').val(),
        meta_key = $('input[name="candidate_meta_key"]').val(),
        meta_value = $('input[name="candidate_meta_value"]').val();

    $(window).scroll(function(){
        if  ($(window).scrollTop() > $(document).height() - $(window).height() - 1.5){
            if (total == null || count > total){ return false; } else { loadArticle(count); }
            count++;
        }
    });

    // AJAX to load pages
    function loadArticle(pageNumber) {
        $.ajax({
            url  : ajax_url,
            type : 'POST',
            data : 'action=infinite_scroll&loop_file=applicants-loop&page_no=' + pageNumber + '&meta_key=' + meta_key + '&meta_value=' + meta_value,
            beforeSend : function() { $('#loading').fadeIn(); },
            success: function(html){
                $(html).hide().appendTo('#applicants').filter('tr.candidate-row').each(function(i) {
                    $('#loading').fadeOut();
                    $(this).fadeTo(500, 1);
                    rowClick();
                })
            }
        });

        console.log(pageNumber);
        return false;
    }

    // Adds ability to click row to view candidate
    function rowClick() {
        $('.candidate-row').click(function() {
            var url = $(this).data('href');
            if ( url.length ) window.document.location = url;
        });
    }

    rowClick();

    // More info panel
    $('#slide').click(function(){
        var moreInfo   = $('#more-info-panel'),
            mainInfo   = $('#main-info'),
            openPanel  = $('#open-panel'),
            closePanel = $('#close-panel');

        if ( $(window).width() >= 768 ) {

            if ( moreInfo.hasClass('visible') ) { // Beginning and end
                moreInfo.animate({"width":"3%"}, "slow").removeClass('visible');
                mainInfo.animate({"width":"96.9%"}, "slow");
                openPanel.fadeIn('slow');
                closePanel.fadeOut('slow');
            } else { // Expanded
                moreInfo.animate({"width":"40%"}, "slow").addClass('visible');
                mainInfo.animate({"width":"59.9%"}, "slow");
                closePanel.fadeIn('slow');
                openPanel.fadeOut('slow');
            }

        }

        // Animate meter bar
        $('.meter > span').each(function() {
            var w = this.style.width;
            $(this)
            .data('origWidth', w)
            .width(0)
            .animate({
                width: $(this).data('origWidth')
            }, 1700);
        });
    });

    // Update Submit button on form change
    $( '.acf-form :input, .acf-form input' ).change(function() {
        $( 'input.button' ).addClass( 'update' );
    });

    // Search Select Dropdown
    if ( $('.staff-survey-select').length ) {
        $('.staff-survey-select').select2({
            theme: "classic"
        });
    }

//============ People Scripts

    $('#s').on('keyup', function() {
        var value = $(this).val().toLowerCase();
        $('.people-filter').filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
            console.log('this');
        });
    });


//============ Out List Scripts

    // Main Dashboard Slider
    $('#slider').bxSlider({
        adaptiveHeight: true,
        controls: false,
        pagerCustom: '#outlist-pager'
    });

//============ Welcome Screen Scripts

    // Welcome Screen View Slider (If more than one visitor)
    $('#welcome-slider').bxSlider({
        mode: 'fade',
        auto: true,
        speed: 500,
        pause: 7000,
        controls: false,
        pager: false
    });

//============ Vendor Database

    // For sample work
    $('.sample-link').fancybox({
        maxWidth    : 800,
        maxHeight   : 600,
        fitToView   : true,
        autoSize    : true,
        helpers : {
            media: {},
        }
    });

    $('.vendor-locale, .your-lightbox').fancybox({
        maxWidth    : 800,
        maxHeight   : 600,
        fitToView   : false,
        width       : '70%',
        height      : '70%',
        autoSize    : false,
        closeClick  : false,
    });

    // Display add lightbox button on checkbox select
    var lightbox_add = $('.lightbox-add');
    $(lightbox_add).hide();
    $('.lightbox-check').change(function() {
       $(lightbox_add).slideDown();
    });

//============ Vacation Request

    $('ul.tabs li').click(function(){
        var tab_id = $(this).attr('data-tab');

        $('ul.tabs li').removeClass('current'); // Not using 'this' so it will remove from other tab
        $('.tab-content').hide().removeClass('current');

        $(this).addClass('current');
        $('#' + tab_id).fadeIn().addClass('current');
    })

//============ Comment Things

    // Replace the editable area with textarea
    $('.editable').on('click', function() {
        var txtEditable = '<div class="form edit-inplace">' +
            '<textarea id="' + $(this).attr("id") +
            '" class="edit-textarea" row="2">' + $(this).text().trim() +
            '</textarea>' + '<div class="edit-inplace-options">' +
            '<span class="edit-save btnSubmit"><i class="fa fa-floppy-o fa-fw"></i></span>' +
            '<span class="edit-cancel"><i class="fa fa-times fa-fw"></i></span></div>';

        $(this).hide().after(txtEditable);
        $('.edit-textarea').focus();
    });

    // Put back the original text on cancel
    $(document).on('click', '.edit-cancel', function() {
        $('.editable').fadeIn();
        $('.edit-inplace').remove();
    });

    // Update Comment
    $(document).on('click', '.edit-save', function() {
        var curObj     = $('.edit-save');
        var comment    = $('.edit-textarea').val();
        var comment_id = $('.delete-comment').attr('id');

        $.ajax({
            url  : ajax_url,
            type : 'POST',
            data : {
                action     : 'save_vendor_comment',
                comment    : comment,
                comment_id : comment_id
            },
            beforeSend: function() {
                $(curObj).text('Saving...').addClass('disabled');
            },
            success: function(json) {
                $('#comment-text-' + comment_id).text(comment);
                $('.editable').fadeIn();
                $('.edit-inplace').remove();
            },
        });
        return false;
    });

    // Delete Comment
    $(document).on('click', '.delete-comment', function() {
        var comment_id = $(this).attr('id');

        if ( confirm('Are you sure you want to delete this comment?') ) {
            $.ajax({
                url  : ajax_url,
                type : 'POST',
                data : {
                    action     : 'delete_vendor_comment',
                    comment_id : comment_id
                },
                success: function(json) {
                    $('#comment-' + comment_id).fadeOut();
                },
            });
            return false;
        }
    });


})(jQuery, this);