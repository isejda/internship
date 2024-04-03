/*
$(document).ready(function () {
    Insert_record();
})

function Insert_record() {
    $(document).on('click', '#insertdata', function () {
        $('#insertdata').prop('disabled', true);

        $('input').on('input', function () {
            // Enable the button when a change occurs
            $('#insertdata').prop('disabled', false);
            $('.error').remove();
        });



        var name = $('#name').val();
        var lastname = $('#lastname').val();
        var email = $('#email').val();
        var birthday = $('#birthday').val();
        var password = $('#password').val();
        var confirmPassword = $('#confirmPassword').val();
        console.log(name, lastname, email, birthday, password, confirmPassword);
        var isValid = true;
        const numbers = /[0-9]/;
        const regularExpression = /^(?=.*[a-zA-Z])(?=.*[0-9!@#$%^&*]).{8,16}$/;


        if (name === "") {
            $('#name').after('<div class="error">Please enter your name</div>');
            isValid = false;
        } else if (numbers.test(name)) {
            $('#name').after('<div class="error">Name must contain only letters</div>');
            isValid = false;
        }


        if (lastname === "") {
            $('#lastname').after('<div class="error">Please enter your lastname</div>');
            isValid = false;
        } else if (numbers.test(lastname)) {
            $('#lastname').after('<div class="error">Lastname must contain only letters</div>');
            isValid = false;
        }


        if (email === "") {
            $('#email').after('<div class="error">Please enter your email</div>');
            isValid = false;
        } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)){
            $('#email').after('<div class="error">Please enter a valid email</div>');
            isValid = false;
        }


        if (birthday === "") {
            $('#birthday').after('<div class="error">Please enter your birthday</div>');
            isValid = false;
        }
        else if(getAge(birthday) < 18){
            $('#birthday').after('<div class="error">You should be over 18</div>');
            isValid = false;
        }



        if (password === "") {
            $('#password').after('<div class="error">Please enter your password</div>');
            isValid = false;
        }else if (password.length < 8) {
            $('#password').after('<div class="error">Password is expected to be 8 characters</div>');
            isValid = false;
        } else if (!regularExpression.test(password)) {
            $('#password').after('<div class="error">Password should contain atleast one number, one special character and one letter</div>');
            isValid = false;
        }


        if (confirmPassword === "") {
            $('#confirmPassword').after('<div class="error">Please confirm your password</div>');
            isValid = false;
        }


        if (password !== confirmPassword) {
            $('#confirmPassword').after('<div class="error">Password do not match</div>');
            isValid = false;
        }


/!*        if (name === "" || lastname === "" || email === "" || birthday === "" || password === "" || confirmPassword === "") {
            $('#message').html('<span style="color: red">Please Fill in the Blanks</span>')
        }
        else {*!/

        if(isValid){
            $.ajax({
                url: '../inspina/backend/adduser.php',
                method: 'post',
                data:{
                    name:name,
                    lastname:lastname,
                    email:email,
                    birthday:birthday,
                    password:password,
                    confirmPassword:confirmPassword,
                },
                success: function (data){
                    if (data.includes('Email already exists!')) {
                        $('#email').after('<div class="error">' + data + '</div>');
                        $('#myModal').modal('show');
                    } else {
                        $('#message').html('<div class="error" style="color: green!important;">' + data + '</div>');
                        $('#myModal').modal('show');
                        $('form').trigger('reset');

                    }
/!*                    $('#message').html(data);
                    $('form').trigger('reset');*!/
                }
            })
        }
    })
    $(document).on('click', '#btn_close', function () {
        $('form').trigger('reset');
        $('#message').html('');
        $('.error').remove();
    })
}

function getAge(DOB) {
    var today = new Date();
    var birthDate = new Date(DOB);
    var age = today.getFullYear() - birthDate.getFullYear();
    var m = today.getMonth() - birthDate.getMonth();
    if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
        age--;
    }
    return age;
}
*/
