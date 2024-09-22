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
                <div class="col-md-3">
                    <select class="form-control" v-model="data.officeId" :class="{'is-invalid' : errors.officeId}" @change="errors.officeId = null;">
                        <option :value="null" disabled hidden>保育園名を選択してください</option>
                        <option v-for="office in offices" :key="office.id" :value="office.id">{{office.name}}</option>
                    </select>
                    <span v-if="errors.officeId" class="error invalid-feedback">
                        {{ errors.officeId }}
                    </span>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="form-row align-items-center">
                <div class="col-md-3">
                    <input type="text" class="form-control" name="time-zone" placeholder="時間帯名入力" v-model="data.name" :class="{'is-invalid' : errors.name}" @keyup="errors.name = null">
                    <span v-if="errors.name" class="error invalid-feedback">
                        {{ errors.name }}
                    </span>
                </div>
                <div class="col-md-3">
                    <div class="d-flex align-items-center">
                        <template v-for="employmentStatus in employmentStatuses">
                            <input type="radio" v-model="data.employmentStatusId" :value="employmentStatus.id" :key="employmentStatus.id">
                            <label class="mr-2 mb-0" :key="employmentStatus.id+'label'">{{employmentStatus.name}}</label>
                        </template>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="d-flex is-invalid align-items-center">
                        <input type="number" class="form-control mr-2 p-1" v-model="data.startTimeHour" min="0" max="23" :class="{'is-invalid' : errors.startTime}" @change="errors.startTime = null">
                        :
                        <input type="number" class="form-control ml-2 p-1" v-model="data.startTimeMinute" min="0" max="59" :class="{'is-invalid' : errors.startTime}" @change="errors.startTime = null">
                    </div>
                    <span v-if="errors.startTime" class="error invalid-feedback">
                        {{ errors.startTime }}
                    </span>
                </div>
                <div class="col-md-2">
                    <div class="d-flex is-invalid align-items-center">
                        <input type="number" class="form-control mr-2 p-1" v-model="data.endTimeHour" min="0" max="23" :class="{'is-invalid' : errors.endTime}" @change="errors.endTime = null">
                        :
                        <input type="number" class="form-control ml-2 p-1" v-model="data.endTimeMinute" min="0" max="59" :class="{'is-invalid' : errors.endTime}" @change="errors.endTime = null">
                    </div>
                    <span v-if="errors.endTime" class="error invalid-feedback">
                        {{ errors.endTime }}
                    </span>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">閉じる</button>
        <button type="submit" class="btn btn-primary" @click="saveWorkingHour">登録</button>
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
            data: {},
            offices: {},
            editMode: null
        },
        computed: {
            ...mapState({
                restDeductions: state => state.constants.restDeductions,
                employmentStatuses: state => state.constants.employmentStatuses
            })
        },
        watch: {
            ['data.id'] : function (){
                this.errors = {
                    officeId: null,
                    name: '',
                    employmentStatusId: null,
                    startTime: null,
                    endTime: null,
                }
            },
        },
        data() {
            return {
                errors: {
                    officeId: null,
                    name: '',
                    startTime: null,
                    endTime: null,
                },
            }
        },
        methods: {
            saveWorkingHour() {
                if (this.actionLoading) return;
                if (!this.validate()) return;
                const requestData = {
                    'office_id': this.data.officeId,
                    'name': this.data.name,
                    'start_time': ("0" + this.data.startTimeHour).slice(-2) + ":" + ("0" + this.data.startTimeMinute).slice(-2),
                    'end_time': ("0" + this.data.endTimeHour).slice(-2) + ":" + ("0" + this.data.endTimeMinute).slice(-2),
                    'employment_status_id': this.data.employmentStatusId
                };
                this.setActionLoading();
                let request;
                if (this.data.id) {
                    request = api.put('working-hours/' + this.data.id, null, requestData);
                } else {
                    request = api.post('working-hours', null, requestData);
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
                if (!this.data.officeId) {
                    this.errors.officeId = this.$t('Please select office');                            // need trans
                    valid = false;
                }
                if (!this.data.employmentStatusId) {
                    this.errors.employmentStatusId = this.$t('Please select employment');                            // need trans
                    valid = false;
                }
                if (!this.data.name) {
                    this.errors.name = this.$t('Please input name');                                 // need trans
                    valid = false;
                }
                if (this.data.name && this.data.name.length > 50) {
                    this.errors.name = this.$t('Please enter 50 characters or less');                                 // need trans
                    valid = false;
                }
                if (!this.data.startTimeHour || !this.data.startTimeMinute) {
                    this.errors.startTime = this.$t('Please input startTime');                       // need trans
                    valid = false;
                }
                if (!this.data.endTimeHour || !this.data.endTimeMinute) {
                    this.errors.endTime = this.$t('Please input endTime');                            // need trans
                    valid = false;
                }
                if (this.data.startTimeHour < 0 || this.data.startTimeHour > 23) {
                    this.errors.startTime = this.$t('Invalid time format');
                    valid = false;
                }
                if (this.data.startTimeMinute < 0 || this.data.startTimeMinute > 59) {
                    this.errors.startTime = this.$t('Invalid time format');
                    valid = false;
                }
                if (this.data.endTimeHour < 0 || this.data.endTimeHour > 23) {
                    this.errors.endTime = this.$t('Invalid time format');
                    valid = false;
                }
                if (this.data.endTimeMinute < 0 || this.data.endTimeMinute > 59) {
                    this.errors.endTime = this.$t('Invalid time format');
                    valid = false;
                }
                return valid;
            }
        }
    }
</script>
