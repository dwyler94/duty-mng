<template>
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">連絡帳種別変更</h5>
            <button class="close" data-dismiss="modal" type="button">
                <span>&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="form-group row">
                <div class="col-3">変更日</div>
                <div class="col-6">{{ dateFormat(date) }}</div>
            </div>
            <div class="form-group row">
                <div class="col-3">種別</div>
                <div class="col-6">
                    <select class="form-control" v-model="selectedType">
                        <option value="1">0歳児クラス</option>
                        <option value="2">1, 2歳児クラス</option>
                        <option value="3">3歳児以上クラス</option>
                    </select>
                </div>
            </div>
            <p class="mb-0">連絡帳フォームを変更した場合、<br>変更した日以降のすべての連絡帳の様式が変更されます。</p>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" data-dismiss="modal" type="button">閉じる</button>
            <button class="btn btn-primary" type="submit" @click="save">登録</button>
        </div>
    </div>
</template>

<script>
    import actionLoading from '../../mixin/actionLoading';
    import api, { apiErrorHandler } from '../../global/api';
    import moment from 'moment-timezone';
    import { showSuccess } from '../../helpers/error';

    export default {
        created() {
            this.selectedType = this.type;
        },
        data() {
            return {
                selectedType: null,
            }
        },
        methods: {
            dateFormat(date) {
                if (date) {
                    return moment(date).format('YYYY年 M月 D日 (ddd)');
                }
            },
            save() {
                if (this.actionLoading) {
                    return;
                }

                this.setActionLoading();

                api.post(
                    'contact-book/child/' + this.childId + '/type',
                    null,
                    {'date': moment(this.date).format('YYYY-MM-DD'), 'type': this.selectedType}
                )
                .then((response) => {
                    this.unsetActionLoading();

                    showSuccess(this.$t('Successfully saved'));

                    this.$emit('success', response);
                })
                .catch(e => {
                    this.unsetActionLoading();

                    apiErrorHandler(e);
                });
            },
            show() {
                this.selectedType = this.type;
            }
        },
        mixins: [actionLoading],
        props: {
            childId: null,
            date: null,
            type: null
        },
    };
</script>
