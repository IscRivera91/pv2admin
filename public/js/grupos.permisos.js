var session_id = $('#session_id').val();
var classConPermiso = 'con-permiso-btn';
var classSinPermiso = 'sin-permiso-btn';

function permisos(metodoId,grupoId) {

    var metodo = 'altaPermiso';
    var quitarClase = classSinPermiso;
    var ponerClase = classConPermiso;

    if ( $('#'+metodoId).hasClass(classConPermiso) ){
        metodo = 'bajaPermiso';
        quitarClase = classConPermiso;
        ponerClase = classSinPermiso;
    }

    var url = 'index.php?controlador=grupos&metodo='+metodo+'&session_id='+session_id+'&metodoId='+metodoId+'&grupoId='+grupoId;
    
    $.ajax({
        url: url,
        type: "POST",
        data: {},
        success: function (data) {
            console.log(data);
            var respuesta = data['respuesta'];
            if(respuesta == true){
                $('#'+metodoId).removeClass(quitarClase).addClass(ponerClase);
                return false;
            }
            alert(data['error'])
            return false;
        },
        error: function (xhr, status) {
            console.log('entro a error');
        }
    });

}