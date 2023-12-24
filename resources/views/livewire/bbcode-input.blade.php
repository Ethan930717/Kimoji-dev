<div
    class="bbcode-input"
    x-data="{
            ConfirmMakingAnnounce() {
            const input = this.$refs.bbcode;
            const selectedText = input.value.substring(input.selectionStart, input.selectionEnd);

            if (!selectedText) {
                Swal.fire({
                    title: '错误',
                    html: '请选中需要转换的内容后再使用本功能',
                    icon: 'error',
                    confirmButtonText: '好的'
                });
                return;
            }else{

            Swal.fire({
                title: '确认添加制作说明',
                html: '当前功能仅限于DIY原盘的说明内容、音乐类资源的专辑歌曲列表、频谱信息以及其他特殊资源信息，不包括PTGEN、Mediainfo等信息。<br><br>是否确认添加制作说明',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: '是',
                cancelButtonText: '否'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.insert('[center][color=#bbff88][size=24][b][spoiler=制作说明][size=16][color=white]', '[/color][/size][/spoiler][/b][/size][/color][/center]\n');
                }
            });
        }
        },

 ConfirmMovieTrans() {
    const input = this.$refs.bbcode;
    const inputValue = input.value.trim();

    if (inputValue.length > 0) {
        Swal.fire({
            title: '确认使用转载模板',
            html: '使用一键模板将会清空当前输入框中的所有内容，是否确认？',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: '是',
            cancelButtonText: '否'
        }).then((result) => {
            if (result.isConfirmed) {
               input.value = '[center][color=#bbff88][size=24][b][spoiler=转载致谢][size=16][color=white][img]/img/friendsite/请替换成制作组的组名.webp[/img][/color][/size][/spoiler][/b][/size][/color][/center]\n' +
                       '[center][color=#bbff88][size=24][b][spoiler=制作说明][size=16][color=white]如果有制作说明信息，请在此添加，否则请删除本条[/color][/size][/spoiler][/b][/size][/color][/center]\n' +
                       '[center][color=#bbff88][size=24][b][spoiler=截图赏析][size=16][color=white]请在此添加截图的BBcode链接[/color][/size][/spoiler][/b][/size][/color][/center]';
                        }
        });
    } else {
        input.value = '[center][color=#bbff88][size=24][b][spoiler=转载致谢][size=16][color=white][img]/img/friendsite/请替换成制作组的组名.webp[/img][/color][/size][/spoiler][/b][/size][/color][/center]\n' +
                       '[center][color=#bbff88][size=24][b][spoiler=制作说明][size=16][color=white]如果有制作说明信息，请在此添加，否则请删除本条[/color][/size][/spoiler][/b][/size][/color][/center]\n' +
                       '[center][color=#bbff88][size=24][b][spoiler=截图赏析][size=16][color=white]请在此添加截图的BBcode链接[/color][/size][/spoiler][/b][/size][/color][/center]';
    }
},
 ConfirmMusicTrans() {
    const input = this.$refs.bbcode;
    const inputValue = input.value.trim();

    if (inputValue.length > 0) {
        Swal.fire({
            title: '确认使用转载模板',
            html: '使用一键模板将会清空当前输入框中的所有内容，是否确认？',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: '是',
            cancelButtonText: '否'
        }).then((result) => {
            if (result.isConfirmed) {
               input.value = '[center][color=#bbff88][size=24][b][spoiler=转载致谢][size=16][color=white][img]/img/friendsite/请替换成制作组的组名.webp[/img][/color][/size][/spoiler][/b][/size][/color][/center]\n' +
                             '[center][color=#bbff88][size=24][b][spoiler=专辑介绍][size=16][color=white]请在此添加专辑的文字介绍，如有条件，请使用优质翻译工具进行汉化[/color][/size][/spoiler][/b][/size][/color][/center]\n' +
                             '[center][color=#bbff88][size=24][b][spoiler=歌曲列表][size=16][color=white]在此添加歌曲列表信息[/color][/size][/spoiler][/b][/size][/color][/center]\n' +
                             '[center][color=#bbff88][size=24][b][spoiler=截图赏析][size=16][color=white]请在此添加截图的BBcode链接，如专辑海报、频率截图等[/color][/size][/spoiler][/b][/size][/color][/center]';
                        }
        });
    } else {
       input.value = '[center][color=#bbff88][size=24][b][spoiler=转载致谢][size=16][color=white][img]/img/friendsite/请替换成制作组的组名.webp[/img][/color][/size][/spoiler][/b][/size][/color][/center]\n' +
                             '[center][color=#bbff88][size=24][b][spoiler=专辑介绍][size=16][color=white]请在此添加专辑的文字介绍，如有条件，请使用优质翻译工具进行汉化[/color][/size][/spoiler][/b][/size][/color][/center]\n' +
                             '[center][color=#bbff88][size=24][b][spoiler=歌曲列表][size=16][color=white]在此添加歌曲列表信息[/color][/size][/spoiler][/b][/size][/color][/center]\n' +
                             '[center][color=#bbff88][size=24][b][spoiler=截图赏析][size=16][color=white]请在此添加截图的BBcode链接，如专辑海报、频率截图等[/color][/size][/spoiler][/b][/size][/color][/center]';
                        }
                        },

       ConfirmVoiceTrans() {
    const input = this.$refs.bbcode;
    const inputValue = input.value.trim();

    if (inputValue.length > 0) {
        Swal.fire({
            title: '确认使用转载模板',
            html: '使用一键模板将会清空当前输入框中的所有内容，是否确认？',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: '是',
            cancelButtonText: '否'
        }).then((result) => {
            if (result.isConfirmed) {
               input.value = '[center][color=#bbff88][size=24][b][spoiler=转载致谢][size=16][color=white][img]/img/friendsite/请替换成制作组的组名.webp[/img][/color][/size][/spoiler][/b][/size][/color][/center]\n' +
                             '[center][color=#bbff88][size=24][b][spoiler=音源简介][size=16][color=white]请在此添加书籍或节目内容的基本介绍[/color][/size][/spoiler][/b][/size][/color][/center]\n' +
                             '[center][color=#bbff88][size=24][b][spoiler=节目列表][size=16][color=white]如有节目详细列表，请在此添加，否则请删除本条[/color][/size][/spoiler][/b][/size][/color][/center]\n' +
                             '[center][color=#bbff88][size=24][b][spoiler=截图赏析][size=16][color=white]请在此添加截图的BBcode链接，如书籍海报、插画或其他相关图片内容[/color][/size][/spoiler][/b][/size][/color][/center]';
                        }
        });
    } else {
        input.value = '[center][color=#bbff88][size=24][b][spoiler=转载致谢][size=16][color=white][img]/img/friendsite/请替换成制作组的组名.webp[/img][/color][/size][/spoiler][/b][/size][/color][/center]\n' +
                             '[center][color=#bbff88][size=24][b][spoiler=音源简介][size=16][color=white]请在此添加书籍或节目内容的基本介绍[/color][/size][/spoiler][/b][/size][/color][/center]\n' +
                             '[center][color=#bbff88][size=24][b][spoiler=节目列表][size=16][color=white]如有节目详细列表，请在此添加，否则请删除本条[/color][/size][/spoiler][/b][/size][/color][/center]\n' +
                             '[center][color=#bbff88][size=24][b][spoiler=截图赏析][size=16][color=white]请在此添加截图的BBcode链接，如书籍海报、插画或其他相关图片内容[/color][/size][/spoiler][/b][/size][/color][/center]';
                        }
                        },



                    convertImages() {
        const input = this.$refs.bbcode;
        let selectedText = input.value.substring(input.selectionStart, input.selectionEnd);

        if (!selectedText) {
            Swal.fire({
                title: '错误',
                html: '请先选定缩略图的 BBCode 代码<br><br>本功能代码来自 <a href= /img/friendsite/tomorrow505.png  target= _blank>Tomorrow505</a>',
                icon: 'error',
                confirmButtonText: '好的'
            });
            return;
        }

        let convertedText = '';
        const imageRegex = /(\[img(?:=\d+)?\])(http[^\[\]]*?(jpg|jpeg|png|gif|webp))/ig;

        if (!imageRegex.test(selectedText)) {
            Swal.fire({
                title: '错误',
                html: '选定的部分不是缩略图 BBCode<br><br>本功能代码来自 <a href= /img/friendsite/tomorrow505.png  target= _blank>Tomorrow505</a>',
                icon: 'error',
                confirmButtonText: '好的'
            });
            return;
        }
        selectedText.match(imageRegex).forEach((item) => {
            item = item.replace(/\[.*\]/g, '');
            if (item.match(/imgbox/)) {
                convertedText += '[img]' + item.replace('thumbs2', 'images2').replace('t.png', 'o.png') + '[/img]\n';
            }
            else if (item.match(/pixhost/)) {
                convertedText += '[img]' + item.replace('//t', '//img').replace('thumbs', 'images') + '[/img]\n';
            }
            else if (item.match(/pterclub.com|beyondhd.co\/(images|cache)|z4a.net\/images/)) {
                convertedText += '[img]' + item.replace(/th.png/g, 'png').replace(/md.png/g, 'png').replace(/t\/mages/, 'i/mages') + '[/img]\n';
            }
            else if (item.match(/tu.totheglory.im/)) {
                convertedText += '[img]' + item.replace(/_thumb.png/, '.png') + '[/img]\n';
            }
            else if (item.match(/img.hdchina.org/)) {
                convertedText += '[img]' + item.replace(/md.png/, 'png') + '[/img]\n';
            }
            else if (item.match(/cinematik/)) {
                convertedText += '[img]' + item.replace(/thu/, 'big') + '[/img]\n';
            }
            else if (item.match(/img4k.net|img.hdhome.org/)) {
                convertedText += '[img]' + item.replace(/md.png/, 'png') + '[/img]\n';
            }
            else {
                convertedText += '[img]' + item + '[/img]\n';
            }
        });

        if (!convertedText) {
            Swal.fire({
                title: '错误',
                html: '无法转换选定的 BBCode，当前本功能仅支持以下图床 <br> imgbox，pixhost，pter，ttg，瓷器，img4k <br><br>本功能代码来自 <a href= /img/friendsite/tomorrow505.png  target= _blank>Tomorrow505</a>',
                icon: 'error',
                confirmButtonText: '好的'
            });
            return;
        }

        // 将转换后的文本放入结果区域
        input.value = input.value.substring(0, input.selectionStart)
            + convertedText
            + input.value.substring(input.selectionEnd);
        input.dispatchEvent(new Event('input'));
    },

        insertThanks(openTag, closeTag) {
            const input = this.$refs.bbcode;
            const start = input.selectionStart;
            const end = input.selectionEnd;
            const selectedText = input.value.substring(start, end);

            if (selectedText.includes('[/img]') && selectedText.includes('[/spoiler]') && selectedText.includes('[/center]')) {
                Swal.fire({
                    title: '错误',
                    html: '请不要在相同内容中重复使用本功能',
                    icon: 'error',
                    confirmButtonText: '好的'
                });
                return;
            }

            // 检查是否选中了包含 [img]、friendsite 和 [/img] 的文本
            if (selectedText.includes('[img]') && selectedText.includes('friendsite') && selectedText.includes('[/img]')) {
                input.value = input.value.substring(0, start)
                    + openTag
                    + selectedText
                    + closeTag
                    + input.value.substring(end);
                input.dispatchEvent(new Event('input'));
                input.focus();
                input.setSelectionRange(start, end + openTag.length + closeTag.length);
            } else if (!selectedText.includes('[img]') || !selectedText.includes('[/img]')) {
                Swal.fire({
                    title: '错误',
                    html: '请完整选定友站插图的BBCode代码后再使用本功能 <br> 选定部分首尾需为 [img] 与 [/img] 标签',
                    icon: 'error',
                    confirmButtonText: '好的'
                });
            } else if (!selectedText.includes('friendsite')) {
                Swal.fire({
                    title: '错误',
                    html: '您选定的内容不是KIMOJI为友站订制的插图 <br> 请您先行移步浏览 <a href= /pages/5 >《转载规则》</a>',
                    icon: 'error',
                    confirmButtonText: '好的'
                    });
                }
            },

        insertWithCheck(openTag, closeTag) {
            const input = this.$refs.bbcode;
            const start = input.selectionStart;
            const end = input.selectionEnd;
            const selectedText = input.value.substring(start, end);

            if (selectedText.includes('[/img]') && selectedText.includes('[/spoiler]') && selectedText.includes('[/center]')) {
                Swal.fire({
                    title: '错误',
                    html: '请不要在相同内容中重复使用本功能',
                    icon: 'error',
                    confirmButtonText: '好的'
                });
                return;
            }
            // 检查是否选中了文本，以及该文本是否包含 [img] 和 [/img]
            if (selectedText.length > 0 && selectedText.includes('[img]') && selectedText.includes('[/img]')) {
                input.value = input.value.substring(0, start)
                    + openTag
                    + selectedText
                    + closeTag
                    + input.value.substring(end);
                input.dispatchEvent(new Event('input'));
                input.focus();
                input.setSelectionRange(start, end + openTag.length + closeTag.length);
            } else {
                // 弹出错误提示
                Swal.fire({
                    title: '错误',
                    html: '请在选定所有截图的BBCode代码后再使用本功能<br>选定部分首尾需为 [img] 与 [/img] 标签',
                    icon: 'error',
                    confirmButtonText: '好的'
                });
            }
        },
        insert(openTag, closeTag) {
            input = $refs.bbcode;
            start = input.selectionStart;
            end = input.selectionEnd;
            input.value = input.value.substring(0, start)
                + openTag
                + input.value.substring(start, end)
                + closeTag
                + input.value.substring(end)
            input.dispatchEvent(new Event('input'));
            input.focus();
            if (openTag.charAt(openTag.length - 2) === '=') {
                input.setSelectionRange(start + openTag.length - 1, start + openTag.length - 1);
            } else if (start == end) {
                input.setSelectionRange(start + openTag.length, end + openTag.length);
            } else {
                input.setSelectionRange(start, end + openTag.length + closeTag.length);
            }

        },
        showButtons: false,
        bbcodePreviewHeight: null,
        isPreviewEnabled: @entangle('isPreviewEnabled'),
        isOverInput: false,
        previousActiveElement: document.activeElement,
    }"
