<template>
    <div class="container-fluid has-fixed-btn">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-6 col-12 d-flex align-items-center">
                                <h5 class="card-title mb-0 pr-5">{{ currentOfficeName }}</h5>
                            </div>
                            <div class="col-md-6 col-12 d-flex align-items-center">
                                <div class="d-flex align-items-center p-0">
                                    <datepicker
                                    :language="ja"
                                    :format="customFormatter"
                                    ref="programaticOpen"
                                    :placeholder="todayDate"
                                    @selected="getContact"
                                    v-model="selectedDate"
                                    :disabled-dates="disabledDates">
                                    </datepicker>
                                    <button type="button" class="btn btn-sm btn-outline mx-0" @click="openDatePicker()">
                                    <i class="fas fa-calendar-alt fa-2x"></i>
                                    </button>
                                </div>
                                <div class="d-flex align-items-center px-3 is-invalid">
                                    <div for="weatherStauts" class="form-label mr-2">天気</div>
                                    <input type="text" class="form-control fixed-width-80 px-0" value="晴れ" id="weatherStauts" :class="{'is-invalid' : errors.weather}" v-model="formData.weather" @change="dataChanged = true; errors.weather = null;inputError = false;"/>
                                </div>
                                <span v-if="errors.weather" class="error invalid-feedback">
                                    {{errors.weather}}
                                </span>
                            </div>
                            <div class="col-md-6 col-12 d-flex align-items-center"></div>
                            <div class="col-md-6 col-12 d-flex align-items-center">
                                <div class="calendar-left flex-grow-1">
                                    <button type="button" class="btn btn-sm btn-outline" @click="onPrev">
                                        <i class="fas fa-caret-left fa-2x"></i>
                                    </button>
                                    <!-- <div class="mx-2">{{ displayDate }}</div> -->
                                    <button type="button" class="btn btn-sm btn-outline-primary mx-2" @click="onCurrentMonth">
                                        今日
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline" @click="onNext">
                                        <i class="fas fa-caret-right fa-2x"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-5 col-12 align-items-center mb-2" style="display:flex;">
                                    <label for="parentname" style="min-width: 100px; margin-bottom:0px;">記入者 保護者様名：</label>
                                    <input type="text" class="form-control" id="parentname" style="width: calc(90% - 130px);" v-model="formData.guardian" :class="{'is-invalid': errors.guardian}" @change="dataChanged = true; errors.guardian = null;inputError = false;"/>
                                    <span v-if="errors.guardian" class="error invalid-feedback">
                                        {{errors.guardian}}
                                    </span>
                                </div>
                            <div class="col-md-5 col-12 align-items-center mb-2" style="display:flex;">
                                <label for="mindername" style="min-width: 80px; margin-bottom:0px;">保育士名：</label>
                                <div>{{formData.nurseName}}</div>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-md-6 col-12">
                                <div class="dark-blue text-center py-2 text-white">
                                    家庭からの連絡事項
                                </div>
                                <div class="light-blue p-4 mt-1" style="height: 300px;">
                                    <textarea class="form-control" style="height: 95%;" v-model="formData.contact0Home" @change="dataChanged = true;">

                                    </textarea>
                                </div>
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="dark-yellow text-center py-2 text-white">
                                    保育園からの連絡事項
                                </div>
                                <div class="light-yellow p-4 mt-1" style="height: 300px;overflow-y:overlay;">
                                    {{formData.contact0School}}
                                </div>
                            </div>
                        </div>
                        <div class="float-right d-flex align-items-center mt-2 fixed-btn-group" :class="{'is-invalid': inputError}">
                            <button class="btn btn-primary float-right mr-md-2" @click="saveContact">登録</button>
                            <button class="btn btn-primary float-right" @click="exportExcel">Excel出力</button>
                        </div>
                        <div v-if="inputError" class="error invalid-feedback text-right" style="margin-top: 60px;">
                            {{$t("Input error")}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import Datepicker from "vuejs-datepicker";
import { ja } from 'vuejs-datepicker/dist/locale';
import moment from 'moment-timezone';
import { mapState } from 'vuex';
import actionLoading from '../../mixin/actionLoading';
import api, { apiErrorHandler } from '../../global/api';
import HourMinuteInput from '../../components/HourMinuteInput.vue';
import { showSuccess } from '../../helpers/error';
import LocalStorage from '../../helpers/localStorage';

const initialFormData = {
    date: new Date(),
    weather: '',
    guardian: '',
    mood: null,
    pickUpPerson: null,
    pickUpTime: null,
    contact0Home: '',
}

export default {
    components: {
        Datepicker
    },
    mixins: [actionLoading],
    props: {
        contact: {},
        child: {},
        date: null,
    },
    data () {
        return {
            dataChanged: false,
            formData: {...initialFormData},
            errors: {},
            inputError: false,
            currentDate: new Date(),
            todayDate: "",
            disabledDates: {
                to: null,
            },
            selectedDate: new Date(),
            ja: ja,
        }
    },
    computed: {
        ...mapState({
            currentOfficeName: state =>  {
                if (state.session.info.office) return state.session.info.office.name;
                return '';
            }
        })
    },
    methods: {
        convertToFormData() {
            //this.initializeFormData();
            if (this.contact) {
                this.formData = {...this.contact};
            }
        },
        initializeFormData() {
            this.formData = {

            };
        },
        getCurrentDate(){
            return moment().format('YYYY年 M月 D日 (ddd)');
        },
        currentTime(){
            var today = new Date();
            var month = today.getMonth() + 1;
            var day = today.getDate();
            return month + "月" + day + "日 "
            + today.getHours() + ":"
            + today.getMinutes();
        },
        customFormatter(date) {
            return moment(date).format('YYYY/M/D(ddd)');
        },
        saveContact() {
            if(this.actionLoading) return;
            if (!this.validate()) return;
            const requestData = this.formData;
            requestData['date'] = moment(this.selectedDate).format('YYYY-MM-DD');
            if(this.formData.pickUpTime)
                requestData['pick_up_time'] = moment(this.formData.pickUpTime, 'h:mm:ss').format('HH:mm');
            this.setActionLoading();
            api.post('contact-book/child/' + this.child.id + '/home/2', null, requestData)
            .then(() => {
                this.unsetActionLoading();
                showSuccess(this.$t('Successfully saved'));
                this.dataChanged = false;
            })
            .catch(e => {
                this.dataChanged = false;
                apiErrorHandler(e);
                this.unsetActionLoading();
            });
        },
        validate() {
            let valid = true;
            if(this.formData.weather && this.formData.weather.length > 10) {
                this.errors.weather = this.$t('Please enter 10 characters or less');
                valid = false;
            }
            if(!this.formData.guardian) {
                this.errors.guardian = this.$t('Please input name');
                valid = false;
            }
            if(this.formData.guardian && this.formData.guardian.length > 20) {
                this.errors.guardian = this.$t('Please enter 20 characters or less');
                valid = false;
            }
            this.inputError = !valid;
            return valid;
        },
        initFormError() {
            this.errors = {
                guardian: null,
            }
        },
        getContact(date) {
            if(this.actionLoading) return;
            this.setActionLoading();
            if(date){
                this.selectedDate = date;
            } else {
                this.selectedDate = new Date();
            }
            this.selectedDate = moment(this.selectedDate).format('YYYY-MM-DD');
            api.get('contact-book/child/' + this.child.id, null, {date: this.selectedDate})
                .then(response => {
                    this.unsetActionLoading();
                    this.dataChanged = false;
                    if (response.contactBook) {
                        this.formData = response.contactBook;
                    } else {
                        this.formData = {...initialFormData};
                    }
                    this.$parent.getContactBook(this.selectedDate);
                })
                .catch(e => {
                    this.dataChanged = false;
                    this.unsetActionLoading();
                    apiErrorHandler(e);
                });
        },
        changeTimeFormat(date) {
            if(date) {
                return moment(date).tz('Asia/Tokyo').format('HH:mm');
            } else {
                return "-";
            }
        },
        openDatePicker(){
            if(this.dataChanged) {
                if(!confirm(this.$t('Are you sure moving to other date without saving data?'))) return;
            }
            this.$refs.programaticOpen.showCalendar();
        },
        exportExcel() {
            const date = moment(this.selectedDate).format('YYYY-MM-DD');
            location.href = process.env.MIX_APP_BASE_URL + 'childcare-contact-book/excel/' + this.child.id + '/?date=' + date + '&token=' + LocalStorage.getToken();
        },
        onNext() {
            var date = moment(this.selectedDate).add(1, 'days').format('YYYY-MM-DD');
            this.getContact(date);
        },
        onPrev() {
            var date = moment(this.selectedDate).add(-1, 'days').format('YYYY-MM-DD');
            this.getContact(date);
        },
        onCurrentMonth(){
            var date = moment().format('YYYY-MM-DD');
            this.getContact(date);
        },
    },
    created() {

    },
    mounted() {
        this.todayDate = this.getCurrentDate().toString();
        this.selectedDate = this.date;
        this.convertToFormData();
        this.initFormError();
        if(this.child.admissionDate) {
            this.disabledDates = {to: moment(this.child.admissionDate).subtract(1, "days").toDate()};
        }
    }
}
</script>
<style scoped>
    .calendar-center {
        display: flex;
        justify-content: center;
        align-items: center;
    }
    .calendar-left {
        display: flex;
        justify-content: left;
        align-items: center;
    }
    .calendar-title {
        display: flex;
        align-items: center;
    }
    @media (max-width: 500px) {
       h5.card-title {
           font-size: 13px!important;
       }
    }
</style>
