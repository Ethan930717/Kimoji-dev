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


import { Howl, Howler } from 'howler';
const audioContainer = document.getElementById('audio-container');
const soundSrc = audioContainer.getAttribute('data-sound-src');

const sound = new Howl({
    src: [soundSrc],
    autoplay: false,
    html5: true, // 开启流式播放
    loop: false,
    volume: 1.0,
    onend: function() {
        requestAnimationFrame(updateProgress);
        console.log('Finished playing');
    }
});

const volumeSlider = document.getElementById('volume-slider');
volumeSlider.addEventListener('input', function() {
    sound.volume(this.value);
});

const playButton = document.getElementById('play-button');
const pauseButton = document.getElementById('pause-button');
// 音量控制
document.getElementById('volume-decrease').addEventListener('click', function() {
    let volume = sound.volume();
    volume = Math.max(0, volume - 0.1); // 减小音量
    sound.volume(volume);
});

document.getElementById('volume-increase').addEventListener('click', function() {
    let volume = sound.volume();
    volume = Math.min(1, volume + 0.1); // 增加音量
    sound.volume(volume);
});

document.getElementById('mute').addEventListener('click', function() {
    sound.mute(!sound.mute()); // 切换静音状态
});

playButton.addEventListener('click', function() {
    if (!sound.playing()) {
        sound.play();
    }
});

pauseButton.addEventListener('click', function() {
    sound.pause();
});

// 获取 Howler 的 Web Audio API AudioContext 和 Master GainNode
const audioCtx = Howler.ctx;
const masterGain = Howler.masterGain;

// 创建 AnalyserNode
const analyser = audioCtx.createAnalyser();
analyser.fftSize = 2048; // FFT 大小，影响细节
const bufferLength = analyser.frequencyBinCount;
const dataArray = new Uint8Array(bufferLength);

// 将 AnalyserNode 连接到 Master GainNode
masterGain.connect(analyser);

// 设置 Canvas
const canvas = document.getElementById('audioCanvas');
const canvasCtx = canvas.getContext('2d');

function draw() {
    // 设置动画循环
    requestAnimationFrame(draw);

    // 获取频率数据
    analyser.getByteFrequencyData(dataArray);

    // 使用 Canvas 绘制频率数据
    canvasCtx.fillStyle = 'rgb(0, 0, 0)';
    canvasCtx.fillRect(0, 0, canvas.width, canvas.height);

    const barWidth = (canvas.width / bufferLength) * 2.5;
    let barHeight;
    let x = 0;

    for (let i = 0; i < bufferLength; i++) {
        barHeight = dataArray[i];
        canvasCtx.fillStyle = `rgb(${barHeight + 100},50,50)`;
        canvasCtx.fillRect(x, canvas.height - barHeight / 2, barWidth, barHeight / 2);

        x += barWidth + 1;
    }
}

draw();

// 获取进度条元素
const progressBar = document.getElementById('progress-bar');

function updateProgress() {
    const seek = sound.seek() || 0; // 获取当前播放时间
    const percent = ((seek / sound.duration()) * 100) || 0; // 计算百分比
    progressBar.style.width = percent + "%"; // 更新进度条宽度

    // 如果音频仍在播放，继续更新进度条
    if (sound.playing()) {
        requestAnimationFrame(updateProgress);
    }
}

// 播放音频
sound.play();

function updateProgress() {
    const seek = sound.seek() || 0;
    const percent = ((seek / sound.duration()) * 100) || 0;
    progressBar.style.width = percent + "%";

    // 更新时间显示
    document.getElementById('current-time').textContent = formatTime(seek);
    document.getElementById('total-time').textContent = formatTime(sound.duration());

    if (sound.playing()) {
        requestAnimationFrame(updateProgress);
    }
}

// 格式化时间为分:秒
function formatTime(seconds) {
    const minutes = Math.floor(seconds / 60);
    const secondsLeft = Math.floor(seconds % 60);
    return `${minutes}:${secondsLeft < 10 ? '0' : ''}${secondsLeft}`;
}

updateProgress(); // 初始调用以设置时间
