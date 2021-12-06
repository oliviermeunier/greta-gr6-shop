export class ProductForm {
    constructor(){
        this.thumbnailInput = document.getElementById('product_thumbnailFile');
        this.thumbnailInput.addEventListener('change', this.onChangeThumbnailInput.bind(this));
    }

    onChangeThumbnailInput(event){
        const file = event.currentTarget.files[0];
        const src = URL.createObjectURL(file);

        document.getElementById('product_thumbnail').src = src;
    }
}