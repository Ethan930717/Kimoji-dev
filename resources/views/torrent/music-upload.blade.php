<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>KIMOJI Music Upload</title>
    <script src="https://sdk.amazonaws.com/js/aws-sdk-2.766.0.min.js"></script>

</head>
<body>

<div id="uploadContainer" style="border: 2px dashed #4CAF50; padding: 20px; text-align: center;">
    <form id="uploadForm" action="/music-upload" method="POST" enctype="multipart/form-data">
        @csrf
        <input id="single-music" class="upload-form-file form__file" type="file" accept="audio/*" name="musicfile" style="display: none;">
        <img src="/img/indexlogo.png" alt="KIMOJI Music Upload" style="margin-bottom: 20px;">
        <h2>拖放音乐到此处或点击上传</h2>
        <h3 style="color:#4caf50">为了保证站点试听效率，上传的FLAC文件将会在后台转码为MP3格式</h3>
        <button type="button" id="selectButton">选择文件</button>
    </form>
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

            // 检查文件大小是否超过 100MB
            if (file.size > 100 * 1024 * 1024) {
                alert('请上传小于100M的音频文件');
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
    function getPresignedUrl(file, callback) {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        // 发送请求到您的服务器以获取预签名 URL
        fetch('/get-presigned-url', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({
                filename: file.name,
                filetype: file.type
            })
        })
            .then(response => {
                console.log(response); // 调试输出
                return response.json();
            })
            .then(data => {
                if (data.url) {
                    callback(null, data.url);
                } else {
                    callback('Unable to retrieve presigned URL.');
                }
            })
            .catch(error => {
                console.error('Error:', error); // 调试输出
                callback(error);
            });
    }

    function uploadFileToS3(file, presignedUrl) {
        const xhr = new XMLHttpRequest();
        xhr.open('PUT', presignedUrl, true);
        xhr.setRequestHeader('Content-Type', file.type);
        
        // 监听上传进度
        xhr.upload.onprogress = function(event) {
            if (event.lengthComputable) {
                const percentComplete = Math.round((event.loaded / event.total) * 100);
                // 更新进度条
                document.getElementById('progressBar').style.width = percentComplete + '%';
            }
        };

        // 上传完成后的处理
        xhr.onload = function() {
            if (xhr.status === 200) {
                document.getElementById('uploadStatus').innerText = '上传成功！';
                const uploadedUrl = presignedUrl.split('?')[0]; // 移除查询字符串
                document.getElementById('uploadedUrl').value = uploadedUrl;
                document.getElementById('uploadResult').style.display = 'block';
            } else {
                document.getElementById('uploadStatus').innerText = '上传失败：' + xhr.statusText;
            }
        };

        xhr.onerror = function() {
            document.getElementById('uploadStatus').innerText = '上传失败：网络或服务器错误';
        };

        xhr.send(file);
    }

    function uploadFile(file) {
        getPresignedUrl(file, function(error, url) {
            if (error) {
                console.error('获取预签名URL失败:', error);
                return;
            }
            uploadFileToS3(file, url);
        });
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

<style>

    body {
        font-family: Arial, sans-serif;
        background-color: #cff6ca;
        color: #333;
        margin: 0;
        padding: 0;
    }

    #uploadContainer {
        width: 80%;
        margin: 50px auto;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        background-color: rgba(217, 245, 211, 0.58);
    }

    #uploadContainer h2 {
        color: #4CAF50;
        font-size: 24px;
        margin-bottom: 15px;
    }

    #selectButton {
        border: none;
        padding: 10px 20px;
        background-color: #4CAF50;
        color: white;
        border-radius: 5px;
        cursor: pointer;
    }

    #selectButton:hover {
        background-color: #45a049;
    }

    #progressContainer {
        border-radius: 5px;
        overflow: hidden;
        display: none;
    }

    #progressBar {
        transition: width 0.4s ease;
    }

    .modal {
        display: none;
        position: fixed;
        z-index: 1;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgb(0,0,0);
        background-color: rgba(0,0,0,0.4);
    }

    .modal-content {
        background-color: #fefefe;
        margin: 15% auto;
        padding: 20px;
        border: 1px solid #888;
        width: 60%;
    }

    .close {
        color: #aaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
    }

    .close:hover,
    .close:focus {
        color: black;
        text-decoration: none;
        cursor: pointer;
    }
    #progressContainer {
        position: relative; /* 设置为相对定位 */
        width: 100%;
        background-color: #ddd;
        margin-top: 20px;
        border-radius: 5px; /* 圆角 */
        overflow: hidden; /* 防止子元素溢出 */
    }

    #progressBar {
        height: 20px;
        width: 0%;
        background-color: #4CAF50;
        border-radius: 5px; /* 圆角 */
        transition: width 0.4s ease;
        line-height: 20px; /* 使文本垂直居中 */
    }

    #uploadStatus {
        position: absolute; /* 绝对定位 */
        width: 100%;
        text-align: center; /* 文字居中 */
        top: 0; /* 垂直居中调整 */
        left: 0;
        margin: 0;
        line-height: 20px; /* 与进度条高度相同，以实现垂直居中 */
        font-size: 14px;
        color: white; /* 文字颜色 */
        font-weight: bold; /* 加粗字体 */
        text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.5); /* 文字阴影 */
        z-index: 2; /* 确保文本在进度条之上 */
    }

    #uploadResult {
        display: none;
    }

    #copyButton {
        border: none;
        padding: 10px 20px;
        background-color: #4CAF50;
        color: white;
        border-radius: 5px;
        cursor: pointer;
        margin-left: 10px;
    }

    #copyButton:hover {
        background-color: #45a049;
    }

    #uploadedUrl {
        padding: 8px 15px;
        border: 1px solid #ccc;
        border-radius: 5px;
        width: 60%; /* 或根据需要调整 */
        margin-right: 10px;
    }

    .confirm-modal {
        display: none;
        position: fixed;
        z-index: 2;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0, 0, 0, 0.4);
    }

    .confirm-modal-content {
        background-color: #fefefe;
        margin: 15% auto;
        padding: 20px;
        border: 1px solid #888;
        width: 40%; /* 或根据需要调整 */
        text-align: center;
    }

    .confirm-button {
        border: none;
        padding: 10px 20px;
        margin: 10px;
        background-color: #4CAF50;
        color: white;
        border-radius: 5px;
        cursor: pointer;
    }

    .confirm-button:hover {
        background-color: #45a049;
    }
</style>
