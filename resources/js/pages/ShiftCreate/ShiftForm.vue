<template>
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="form-group mb-0">
                <label v-for="item in employmentStatuses" :key="item.id" class="mr-2">
                    <input type="radio" :value="item.id" v-model="employee.employmentStatusId" disabled>
                    <label class="ml-auto">{{item.name}}</label>
                </label>
            </div>
            <div class="form-group">
                <label>出勤1</label>
                <select class="form-control" v-model="formData.workingHourId1" @change="setWorkingHourToInput1" :disabled="!!formData.vacationReasonId">
                    <option></option>
                    <option v-for="workingHour in workingHours" :key="workingHour.id" :value="workingHour.id">
                        {{workingHour.name}} {{workingHour.startTime}} ~ {{workingHour.endTime}}
                    </option>
                </select>
                <div class="form-row align-items-center mt-3">
                    <div class="col-md-5">
                        <div class="d-flex is-invalid">
                            <input type="number" v-model="formData.startTimeHour1" class="form-control mr-2" :class="{'is-invalid' : errors.startTime1}"
                            @change="() => {formData.workingHourId1 = null; errors.startTime1=null;}" :disabled="!!formData.vacationReasonId" min="0" max="23">
                            <input type="number" v-model="formData.startTimeMin1" class="form-control" :class="{'is-invalid' : errors.startTime1}"
                            @change="() => {formData.workingHourId1 = null; errors.startTime1=null}" :disabled="!!formData.vacationReasonId" min="0" max="59">
                        </div>
                        <span v-if="errors.startTime1" class="error invalid-feedback">
                            {{ errors.startTime1 }}
                        </span>
                    </div>
                    <div class="form-control-label">⇒</div>
                    <div class="col-md-5">
                        <div class="d-flex is-invalid">
                            <input type="number" v-model="formData.endTimeHour1" class="form-control mr-2" :class="{'is-invalid' : errors.endTime1}"
                            @change="() => {formData.workingHourId1 = null; errors.endTime1=null}" :disabled="!!formData.vacationReasonId" min="0" max="23">
                            <input type="number" v-model="formData.endTimeMin1" class="form-control" :class="{'is-invalid' : errors.endTime1}"
                            @change="() => {formData.workingHourId1 = null; errors.endTime1=null}" :disabled="!!formData.vacationReasonId" min="0" max="59">
                        </div>
                        <span v-if="errors.endTime1" class="error invalid-feedback">
                            {{ errors.endTime1 }}
                        </span>
                    </div>
                </div>
                <label>休憩</label>
                <div class="form-row align-items-center">
                    <div class="col-md-5">
                        <div class="d-flex is-invalid">
                            <input type="number" v-model="formData.restStartTimeHour1" class="form-control mr-2" :class="{'is-invalid' : errors.restStartTime1}"
                            @change="() => {errors.restStartTime1=null}" :disabled="formData.vacationReasonId !== 0" min="0" max="23">
                            <input type="number" v-model="formData.restStartTimeMin1" class="form-control" :class="{'is-invalid' : errors.restStartTime1}"
                            @change="() => {errors.restStartTime1=null}" :disabled="formData.vacationReasonId !== 0" min="0" max="59">
                        </div>
                         <span v-if="errors.restStartTime1" class="error invalid-feedback">
                            {{ errors.restStartTime1 }}
                        </span>
                    </div>
                    <div class="form-control-label">⇒</div>
                    <div class="col-md-5">
                        <div class="d-flex is-invalid">
                            <input type="number" v-model="formData.restEndTimeHour1" class="form-control mr-2" :class="{'is-invalid' : errors.restEndTime1}"
                            @change="() => {errors.restEndTime1=null}" :disabled="formData.vacationReasonId !== 0" min="0" max="23">
                            <input type="number" v-model="formData.restEndTimeMin1" class="form-control" :class="{'is-invalid' : errors.restEndTime1}"
                            @change="() => {errors.restEndTime1=null}" :disabled="formData.vacationReasonId !== 0" min="0" max="59">
                        </div>
                        <span v-if="errors.restEndTime1" class="error invalid-feedback">
                            {{ errors.restEndTime1 }}
                        </span>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label>出勤2</label>
                <select class="form-control" v-model="formData.workingHourId2" @change="setWorkingHourToInput2" :disabled="formData.vacationReasonId !== 0">
                    <option></option>
                    <option v-for="workingHour in workingHours" :key="workingHour.id" :value="workingHour.id">
                        {{workingHour.name}} {{workingHour.startTime}} ~ {{workingHour.endTime}}
                    </option>
                </select>
                <div class="form-row align-items-center mt-3">
                    <div class="col-md-5">
                        <div class="d-flex is-invalid">
                            <input type="number" v-model="formData.startTimeHour2" class="form-control mr-2" :class="{'is-invalid' : errors.startTime2}"
                            @change="() => {formData.workingHourId2 = null; errors.startTime2=null;}" :disabled="formData.vacationReasonId !== 0" min="0" max="23">
                            <input type="number" v-model="formData.startTimeMin2" class="form-control" :class="{'is-invalid' : errors.startTime2}"
                            @change="() => {formData.workingHourId2 = null; errors.startTime2=null;}" :disabled="formData.vacationReasonId !== 0" min="0" max="59">
                        </div>
                        <span v-if="errors.startTime2" class="error invalid-feedback">
                            {{ errors.startTime2 }}
                        </span>
                    </div>
                    <div class="form-control-label">⇒</div>
                    <div class="col-md-5">
                        <div class="d-flex is-invalid">
                            <input type="number" v-model="formData.endTimeHour2" class="form-control mr-2" :class="{'is-invalid' : errors.endTime2}"
                            @change="() => {formData.workingHourId2 = null; errors.endTime2=null;}" :disabled="formData.vacationReasonId !== 0" min="0" max="23">
                            <input type="number" v-model="formData.endTimeMin2" class="form-control" :class="{'is-invalid' : errors.endTime2}"
                            @change="() => {formData.workingHourId2 = null; errors.endTime2=null;}" :disabled="formData.vacationReasonId !== 0" min="0" max="59">
                        </div>
                        <span v-if="errors.endTime2" class="error invalid-feedback">
                            {{ errors.endTime2 }}
                        </span>
                    </div>
                </div>
                <label>休憩</label>
                <div class="form-row align-items-center">
                    <div class="col-md-5">
                        <div class="d-flex is-invalid">
                            <input type="number" v-model="formData.restStartTimeHour2" class="form-control mr-2" :class="{'is-invalid' : errors.restStartTime2}"
                            @change="() => {errors.restStartTime2=null}" :disabled="formData.vacationReasonId !== 0" min="0" max="23">

                            <input type="number" v-model="formData.restStartTimeMin2" class="form-control" :class="{'is-invalid' : errors.restStartTime2}"
                            @change="() => {errors.restStartTime2=null}" :disabled="formData.vacationReasonId !== 0" min="0" max="59">
                        </div>
                        <span v-if="errors.restStartTime2" class="error invalid-feedback">
                            {{ errors.restStartTime2 }}
                        </span>
                    </div>
                    <div class="form-control-label">⇒</div>
                    <div class="col-md-5">
                        <div class="d-flex is-invalid">
                            <input type="number" v-model="formData.restEndTimeHour2" class="form-control mr-2" :class="{'is-invalid' : errors.restEndTime2}"
                            @change="() => {errors.restEndTime2=null}" :disabled="formData.vacationReasonId !== 0" min="0" max="23">
                            <input type="number" v-model="formData.restEndTimeMin2" class="form-control" :class="{'is-invalid' : errors.restEndTime2}"
                            @change="() => {errors.restEndTime2=null}" :disabled="formData.vacationReasonId !== 0" min="0" max="59">
                        </div>
                        <span v-if="errors.restEndTime2" class="error invalid-feedback">
                            {{ errors.restEndTime2 }}
                        </span>
                    </div>
                </div>
            </div>
            <div class="form-group mb-0">
                <label class="mr-2">
                    <input type="radio" v-model="formData.vacationReasonId" :value="$vacationId.WORK">
                    <label class="ml-auto">勤務</label>
                </label>
                <label class="mr-2">
                    <input type="radio" v-model="formData.vacationReasonId" :value="$vacationId.ANNUAL_PAID">
                    <label class="ml-auto">年次有休</label>
                </label>
                <label class="mr-2">
                    <input type="radio" v-model="formData.vacationReasonId" :value="$vacationId.SPECIAL_PAID">
                    <label class="ml-auto">特休有給</label>
                </label>
                <label class="mr-2">
                    <input type="radio" v-model="formData.vacationReasonId" :value="$vacationId.SPECIAL_UNPAID">
                    <label class="ml-auto">特休無給</label>
                </label>
                <label class="mr-2">
                    <input type="radio" v-model="formData.vacationReasonId" :value="$vacationId.OTHER_UNPAID">
                    <label class="ml-auto">その他無給</label>
                </label>
                <!-- <label v-for="item in vacations" :key="item.id" class="mr-2">
                    <input type="radio" v-model="formData.vacationReasonId" :value="item.id">
                    <label class="ml-auto">{{item.name}}</label>
                </label> -->
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-danger float-left" style="margin-right: 55%;" v-if="shifts[0] || shifts[1]" @click="deleteShift">削除</button>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">閉じる</button>
            <button type="submit" class="btn btn-primary" @click="saveWorkingHours">登録</button>
        </div>
    </div>
