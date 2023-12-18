function imageGallery(images) {
    console.log('images: ', images);
    const list = images.map(v=>v.url)
    return {
        images:list, // Populate this array with your image URLs
        currentSlide: 0,
        showModal: false,

        openModal(index) {
            this.images = []
            this.currentSlide = index;
            // const list = document.querySelectorAll(".thumbnail")
            // list.forEach((v)=>{
            //     this.images.push(v.src)
            // })
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
