if (window.location.pathname === '/partida/jugar' || window.location.pathname === '/partida/respuestaCorrecta' ||
    window.location.pathname === '/duelo/versus' || window.location.pathname === '/duelo/correcto' ){
    let tiempoInicial = 9;

    function mostrarTemporizador() {
        document.getElementById("temporizador").textContent = tiempoInicial;

        tiempoInicial--;

        if (tiempoInicial < 0) {
            clearInterval(temporizador);
        }

        if(window.location.pathname === '/duelo/versus' || window.location.pathname === '/partida/jugar'){
            setTimeout(function() {
                if(window.location.pathname === '/duelo/versus'){
                    window.location.href = "/duelo/resultado";
                }else{
                    window.location.href = "/partida/respuestaIncorrecta";
                }
            }, 9500);
        }

        if(window.location.pathname === '/partida/respuestaCorrecta'){
            setTimeout(function() {
                    window.location.href = "/partida/jugar";
            }, 9500);
        }
    }

    let temporizador = setInterval(mostrarTemporizador, 1000);
}