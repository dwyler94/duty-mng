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
                <div class="form-row">
                    <div class="col-md-3">
                        <input type="text" name="region_name"
                        class="form-control"
                        placeholder="エリア名入力"
                        v-model="data.name"
                        :class="{'is-invalid' : errors.name}"
                        @keyup="errors.name = null">
                        <span v-if="errors.name" class="error invalid-feedback">
                            {{ errors.name }}
                        </span>
                    </div>
                    <div class="col-md-8">
                        <div class="form-row">
                            <template v-for="office in offices">
                                <div class="col-md-4" :key="office.id" :class="{'is-invalid' : errors.offices}">
                                    <div class="form-row">
                                        <div class="col-md-1">
                                            <input type="checkbox" name="office_name" :value="office.id" v-model="data.offices"
                                            :disabled="selectedOffices.find(e => e.id === office.id) && !data.offices.includes(office.id)"
                                            @click="onOfficeChange(office.id)"
                                            @change="errors.offices = null">
                                        </div>
                                        <div class="col-md-10">
                                            <label class="ml-auto">{{office.name}}</label>
                                        </div>
                                    </div>
                                </div>
                            </template>
                            <span v-if="errors.offices" class="error invalid-feedback">
                                {{ errors.offices }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">閉じる</button>
            <button type="submit" class="btn btn-primary" @click="saveRegion">登録</button>
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
            offices: {},
            selectedOffices: {},
            editMode: null
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
            saveRegion() {
                if (this.actionLoading) return;
                if (!this.validate()) return;
                this.setActionLoading();
                let request;
                if (this.data.id) {
                    request = api.post('region/' + this.data.id, null, this.data);
                } else {
                    request = api.post('region', null, this.data);
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
                    this.errors.name = this.$t('Please enter 50 characters or less');                                 // need trans
                    valid = false;
                }
                return valid;
            },
            onOfficeChange(officeId) {
                const index = this.selectedOffices.findIndex(item => item.id == officeId);
                if(index != -1){
                    this.selectedOffices.splice(index, 1);
                }
            }
        }
    }
</script>
