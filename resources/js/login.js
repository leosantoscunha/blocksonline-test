require('./app');

function login() {
    $('form').on('submit', function (event) {
        event.preventDefault();

        const url = $(this).data('action');
        let params = $(this).serializeArray().reduce(function (obj, item) {
            obj[item.name] = item.value;
            return obj;
        }, {});

        if (validationForm()) {
            axios.post(url, params)
                .then(({data}) => {
                    console.log(data)

                    if (!data.status) {
                        alert(data.message);
                    } else {
                        alert(data.message);
                        window.location.href = "/dashboard";
                    }

                })
                .catch(error => {
                    console.log(error)
                    console.log({error})
                    let message = "Error to try login.";

                    if (error.response !== undefined && error.response.data) {
                        ({message} = error.response.data);
                    }

                    alert(message);
                });
        }
    })
}

function validationForm() {
    if ($('#username').val() === '') {
        alert("User Name is required");
        $('#username').focus()
        return false;
    }

    if ($('#password').val() === '') {
        alert("Password is required");
        $('#password').focus()
        return false;
    }

    return true
}

$(function () {
    login();
})
