<template>
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="form-group">
                <label class="mr-2">
                    <input type="radio" v-model="formData.downloadType" :value="0" @change="displayDate(0)">
                    <label class="ml-auto">表示月</label>
                </label>
                <label class="mr-2">
                    <input type="radio" v-model="formData.downloadType" :value="1" @change="displayDate(1)">
                    <label class="ml-auto">期間指定</label>
                </label>
            </div>
            <div class="form-group" v-if="formData.downloadType == 0">
                <h3 class="mr-2">
                    <label class="ml-auto">{{changeFormatMonth(formData.selectedMonth)}}</label>
                </h3>
            </div>
            <template v-else>
                <div class="form-group">
                    <select class="form-control select" v-model="formData.firstMonth" :class="{'is-invalid' : errors.firstMonth}" @change="errors.firstMonth = null; errors.lastMonth = null;">
                        <option v-for="month in csvMonths" :key="'firstMonth' + month" :value="month">{{changeFormatMonth(month)}}</option>
                    </select>
                    <span v-if="errors.firstMonth" class="error invalid-feedback">
                        {{ errors.firstMonth }}
                    </span>
                </div>
                <div style="text-align:-webkit-center;"><h1 style="transform: rotate(90deg); text-align:center; width:10%">～</h1></div>
                <div class="form-group">
                    <select class="form-control select" v-model="formData.lastMonth" :class="{'is-invalid' : errors.lastMonth}" @change="errors.firstMonth = null; errors.lastMonth = null;">
                        <option v-for="month in csvMonths" :key="'lastMonth' + month" :value="month">{{changeFormatMonth(month)}}</option>
                    </select>
                    <span v-if="errors.lastMonth" class="error invalid-feedback">
                        {{ errors.lastMonth }}
                    </span>
                </div>
            </template>
        </div>
        <div class="modal-footer justify-content-center">
            <button type="button" class="btn btn-secondary" @click="outputExcel()">出力</button>
        </div>
    </div>
</template>
<script>

import moment from 'moment-timezone';
import { mapState } from 'vuex';
import api, { apiErrorHandler } from '../../global/api';
import actionLoading from '../../mixin/actionLoading';
import { showSuccess } from '../../helpers/error';
import LocalStorage from '../../helpers/localStorage';

    export default {
        mixins: [actionLoading],
        props: {
            csvMonths: [],
            currentMonth: '',
            officeId: null,
        },
        computed: {
            ...mapState({
                applicationClasses: state => state.constants.applicationClasses
            }),
        },
        watch: {
            'csvMonths' : function (){
                    this.errors = {
                        firstMonth: null,
                        lastMonth: null,
                };
                this.convertToFormData();
                this.initFormError();
            },
        },
        data() {
            return {
                formData: {
                    downloadType: 0,
                    firstMonth: '',
                    lastMonth: '',
                    selectedMonth: '',
                },
                errors: {
                    firstMonth: null,
                    lastMonth: null,
                },
            }
        },
        methods: {
            displayDate(value) {
                this.formData.downloadType = value;
            },
            changeFormatMonth(month) {
                let displayMonth = moment(month, "YYYY-MM").format("YYYY年　M月");
                return displayMonth;
            },
            outputExcel() {
                // if (!this.officeId) return;

                if (!this.validate()) return;
                let request = {};
                if (!this.formData.downloadType) {
                    request['start'] = this.formData.selectedMonth.replace('-', '');
                } else {
                   request['end'] = this.formData.lastMonth.replace('-', '');
                }
                if (this.officeId) {
                    request['officeId'] = this.officeId;
                }

                api.get('work-total/csv-available', null, request)
                    .then(response => {
                        this.unsetActionLoading();
                        let apiUrl = '/work-total/csv';
                        if (!this.formData.downloadType) {
                            apiUrl += '?start=' + this.formData.selectedMonth.replace('-', '');
                        } else {
                            apiUrl += '?start=' + this.formData.firstMonth.replace('-', '') + '&end=' + this.formData.lastMonth.replace('-', '');
                        }
                        if (this.officeId) {
                            apiUrl += '&office_id=' + this.officeId;
                        }
                        location.href = apiUrl + '&token=' + LocalStorage.getToken();
                    })
                    .catch(e => {
                        this.unsetActionLoading();
                        apiErrorHandler(e);
                    });

                // request = api.get('application/approve/' + this.application.id);
                // request.then(() => {
                //         this.unsetActionLoading();
                //         showSuccess(this.$t("Successfully saved"));
                //         this.$emit('success');
                //     })
                //     .catch(e => {
                //         apiErrorHandler(e);
                //         this.unsetActionLoading();
                //     });
            },
            convertToFormData() {
                this.initializeFormData();
                this.formData.selectedMonth = this.currentMonth;
                if (this.csvMonths && this.csvMonths.length > 0) {
                    this.formData.firstMonth = this.csvMonths[0];
                    this.formData.lastMonth = this.csvMonths[1];
                }
            },
            initializeFormData() {
                this.formData = {
                    downloadType: 0,
                    firstMonth: '',
                    lastMonth: '',
                    selectedMonth: '',
                };
            },
            initFormError() {
                this.errors = {
                    firstMonth: '',
                    lastMonth: '',
                }
            },
            validate() {
                let valid = true;
                if (this.formData.downloadType == 1 && this.formData.firstMonth >= this.formData.lastMonth) {
                    this.errors.firstMonth = this.$t('firstMonth must be later than lastMonth');
                    this.errors.lastMonth = this.$t('firstMonth must be later than lastMonth');                                 // need trans
                    valid = false;
                }
                return valid;
            },
        }
    }
</script>
