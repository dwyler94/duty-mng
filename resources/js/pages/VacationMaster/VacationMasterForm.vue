<template>
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" v-if="!editMode">新規登録</h5>
            <h5 class="modal-title" v-else>編集</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="form-group">
                <div class="form-row align-items-center">
                    <div class="col-md-10">
                        <input type="text"
                            name="vacation_name"
                            class="form-control"
                            placeholder="休暇理由"
                            v-model="data.name"
                            :class="{'is-invalid' : errors.name}"
                            @keyup="errors.name = null">
                            <span v-if="errors.name" class="error invalid-feedback">
                                {{ errors.name }}
                            </span>
                    </div>

                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">閉じる</button>
            <button type="submit" class="btn btn-primary" @click="saveVacation">登録</button>
        </div>
    </div>
</template>
<script>
import api, { apiErrorHandler } from '../../global/api';
import actionLoading from '../../mixin/actionLoading';
import { showSuccess } from '../../helpers/error';

    export default {
        mixins: [actionLoading],
        props: {
            data: {},
            editMode: null,
        },
        watch: {
            ['data.id'] : function (){
                this.errors = {
                    name: '',
                };
            },
        },
        data() {
            return {
                errors: {
                    name: '',
                }
            }
        },

        methods: {
            saveVacation() {
                if (this.actionLoading) return;
                if (!this.validate()) return;
                this.setActionLoading();
                let request;
                if (this.data.id) {
                    request = api.post('reason-for-vacation/' + this.data.id, null, this.data);
                } else {
                    request = api.post('reason-for-vacation', null, this.data);
                }
                request.then(() => {
                        this.unsetActionLoading();
                        showSuccess(this.$t("Successfully saved"));
                        this.$emit('success');
                    })
                    .catch(e => {
                        apiErrorHandler(e);
                        this.unsetActionLoading();
                    });
            },
            validate() {
                let valid = true;
                if (!this.data.name) {
                    this.errors.name = this.$t('Please input name');                                 // need trans
                    valid = false;
                }
                if (this.data.name && this.data.name.length > 50) {
                    this.errors.name = this.$t('Please enter 50 characters or less');               // need trans
                    valid = false;
                }
                return valid;
            }
        }
    }
</script>