</template>
<script>
import { mapState } from 'vuex';
import api, { apiErrorHandler } from '../../global/api';
import actionLoading from '../../mixin/actionLoading';
import { showSuccess } from '../../helpers/error';

    export default {
        mixins: [actionLoading],
        props: {
            employee: {},
            workingHours: [],
            vacations: [],
            shifts: [],
            date: '',
            officeId: null,
        },
        computed: {
            ...mapState({
                restDeductions: state => state.constants.restDeductions,
                employmentStatuses: state => state.constants.employmentStatuses
            }),
        },
        watch: {
            ['formData.id'] : function (){
                    this.errors = {
                        startTime1: '',
                        endTime1:   '',
                        startTime2: '',
                        endTime2:    '',
                        restStartTime1: '',
                        restEndTime1:    '',
                        restStartTime2: '',
                        restEndTime2:   '',
                        vacationReasonId:   null
                };
            },
            employee: function() {
                this.convertToFormData();
                this.initFormError();
            },
            date: function() {
                this.convertToFormData();
                this.initFormError();
            }
        },
        data() {
            return {
                formData: {
                    workingHourId1: null,
                    startTimeHour1: '',
                    startTimeMin1:  '',
                    endTimeHour1:   '',
                    endTimeMin1:    '',
                    workingHourId2: null,
                    startTimeHour2: '',
                    startTimeMin2:  '',
                    endTimeHour2:   '',
                    endTimeMin2:    '',
                    restStartTimeHour1: '',
                    restStartTimeMin1:  '',
                    restEndTimeHour1:   '',
                    restEndTimeMin1:    '',
                    restStartTimeHour2: '',
                    restStartTimeMin2:  '',
                    restEndTimeHour2:   '',
                    restEndTimeMin2:    '',
                    vacationReasonId:   0,
                },
                errors: {
                    startTime1: '',
                    endTime1:   '',
                    startTime2: '',
                    endTime2:    '',
                    restStartTime1: '',
                    restEndTime1:    '',
                    restStartTime2: '',
                    restEndTime2:   '',
                    vacationReasonId:   null
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

                this.formData.startTimeHour1 = this.setTwoDigits(this.formData.startTimeHour1);
                this.formData.startTimeMin1 = this.setTwoDigits(this.formData.startTimeMin1);
                this.formData.endTimeHour1 = this.setTwoDigits(this.formData.endTimeHour1);
                this.formData.endTimeMin1 = this.setTwoDigits(this.formData.endTimeMin1);
                this.formData.startTimeHour2 = this.setTwoDigits(this.formData.startTimeHour2);
                this.formData.startTimeMin2 = this.setTwoDigits(this.formData.startTimeMin2);
                this.formData.endTimeHour2 = this.setTwoDigits(this.formData.endTimeHour2);
                this.formData.endTimeMin2 = this.setTwoDigits(this.formData.endTimeMin2);
                this.formData.restStartTimeHour1 = this.setTwoDigits(this.formData.restStartTimeHour1);
                this.formData.restStartTimeMin1 = this.setTwoDigits(this.formData.restStartTimeMin1);
                this.formData.restEndTimeHour1 = this.setTwoDigits(this.formData.restEndTimeHour1);
                this.formData.restEndTimeMin1 = this.setTwoDigits(this.formData.restEndTimeMin1);
                this.formData.restStartTimeHour2 = this.setTwoDigits(this.formData.restStartTimeHour2);
                this.formData.restStartTimeMin2 = this.setTwoDigits(this.formData.restStartTimeMin2);
                this.formData.restEndTimeHour2 = this.setTwoDigits(this.formData.restEndTimeHour2);
                this.formData.restEndTimeMin2 = this.setTwoDigits(this.formData.restEndTimeMin2);

                if(this.formData.vacationReasonId && this.formData.vacationReasonId !== 0) return true;
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
                // if (this.formData.restStartTimeHour1 && this.formData.restStartTimeHour1 > this.formData.restEndTimeHour1) {
                //     this.errors.restStartTime1 = this.$t('start time must be earlier than end time');             //need trans
                //     this.errors.restEndTime1 = this.$t('start time must be earlier than end time');
                //     valid = false;
                // }
                // if (this.formData.restStartTimeHour2 && this.formData.restStartTimeHour2 > this.formData.restEndTimeHour2) {
                //     this.errors.restStartTime2 = this.$t('start time must be earlier than end time');             //need trans
                //     this.errors.restEndTime2 = this.$t('start time must be earlier than end time');
                //     valid = false;
                // }
                // if (this.formData.restStartTimeHour1 && this.formData.restEndTimeHour1 && this.formData.restStartTimeHour1 == this.formData.restEndTimeHour1) {
                //     if(this.formData.restStartTimeMin1 && this.formData.restEndTimeMin1 && this.formData.restStartTimeMin1 > this.formData.restEndTimeMin1) {
                //         this.errors.restStartTime1 = this.$t('start time must be earlier than end time');             //need trans
                //         this.errors.restEndTime1 = this.$t('start time must be earlier than end time');
                //         valid = false;
                //     }
                // }
                // if (this.formData.restStartTimeHour2 && this.formData.restEndTimeHour2 && this.formData.restStartTimeHour2 == this.formData.restEndTimeHour2) {
                //     if(this.formData.restStartTimeMin2 && this.formData.restEndTimeMin2 && this.formData.restStartTimeMin2 > this.formData.restEndTimeMin2) {
                //         this.errors.restStartTime2 = this.$t('start time must be earlier than end time');             //need trans
                //         this.errors.restEndTime2= this.$t('start time must be earlier than end time');
                //         valid = false;
                //     }
                // }

                if (this.formData.restStartTimeHour1 && this.formData.restStartTimeHour1 < this.formData.startTimeHour1) {
                    this.errors.restStartTime1 = this.$t('rest time must be in range of schedule');                 //need trans
                    valid = false;
                }
                if (this.formData.restStartTimeHour2 && this.formData.restStartTimeHour2 < this.formData.startTimeHour2) {
                    this.errors.restStartTime2 = this.$t('rest time must be in range of schedule');                 //need trans
                    valid = false;
                }

                // if (this.formData.restStartTimeHour1 && this.formData.restStartTimeHour1 == this.formData.startTimeHour1) {
                //     if(this.formData.restStartTimeMin1 && this.formData.restStartTimeMin1 < this.formData.startTimeMin1) {
                //         this.errors.restStartTime1 = this.$t('start time must be earlier than end time');             //need trans
                //         valid = false;
                //     }
                // }
                // if (this.formData.restStartTimeHour2 && this.formData.restStartTimeHour2 == this.formData.startTimeHour2) {
                //     if(this.formData.restStartTimeMin2 && this.formData.restStartTimeMin2 < this.formData.startTimeMin2) {
                //         this.errors.restStartTime2 = this.$t('start time must be earlier than end time');             //need trans
                //         valid = false;
                //     }
                // }
                if (this.formData.restEndTimeHour1 && this.formData.restEndTimeHour1 > this.formData.endTimeHour1) {
                    this.errors.restEndTime1 = this.$t('rest time must be in range of schedule');               //need trans
                    valid = false;
                }
                if (this.formData.restEndTimeHour2 && this.formData.restEndTimeHour2 > this.formData.endTimeHour2) {
                    this.errors.restEndTime2 = this.$t('rest time must be in range of schedule');               //need trans
                    valid = false;
                }

                // if (this.formData.restEndTimeHour1 && this.formData.restEndTimeHour1 == this.formData.endTimeHour1) {
                //     if(this.formData.restEndTimeMin1 && this.formData.restEndTimeMin1 > this.formData.endTimeMin1) {
                //         this.errors.restEndTime1 = this.$t('start time must be earlier than end time');             //need trans
                //         valid = false;
                //     }
                // }
                // if (this.formData.restEndTimeHour2 && this.formData.restEndTimeHour2 == this.formData.endTimeHour2) {
                //     if(this.formData.restEndTimeMin2 && this.formData.restEndTimeMin2 > this.formData.endTimeMin2) {
                //         this.errors.restEndTime2= this.$t('start time must be earlier than end time');            //need trans
                //         valid = false;
                //     }
                // }
                return valid;
            },
            saveWorkingHours() {
                if (this.actionLoading) return;
                if(!this.validate()) return;
                 let requestData = {
                    'user_id': this.employee.id,
                    'date': this.date,
                    'shifts': []
                };
                if(this.formData.vacationReasonId && this.formData.vacationReasonId !== 0) {
                    requestData['shifts'].push({
                            'rest_start_time': null,
                            'rest_end_time': null,
                            'end_time': null,
                            'start_time': null,
                            'id': this.shifts[0]? this.shifts[0].id : null,
                            'working_hours_id': null,
                            'vacation_reason_id': this.formData.vacationReasonId,
                        });
                } else {
                    if((this.formData.startTimeHour1 && this.formData.startTimeMin1 &&
                        this.formData.endTimeHour1 && this.formData.endTimeMin1) || this.formData.workingHourId1) {
                        requestData['shifts'].push({
                                'rest_start_time': this.formData.restStartTimeHour1 ? this.formData.restStartTimeHour1 + ':' + this.formData.restStartTimeMin1 : null,
                                'rest_end_time': this.formData.restEndTimeHour1 ? this.formData.restEndTimeHour1 + ':' + this.formData.restEndTimeMin1 : null,
                                'end_time': this.formData.endTimeHour1 + ':' + this.formData.endTimeMin1,
                                'start_time': this.formData.startTimeHour1 + ':' + this.formData.startTimeMin1,
                                'id': this.shifts[0]? this.shifts[0].id : null,
                                'working_hours_id': this.formData.workingHourId1,
                                'vacation_reason_id': null,
                            });
                    }
                    if((this.formData.startTimeHour2 && this.formData.startTimeMin2 &&
                        this.formData.endTimeHour2 && this.formData.endTimeMin2) || this.formData.workingHourId2) {
                        requestData['shifts'].push({
                                'rest_start_time': this.formData.restStartTimeHour2 ? this.formData.restStartTimeHour2 + ':' + this.formData.restStartTimeMin2 : null,
                                'rest_end_time': this.formData.restEndTimeHour2 ? this.formData.restEndTimeHour2 + ':' + this.formData.restEndTimeMin2 : null,
                                'end_time': this.formData.endTimeHour2 + ':' + this.formData.endTimeMin2,
                                'start_time': this.formData.startTimeHour2 + ':' + this.formData.startTimeMin2,
                                'id': this.shifts[1]? this.shifts[1].id : null,
                                'working_hours_id': this.formData.workingHourId2,
                                'vacation_reason_id': null,
                            });
                    }
                }
                this.setActionLoading();
                let request;
                request = api.post('shift/' + this.officeId, null, requestData);
                request.then(() => {
                        this.unsetActionLoading();
                        showSuccess(this.$t("Successfully saved"));
                        this.$emit('success');
                    })
                    .catch(e => {
                        apiErrorHandler(e);
                        this.unsetActionLoading();
                    })
            },
            convertToFormData() {
                this.initializeFormData();
                if (this.shifts[0]) {
                    this.formData.workingHourId1 = this.shifts[0].workingHoursId;
                    this.formData.startTimeHour1 = this.shifts[0].startTime ? this.shifts[0].startTime.split(':')[0] : '';
                    this.formData.startTimeMin1 = this.shifts[0].startTime ? this.shifts[0].startTime.split(':')[1] : '';
                    this.formData.endTimeHour1 = this.shifts[0].endTime ? this.shifts[0].endTime.split(':')[0] : '';
                    this.formData.endTimeMin1 = this.shifts[0].endTime ? this.shifts[0].endTime.split(':')[1] : '';
                    this.formData.restStartTimeHour1 = this.shifts[0].restStartTime ? this.shifts[0].restStartTime.split(':')[0] : '';
                    this.formData.restStartTimeMin1 = this.shifts[0].restStartTime ? this.shifts[0].restStartTime.split(':')[1] : '';
                    this.formData.restEndTimeHour1 = this.shifts[0].restEndTime ? this.shifts[0].restEndTime.split(':')[0] : '';
                    this.formData.restEndTimeMin1 = this.shifts[0].restEndTime ? this.shifts[0].restEndTime.split(':')[1] : '';
                }
                if (this.shifts[1]) {
                    this.formData.workingHourId2 = this.shifts[1].workingHoursId;
                    this.formData.startTimeHour2 = this.shifts[1].startTime ? this.shifts[1].startTime.split(':')[0] : '';
                    this.formData.startTimeMin2 = this.shifts[1].startTime ? this.shifts[1].startTime.split(':')[1] : '';
                    this.formData.endTimeHour2 = this.shifts[1].endTime ? this.shifts[1].endTime.split(':')[0] : '';
                    this.formData.endTimeMin2 = this.shifts[1].endTime ? this.shifts[1].endTime.split(':')[1] : '';
                    this.formData.restStartTimeHour2 = this.shifts[1].restStartTime ? this.shifts[1].restStartTime.split(':')[0] : '';
                    this.formData.restStartTimeMin2 = this.shifts[1].restStartTime ? this.shifts[1].restStartTime.split(':')[1] : '';
                    this.formData.restEndTimeHour2 = this.shifts[1].restEndTime ? this.shifts[1].restEndTime.split(':')[0] : '';
                    this.formData.restEndTimeMin2 = this.shifts[1].restEndTime ? this.shifts[1].restEndTime.split(':')[1] : '';
                }
                if(this.shifts[0])
                    this.formData.vacationReasonId = !this.shifts[0].vacationReasonId ? 0 : this.shifts[0].vacationReasonId;
                else
                    this.formData.vacationReasonId = 0;


            },
            initializeFormData() {
                this.formData = {
                    workingHourId1: null,
                    startTimeHour1: '',
                    startTimeMin1:  '',
                    endTimeHour1:   '',
                    endTimeMin1:    '',
                    workingHourId2: null,
                    startTimeHour2: '',
                    startTimeMin2:  '',
                    endTimeHour2:   '',
                    endTimeMin2:    '',
                    restStartTimeHour1: '',
                    restStartTimeMin1:  '',
                    restEndTimeHour1:   '',
                    restEndTimeMin1:    '',
                    restStartTimeHour2: '',
                    restStartTimeMin2:  '',
                    restEndTimeHour2:   '',
                    restEndTimeMin2:    '',
                    vacationReasonId:   0,
                };
            },
            initFormError() {
                this.errors = {
                    startTime1: '',
                    endTime1:   '',
                    startTime2: '',
                    endTime2:    '',
                    restStartTime1: '',
                    restEndTime1:    '',
                    restStartTime2: '',
                    restEndTime2:   '',
                    vacationReasonId:   null,
                }
            },
            setWorkingHourToInput1()
            {
                if(!this.formData.workingHourId1) return;
                this.errors.startTime1=null;
                this.errors.endTime1=null;
                this.errors.startTime2=null;
                this.errors.endTime2=null;
                this.formData.startTimeHour1 = this.workingHours.find(item => item.id === this.formData.workingHourId1).startTime.split(":")[0];
                this.formData.startTimeMin1 = this.workingHours.find(item => item.id === this.formData.workingHourId1).startTime.split(":")[1];
                this.formData.endTimeHour1 = this.workingHours.find(item => item.id === this.formData.workingHourId1).endTime.split(":")[0];
                this.formData.endTimeMin1 = this.workingHours.find(item => item.id === this.formData.workingHourId1).endTime.split(":")[1];

            },
            setWorkingHourToInput2() {
                if(!this.formData.workingHourId2) return;
                this.errors.startTime1=null;
                this.errors.endTime1=null;
                this.errors.startTime2=null;
                this.errors.endTime2=null;
                this.formData.startTimeHour2 = this.workingHours.find(item => item.id === this.formData.workingHourId2).startTime.split(":")[0];
                this.formData.startTimeMin2 = this.workingHours.find(item => item.id === this.formData.workingHourId2).startTime.split(":")[1];
                this.formData.endTimeHour2 = this.workingHours.find(item => item.id === this.formData.workingHourId2).endTime.split(":")[0];
                this.formData.endTimeMin2 = this.workingHours.find(item => item.id === this.formData.workingHourId2).endTime.split(":")[1];
            },
            setTwoDigits(number) {
                if(!number) return;
                return ('0' + number).slice(-2);
            },
            deleteShift() {
                if (this.actionLoading) return;
                if (!confirm(this.$t("Are you sure you want to delete?"))) return;
                //this.setActionLoading();
                if(this.shifts.length > 0){
                    if(this.shifts[0]) {
                        api.delete('shift/' + this.shifts[0].id)
                        .then(() => {
                            // this.unsetActionLoading();
                            if(this.shifts[1]) {
                                api.delete('shift/' + this.shifts[1].id)
                                .then(() => {
                                    // this.unsetActionLoading();
                                    showSuccess(this.$t('Successfully deleted'));
                                    this.$emit('success');
                                })
                                .catch(e => {
                                    apiErrorHandler(e)
                                })
                                .finally(() => {
                                    // this.unsetActionLoading();
                                });
                            }
                            showSuccess(this.$t('Successfully deleted'));
                            // this.unsetActionLoading();
                            this.$emit('success');
                        })
                        .catch(e => {
                            // this.unsetActionLoading();
                            apiErrorHandler(e)
                        });
                    }
                } else {
                    // this.unsetActionLoading();
                    return;
                }
            }
        }
    }
</script>
