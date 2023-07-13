if(window.location.pathname === '/partida/jugar' || window.location.pathname === '/duelo/versus'){

    $('input[type="submit"]').on('click', function () {
        var valor = $(this).val();

        var datos = {
            respuesta: valor
        };


        $.ajax({
            url: '/time/response',
            method: 'POST',
            data: JSON.stringify(datos),
            contentType: 'application/json',
        });

        });


}