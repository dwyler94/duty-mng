<template>
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">QRコード</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="form-group row justify-content-center">
                <div class="col-md-10 text-center">
                    <img :src="this.baseUrl + '/storage/qrs/'+childId+'.png'" id="qrImage" ref="input"/>
                </div>
                <div class="mt-3">
                    <button class="btn btn-sm btn-primary" @click="copyQrImage()">
                        QRコードコピー
                    </button>

                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">閉じる</button>
        </div>
    </div>
</template>
<script>
import { copyImageToClipboard } from 'copy-image-clipboard';

    export default {
        // mixins: [actionLoading],
        props: {
            childId: null,
        },
        data() {
            return {
                baseUrl: window.location.origin
            }
        },
        methods: {
            copyQrImage() {
                const src = $("#qrImage").attr("src");
                copyImageToClipboard(src)
                .then(() => {
                    console.log('Image Copied')
                })
                .catch((e) => {
                    console.log('Error: ', e.message)
                })
            },
        }
    }
</script>
<style scoped>

</style>
