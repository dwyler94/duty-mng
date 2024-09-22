<template>
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">申請</h5>
            <!-- <h5 class="modal-title" v-show="editmode">再申請</h5> -->
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="form-group">
                <label v-for="item in applicationClasses" :key="item.id" class="mr-2">
                    <input v-model="formData.applicationClassId" type="radio" :value="item.id" :disabled="formData.isApproved">
                    <label class="ml-auto">{{item.name}}</label>
                </label>
            </div>

            <div class="form-group">
                <i class="fas fa-square-full"></i>
                <label>申請日時</label>
                <div>{{ formData.applicationDate ? moment(formData.applicationDate).tz('asia/Tokyo').format('MM月 dd日 HH:mm') : currentTime() }}</div>
            </div>
            <div class="form-group">
                <i class="fas fa-square-full"></i>
                <label>修正時間</label>
                <div class="form-row align-items-center">
                    <div class="col-md-5">
                        <div class="d-flex is-invalid">
                            <input v-model="formData.startTimeHour" type="number" min="0" max="23" :class="{'is-invalid' : errors.startTime}" @change="() => {errors.startTime=null}"
                                class="form-control mr-2" :disabled="formData.isApproved">
                            <input v-model="formData.startTimeMin" type="number" min="0" max="59" :class="{'is-invalid' : errors.startTime}" @change="() => {errors.startTime=null}"
                                class="form-control" :disabled="formData.isApproved">
                        </div>
                        <span v-if="errors.startTime" class="error invalid-feedback">
                            {{ errors.startTime }}
                        </span>
                    </div>
                    <div class="form-control-label">⇒</div>
                    <div class="col-md-5">
                        <div class="d-flex is-invalid">
                            <input v-model="formData.endTimeHour" type="number" min="0" max="23" :class="{'is-invalid' : errors.endTime}" @change="() => {errors.endTime=null}"
                                class="form-control mr-2" :disabled="formData.isApproved">
                            <input v-model="formData.endTimeMin" type="number" min="0" max="59" :class="{'is-invalid' : errors.endTime}" @change="() => {errors.endTime=null}"
                                class="form-control" :disabled="formData.isApproved">
                        </div>
                        <span v-if="errors.endTime" class="error invalid-feedback">
                            {{ errors.endTime }}
                        </span>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <i class="fas fa-square-full"></i>
                <label>申請理由</label>
                <input v-model="formData.reason" type="text" class="form-control" :class="{'is-invalid' : errors.reason}" @change="() => {errors.reason=null}" :disabled="formData.isApproved">
                <span v-if="errors.reason" class="error invalid-feedback">
                    {{ errors.reason }}
                </span>
            </div>

        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">閉じる</button>
            <button type="submit" class="btn btn-primary" @click="saveApp" :disabled="formData.isApproved">申請</button>
        </div>
    </div>
