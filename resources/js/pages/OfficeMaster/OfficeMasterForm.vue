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
                        <input type="text" name="office_number"
                            class="form-control"
                            placeholder="事業所No入力"
                            v-model="formData.number"
                            :class="{'is-invalid' : errors.number}"
                            @keyup="errors.number = null"
                        />
                        <span class="error invalid-feedback">
                            {{ errors.number }}
                        </span>
                    </div>
                    <div class="col-md-4">
                        <input type="text" name="office_name"
                            class="form-control"
                            placeholder="事業所名入力"
                            v-model="formData.name"
                            :class="{'is-invalid' : errors.name}"
                            @keyup="errors.name = null"
                        />
                        <span v-if="errors.name" class="error invalid-feedback">
                            {{ errors.name }}
                        </span>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="form-row align-items-center">
                    <template v-for="restDeduction in restDeductions">
                        <div class="col-md-5" :key="restDeduction.id">
                            <input type="radio" name="type" :value="restDeduction.id" v-model="formData.restDeductionId" @change="errors.restDeductionId = null">
                            <label class="ml-auto">{{ restDeduction.name }}</label>
                        </div>
                        <div class="col-md-1" :key="restDeduction.id + '_space'"></div>
                    </template>
                    <span v-if="errors.restDeductionId" class="error invalid-feedback d-block">
                        {{ errors.restDeductionId }}
                    </span>
                </div>
            </div>

            <div class="form-group">
                <div class="form-row">
                    <div class="col-md-1">
                        <input type="radio" name="kind" :value="2" v-model="formData.type">
                        <label class="ml-auto">保育園</label>
                    </div>
                    <div class="col-md-1">
                        <input type="radio" name="kind" :value="1" v-model="formData.type">
                        <label class="ml-auto">その他</label>
                    </div>
                </div>
            </div>
            <template v-if="formData.type == 2">
            <div class="form-group">
                <div class="form-row">
                    <div class="col-md-4">
                        <label>以下の項目は保育園のみ登録</label>
                    </div>
                </div>
                <div class="form-row">
                    <div class="col-md-2">
                        保育標準時間：
                    </div>
                    <div class="col-md-2">
                        <div class="d-flex is-invalid align-items-center">
                            <input type="number" class="form-control mr-2 p-1" min="0" max="23" v-model="formData.openTimeHour" :class="{'is-invalid' : errors.startTime}" @change="errors.startTime = null">
                        :
                            <input type="number" class="form-control ml-2 p-1" min="0" max="59" v-model="formData.openTimeMin" :class="{'is-invalid' : errors.startTime}" @change="errors.startTime = null">
                        </div>
                        <span v-if="errors.startTime" class="error invalid-feedback">
                            {{ errors.startTime }}
                        </span>
                    </div>
                    ~
                    <div class="col-md-2">
                        <div class="d-flex is-invalid align-items-center">
                            <input type="number" class="form-control mr-2 p-1" min="0" max="23" v-model="formData.closeTimeHour" :class="{'is-invalid' : errors.endTime}" @change="errors.endTime = null">
                        :
                            <input type="number" class="form-control ml-2 p-1" min="0" max="59" v-model="formData.closeTimeMin" :class="{'is-invalid' : errors.endTime}" @change="errors.endTime = null">
                        </div>
                        <span v-if="errors.endTime" class="error invalid-feedback">
                            {{ errors.endTime }}
                        </span>
                    </div>
                </div>
                <div class="form-row" id="div-short-time" v-if="formData.businessTypeId == 2 || formData.businessTypeId == 3">
                    <div class="col-md-2">
                        保育短時間：
                    </div>
                    <div class="col-md-2">
                        <div class="d-flex is-invalid align-items-center">
                            <input type="number" class="form-control mr-2 p-1" min="0" max="23" v-model="formData.openTimeShortHour" :class="{'is-invalid' : errors.startTimeShort}" @change="errors.startTimeShort = null">
                            :
                            <input type="number" class="form-control ml-2 p-1" min="0" max="59" v-model="formData.openTimeShortMin" :class="{'is-invalid' : errors.startTimeShort}" @change="errors.startTimeShort = null">
                        </div>
                        <span v-if="errors.startTimeShort" class="error invalid-feedback">
                            {{ errors.startTimeShort }}
                        </span>
                    </div>
                    ~
                    <div class="col-md-2">
                        <div class="d-flex is-invalid align-items-center">
                            <input type="number" class="form-control mr-2 p-1" min="0" max="23" v-model="formData.closeTimeShortHour" :class="{'is-invalid' : errors.endTimeShort}" @change="errors.endTimeShort = null">
                            :
                            <input type="number" class="form-control ml-2 p-1" min="0" max="59" v-model="formData.closeTimeShortMin" :class="{'is-invalid' : errors.endTimeShort}" @change="errors.endTimeShort = null">
                        </div>
                        <span v-if="errors.endTimeShort" class="error invalid-feedback">
                            {{ errors.endTimeShort }}
                        </span>
                    </div>
                </div>
                <div class="form-row mt-1">
                    <div class="col-md-2">
                        定員数：
                    </div>
                    <div class="col-md-1">
                        <div class="d-flex align-items-center is-invalid">
                            <input type="number" class="form-control" min="0" max="1000" name="capacity" v-model="formData.capacity" :class="{'is-invalid' : errors.capacity}" @change="errors.capacity = null">
                            名
                        </div>
                        <span v-if="errors.capacity" class="error invalid-feedback">
                            {{ errors.capacity }}
                        </span>
                    </div>
                </div>
                <div class="form-row mt-1">
                    <div class="col-md-2">
                        適正職員数：<br>
                        (一人当たり)
                    </div>
                    <div class="col-md-2">
                        <div class="d-flex mb-1 align-middle is-invalid">
                            <div class="mr-1 align-self-center">0歳児</div>
                            <input type="number" class="form-control" style="width:50%" name="appropriateNumber0" min="0" max="1000" v-model="formData.appropriateNumber0" :class="{'is-invalid' : errors.appropriateNumber0}" @change="errors.appropriateNumber0 = null">
                            <div class="ml-1 align-self-center">名</div>
                        </div>
                        <span v-if="errors.appropriateNumber0" class="error invalid-feedback">
                            {{ errors.appropriateNumber0 }}
                        </span>
                        <div class="d-flex is-invalid">
                            <div class="mr-1 align-self-center">3歳児</div>
                            <input type="number" class="form-control" style="width:50%" name="appropriateNumber3" min="0" max="1000" v-model="formData.appropriateNumber3" :class="{'is-invalid' : errors.appropriateNumber3}" @keyup="errors.appropriateNumber3 = null">
                            <div class="ml-1 align-self-center">名</div>
                        </div>
                        <span v-if="errors.appropriateNumber3" class="error invalid-feedback">
                                {{ errors.appropriateNumber3 }}
                            </span>
                    </div>
                    <div class="col-md-2">
                        <div class="d-flex mb-1 is-invalid">
                            <div class="mr-1 align-self-center">1歳児</div>
                            <input type="number" class="form-control" style="width:50%" name="appropriateNumber1" min="0" max="1000" v-model="formData.appropriateNumber1" :class="{'is-invalid' : errors.appropriateNumber1}" @keyup="errors.appropriateNumber1 = null">
                            <div class="ml-1 align-self-center">名</div>
                        </div>
                        <span v-if="errors.appropriateNumber1" class="error invalid-feedback">
                            {{ errors.appropriateNumber1 }}
                        </span>
                        <div class="d-flex mb-1 is-invalid">
                            <div class="mr-1 align-self-center">4歳児</div>
                            <input type="number" class="form-control" style="width:50%" name="appropriateNumber4" min="0" max="1000" v-model="formData.appropriateNumber4" :class="{'is-invalid' : errors.appropriateNumber4}" @keyup="errors.appropriateNumber4 = null">
                            <div class="ml-1 align-self-center">名</div>
                        </div>
                        <span v-if="errors.appropriateNumber4" class="error invalid-feedback">
                            {{ errors.appropriateNumber4 }}
                        </span>
                    </div>
                    <div class="col-md-2">
                        <div class="d-flex mb-1 is-invalid">
                            <div class="mr-1 align-self-center">2歳児</div>
                            <input type="number" class="form-control" style="width:50%" name="appropriateNumber2" min="0" max="1000" v-model="formData.appropriateNumber2" :class="{'is-invalid' : errors.appropriateNumber2}" @keyup="errors.appropriateNumber2 = null">
                            <div class="ml-1 align-self-center is-invalid">名</div>
                        </div>
                        <span v-if="errors.appropriateNumber2" class="error invalid-feedback">
                            {{ errors.appropriateNumber2 }}
                        </span>
                        <div class="d-flex">
                            <div class="mr-1 align-self-center is-invalid">5歳児</div>
                            <input type="number" class="form-control" style="width:50%" name="appropriateNumber5" min="0" max="1000" v-model="formData.appropriateNumber5" :class="{'is-invalid' : errors.appropriateNumber5}" @keyup="errors.appropriateNumber5 = null">
                            <div class="ml-1 align-self-center">名</div>
                        </div>
                        <span v-if="errors.appropriateNumber5" class="error invalid-feedback">
                            {{ errors.appropriateNumber5 }}
                        </span>
                    </div>
                </div>
                <div class="form-row mt-1">
                    <div class="col-md-2">
                        事業種別：
                    </div>
                    <div class="col-md-6">
                        <select class="form-control" v-model="formData.businessTypeId" name="businessTypeId" :class="{'is-invalid' : errors.businessTypeId}" @change="errors.businessTypeId = null" v-on:change="selectedBusinessType">
                            <option :value="null"></option>
                            <option v-for="business in businessTypes" :key="business.id" :value="business.id">{{business.label}}</option>
                        </select>
                        <span v-if="errors.businessTypeId" class="error invalid-feedback">
                            {{ errors.businessTypeId }}
                        </span>
                    </div>
                </div>
            </div>
            </template>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">閉じる</button>
            <button type="button" class="btn btn-primary" @click="saveOffice">登録</button>
        </div>
    </div>
