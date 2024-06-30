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
    // 为所有设置了 data-fancybox 属性的图片添加点击事件
    document.querySelectorAll('img[data-fancybox="gallery"]').forEach(img => {
        img.addEventListener('click', function() {
            // Fancybox 会根据 data-fancybox 属性自动处理这些图片
            Fancybox.show([{ src: img.getAttribute('data-src'), type: 'image' }]);
        });
    });

    // Fancybox 全局设置
    Fancybox.defaults.Hash = false;
    Fancybox.defaults.backFocus = false;
    Fancybox.defaults.trapFocus = false;
}