</template>
<script>
import moment from 'moment-timezone';
import { mapState } from 'vuex';
import api, { apiErrorHandler } from '../../global/api';
import actionLoading from '../../mixin/actionLoading';
import { showSuccess } from '../../helpers/error';

    export default {
        mixins: [actionLoading],
        props: {
            applications: [],
            attendanceId: null,
            dayIndex: null,
            month: '',
        },
        watch: {
            ['applications'] : function (){
                this.errors = {
                    applicationClassId: null,
                    startTime: null,
                    endTime: null,
                    reason: null,
                },
                this.convertToFormData();
                this.initFormError();
            },
        },
        data() {
            return {
                errors: {
                    applicationClassId: null,
                    startTime: '',
                    endTime: '',
                    reason:  '',
                },
                formData: {
                    applicationClassId: 1,
                    applicationDate: '',
                    startTimeHour: '',
                    startTimeMin:  '',
                    endTimeHour:   '',
                    endTimeMin:    '',
                    reason:        '',
                    attendanceId: null,
                    isApproved: null,
                },
            }
        },
        computed: {
            ...mapState({
                applicationClasses: state => state.constants.applicationClasses
            })
        },
        methods: {
            saveApp() {
                if (this.actionLoading) return;
                if (!this.validate()) return;
                const requestData = {
                    'application_class_id': this.formData.applicationClassId,
                    'attendance_id': this.formData.attendanceId,
                    'application_date': this.formData.applicationDate,
                    'reason': this.formData.reason,
                    'time_before_correction': moment(new Date()).format('YYYY-MM-DD') + " " + ("0" + this.formData.startTimeHour).slice(-2) + ":" + ("0" + this.formData.startTimeMin).slice(-2) + ":00",
                    'time_after_correction': moment(new Date()).format('YYYY-MM-DD') + " " + ("0" + this.formData.endTimeHour).slice(-2) + ":" + ("0" + this.formData.endTimeMin).slice(-2) + ":00"
                };
                this.setActionLoading();
                let request;
                if (this.applications && this.applications.length > 0) {
                    request = api.put('application/' + this.applications[0].id, null, requestData);
                } else {
                    requestData['application_date'] = this.getDate(this.dayIndex);
                    request = api.post('application', null, requestData);
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
            currentTime(){
                var today = new Date();
                var month = today.getMonth() + 1;
                var day = today.getDate();
                return month + "月" + day + "日 "
                + today.getHours() + ":"
                + ('0' + today.getMinutes()).slice(-2);
            },
            getDate(index) {
                const date = this.month + '-' + ('0' + index).slice(-2);
                return date;
            },
            validate() {
                let valid = true;
                if (!this.formData.applicationClassId) {
                    this.errors.applicationClassId = this.$t('Please select office');                            // need trans
                    valid = false;
                }
                if (!this.formData.reason) {
                    this.errors.reason = this.$t('Please input reason');                                 // need trans
                    valid = false;
                }
                if (!this.formData.startTimeHour || !this.formData.startTimeMin) {
                    this.errors.startTime = this.$t('Please input updatedBeforeTime');                        // need trans
                    valid = false;
                }
                if (this.formData.startTimeHour < 0 || this.formData.startTimeHour > 23) {
                    this.errors.startTime = this.$t('Invalid time format');
                    valid = false;
                }
                if (this.formData.startTimeMin < 0 || this.formData.startTimeMin > 59) {
                    this.errors.startTime = this.$t('Invalid time format');
                    valid = false;
                }
                if (this.formData.endTimeHour < 0 || this.formData.endTimeHour > 23) {
                    this.errors.endTime = this.$t('Invalid time format');
                    valid = false;
                }
                if (this.formData.endTimeMin < 0 || this.formData.endTimeMin > 59) {
                    this.errors.endTime = this.$t('Invalid time format');
                    valid = false;
                }
                if (!this.formData.endTimeHour || !this.formData.endTimeMin) {
                    this.errors.endTime = this.$t('Please input updatedAfterTime');                            // need trans
                    valid = false;
                }
                // if (this.formData.startTimeHour > this.formData.endTimeHour) {
                //     this.errors.startTime = this.$t('start time must be earlier than end time');             //need trans
                //     this.errors.endTime = this.$t('start time must be earlier than end time');
                //     valid = false;
                // }
                // if (this.formData.startTimeHour == this.formData.endTimeHour && this.formData.startTimeMin > this.formData.endTimeMin) {
                //     this.errors.startTime = this.$t('start time must be earlier than end time');             //need trans
                //     this.errors.endTime = this.$t('start time must be earlier than end time');
                //     valid = false;
                // }
                return valid;
            },
            convertToFormData() {
                this.initializeFormData();
                if (this.applications) {
                    this.formData.startTimeHour = this.applications[0] ? moment(this.applications[0].timeBeforeCorrection).tz('asia/Tokyo').format('HH') : '';
                    this.formData.startTimeMin = this.applications[0] ? moment(this.applications[0].timeBeforeCorrection).tz('asia/Tokyo').format('mm') : '';
                    this.formData.endTimeHour = this.applications[0] ? moment(this.applications[0].timeAfterCorrection).tz('asia/Tokyo').format('HH') : '';
                    this.formData.endTimeMin = this.applications[0] ? moment(this.applications[0].timeAfterCorrection).tz('asia/Tokyo').format('mm') : '';
                    this.formData.reason = this.applications[0] ? this.applications[0].reason : '';
                    this.formData.applicationDate = this.applications[0] ? this.applications[0].applicationDate : '';
                    this.formData.isApproved = this.applications[0] ? this.applications[0].isApproved : null;
                }

                this.formData.attendanceId = this.attendanceId;

                if(this.applications)
                    this.formData.applicationClassId = !this.applications[0] ? 1 : this.applications[0].applicationClassId;
                else
                    this.formData.applicationClassId = 1;
            },
            initializeFormData() {
                this.formData = {
                    applicationClassId: 1,
                    applicationDate: '',
                    startTimeHour: '',
                    startTimeMin:  '',
                    endTimeHour:   '',
                    endTimeMin:    '',
                    reason:        '',
                    attendanceId:   null,
                    applicationDate: '',
                    isApproved: null
                };
            },
            initFormError() {
                this.errors = {
                    applicationClassId: null,
                    startTime: '',
                    endTime: '',
                    reason:  '',
                }
            },
        }
    }
</script>
