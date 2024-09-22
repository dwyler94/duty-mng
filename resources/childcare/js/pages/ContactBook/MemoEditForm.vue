<template>
    <div class="fade modal" id="contact-book-meal-edit-form">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ cardTitle }}</h5>
                    <button class="close" data-dismiss="modal" type="button">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <textarea
                        class="form-control"
                        cols="30"
                        ref="memoTextarea"
                        rows="10"
                        v-model="content"
                        :disabled="isDisabled"
                        :maxlength="textareaMax"
                    ></textarea>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-dismiss="modal" type="button">閉じる</button>
                    <button class="btn btn-primary" type="submit" v-if="!isDisabled" @click="save">入力</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        created() {

        },
        data() {
            return {
                formDataKey: '',
                cardTitle: '',
                content: '',
                isDisabled: false,
            }
        },
        methods: {
            showModal(title, content, key, isDisabled) {
                this.cardTitle = title;
                this.content = content;
                this.formDataKey = key;
                this.isDisabled = isDisabled;

                $("#contact-book-meal-edit-form").modal('show');
                $("#contact-book-meal-edit-form").on('shown.bs.modal', () => {
                    this.$refs.memoTextarea.focus();
                })
            },
            save() {
                this.$emit('replaceFormData', this.formDataKey, this.content);
            },
        },
        props: {
            textareaMax: {
                type: Number,
                default: 200
            }
        }
    };
</script>
<style scoped>
    textarea {
        font-size: 16px;
    }
</style>
