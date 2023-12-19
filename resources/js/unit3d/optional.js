function imageGallery(images) {
    return {
        images: images && images.length > 0? images.map(v=>v.url) : [], // Populate this array with your image URLs
        currentSlide: 0,
        showModal: false,

        openModal(index) {
            this.currentSlide = index;
            this.showModal = true;
        },

        closeModal() {
            this.showModal = false;
        },

        changeSlide(n) {
            this.currentSlide += n;
            if (this.currentSlide >= this.images.length) this.currentSlide = 0;
            if (this.currentSlide < 0) this.currentSlide = this.images.length - 1;
        }
    }
}



//Howler
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
//音量控制
const volumeSlider = document.getElementById('volume-slider');
volumeSlider.addEventListener('input', function() {
    sound.volume(this.value);
});

//播放/暂停
const togglePlayPauseButton = document.getElementById('play-pause-button');
togglePlayPauseButton.addEventListener('click', function() {
    if (sound.playing()) {
        sound.pause();
    } else {
        sound.play();
    }
});
// 更新播放/暂停按钮的图标
function updatePlayPauseButton() {
    if (sound.playing()) {
        togglePlayPauseButton.classList.remove('fa-play');
        togglePlayPauseButton.classList.add('fa-pause');
    } else {
        togglePlayPauseButton.classList.remove('fa-pause');
        togglePlayPauseButton.classList.add('fa-play');
    }
}
// 静音按钮状态更新
const muteButton = document.getElementById('mute');
muteButton.addEventListener('click', function() {
    sound.mute(!sound.mute());
    updateMuteButton();
});

function updateMuteButton() {
    if (sound.mute()) {
        muteButton.style.color = '#A3BAEAFF';
    } else {
        muteButton.style.color = 'white';
    }
}

// 更新播放状态相关的UI
sound.on('play', updatePlayPauseButton);
sound.on('pause', updatePlayPauseButton);
sound.on('end', updatePlayPauseButton);


// 获取进度条元素
const progressBar = document.getElementById('progress-bar');
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


//canvas
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