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

// 前端 JavaScript 代码
document.addEventListener('DOMContentLoaded', function() {
    const uploadButton = document.getElementById('uploadButton');
    const fileInput = document.getElementById('single-music');
    const progressBar = document.getElementById('progress-bar'); // 获取进度条元素

    uploadButton.addEventListener('click', function(e) {
        const file = fileInput.files[0];

        if (file && file.type.startsWith('audio/')) {
            e.preventDefault();
            const formData = new FormData();
            formData.append('file', file);

            // 使用 XMLHttpRequest 来支持上传进度事件
            const xhr = new XMLHttpRequest();

            // 监听上传进度事件
            xhr.upload.addEventListener('progress', function(e) {
                if (e.lengthComputable) {
                    const percentComplete = (e.loaded / e.total) * 100;
                    progressBar.style.width = percentComplete + '%'; // 更新进度条宽度
                }
            }, false);

            xhr.open('PUT', 'https://file.kimoji.club', true);
            xhr.setRequestHeader('Authorization', 'Basic ' + btoa('kimoji:forever')); // 如果需要身份验证的话
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    const status = xhr.status;
                    if (status === 0 || (status >= 200 && status < 400)) {
                        // 请求成功
                        console.log('上传结果:', xhr.responseText);
                        // 处理上传后的响应
                    } else {
                        // 请求出错
                        console.error('错误:', xhr.statusText);
                    }
                }
            };
            xhr.send(formData);
        } else {
            alert('请选择一个音频文件');
        }
    });
});