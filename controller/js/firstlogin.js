$( document ).ready(function() {
    $("#password1").keyup( function(event) {
        var pass1 = $(this).val();
        var valid = validPassword(pass1);
        $('#pass1note').text(valid);
        
        comparePasswords();
    });

    $('#password2').keyup( function(event) {
        comparePasswords();
    });

    $('#setpassword').button()
    .click( function(event) {
        var pass1 = $('#password1').val();
        if (validPassword(pass1) != 'OK') {
            return false;
        }
        if (! comparePasswords()) {
            return false;
        }

        $.ajax({ 
            url : "/setpassword.json",
            data : {
                email : $("#email").val(), 
                password : pass1,
                token : $("#token").val()
            },
            type : "POST",
            dataType : "json",
            success : function( json ) {
                if (json.status == 1) {
                    $('#passwordset').dialog('open');
                }
                else {
                    $("#errordiv").text(json.error);
                }
            }
        });
    });

    $('#passwordset').dialog({
        autoOpen : false,
        modal : true,
        buttons : [
            { 
                text: "Continue",
                click: function() {
                    window.location = "/index";
                }
            }
        ]
    });
});


function comparePasswords() {
    var pass1 = $('#password1').val();
    var pass2 = $('#password2').val();

    if (pass1 != pass2) {
        $('#pass2note').text('Passwords do not match');
        return false;
    }
    else if (validPassword(pass1) == 'OK') {
        $('#pass2note').text('OK');
        return true;
    }
    else {
        $('#pass2note').text('');
        return false;
    }
}
        



