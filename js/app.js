$(document).ready(function(){
    function validateEmail(email) {
        const re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return re.test(String(email).toLowerCase());
    }
    //Deleting Category
    $(".btn-category-delete,.btn-item-delete,.btn-member-delete").click(function(){
        var title = $(this).attr('data-title');
        var action = $(this).attr('data-action');
        bootbox.confirm("Are you sure, you want to delete " + title + "?", function(result){
            if(result){
                window.location.href = action;
            }
        });
    });
    $("input[type=file]").on('change',function(){
        var id = $(this).attr('id');
        var fileName = $(this).val();
        $(this).next('.custom-file-label').html(fileName);
    });

    $('.form-account input').on("focus",function(){
        if(!$(".form-alert-msg").hasClass("hide")){
            $(".form-alert-msg").addClass("hide");
        }
        $(".form-alert-msg > .alert").html("");
    });

    $("#itemPrice").mask('00000.00', {reverse: true});

    $('.btn-account-submit').click(function(e){
        var firstname = $("#accountFirstName").val();
        var lastname = $("#accountLastName").val();
        var email = $("#accountEmail").val();
        var username = $("#accountUserName").val();
        var password = $("#accountPassword").val();
        var confirmpassword = $("#accountConfirmPassword").val();
        var isSubmit = true;

        if(firstname==""||
            lastname==""|| 
            email==""||
            username==""||
            password==""||
            confirmpassword==""){
                isSubmit = false;
        }

        if(isSubmit){
            if(password!=confirmpassword){
                $(".form-alert-msg").removeClass("hide");
                $(".form-alert-msg .alert-full").html("Password need to match");
            }
            if(!validateEmail(email)){
                $(".form-alert-msg").removeClass("hide");
                $(".form-alert-msg .alert-full").html("Email is not valid");
            }
            else{
                if(!$(".form-alert-msg").hasClass("hide")){
                    $(".form-alert-msg").addClass("hide");
                }
                $(".form-alert-msg > .alert").html("");
                $(".form-account").submit();
            }
        }
        else{
            $(".form-alert-msg").removeClass("hide");
            $(".form-alert-msg .alert-full").html("Please fill in all the fields");
        }
    });
});