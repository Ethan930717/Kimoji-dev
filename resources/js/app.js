window._ = require('lodash');
/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

window.axios = require('axios');
window.axios.defaults.headers.common = {
    'X-Requested-With': 'XMLHttpRequest',
    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
};

/**
 * Next we will register the CSRF Token as a common header with Axios so that
 * all outgoing HTTP requests automatically have it attached. This is just
 * a simple convenience so we don't have to attach every token manually.
 */

let token = document.head.querySelector('meta[name="csrf-token"]');

if (token) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
} else {
    console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
}

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
import Vue from 'vue';
import chatbox from './components/chat/Chatbox';

if (document.getElementById('vue')) {
    new Vue({
        el: '#vue',
        components: { chatbox: chatbox },
    });
}

/*
 * NPM Packages
 */
// Sweet Alert
window.Swal = require('sweetalert2');

// howler js
import { Howl, Howler } from 'howler';

document.addEventListener('DOMContentLoaded', function () {
    var audioPlayer = document.getElementById('audio-player');
    var soundPath = audioPlayer.getAttribute('data-sound-path');
    var playButton = document.getElementById('play-btn');
    var pauseButton = document.getElementById('pause-btn');
    var volumeControl = document.getElementById('volume-control');

    var sound = new Howl({
        src: [soundPath],
        html5: true,
        volume: volumeControl.value
    });

    playButton.addEventListener('click', function() {
        sound.play();
    });

    pauseButton.addEventListener('click', function() {
        sound.pause();
    });

    volumeControl.addEventListener('input', function() {
        sound.volume(this.value);
    });


    // 频率显示
    var canvas = document.getElementById('frequency-display');
    var ctx = canvas.getContext('2d');

    // 使用 Howler 的 FFT 功能显示频率（需要 Web Audio API）
    if (Howler.ctx) {
        var analyser = Howler.ctx.createAnalyser();
        Howler.masterGain.connect(analyser);
        analyser.fftSize = 2048;
        var bufferLength = analyser.frequencyBinCount;
        var dataArray = new Uint8Array(bufferLength);

        function draw() {
            requestAnimationFrame(draw);
            analyser.getByteFrequencyData(dataArray);
            ctx.fillStyle = 'rgb(200, 200, 200)';
            ctx.fillRect(0, 0, canvas.width, canvas.height);
            var barWidth = (canvas.width / bufferLength) * 2.5;
            var barHeight;
            var x = 0;

            for(var i = 0; i < bufferLength; i++) {
                barHeight = dataArray[i];
                ctx.fillStyle = 'rgb(' + (barHeight+100) + ',50,50)';
                ctx.fillRect(x, canvas.height - barHeight / 2, barWidth, barHeight / 2);
                x += barWidth + 1;
            }
        }
        draw();
    }
});

