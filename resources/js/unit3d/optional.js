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
