<template>
    <div class="fade modal" id="ConfirmModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ title }}</h5>
                    <button class="close" data-dismiss="modal" type="button">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body" v-text="message"></div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-dismiss="modal" type="button">いいえ</button>
                    <button class="btn btn-primary" type="button" @click="confirmed()">はい</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                message: '',
                resolve: null,
                result: false,
                title: '',
            }
        },
        methods: {
            confirm(title, message) {
                return new Promise((resolve) => {
                    this.message = message;
                    this.resolve = resolve;
                    this.result = false;
                    this.title = title;

                    $('#ConfirmModal').modal('show')
                });
            },
            confirmed() {
                this.result = true;

                $('#ConfirmModal').modal('hide');
            }
        },
        mounted() {
            $('#ConfirmModal').on('hide.bs.modal', () => {
                this.resolve(this.result);
            });
        }
    }
</script>

<style scoped>
    #ConfirmModal .modal-body {
        white-space: pre;
    }
</style>
