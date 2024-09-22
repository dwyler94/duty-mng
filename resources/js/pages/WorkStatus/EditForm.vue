<template>
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="form-group">
                <label>出勤1</label>
                <div class="form-row align-items-center mt-1">
                    <div class="col-md-5">
                        <div class="d-flex is-invalid">
                            <input type="number" v-model="formData.startTimeHour1" class="form-control mr-2" :class="{'is-invalid' : errors.startTime1}"
                            @change="() => {errors.startTime1=null;}" min="0" max="23">
                            <input type="number" v-model="formData.startTimeMin1" class="form-control" :class="{'is-invalid' : errors.startTime1}"
                            @change="() => {errors.startTime1=null}" min="0" max="59">
                        </div>
                        <span v-if="errors.startTime1" class="error invalid-feedback">
                            {{ errors.startTime1 }}
                        </span>
                    </div>
                    <div class="form-control-label">⇒</div>
                    <div class="col-md-5">
                        <div class="d-flex is-invalid">
                            <input type="number" v-model="formData.endTimeHour1" class="form-control mr-2" :class="{'is-invalid' : errors.endTime1}"
                            @change="() => {errors.endTime1=null}" min="0" max="23">
                            <input type="number" v-model="formData.endTimeMin1" class="form-control" :class="{'is-invalid' : errors.endTime1}"
                            @change="() => {errors.endTime1=null}" min="0" max="59">
                        </div>
                        <span v-if="errors.endTime1" class="error invalid-feedback">
                            {{ errors.endTime1 }}
                        </span>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label>出勤2</label>
                <div class="form-row align-items-center mt-1">
                    <div class="col-md-5">
                        <div class="d-flex is-invalid">
                            <input type="number" v-model="formData.startTimeHour2" class="form-control mr-2" :class="{'is-invalid' : errors.startTime2}"
                            @change="() => {errors.startTime2=null;}" min="0" max="23">
                            <input type="number" v-model="formData.startTimeMin2" class="form-control" :class="{'is-invalid' : errors.startTime2}"
                            @change="() => {errors.startTime2=null;}" min="0" max="59">
                        </div>
                        <span v-if="errors.startTime2" class="error invalid-feedback">
                            {{ errors.startTime2 }}
                        </span>
                    </div>
                    <div class="form-control-label">⇒</div>
                    <div class="col-md-5">
                        <div class="d-flex is-invalid">
                            <input type="number" v-model="formData.endTimeHour2" class="form-control mr-2" :class="{'is-invalid' : errors.endTime2}"
                            @change="() => {errors.endTime2=null;}" min="0" max="23">
                            <input type="number" v-model="formData.endTimeMin2" class="form-control" :class="{'is-invalid' : errors.endTime2}"
                            @change="() => {errors.endTime2=null;}" min="0" max="59">
                        </div>
                        <span v-if="errors.endTime2" class="error invalid-feedback">
                            {{ errors.endTime2 }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">閉じる</button>
            <button type="submit" class="btn btn-primary" @click="saveWorkStatus">保存</button>
        </div>
    </div>
</template>
<script>
import { mapState } from 'vuex';
import api, { apiErrorHandler } from '../../global/api';
import actionLoading from '../../mixin/actionLoading';
import { showSuccess } from '../../helpers/error';
import moment from 'moment-timezone';

    export default {
        mixins: [actionLoading],
        props: {
            workStatus: {},
            userId: null,
            date: null,
        },
        computed: {
            ...mapState({
                employmentStatuses: state => state.constants.employmentStatuses
            }),
        },
        watch: {
            ['workStatus.id'] : function (){
                    this.errors = {
                        startTime1: '',
                        endTime1:   '',
                        startTime2: '',
                        endTime2:    '',
                };
                this.convertToFormData();
                this.initFormError();
            },
        },
        data() {
            return {
                formData: {
                    startTimeHour1: '',
                    startTimeMin1:  '',
                    endTimeHour1:   '',
                    endTimeMin1:    '',
                    startTimeHour2: '',
                    startTimeMin2:  '',
                    endTimeHour2:   '',
                    endTimeMin2:    '',
                },
                errors: {
                    startTime1: '',
                    endTime1:   '',
                    startTime2: '',
                    endTime2:    '',
                }
            }
        },
        methods: {

            validate() {
                let valid = true;

                this.formData.startTimeHour1 = this.setTwoDigits(this.formData.startTimeHour1);
                this.formData.startTimeMin1 = this.setTwoDigits(this.formData.startTimeMin1);
                this.formData.endTimeHour1 = this.setTwoDigits(this.formData.endTimeHour1);
                this.formData.endTimeMin1 = this.setTwoDigits(this.formData.endTimeMin1);
                this.formData.startTimeHour2 = this.setTwoDigits(this.formData.startTimeHour2);
                this.formData.startTimeMin2 = this.setTwoDigits(this.formData.startTimeMin2);
                this.formData.endTimeHour2 = this.setTwoDigits(this.formData.endTimeHour2);
                this.formData.endTimeMin2 = this.setTwoDigits(this.formData.endTimeMin2);
                // this.formData.behindTime1 = this.setTwoDigits(this.formData.behindTime1);
                // this.formData.leaveEarly1 = this.setTwoDigits(this.formData.leaveEarly1);
                // this.formData.behindTime2 = this.setTwoDigits(this.formData.behindTime2);
                // this.formData.leaveEarly2 = this.setTwoDigits(this.formData.leaveEarly2);

                if (!this.formData.startTimeHour1 && !this.formData.startTimeHour2) {
                    this.errors.startTime1 = this.$t('Please input time');                                 // need trans
                    valid = false;
                }
                if (!this.formData.startTimeMin1 && !this.formData.startTimeMin2) {
                    this.errors.startTime1 = this.$t('Please input time');                                 // need trans
                    valid = false;
                }
                if (!this.formData.endTimeHour1 && !this.formData.endTimeHour2) {
                    this.errors.endTime1 = this.$t('Please input time');                                 // need trans
                    valid = false;
                }
                if (!this.formData.endTimeMin1 && !this.formData.endTimeMin2) {
                    this.errors.endTime1 = this.$t('Please input time');                                 // need trans
                    valid = false;
                }
                if (!this.formData.startTimeHour2 && !this.formData.startTimeHour1) {
                    this.errors.startTime2 = this.$t('Please input time');                                 // need trans
                    valid = false;
                }
                if (!this.formData.startTimeMin2 && !this.formData.startTimeMin1) {
                    this.errors.startTime2 = this.$t('Please input time');                                 // need trans
                    valid = false;
                }
                if (!this.formData.endTimeHour2 && !this.formData.endTimeHour1) {
                    this.errors.endTime2 = this.$t('Please input time');                                 // need trans
                    valid = false;
                }
                if (!this.formData.endTimeMin2 && !this.formData.endTimeMin1) {
                    this.errors.endTime2 = this.$t('Please input time');                                 // need trans
                    valid = false;
                }
                if (this.formData.startTimeHour1 + ":" + this.formData.startTimeMin1 <= this.formData.startTimeHour2 + ":" + this.formData.startTimeMin2) {
                    if(this.formData.endTimeHour1 + ":" + this.formData.endTimeMin1 >= this.formData.startTimeHour2 + ":" + this.formData.startTimeMin2) {
                        this.errors.startTime2 = this.$t('Please select other time in order to avoid time overlap');                                 // need trans
                        valid = false;
                    }
                }
                if (this.formData.startTimeHour1 + ":" + this.formData.startTimeMin1 >= this.formData.startTimeHour2 + ":" + this.formData.startTimeMin2) {
                    if(this.formData.endTimeHour1 + ":" + this.formData.endTimeMin1 <= this.formData.endTimeHour2 + ":" + this.formData.endTimeMin2) {
                        this.errors.startTime1 = this.$t('Please select other time in order to avoid time overlap');                                 // need trans
                        valid = false;
                    }
                }
                // if (this.formData.startTimeHour1 > this.formData.endTimeHour1) {
                //     this.errors.startTime1 = this.$t('start time must be earlier than end time');             //need trans
                //     this.errors.endTime1 = this.$t('start time must be earlier than end time');
                //     valid = false;
                // }
                // if (this.formData.startTimeHour2 > this.formData.endTimeHour2) {
                //     this.errors.startTime2 = this.$t('start time must be earlier than end time');             //need trans
                //     this.errors.endTime1 = this.$t('start time must be earlier than end time');
                //     valid = false;
                // }

                return valid;
            },
            saveWorkStatus() {
                if (this.actionLoading) return;
                if(!this.validate()) return;

                let requestData = {};
                if((this.formData.startTimeHour1 && this.formData.startTimeMin1 &&
                    this.formData.endTimeHour1 && this.formData.endTimeMin1)) {
                    // requestData['behind_time_1'] = this.formData.behindTime1 ? this.formData.behindTime1 : null;
                    // requestData['leave_early_1'] = this.formData.leaveEarly1 ? this.formData.leaveEarly1 : null;
                    requestData['leave_time_1'] = moment(this.workStatus.leaveTime1 ? this.workStatus.leaveTime1 : this.date).format('YYYY-MM-DD') + ' ' + this.formData.endTimeHour1 + ':' + this.formData.endTimeMin1 + ':00';
                    requestData['commuting_time_1'] = moment(this.workStatus.commutingTime1 ? this.workStatus.commutingTime1 : this.date).format('YYYY-MM-DD') + ' ' + this.formData.startTimeHour1 + ':' + this.formData.startTimeMin1 + ':00';
                }
                if((this.formData.startTimeHour2 && this.formData.startTimeMin2 &&
                    this.formData.endTimeHour2 && this.formData.endTimeMin2)) {
                    // requestData['behind_time_2'] = this.formData.behindTime2 ? this.formData.behindTime2 : null;
                    // requestData['leave_early_2'] = this.formData.leaveEarly2 ? this.formData.leaveEarly2 : null;
                    requestData['leave_time_2'] = moment(this.workStatus.leaveTime2 ? this.workStatus.leaveTime2 : this.date).format('YYYY-MM-DD') + ' ' + this.formData.endTimeHour2 + ':' + this.formData.endTimeMin2 + ':00';
                    requestData['commuting_time_2'] = moment(this.workStatus.commutingTime2 ? this.workStatus.commutingTime2 : this.date).format('YYYY-MM-DD') + ' ' + this.formData.startTimeHour2 + ':' + this.formData.startTimeMin2 + ':00';
                }

                this.setActionLoading();
                let request;
                if(this.workStatus.id) {
                    request = api.put('attend/' + this.workStatus.id, null, requestData);
                } else {
                    requestData['user_id'] = this.userId;
                    requestData['date'] = this.date;
                    request = api.post('attend', null, requestData);
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
            convertToFormData() {
                this.initializeFormData();

                if (this.workStatus) {
                    this.formData.startTimeHour1 = this.workStatus.commutingTime1 ? moment(this.workStatus.commutingTime1).tz('Asia/Tokyo').format('HH') : '';
                    this.formData.startTimeMin1 = this.workStatus.commutingTime1 ? moment(this.workStatus.commutingTime1).tz('Asia/Tokyo').format('mm') : '';
                    this.formData.endTimeHour1 = this.workStatus.leaveTime1 ? moment(this.workStatus.leaveTime1).tz('Asia/Tokyo').format('HH') : '';
                    this.formData.endTimeMin1 = this.workStatus.leaveTime1 ? moment(this.workStatus.leaveTime1).tz('Asia/Tokyo').format('mm') : '';
                    // this.formData.behindTime1 = this.workStatus.behindTime1 ? this.workStatus.behindTime1 : '';
                    // this.formData.leaveEarly1 = this.workStatus.leaveEarly1 ? this.workStatus.leaveEarly1 : '';
                    this.formData.startTimeHour2 = this.workStatus.commutingTime2 ? moment(this.workStatus.commutingTime2).tz('Asia/Tokyo').format('HH') : '';
                    this.formData.startTimeMin2 = this.workStatus.commutingTime2 ? moment(this.workStatus.commutingTime2).tz('Asia/Tokyo').format('mm') : '';
                    this.formData.endTimeHour2 = this.workStatus.leaveTime2 ? moment(this.workStatus.leaveTime2).tz('Asia/Tokyo').format('HH') : '';
                    this.formData.endTimeMin2 = this.workStatus.leaveTime2 ? moment(this.workStatus.leaveTime2).tz('Asia/Tokyo').format('mm') : '';
                    // this.formData.behindTime2 = this.workStatus.behindTime2 ? this.workStatus.behindTime2 : '';
                    // this.formData.leaveEarly2 = this.workStatus.leaveEarly2 ? this.workStatus.leaveEarly2 : '';
                }
            },
            initializeFormData() {
                this.formData = {
                    startTimeHour1: '',
                    startTimeMin1:  '',
                    endTimeHour1:   '',
                    endTimeMin1:    '',
                    startTimeHour2: '',
                    startTimeMin2:  '',
                    endTimeHour2:   '',
                    endTimeMin2:    '',
                };
            },
            initFormError() {
                this.errors = {
                    startTime1: '',
                    endTime1:   '',
                    startTime2: '',
                    endTime2:    '',
                }
            },
            setTwoDigits(number) {
                if(!number) return;
                return ('0' + number).slice(-2);
            }
        }
    }
</script>
