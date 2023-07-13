const musicaFondo = document.getElementById('musica-fondo');

function toggleMusic() {
    if (musicaFondo.paused) {
        musicaFondo.play();
        localStorage.setItem('musicState', 'on');
    } else {
        musicaFondo.pause();
        localStorage.setItem('musicState', 'off');
    }
}

const musicState = localStorage.getItem('musicState');
if (musicState === 'off') {
    musicaFondo.pause();
} else {
    musicaFondo.play();
}