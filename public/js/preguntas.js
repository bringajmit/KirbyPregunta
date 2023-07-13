if (window.location.pathname === '/partida/jugar' || window.location.pathname === '/duelo/versus') {

    function preguntar() {
        $.ajax({
            url: "/partida/nuevaPregunta",
            method: "GET",
            success: function (preguntatxt) {
                var respuesta = JSON.parse(preguntatxt);
                var pregunta = respuesta.pregunta;
                var idPregunta= respuesta.idPregunta;
                var opciones = respuesta.opciones;
                $('#pregunta').text(pregunta);
                $('#idPregunta').val(idPregunta);
                $('#opcion1').val(opciones[0]);
                $('#opcion2').val(opciones[1]);
                $('#opcion3').val(opciones[2]);
                $('#opcion4').val(opciones[3])
            },
            error: function (xhr, status, error) {
                alert("Ocurrió un error en la solicitud AJAX");
            }
        });
    }

    $(document).ready(function () {
        preguntar()
    });

}
