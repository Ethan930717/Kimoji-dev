
/*
 * @Author: yanghongxuan
 * @Date: 2023-12-11 20:04:18
 * @LastEditors: yanghongxuan
 * @LastEditTime: 2023-12-11 20:21:46
 * @Description:
 */
function copyMagnetLinkToClipboard(rsskey, info_hash, name, size, passkey) {
    const magnetLink = `magnet:?dn=${encodeURIComponent(name)}&xt=urn:btih:${info_hash}&as=${encodeURIComponent(rsskey)}&tr=${encodeURIComponent(passkey)}&xl=${size}`;
    navigator.clipboard.writeText(magnetLink).then(function() {
        alert('Magnet link copied to clipboard');
    }, function(err) {
        console.error('Could not copy magnet link: ', err);
    });
}

var modal;
var modalImg;
var images;
var currentSlide;

document.addEventListener('DOMContentLoaded', function () {
    modal = document.getElementById("myModal");
    modalImg = document.getElementById("img01");
    images = document.querySelectorAll('.thumbnail');
    currentSlide = 0;
});

function openModal(imgElement, index) {
    modal.style.display = "block";
    modalImg.src = imgElement.src;
    currentSlide = index;
}

function closeModal() {
    modal.style.display = "none";
}

function plusSlides(n) {
    currentSlide += n;
    if (currentSlide >= images.length) currentSlide = 0;
    if (currentSlide < 0) currentSlide = images.length - 1;
    modalImg.src = images[currentSlide].src;
}
$(function(){
    const modal = $("#myModal");
    const close = modal.find(".close");
    const modalImg = modal.find(".modal-content");
    const prev = modal.find(".prev");
    const next = modal.find(".next");
    const alImg = $("#img01");
    const images = $('.thumbnail');
    let currentSlide = 0;

    images.click(function(){
        const imgSrc = $(this).attr('src'); // 假设这返回正确的路径
        const index = images.index(this);
        currentSlide = index;
        modalImg.attr('src', imgSrc);
        modal.show();
    });


    $("#myModal close").click(function(){
        modal.hide()
    })

    function plusSlides(n) {
        currentSlide += n;
        if (currentSlide >= images.length) currentSlide = 0;
        if (currentSlide < 0) currentSlide = images.length - 1;
        modalImg.attr('src', $(images[currentSlide]).attr('src'));
    }
    $(".close").click(function() {
        modal.hide();
    });
    $(".next").click(function() {
        plusSlides(1);
    });

    $(".prev").click(function() {
        plusSlides(-1);
    });
})
