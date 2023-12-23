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
    const aplayerContainer = document.getElementById('aplayer-container');
    const coverUrl = aplayerContainer.dataset.cover;
    const songName = aplayerContainer.dataset.name;
    const artistName = aplayerContainer.dataset.artist;
    const songUrl = aplayerContainer.dataset.url;

    const ap = new APlayer({
        container: document.getElementById('aplayer'),
        audio: [{
            name: songName,
            artist: artistName,
            url: songUrl,
            cover: coverUrl
        }]
    });
});

//上传音乐
document.addEventListener('DOMContentLoaded', function() {
    var uploadButton = document.getElementById('uploadMusic');
    if (uploadButton) {
        uploadButton.addEventListener('click', openUploadWindow);
    }
});
function openUploadWindow() {
    const uploadWindowWidth = 600; // 设置窗口宽度
    const uploadWindowHeight = 400; // 设置窗口高度
    const left = (screen.width / 2) - (uploadWindowWidth / 2);
    const top = (screen.height / 2) - (uploadWindowHeight / 2);

    window.open(
        'https://file.kimoji.club',
        'UploadWindow',
        `width=${uploadWindowWidth},height=${uploadWindowHeight},top=${top},left=${left}`
    );

    window.addEventListener('message', function(event) {
        // 检查消息类型
        if (event.data.type && event.data.type === 'uploadSuccess') {
            // 获取上传的文件 URL
            var uploadedMusicUrl = event.data.url;
            // 更新隐藏输入字段的值
            document.getElementById('music_url').value = uploadedMusicUrl;
        }
    }, false);
}



