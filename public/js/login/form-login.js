$('#loginForm').submit(function (event) {
    event.preventDefault();
    var formData = $(this).serialize();
    $.ajax({
        type: 'POST',
        url: 'login',
        data: formData,
        success: function (response) {
            if(response.success){
                window.location.href = "/panel";
            }

            if(response.error){
                document.getElementById('alert').innerHTML = `<div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>${response.error}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>`
            }
        },
        error: function (xhr) {
            console.log(xhr);
        }
    });
});