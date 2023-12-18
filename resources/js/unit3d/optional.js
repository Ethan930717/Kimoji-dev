function imageGallery(images) {
    return {
        images: images && images.length > 0 ? images.map(v => v.url) : [],
        currentSlide: 0,
        showModal: false,
        imageTransition: false,

        openModal(index) {
            this.currentSlide = index;
            this.showModal = true;
            this.imageTransition = true;
        },

        closeModal() {
            this.showModal = false;
            this.imageTransition = false;
        },

        changeSlide(n) {
            this.imageTransition = false;
            this.$nextTick(() => {
                this.currentSlide += n;
                if (this.currentSlide >= this.images.length) this.currentSlide = 0;
                if (this.currentSlide < 0) this.currentSlide = this.images.length - 1;
                this.imageTransition = true;
            });
        }
    }
}
