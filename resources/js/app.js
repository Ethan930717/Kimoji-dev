window._ = require('lodash');
import 'select2';
/**
 * We'll load jQuery and the Bootstrap jQuery plugin which provides support
 * for JavaScript based Bootstrap features such as modals and tabs. This
 * code may be modified to fit the specific needs of your application.
 */

try {
    // Note: Eventually we will end up 100% jQuery free.
    window.$ = window.jQuery = require('jquery');
} catch (e) {}

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': document.head.querySelector('meta[name="csrf-token"]').content,
    },
});

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
<<<<<<< Updated upstream
$(function(){
    // 初始化 autocat 作为 select2 控件并处理其 change 事件
    $('#autocat').select2({ minimumResultsForSearch: -1 }).on('change', function() {
        var alpineComponentRoot = $(this).closest('[x-data]');
        var selectedValue = $(this).val();

        if (alpineComponentRoot.length && alpineComponentRoot[0].__x) {
            alpineComponentRoot[0].__x.$data.cat = selectedValue;
            if (alpineComponentRoot[0].__x.$data.cats && alpineComponentRoot[0].__x.$data.cats[selectedValue]) {
                alpineComponentRoot[0].__x.$data.cats[selectedValue].type = alpineComponentRoot[0].__x.$data.cats[selectedValue].type;
            }
        }
    });

    // 初始化其他 select2 控件并处理其 select2:select 事件
    $("select.select2:not(#autocat)").each(function () {
        $(this).select2({ minimumResultsForSearch: -1 })
            .on("select2:select", function (e) {
                var alpineComponentRoot = $(this).closest('[x-data]');
                var selectedValue = $(this).val();

                if (alpineComponentRoot.length && alpineComponentRoot[0].__x) {
                    // 更新 Alpine.js 的数据模型
                    // 添加适用于此元素的特定逻辑
                }
            })
            .on("keydown", function(e) {
                var term = e.key;
                if (term.length === 1 && term.match(/[a-z]/i)) {
                    var matched = $(this).find("option").filter(function() {
                        return $(this).text().toLowerCase().startsWith(term.toLowerCase());
                    }).first();

                    if (matched.length) {
                        $(this).val(matched.val()).trigger("change");
                    }
                }
            });
    });
});

=======


/*$(function () {
    var formSelect = $('.form__select');
    formSelect.select2({minimumResultsForSearch: -1});
});*/
>>>>>>> Stashed changes
