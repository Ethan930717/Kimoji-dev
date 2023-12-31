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

import APlayer from 'aplayer';

document.addEventListener('DOMContentLoaded', function() {
    // 常规模式播放器
    const aplayerContainer = document.getElementById('aplayer-container');
    if (aplayerContainer) {
        const coverUrl = aplayerContainer.dataset.cover;
        const songName = aplayerContainer.dataset.name;
        const artistName = aplayerContainer.dataset.artist;
        const songUrl = aplayerContainer.dataset.url;

        new APlayer({
            container: aplayerContainer,
            audio: [{
                name: songName,
                artist: artistName,
                url: songUrl,
                cover: coverUrl
            }]
        });
    }
});

//上传音乐
document.addEventListener('DOMContentLoaded', function() {
    var uploadButton = document.getElementById('uploadMusic');
    if (uploadButton) {
        uploadButton.addEventListener('click', openUploadWindow);
    }
});
function openUploadWindow() {
    const uploadWindowWidth = 900; // 设置窗口宽度
    const uploadWindowHeight = 600; // 设置窗口高度
    const left = (screen.width / 2) - (uploadWindowWidth / 2);
    const top = (screen.height / 2) - (uploadWindowHeight / 2);

    window.open(
        'https://file.kimoji.club',
        'UploadWindow',
        `width=${uploadWindowWidth},height=${uploadWindowHeight},top=${top},left=${left}`
    );
}

//图片浏览
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

function imageModal() {
    return {
        showModal: false,
        imageUrl: '',

        openModal(url) {
            this.imageUrl = url;
            this.showModal = true;
        },

        closeModal() {
            this.showModal = false;
        }
    }
}

function openImageModal(imageUrl) {
    const imageModal = document.createElement('div');
    imageModal.style.position = 'fixed';
    imageModal.style.top = '0';
    imageModal.style.left = '0';
    imageModal.style.width = '100%';
    imageModal.style.height = '100%';
    imageModal.style.backgroundColor = 'rgba(0, 0, 0, 0.8)';
    imageModal.style.display = 'flex';
    imageModal.style.justifyContent = 'center';
    imageModal.style.alignItems = 'center';
    imageModal.style.zIndex = '1000';

    const img = document.createElement('img');
    img.src = imageUrl;
    img.style.maxWidth = '90%';
    img.style.maxHeight = '90%';
    img.style.margin = 'auto';

    imageModal.appendChild(img);
    document.body.appendChild(imageModal);

    imageModal.addEventListener('click', function() {
        document.body.removeChild(imageModal);
    });
}











