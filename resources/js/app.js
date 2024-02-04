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

function initializeAPlayer() {
    const aplayerElement = document.getElementById('aplayer');
    if (aplayerElement) {
        const coverUrl = aplayerElement.dataset.cover;
        const songName = aplayerElement.dataset.name;
        const artistName = aplayerElement.dataset.artist;
        const songUrl = aplayerElement.dataset.url;
        const lrcUrl = aplayerElement.dataset.lrc; // 可能是 undefined 或空字符串

        const aplayerConfig = {
            container: aplayerElement,
            fixed: true,
            audio: [{
                name: songName,
                artist: artistName,
                url: songUrl,
                cover: coverUrl
            }]
        };

        if (lrcUrl && lrcUrl.trim() !== '') {
            aplayerConfig.lrcType = 3;
            aplayerConfig.audio[0].lrc = lrcUrl;
        }

        new APlayer(aplayerConfig);
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const loadPlayerBtn = document.getElementById('loadPlayerBtn');
    if (loadPlayerBtn) {
        loadPlayerBtn.addEventListener('click', function() {
            initializeAPlayer();
            // 点击后，可选择隐藏按钮
            loadPlayerBtn.style.display = 'none';
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

//url创建工单
document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);

    if (urlParams.has('category_id')) {
        document.getElementById('category_id').value = urlParams.get('category_id');
    }

    if (urlParams.has('priority_id')) {
        document.getElementById('priority_id').value = urlParams.get('priority_id');
    }

    if (urlParams.has('subject')) {
        document.getElementById('ticket_subject').value = decodeURIComponent(urlParams.get('subject'));
    }

    if (urlParams.has('body')) {
        document.getElementById('bbcode-body').value = decodeURIComponent(urlParams.get('body'));
    }

});

document.addEventListener('DOMContentLoaded', function() {
    // 为 spectrogram-image 元素添加点击事件
    const spectrogramImages = document.querySelectorAll('.spectrogram-image');
    spectrogramImages.forEach(img => {
        img.addEventListener('click', function() {
            openImageModal(img.src);
        });
    });

    // 为 meta__poster 元素添加点击事件
    const metaPosters = document.querySelectorAll('.meta__poster');
    metaPosters.forEach(poster => {
        poster.addEventListener('click', function() {
            openImageModal(poster.src);
        });
    });

    // 为 torrent-search--list__music_poster-img 元素添加点击事件
    const musicPosters = document.querySelectorAll('.torrent-search--list__music_poster-img');
    musicPosters.forEach(poster => {
        poster.addEventListener('click', function() {
            openImageModal(poster.src);
        });
    });

    // 为 torrent-card__image 元素添加点击事件
    const torrentCardImages = document.querySelectorAll('.torrent-card__image');
    torrentCardImages.forEach(image => {
        image.addEventListener('click', function() {
            openImageModal(image.src);
        });
    });
});










