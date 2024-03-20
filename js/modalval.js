
$(document).ready(function () {
    Insert_record();
})


//INSERT RECORD IN THE DATABASE

function Insert_record()
{
    $(document).on('click', '#insertdata', function (){
        var name = $('#name').val();
        var lastname = $('#lastname').val();
        var email = $('#email').val();
        var birthday = $('#birthday').val();
        var password = $('#password').val();
        var confirmPassword = $('#confirmPassword').val();
        // console.log(name, lastname, email, birthday, password, confirmPassword);

        if(name === "" || lastname === "" || email === "" || birthday === "" || password === "" || confirmPassword === "" ){
            $('#message').html('Please Fill in the Blanks')
        }
        else {
            $.ajax({
                url: 'insert.php',
                method: 'post',
                data:{
                    name:name,
                    lastname:lastname,
                    email:email,
                    birthday:birthday,
                    password:password,
                    confirmPassword:confirmPassword
                },
                success: function (data){
                    $('#message').html(data);
                    $('#studentaddmodal').modal('show');
                }
            })
        }
    })


}

https://www.youtube.com/watch?v=x3OYLQFPfd0 34:50