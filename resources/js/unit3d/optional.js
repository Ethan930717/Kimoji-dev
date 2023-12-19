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