>
    <p class="bbcode-input__tabs">
        <input class="bbcode-input__tab-input" type="radio" id="{{ $name }}-bbcode-preview-disabled" name="isPreviewEnabled" value="0" wire:model="isPreviewEnabled" />
        <label class="bbcode-input__tab-label" for="{{ $name }}-bbcode-preview-disabled">编辑</label>
        <input class="bbcode-input__tab-input" type="radio" id="{{ $name }}-bbcode-preview-enabled" name="isPreviewEnabled" value="1" wire:model="isPreviewEnabled" />
        <label class="bbcode-input__tab-label" for="{{ $name }}-bbcode-preview-enabled">{{ __('common.preview') }}</label>
    </p>

    <p class="bbcode-input__icon-bar-toggle">
        <button type="button" class="form__button form__button--text" x-on:click="showButtons = ! showButtons">BBCode</button>
    </p>
    <menu class="bbcode-input__icon-bar" x-cloak x-show="showButtons">
        <li>
            <button type="button" class="form__standard-icon-button" x-on:click="insert('[b]', '[/b]')">
                <abbr title="Bold">
                    <i class="{{ config('other.font-awesome') }} fa-bold"></i>
                </abbr>
            </button>
        </li>
        <li>
            <button type="button" class="form__standard-icon-button" x-on:click="insert('[i]', '[/i]')">
                <abbr title="Italics">
                    <i class="{{ config('other.font-awesome') }} fa-italic"></i>
                </abbr>
            </button>
        </li>
        <li>
            <button type="button" class="form__standard-icon-button" x-on:click="insert('[u]', '[/u]')">
                <abbr title="Underline">
                    <i class="{{ config('other.font-awesome') }} fa-underline"></i>
                </abbr>
            </button>
        </li>
        <li>
            <button type="button" class="form__standard-icon-button" x-on:click="insert('[s]', '[/s]')">
                <abbr title="Strikethrough">
                    <i class="{{ config('other.font-awesome') }} fa-strikethrough"></i>
                </abbr>
            </button>
        </li>
        <hr class="bbcode-input__icon-separator">
        <li>
            <button type="button" class="form__standard-icon-button" x-on:click="insert('[img]', '[/img]')">
                <abbr title="Insert Image">
                    <i class="{{ config('other.font-awesome') }} fa-image"></i>
                </abbr>
            </button>
        </li>
        <li>
            <button type="button" class="form__standard-icon-button" x-on:click="insert('[video=&quot;youtube&quot;]', '[/video]')">
                <abbr title="Insert YouTube">
                    <i class="fab fa-youtube"></i>
                </abbr>
            </button>
        </li>
        <li>
            <button type="button" class="form__standard-icon-button" x-on:click="insert('[url]', '[/url]')">
                <abbr title="Link">
                    <i class="{{ config('other.font-awesome') }} fa-link"></i>
                </abbr>
            </button>
        </li>
        <hr class="bbcode-input__icon-separator">
        <li>
            <button type="button" class="form__standard-icon-button" x-on:click="insert('\n[list]\n[*]', '\n[/list]\n')">
                <abbr title="Unordered List">
                    <i class="{{ config('other.font-awesome') }} fa-list"></i>
                </abbr>
            </button>
        </li>
        <li>
            <button type="button" class="form__standard-icon-button" x-on:click="insert('\n[list=1]\n[*]', '\n[/list]\n')">
                <abbr title="Ordered List">
                    <i class="{{ config('other.font-awesome') }} fa-list-ol"></i>
                </abbr>
            </button>
        </li>
        <hr class="bbcode-input__icon-separator">
        <li>
            <button type="button" class="form__standard-icon-button" x-on:click="insert('[color=]', '[/color]')">
                <abbr title="Font Color">
                    <i class="{{ config('other.font-awesome') }} fa-palette"></i>
                </abbr>
            </button>
        </li>
        <li>
            <button type="button" class="form__standard-icon-button" x-on:click="insert('[size=]', '[/size]')">
                <abbr title="Font Size">
                    <i class="{{ config('other.font-awesome') }} fa-text-size"></i>
                </abbr>
            </button>
        </li>
        <li>
            <button type="button" class="form__button form__button--text" x-on:click="insert('[font=]', '[/font]')">
                <abbr title="Font Family">
                    Font
                </abbr>
            </button>
        </li>
        <hr class="bbcode-input__icon-separator">
        <li>
            <button type="button" class="form__standard-icon-button" x-on:click="insert('\n[left]\n', '\n[/left]\n')">
                <abbr title="Align left">
                    <i class="{{ config('other.font-awesome') }} fa-align-left"></i>
                </abbr>
            </button>
        </li>
        <li>
            <button type="button" class="form__standard-icon-button" x-on:click="insert('\n[center]\n', '\n[/center]\n')">
                <abbr title="Align center">
                    <i class="{{ config('other.font-awesome') }} fa-align-center"></i>
                </abbr>
            </button>
        </li>
        <li>
            <button type="button" class="form__standard-icon-button" x-on:click="insert('\n[right]\n', '\n[/right]\n')">
                <abbr title="Align right">
                    <i class="{{ config('other.font-awesome') }} fa-align-right"></i>
                </abbr>
            </button>
        </li>
        <hr class="bbcode-input__icon-separator">
        <li>
            <button type="button" class="form__standard-icon-button" x-on:click="insert('[quote]', '[/quote]')">
                <abbr title="Quote">
                    <i class="{{ config('other.font-awesome') }} fa-quote-right"></i>
                </abbr>
            </button>
        </li>
        <li>
            <button type="button" class="form__standard-icon-button" x-on:click="insert('[code]', '[/code]')">
                <abbr title="Code">
                    <i class="{{ config('other.font-awesome') }} fa-code"></i>
                </abbr>
            </button>
        </li>
        <li>
            <button type="button" class="form__standard-icon-button" x-on:click="insert('[spoiler]', '[/spoiler]')">
                <abbr title="Spoiler">
                    <i class="{{ config('other.font-awesome') }} fa-eye-slash"></i>
                </abbr>
            </button>
        </li>
        <li>
            <button type="button" class="form__standard-icon-button" x-on:click="insert('[note]', '[/note]')">
                <abbr title="Note">
                    <i class="{{ config('other.font-awesome') }} fa-sticky-note"></i>
                </abbr>
            </button>
        </li>
        <li>
            <button type="button" class="form__standard-icon-button" x-on:click="insert('[alert]', '[/alert]')">
                <abbr title="Alert">
                    <i class="{{ config('other.font-awesome') }} fa-file-exclamation"></i>
                </abbr>
            </button>
        </li>
        <li>
            <button type="button" class="form__standard-icon-button" x-on:click="insert('[table]\n[tr]\n[td]', '[/td]\n[/tr]\n[/table]')">
                <abbr title="Table">
                    <i class="{{ config('other.font-awesome') }} fa-table"></i>
                </abbr>
            </button>
        </li>
        <li>
            <button
                type="button"
                class="form__standard-icon-button"
                x-on:click="Swal.fire({
                    title: 'Emoji选择器',
                    html: '如果您使用的是MacOS，请按 Ctrl + Cmd + 空格键<br>如果您使用的是Windows或Linux，请按 Windows 徽标键 + . (句号键)',
                    icon: 'info',
                    showConfirmButton: true,
                })"
            >
                <abbr title="If using MacOS, press Ctrl + Cmd + Space bar&NewLine;If using Windows or Linux, press Windows logo key + .">
                    <i class="{{ config('other.font-awesome') }} fa-face-smile"></i>
                </abbr>
            </button>
        </li>
        <li><button type="button" class="form__button form__button--text" x-on:click="insertThanks('[center][color=#bbff88][size=24][b][spoiler=转载致谢]', '[/spoiler][/b][/size][/color][/center]\n')">转载致谢</button></li>
        <li><button type="button" class="form__button form__button--text" x-on:click="confirm-making-announce">制作说明</button></li>
        <li><button type="button" class="form__button form__button--text" x-on:click="insertWithCheck('[center][color=#bbff88][size=24][b][spoiler=截图赏析]', '[/spoiler][/b][/size][/color][/center]\n')">截图赏析</button></li>
        <li><button type="button" class="form__button form__button--text" x-on:click="convertImages()">转换大图</button></li>
        <li><button type="button" class="form__button form__button--text" x-on:click="showoneclickbuttons = ! showoneclickbuttons">一键模板</button></li>
        </p>
        <menu class="bbcode-input__icon-bar" x-cloak x-show="showoneclickbuttons">
            <li><button type="button" class="form__button form__button--text" x-on:click="ConfirmMovieTrans">影视模板</button></li>
            <li><button type="button" class="form__button form__button--text" x-on:click="ConfirmMusicTrans">音乐模板</button></li>
            <li><button type="button" class="form__button form__button--text" x-on:click="ConfirmVoiceTrans">有声模板</button></li>

        </menu>

    </menu>
    <div class="bbcode-input__tab-pane">
        <div class="bbcode-input__preview bbcode-rendered" x-show="isPreviewEnabled">
            @joypixels($contentHtml)
        </div>
        <p class="form__group" x-show="! isPreviewEnabled">
            <textarea
                id="bbcode-{{ $name }}"
                name="{{ $name }}"
                class="form__textarea bbcode-input__input"
                placeholder=" "
                x-ref="bbcode"
                x-on:mouseup="
                    if (isOverInput) {
                        bbcodePreviewHeight = $el.style.height;
                    }
                "
                x-on:mousedown="previousActiveElement = document.activeElement;"
                x-on:mouseover="isOverInput = true"
                x-on:mouseleave="isOverInput = false"
                wire:model="contentBbcode"
                x-bind:style="{ height: bbcodePreviewHeight !== null && bbcodePreviewHeight, transition: previousActiveElement === $el ? 'none' : 'border-color 600ms cubic-bezier(0.25, 0.8, 0.25, 1), height 600ms cubic-bezier(0.25, 0.8, 0.25, 1)' }"
                @if ($isRequired)
                    required
                @endif
            ></textarea>
            <label class="form__label form__label--floating" for="bbcode-{{ $name }}">
                {{ $label }}
            </label>
        </p>
    </div>
</div>
