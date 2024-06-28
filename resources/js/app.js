import { Fancybox } from "@fancyapps/ui";
import "@fancyapps/ui/dist/fancybox/fancybox.css";


// 其他已有的代码
window._ = require('lodash');
window.axios = require('axios');
window.axios.defaults.headers.common = {
    'X-Requested-With': 'XMLHttpRequest',
    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
};

let token = document.head.querySelector('meta[name="csrf-token"]');
if (token) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
} else {
    console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
}

import Vue from 'vue';
import chatbox from './components/chat/Chatbox';

if (document.getElementById('vue')) {
    new Vue({
        el: '#vue',
        components: { chatbox: chatbox },
    });
}

window.Swal = require('sweetalert2');
import APlayer from 'aplayer';

function initializeAPlayer() {
    const aplayerElement = document.getElementById('aplayer');
    if (aplayerElement) {
        const coverUrl = aplayerElement.dataset.cover;
        const songName = aplayerElement.dataset.name;
        const artistName = aplayerElement.dataset.artist;
        const songUrl = aplayerElement.dataset.url;
        const lrcUrl = aplayerElement.dataset.lrc;

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
            const username = loadPlayerBtn.getAttribute('data-username');
            const url = `/users/${username}/increment-listen-count`;
            this.style.display = 'none';
            fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                },
            })
            initializeAPlayer();
        });
    }
});


// URL 创建工单
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

// 图片放大功能
document.addEventListener('DOMContentLoaded', function() {
    bindClickEvents();

    document.addEventListener('livewire:load', function() {
        bindClickEvents();
    });

    document.addEventListener('livewire:update', function() {
        bindClickEvents();
    });
});

function bindClickEvents() {
    // 为 spectrogram-image 元素添加点击事件
    const spectrogramButtons = document.querySelectorAll('[data-spectrogram-button]');
    spectrogramButtons.forEach(button => {
        button.setAttribute('data-fancybox', 'gallery');
        const spectrogramImage = button.querySelector('.spectrogram-image');
        if (spectrogramImage) {
            button.addEventListener('click', function() {
                Fancybox.show([{ src: spectrogramImage.src, type: 'image' }]);
            });
        }
    });

    // 为 meta__poster 元素添加点击事件
    const metaPosters = document.querySelectorAll('.meta__poster');
    metaPosters.forEach(poster => {
        poster.setAttribute('data-fancybox', 'gallery');
        poster.addEventListener('click', function() {
            Fancybox.show([{ src: poster.src, type: 'image' }]);
        });
    });

    // 为 torrent-search--list__music_poster-img 元素添加点击事件
    const musicPosters = document.querySelectorAll('.torrent-search--list__music_poster-img');
    musicPosters.forEach(poster => {
        poster.setAttribute('data-fancybox', 'gallery');
        poster.addEventListener('click', function() {
            Fancybox.show([{ src: poster.src, type: 'image' }]);
        });
    });

    // 为 torrent-card__image 元素添加点击事件
    const torrentCardImages = document.querySelectorAll('.torrent-card__image');
    torrentCardImages.forEach(image => {
        image.setAttribute('data-fancybox', 'gallery');
        image.addEventListener('click', function() {
            Fancybox.show([{ src: image.src, type: 'image' }]);
        });
    });

    // 为 video-poster 元素添加点击事件
    const videoPosters = document.querySelectorAll('.video-poster');
    videoPosters.forEach(poster => {
        poster.setAttribute('data-fancybox', 'gallery');
        poster.addEventListener('click', function() {
            Fancybox.show([{ src: poster.src, type: 'image' }]);
        });
    });

    // 为 gallery-image 元素添加点击事件
    const galleryImages = document.querySelectorAll('.gallery-image');
    galleryImages.forEach(image => {
        image.setAttribute('data-fancybox', 'gallery');
    });

    // 绑定Fancybox到所有带有data-fancybox属性的元素
    Fancybox.defaults.Hash = false;
    Fancybox.defaults.backFocus = false;
    Fancybox.defaults.trapFocus = false;

    Fancybox.bind('[data-fancybox="gallery"]', {
        caption : function(fancybox, carousel, slide) {
            return slide.$trigger.dataset.caption || '';
        }
    });
}