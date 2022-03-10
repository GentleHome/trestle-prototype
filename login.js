$(document).ready(function () {
});

function loginUser() {
    let username = $('#login-user').val();
    let password = $('#login-pass').val();
    let remember_me = 1;
    if (!$("#remember_me").prop('checked')) {
        remember_me = 0;
    }
    console.log(remember_me);
    $.ajax({
        method: 'POST',
        url: './api/login.php',
        data: { username: username, password: password, remember_me: remember_me },
        success: function (data) {
            let newdata = JSON.parse(data);
            if (newdata.hasOwnProperty('messages')) {
                Swal.fire({
                    icon: 'success',
                    title: newdata.messages,
                    showConfirmButton: false,
                    timer: 1500
                }).then(function () {
                    $('#login-user').val('');
                    $('#login-pass').val('');
                    $('#return-message').text('');
                    window.location.replace('./integrated_calendar/views/calendar.php');
                });
            } else {
                $('#return-message').text(newdata.errors);
            }
        }
    })
}
function checkPassword() {
    let password = $('#reg-pass').val();
    let cpass = $('#reg-cpass').val();
    if ((password != "") && (cpass != "") && password != cpass) {
        $('#password-message').text('Password does not match.');
    } else {
        $('#password-message').text('');
    }
}
function registerUser() {
    let username = $('#reg-username').val();
    let password1 = $('#reg-pass').val();
    let password2 = $('#reg-cpass').val();
    let email = $('#reg-email').val();
    if (($('#username-message').text() == "" && $('#password-message').text() == "") && (username != "" && password1 != "" && password2 != "" && email != "")) {
        if (password1 == password2) {
            $.ajax({
                method: 'POST',
                url: './api/register.php',
                data: {
                    username: username,
                    password1: password1,
                    password2: password2,
                },
                success: function (data) {
                    data = JSON.parse(data);
                    if (data.hasOwnProperty('success')) {
                        Swal.fire({
                            icon: 'success',
                            title: data.success,
                            showConfirmButton: false,
                            timer: 1500
                        }).then(function () {
                            $('#reg-username').val('');
                            $('#reg-pass').val('');
                            $('#reg-cpass').val('');
                            $('#reg-email').val('');
                            $('#password-message').text('');
                            window.location.replace('./signup.php');
                        });

                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Registration Failed!',
                            text: data.errors,
                        });
                    }
                }
            })
        }
    }
}