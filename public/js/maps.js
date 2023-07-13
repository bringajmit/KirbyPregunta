
var ciudadLinks = document.getElementsByClassName('ciudad-link');
for (var i = 0; i < ciudadLinks.length; i++) {
    ciudadLinks[i].addEventListener('click', function (event) {
        event.preventDefault();
        var ciudad = this.getAttribute('data-ciudad');
        abrirMapa(ciudad);
    });
}

function abrirMapa(ciudad) {
    var url = 'https://www.google.com/maps?q=' + encodeURIComponent(ciudad);
    window.open(url);
}


