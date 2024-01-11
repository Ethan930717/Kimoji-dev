
//画廊
function imageGallery(images) {
    return {
        images: images && images.length > 0? images.map(v=>v.url) : [], // Populate this array with your image URLs
        currentSlide: 0,
        showModal: false,

        openModal(index) {
            this.currentSlide = index;
            this.showModal = true;
        },

        closeModal() {
            this.showModal = false;
        },

        changeSlide(n) {
            this.currentSlide += n;
            if (this.currentSlide >= this.images.length) this.currentSlide = 0;
            if (this.currentSlide < 0) this.currentSlide = this.images.length - 1;
        }
    }
}

function imageModal() {
    return {
        showModal: false,
        imageUrl: '',

        openModal(url) {
            this.imageUrl = url;
            this.showModal = true;
        },

        closeModal() {
            this.showModal = false;
        }
    }
}

function openImageModal(imageUrl) {
    const imageModal = document.createElement('div');
    imageModal.style.position = 'fixed';
    imageModal.style.top = '0';
    imageModal.style.left = '0';
    imageModal.style.width = '100%';
    imageModal.style.height = '100%';
    imageModal.style.backgroundColor = 'rgba(0, 0, 0, 0.8)';
    imageModal.style.display = 'flex';
    imageModal.style.justifyContent = 'center';
    imageModal.style.alignItems = 'center';
    imageModal.style.zIndex = '1000';

    const img = document.createElement('img');
    img.src = imageUrl;
    img.style.maxWidth = '90%';
    img.style.maxHeight = '90%';
    img.style.margin = 'auto';

    imageModal.appendChild(img);
    document.body.appendChild(imageModal);

    imageModal.addEventListener('click', function() {
        document.body.removeChild(imageModal);
    });
}
