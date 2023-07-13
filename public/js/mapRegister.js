

// Configurar la vista inicial del mapa en Argentina
var map = L.map('map').setView([-34.61, -58.38], 13);
// Agregar una capa de mapa base (por ejemplo, OpenStreetMap)
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors'
}).addTo(map);
var marker;

// Escuchar el evento 'click' en el mapa
map.on('click', function (e) {
    var coord = e.latlng;
    if (marker) {
        // Si ya hay un marcador, eliminarlo
        map.removeLayer(marker);
    }
    // Colocar un nuevo marcador en la ubicación seleccionada
    marker = L.marker(coord).addTo(map);

    // Obtener la dirección correspondiente a las coordenadas
    obtenerDireccion(coord);
});

// Escucha el 'input'
document.getElementById('ciudad').addEventListener('input', function () {
    var direccion = this.value;
    buscarUbicacion(direccion);
});

function obtenerDireccion(coord) {
    var url = 'https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=' + coord.lat + '&lon=' + coord.lng;
    fetch(url)
        .then(response => response.json())
        .then(data => {
            var direccion = data.address.city;
            // Actualizar el valor del campo de entrada con el nombre de la ciudad
            document.getElementById('ciudad').value = direccion;
        })
        .catch(error => {
            console.log(error);
        });
}

function buscarUbicacion(direccion) {
    var url = 'https://nominatim.openstreetmap.org/search?format=jsonv2&q=' + direccion + '&countrycodes=AR';
    fetch(url)
        .then(response => response.json())
        .then(data => {
            if (data.length > 0) {
                var lat = data[0].lat;
                var lon = data[0].lon;
                var coord = L.latLng(lat, lon);
                map.setView(coord, 13);
                if (marker) {
                    // Si ya hay un marcador, eliminarlo
                    map.removeLayer(marker);
                }
                // Colocar un nuevo marcador en la ubicación encontrada
                marker = L.marker(coord).addTo(map);
            }
        })
        .catch(error => {
            console.log(error);
        });
}