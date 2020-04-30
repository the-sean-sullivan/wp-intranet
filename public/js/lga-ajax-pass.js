jQuery(document).ready(function($) {
    
    // for lost password
    $("form.lostpass").submit(function(){
        var submit = $(".lostpass #submit"),
            preloader = $(".lostpass #loading"),
            message = $(".lostpass #message"),
            contents = {
                action: 'lost_pass',
                nonce: this.rs_user_lost_password_nonce.value,
                user_login: this.user_login.value
            };
        
        // disable button onsubmit to avoid double submision
        submit.attr("disabled", "disabled").addClass('disabled');
        
        // Display our pre-loading
        preloader.css({'display':'block'});
        
        $.post( reset_ajax.url, contents, function( data ){
            submit.removeAttr("disabled").removeClass('disabled');
            
            // hide pre-loader
            preloader.css({'display':'none'});
            
            // display return data
            message.html( data );
        });
        
        return false;
    });
    
    
    // for reset password
    $("form.resetpass").submit(function(){
        var submit = $(".resetpass #submit"),
            preloader = $(".resetpass #loading"),
            message    = $(".resetpass #message"),
            contents = {
                action: 'reset_pass',
                nonce: this.rs_user_reset_password_nonce.value,
                pass1: this.pass1.value,
                pass2: this.pass2.value,
                user_key: this.user_key.value,
                user_login: this.user_login.value
            };
        
        // disable button onsubmit to avoid double submision
        submit.attr("disabled", "disabled").addClass('disabled');
        
        // Display our pre-loading
        preloader.css({'display':'block'});
        
        $.post( reset_ajax.url, contents, function( data ){
            submit.removeAttr("disabled").removeClass('disabled');
            
            // hide pre-loader
            preloader.css({'display':'none'});
            
            // display return data
            message.html( data );
        });
        
        return false;
    });
    
});