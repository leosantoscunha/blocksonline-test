require('./app');

function storeUser() {
    $('form').on('submit', function (event) {
        event.preventDefault();

        const url = $(this).data('action');

        let params = $(this).serializeArray().reduce(function (obj, item) {
            obj[item.name] = item.value;
            return obj;
        }, {});

        console.log(params)
        if (validationForm()) {
            let method = params.user_id !== "" ? 'PUT' : 'POST';

            axios.request({
                method: method,
                url: url,
                data: params
            }).then(() => {
                let message = "User Update successfully";
                if (params.error !== undefined) {
                    message = params.error.message;
                }

                alert(message);
                window.location.href = "/dashboard";
            }).catch(error => {
                if (error.response !== undefined && error.response.data) {
                    let {message, errors} = error.response.data;
                    message = message + '\n';

                    if (errors.validate !== undefined) {
                        for (const [key, value] of Object.entries(errors.validate)) {
                            console.log(`${key}: ${value}`);
                            message += value + '\n';
                        }
                    }

                    alert(message);
                }
            });
        }
    })
}

function validationForm() {
    if ($('#post_code').val() === '') {
        alert("Post Code is required");
        $('#post_code').focus()
        return false;
    }

    if ($('#post_code').val().length > 6) {
        alert("Post Code is too long");
        $('#post_code').focus()
        return false;
    }

    if ($('#userName').val() === '') {
        alert("User Name is required");
        $('#userName').focus()
        return false;
    }

    const userPasswordElement = $('#userPassword');

    if ($('user_id').val() !== '' && $('#userPassword').val() !== '') {
        const passwordConfirmation = $('#password_confirmation');
        const userPassword = userPasswordElement.val();
        let message = "";

        if (userPassword.length < 8) {
            message += "Error: Password must contain at least six characters!\n";
            userPasswordElement.focus();
            alert(message);
            return false;
        }

        let re = /[0-9]/;
        if (!re.test(userPassword)) {
            message = "Error: password must contain at least one number (0-9)!\n";
            userPasswordElement.focus();
            alert(message);
            return false;
        }
        re = /[a-z]/;
        if (!re.test(userPassword)) {
            message = "Error: password must contain at least one lowercase letter (a-z)!\n";
            userPasswordElement.focus();
            alert(message);
            return false;
        }
        re = /[A-Z]/;
        if (!re.test(userPassword)) {
            message = "Error: password must contain at least one uppercase letter (A-Z)!\n";
            userPasswordElement.focus();
            alert(message);
            return false;
        }

        if (passwordConfirmation.val() !== userPassword) {
            message = "Error: Please check that you've entered and confirmed your password!\n";
            userPasswordElement.focus();
            alert(message);
            return false;
        }

        return true;
    }
    if ($('#address').val() === '') {
        alert("Address is required");
        $('#address').focus()
        return false;
    }
    if ($('#city').val() === '') {
        alert("City is required");
        $('#city').focus()
        return false;
    }

    if (!validateDOB()) {
        $('#age').focus()
        return false;

    }

    return true
}

function validateDOB() {

    let birthday = document.getElementById("age").value;
    let regexVar = /^([0-9]{2})\/([0-9]{2})\/([0-9]{4})$/;
    let regexVarTest = regexVar.test(birthday);
    let userBirthDate = new Date(birthday.replace(regexVar, "$3-$2-$1"));
    let todayYear = (new Date()).getFullYear();
    let cutOff18 = new Date();

    cutOff18.setFullYear(todayYear - 18);

    if (!regexVarTest) {
        alert("Enter date of birth as dd/mm/yyyy");
        return false;
    } else if (isNaN(userBirthDate)) {
        alert("date of birth is invalid");
        return false;
    } else if (userBirthDate > cutOff18) {
        alert("you have to be older than 18");
        return false;
    }

    return true;
}

$(function () {
    $('#age').mask('00/00/0000');
    $("#post_code").mask("AA-AAA", {placeholder: "XX-XXX"});
    $('input[name="post_code"]').focusout(function () {
        $(this).val(this.value.toUpperCase());
    });

    storeUser();
})
