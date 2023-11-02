function registerAssembly(){
    var select   = document.getElementById("nameDB");
    var nombreDB = select.value;
    var aliasDB  = $('#aliasDB').val();
    var token    = $('input[name="_token"]').val();

    $.ajax({
        type: 'POST',
        url: 'store',
        data: {
            nombreDB: nombreDB,
            aliasDB: aliasDB,
            _token: token
        },
        success: function (response) {
        console.log(response)
        },
        error: function (xhr) {
            console.log(xhr);
        }
    });
}