<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>KIMOJI Music Upload</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
<div id="uploadContainer" style="border: 2px dashed #4CAF50; padding: 20px; text-align: center;">
    <input id="single-music" class="upload-form-file form__file" type="file" accept="audio/*" name="single-music" style="display: none;">
    <img src="indexlogo.png" alt="KIMOJI Music Upload" style="margin-bottom: 20px;">
    <h2>拖放音乐到此处或点击上传</h2>
    <h3 style="color:#4caf50">为了保证站点试听效率，上传的FLAC文件将会在后台转码为320K MP3格式</h3>
    <button id="selectButton">选择文件</button>
    <div id="progressContainer" style="width: 100%; background-color: #ddd; margin-top: 20px;">
        <div id="progressBar" style="height: 20px; width: 0%; background-color: #4CAF50;"></div>
        <div id="uploadStatus" style="position: absolute; top: 0; left: 0; width: 100%; text-align: center; line-height: 20px;">等待上传...</div>
    </div>
</div>
<div id="uploadResult" style="margin-top: 20px; text-align: center;">
    <input type="text" id="uploadedUrl" style="width: 70%;" readonly>
    <button id="copyButton">复制链接</button>
</div>

</body>
</html>
<script>
    document.getElementById('copyButton').addEventListener('click', function() {
        const copyText = document.getElementById('uploadedUrl');
        copyText.select();
        document.execCommand('copy');
        // 弹出提示询问是否关闭窗口
        if (confirm('链接已复制，是否关闭窗口？')) {
            window.close();
        }
    })

    document.getElementById('uploadContainer').addEventListener('dragover', function (e) {
        e.preventDefault();
        e.stopPropagation();
    });

    document.getElementById('uploadContainer').addEventListener('drop', function (e) {
        e.preventDefault();
        e.stopPropagation();
        if (e.dataTransfer.files && e.dataTransfer.files[0]) {
            const file = e.dataTransfer.files[0];
            if (file.type.match('audio.*')) {
                uploadFile(file);
            } else {
                document.getElementById('uploadStatus').innerText = '请上传音频文件！';
            }
        }
    });

    document.getElementById('selectButton').addEventListener('click', function () {
        document.getElementById('single-music').click();
    });

    document.getElementById('single-music').addEventListener('change', function (e) {
        e.preventDefault();
        if (this.files && this.files[0]) {
            const file = this.files[0];

            // 检查文件大小是否超过 150MB
            if (file.size > 150 * 1024 * 1024) {
                alert('请上传小于150M的音频文件');
                return;
            }

            // 检查文件类型是否为音频
            if (file.type.match('audio.*')) {
                uploadFile(file);
            } else {
                alert('请上传音频文件！');
                document.getElementById('progressBar').style.width = '0%';
            }
        }
    });
    function uploadFile(file) {

        const api = location.origin;
        const formData = new FormData();
        formData.append('musicfile', file);

        const xhr = new XMLHttpRequest();

        // 显示进度条
        document.getElementById('progressContainer').style.display = 'block';
        xhr.upload.addEventListener('progress', function (e) {
            if (e.lengthComputable) {
                const percentComplete = e.loaded / e.total * 100;
                document.getElementById('progressBar').style.width = percentComplete + '%';
                document.getElementById('uploadStatus').innerText = '上传中请稍后...';
            }
        });
        xhr.onload = function (res) {
            if (res.target.status == 200) {
                document.getElementById('uploadStatus').innerText = '上传成功！';
                const data = JSON.parse(res.target.response);
                const uploadedMusicUrl = api + data.url;
                console.log('上传地址是：', uploadedMusicUrl);
                let modifiedUrl = uploadedMusicUrl.replace(/\.flac$/, '.mp3');
                document.getElementById('uploadedUrl').value = modifiedUrl;
                document.getElementById('uploadResult').style.display = 'block';
            } else {
                let errorMsg = '上传失败：' + (xhr.statusText || '无响应文本');
                document.getElementById('uploadStatus').innerText = errorMsg;
            }
        };
        xhr.onerror = function () {
            document.getElementById('uploadStatus').innerText = '上传失败：网络或服务器错误';
        };

        xhr.open('POST', `${api}/upload`, true);
        xhr.send(formData);
    }

    var modal = document.getElementById('uploadModal');
    var btn = document.getElementById('openModalButton');
    var span = document.getElementsByClassName('close')[0];

    btn.onclick = function() {
        modal.style.display = 'block';
    }

    span.onclick = function() {
        modal.style.display = 'none';
    }

    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = 'none';
        }
    }

</script>