</template>
<script>
import { mapState } from 'vuex';
import api, { apiErrorHandler } from '../../global/api';
import actionLoading from '../../mixin/actionLoading';
import { showSuccess } from '../../helpers/error';
import moment from 'moment';

    export default {
        mixins: [actionLoading],
        props: {
            data: {},
            editMode: null,
            modalOpen: null,
        },
        computed: {
            ...mapState({
                restDeductions: state => state.constants.restDeductions,
                businessTypes: state => state.constants.businessTypes
            })
        },
        watch: {
            ['data.id'] : function (){
                this.errors = {
                    name: '',
                    number: '',
                    restDeductionId: '',
                    startTime: null,
                    endTime: null,
                    startTimeShort: null,
                    endTimeShort: null,
                    capacity: null,
                    appropriateNumber0: null,
                    appropriateNumber1: null,
                    appropriateNumber2: null,
                    appropriateNumber3: null,
                    appropriateNumber4: null,
                    appropriateNumber5: null,
                    businessTypeId: null
                };
                this.convertToFormData();
            },
            'modalOpen' : function (){
                this.errors = {
                    name: '',
                    number: '',
                    restDeductionId: '',
                    startTime: null,
                    endTime: null,
                    startTimeShort: null,
                    endTimeShort: null,
                    capacity: null,
                    appropriateNumber0: null,
                    appropriateNumber1: null,
                    appropriateNumber2: null,
                    appropriateNumber3: null,
                    appropriateNumber4: null,
                    appropriateNumber5: null,
                    businessTypeId: null
                };
                this.convertToFormData();
                this.toggleShortTime(this.data.businessTypeId);
            },
        },
        data() {
            return {
                formData: {
                    number: '',
                    name: '',
                    restDeductionId: '',
                    isNursery: false,
                    openTime: null,
                    openTimeHour: null,
                    openTimeMin: null,
                    openTimeShortHour: null,
                    openTimeShortMin: null,
                    closeTimeHour: null,
                    closeTimeMin: null,
                    closeTimeShortHour: null,
                    closeTimeShortMin: null,
                    capacity: null,
                    appropriateNumber0: null,
                    appropriateNumber1: null,
                    appropriateNumber2: null,
                    appropriateNumber3: null,
                    appropriateNumber4: null,
                    appropriateNumber5: null,
                    businessTypeId: null
                },
                errors: {
                    name: '',
                    number: '',
                    restDeductionId: '',
                    startTime: null,
                    endTime: null,
                    startTimeShort: null,
                    endTimeShort: null,
                    capacity: null,
                    appropriateNumber0: null,
                    appropriateNumber1: null,
                    appropriateNumber2: null,
                    appropriateNumber3: null,
                    appropriateNumber4: null,
                    appropriateNumber5: null,
                    businessTypeId: null
                },
                nurseOffice: 0,
            }
        },
        methods: {
            toggleShortTime: function (val) {
                if (val == 1 || val == 4) {
                    this.formData.openTimeShortHour = null;
                    this.formData.openTimeShortMin = null;
                    this.formData.closeTimeShortHour = null;
                    this.formData.closeTimeShortMin = null;                }
            },
            selectedBusinessType: function (e) {
                this.toggleShortTime(e.target.value);
            },
            saveOffice() {
                if (this.actionLoading) return;
                if (!this.validate()) return;
                this.setActionLoading();
                let request;
                if(this.formData.openTimeHour && this.formData.openTimeMin) {
                    this.formData['open_time'] = ('0' + this.formData.openTimeHour).slice(-2) + ":" + ('0' + this.formData.openTimeMin).slice(-2);
                    this.formData['close_time'] = ('0' + this.formData.closeTimeHour).slice(-2) + ":" + ('0' + this.formData.closeTimeMin).slice(-2);
                }
                if(this.formData.openTimeShortHour && this.formData.openTimeShortMin) {
                    this.formData['open_time_short'] = ('0' + this.formData.openTimeShortHour).slice(-2) + ":" + ('0' + this.formData.openTimeShortMin).slice(-2);
                    this.formData['close_time_short'] = ('0' + this.formData.closeTimeShortHour).slice(-2) + ":" + ('0' + this.formData.closeTimeShortMin).slice(-2);
                } else {
                    this.formData['open_time_short'] = null;
                    this.formData['close_time_short'] = null;
                }
                if(!this.formData.businessTypeId) {
                    this.formData['business_type_id'] = null;
                }
                if (this.data.id) {
                    request = api.put('office-master/' + this.data.id, null, this.formData);
                } else {
                    request = api.post('office-master', null, this.formData);
                }
                request.then(() => {
                        this.unsetActionLoading();
                        showSuccess(this.$t("Successfully saved"));
                        this.unsetActionLoading();
                        this.$emit('success');
                    })
                    .catch(e => {
                        apiErrorHandler(e);
                        this.unsetActionLoading();
                    });
            },
            convertToFormData() {
                if(this.data) {
                    this.formData = {...this.data};
                    this.formData['openTimeHour'] = this.data.openTime ? moment(this.data.openTime, 'hh:mm:ss').format('HH') : '';
                    this.formData['openTimeMin'] = this.data.openTime ? moment(this.data.openTime, 'hh:mm:ss').format('mm') : '';
                    this.formData['closeTimeHour'] = this.data.closeTime ? moment(this.data.closeTime, 'hh:mm:ss').format('HH') : '';
                    this.formData['closeTimeMin'] = this.data.closeTime ? moment(this.data.closeTime, 'hh:mm:ss').format('mm') : '';
                    this.formData['openTimeShortHour'] = this.data.openTimeShort ? moment(this.data.openTimeShort, 'hh:mm:ss').format('HH') : '';
                    this.formData['openTimeShortMin'] = this.data.openTimeShort ? moment(this.data.openTimeShort, 'hh:mm:ss').format('mm') : '';
                    this.formData['closeTimeShortHour'] = this.data.closeTimeShort ? moment(this.data.closeTimeShort, 'hh:mm:ss').format('HH') : '';
                    this.formData['closeTimeShortMin'] = this.data.closeTimeShort ? moment(this.data.closeTimeShort, 'hh:mm:ss').format('mm') : '';
                }
            },
            validate() {
                let valid = true;
                if (!this.formData.number) {
                    this.errors.number = this.$t('Please input officeNumber');                            // need trans
                    valid = false;
                }
                if (this.formData.number && this.formData.number.length > 20) {
                    this.errors.number = this.$t('Please enter 20 characters or less');                            // need trans
                    valid = false;
                }
                if (!this.formData.name) {
                    this.errors.name = this.$t('Please input name');                                 // need trans
                    valid = false;
                }
                if (this.formData.name && this.formData.name.length > 50) {
                    this.errors.name = this.$t('Please enter 50 characters or less');                                 // need trans
                    valid = false;
                }
                if (!this.formData.restDeductionId) {
                    this.errors.restDeductionId = this.$t('Please select rest deduction type');       // need trans
                    valid = false;
                }
                if(this.formData.type == 2) {
                    if(!this.formData.capacity) {
                        this.errors.capacity = this.$t('Please input number');
                        valid = false;
                    }
                    if(this.formData.capacity && this.formData.capacity < 0) {
                        this.errors.capacity = this.$t('Please input an integer');
                        valid = false;
                    }
                    if(!this.formData.openTimeHour || !this.formData.openTimeMin) {
                        this.errors.startTime = this.$t('Please input startTime');
                        valid = false;
                    }
                    if(!this.formData.closeTimeHour || !this.formData.closeTimeMin) {
                        this.errors.endTime = this.$t('Please input endTime');
                        valid = false;
                    }
                    if (this.formData.openTimeHour < 0 || this.formData.openTimeHour > 23) {
                        this.errors.startTime = this.$t('Invalid time format');
                        valid = false;
                    }
                    if (this.formData.openTimeMin < 0 || this.formData.openTimeMin > 59) {
                        this.errors.startTime = this.$t('Invalid time format');
                        valid = false;
                    }
                    if (this.formData.closeTimeHour < 0 || this.formData.closeTimeHour > 23) {
                        this.errors.endTime = this.$t('Invalid time format');
                        valid = false;
                    }
                    if (this.formData.closeTimeMin < 0 || this.formData.closeTimeMin > 59) {
                        this.errors.endTime = this.$t('Invalid time format');
                        valid = false;
                    }
                    if (this.formData.closeTimeHour && this.formData.closeTimeMin && ('0' + this.formData.closeTimeHour).slice(-2) + ":" + ('0' + this.formData.closeTimeMin).slice(-2) < ('0' + this.formData.openTimeHour).slice(-2) + ":" + ('0' + this.formData.openTimeMin).slice(-2)) {
                        this.errors.endTime = this.$t('start time must be earlier than end time');
                        valid = false;
                    }
                    if(this.formData.businessTypeId == 2 || this.formData.businessTypeId == 3) {
                        if(!this.formData.openTimeShortHour || !this.formData.openTimeShortMin) {
                            this.errors.startTimeShort = this.$t('Please input startTime');
                            valid = false;
                        }
                        if(!this.formData.closeTimeShortHour || !this.formData.closeTimeShortMin) {
                            this.errors.endTimeShort = this.$t('Please input endTime');
                            valid = false;
                        }
                        if (this.formData.openTimeShortHour < 0 || this.formData.openTimeShortHour > 23) {
                            this.errors.startTimeShort = this.$t('Invalid time format');
                            valid = false;
                        }
                        if (this.formData.openTimeShortMin < 0 || this.formData.openTimeShortMin > 59) {
                            this.errors.startTimeShort = this.$t('Invalid time format');
                            valid = false;
                        }
                        if (this.formData.closeTimeShortHour < 0 || this.formData.closeTimeShortHour > 23) {
                            this.errors.endTimeShort = this.$t('Invalid time format');
                            valid = false;
                        }
                        if (this.formData.closeTimeShortMin < 0 || this.formData.closeTimeShortMin > 59) {
                            this.errors.endTimeShort = this.$t('Invalid time format');
                            valid = false;
                        }
                        if (this.formData.closeTimeShortHour && this.formData.closeTimeShortMin && ('0' + this.formData.closeTimeShortHour).slice(-2) + ":" + ('0' + this.formData.closeTimeShortMin).slice(-2) < ('0' + this.formData.openTimeShortHour).slice(-2) + ":" + ('0' + this.formData.openTimeShortMin).slice(-2)) {
                            this.errors.endTimeShort = this.$t('start time must be earlier than end time');
                            valid = false;
                        }
                    }
                    if(this.formData.appropriateNumber0 && this.formData.appropriateNumber0 < 0) {
                        this.errors.appropriateNumber0 = this.$t('Please input an integer');
                        valid = false;
                    }
                    if(this.formData.appropriateNumber1 && this.formData.appropriateNumber1 < 0) {
                        this.errors.appropriateNumber1 = this.$t('Please input an integer');
                        valid = false;
                    }
                    if(this.formData.appropriateNumber2 && this.formData.appropriateNumber2 < 0) {
                        this.errors.appropriateNumber2 = this.$t('Please input an integer');
                        valid = false;
                    }
                    if(this.formData.appropriateNumber3 && this.formData.appropriateNumber3 < 0) {
                        this.errors.appropriateNumber3 = this.$t('Please input an integer');
                        valid = false;
                    }
                    if(this.formData.appropriateNumber4 && this.formData.appropriateNumber4 < 0) {
                        this.errors.appropriateNumber4 = this.$t('Please input an integer');
                        valid = false;
                    }
                    if(this.formData.appropriateNumber5 && this.formData.appropriateNumber5 < 0) {
                        this.errors.appropriateNumber5 = this.$t('Please input an integer');
                        valid = false;
                    }
                    if(!this.formData.businessTypeId) {
                        this.errors.businessTypeId = this.$t('Please input office kind');
                        valid = false;
                    }
                }
                return valid;
            }
        }
    }
</script>
