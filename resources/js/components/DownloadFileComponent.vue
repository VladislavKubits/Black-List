<template>
    <form @submit.prevent="submit">
        <div class="form-group">
            <div class="custom-file">
                <input type="file"
                    class="custom-file-input"
                    id="customFile"
                    @change="onAttachmentChange">
                <label class="custom-file-label" for="customFile">Выбор файла</label>
            </div>
        </div>
    </form>
</template>

<script>
    export default {
        data () {
            return {
                name: null,
                attachment: null
            }
        },
        methods: {
            submit () {
                const config = { 'content-type': 'multipart/form-data' };
                const formData = new FormData();
                formData.append('name', this.name);
                formData.append('attachment', this.attachment);

                // axios.post('/', formData, config)
                //     .then(response => console.log(response.data.message))
                //     .catch(error => console.log(error))
            },
            onAttachmentChange (e) {
                this.attachment = e.target.files[0];

                let file = {
                    "name": this.attachment.name,
                    "content": null
                };

                let _this = this;

                let reader = new FileReader();
                reader.readAsDataURL(this.attachment);
                reader.onload = function () {
                    file.content = reader.result;
                    
                    _this.$emit('changeCurrentFile', file);
                };
                reader.onerror = function (error) {
                    console.log('Error: ', error);
                };
            }
        }
    }
</script>