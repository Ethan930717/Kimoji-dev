$(function(){
    const modal = $("#myModal");
    const close = modal.find(".close");
    const modalImg = modal.find(".modal-content");
    const prev = modal.find(".prev");
    const next = modal.find(".next");
    const images = $('.thumbnail');
    let currentSlide = 0;

    images.click(function(){
        const imgSrc = $(this).attr('src');
        const index = images.index(this);
        currentSlide = index;
        modalImg.attr('src', imgSrc);
        modal.fadeIn(); // 使用 fadeIn() 替代 show()
    });

    close.click(function(){
        modal.fadeOut(); // 使用 fadeOut() 替代 hide()
    });

    function plusSlides(n) {
        currentSlide += n;
        if (currentSlide >= images.length) currentSlide = 0;
        if (currentSlide < 0) currentSlide = images.length - 1;
        modalImg.fadeOut(function() {
            $(this).attr('src', $(images[currentSlide]).attr('src')).fadeIn();
        });
    }

    next.click(function() {
        plusSlides(1);
    });

    prev.click(function() {
        plusSlides(-1);
    });
});
