 $(document).ready(function() {
   login = $("#form_action").serialize();//alert(values);
    $.ajax({
        url: 'public/login_process.php',
        type: 'POST',
        dataType:"json",
        data: login,
        success: function(){
            alert("success");
        },
        error: function(){
            alert("failure");
        }
    }); 
}); 
