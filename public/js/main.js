function cambiarPassword() {
    var respuesta = validaPassword();
    $('#password').focus();
    return respuesta;
}

function validaPassword() {
    var password = $('#password').val();
    var confirmaPassword = $('#confirmaPassword').val();
    if ( password != confirmaPassword) {
        alertify.alert('La contaseña debe coincidir').set('basic', true);  
        return false;
    }
    return true;
}