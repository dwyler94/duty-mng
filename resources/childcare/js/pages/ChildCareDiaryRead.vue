<template>
    <section class="content">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header calendar-title">
                            <h3 class="card-title mb-0">{{ office.name }}</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4 text-center">
                                    <div>{{ office.name }}</div>
                                    <h3>保育日誌</h3>
                                    <div>{{ classLabel }}</div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card-tools calendar-center flex-grow-1">
                                        <datepicker
                                        :language="ja"
                                        :format="customFormatter"
                                        ref="programaticOpen"
                                        :placeholder="todayDate"
                                        @selected="getDiaryRead"
                                        v-model="selectedDate">
                                        </datepicker>
                                        <button type="button" class="btn btn-sm btn-outline mx-2" @click="openDatePicker()">
                                            <i class="fas fa-calendar-alt fa-2x"></i>
                                        </button>
                                    </div>
                                    <div class="calendar-center flex-grow-1">
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
                                    <div class="text-center">天気：<input type="text" class="input-fit" v-model="weather" readonly/></div>
                                </div>
                                <div class="col-md-4">
                                    <table class="table table-bordered table-diary">
                                        <thead class="text-center">
                                            <tr class="light-green">
                                                <th class="align-middle">園長</th>
                                                <th class="align-middle">主任・副主任<br>保育リーダー</th>
                                                <th class="align-middle">担任</th>
                                            </tr>
                                        </thead>
                                        <tbody class="text-center">
                                            <tr>
                                                <td class="p-0">
                                                    <div style="height: 50px;">

                                                    </div>
                                                </td>
                                                <td class="p-0">
                                                    <div style="height: 50px;">

                                                    </div>
                                                </td>
                                                <td class="p-0">
                                                    <div style="height: 50px;">

                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-md-3 text-center">
                                    <table class="table table-bordered table-diary">
                                        <thead class="text-center">
                                            <tr class="dark-blue">
                                                <th>在籍</th>
                                                <th>出席</th>
                                                <th>欠席</th>
                                            </tr>
                                        </thead>
                                        <tbody class="text-center">
                                            <tr>
                                                <td class="p-0 align-middle">
                                                    <div style="height: 100px;display:flex;align-items:center;justify-content:center;">
                                                        {{ stat.all }}
                                                    </div>
                                                </td>
                                                <td class="p-0 align-middle">
                                                    <div style="height: 100px;display:flex;align-items:center;justify-content:center;">
                                                        {{ stat.attend }}
                                                    </div>
                                                </td>
                                                <td class="p-0 align-middle">
                                                    <div style="height: 100px;display:flex;align-items:center;justify-content:center;">
                                                        {{ stat.absent }}
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="col-md-9">
                                    <table class="table table-bordered table-diary">
                                        <thead class="text-center">
                                            <tr class="dark-blue">
                                                <th>欠席児とその理由</th>
                                                <th>特記事項・行事等</th>
                                            </tr>
                                        </thead>
                                        <tbody class="text-center">
                                            <tr>
                                                <td class="p-0">
                                                    <textarea class="textarea-fit" v-model="reasonForAbsence" readonly>
                                                    </textarea>
                                                </td>
                                                <td class="p-0">
                                                    <textarea class="textarea-fit" v-model="specialNote" readonly>
                                                    </textarea>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-md-12">
                                    <table class="table table-bordered table-diary">
                                        <tbody class="text-center">
                                            <tr>
                                                <td rowspan="2" class="align-middle dark-blue p-1">
                                                   日案
                                                </td>
                                                <td class="dark-yellow align-middle">
                                                    ねらい
                                                </td>
                                                <td class="dark-yellow align-middle">
                                                    活動内容
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="p-0">
                                                    <textarea class="textarea-fit" v-model="aim" readonly>

                                                    </textarea>
                                                </td>
                                                <td class="p-0">
                                                    <textarea class="textarea-fit" v-model="activityContent" readonly>

                                                    </textarea>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="dark-blue p-1 align-middle">
                                                    援助<br>配慮事項
                                                </td>
                                                <td colspan="2" class="p-0">
                                                    <textarea class="textarea-fit" v-model="consideration" readonly>
                                                    </textarea>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="dark-blue p-1 align-middle">
                                                    保育の実際<br>評価・反省
                                                </td>
                                                <td colspan="2" class="p-0">
                                                    <textarea class="textarea-fit" v-model="evaluationReflection" readonly>
                                                    </textarea>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-md-12">
                                    <table class="table table-bordered table-diary">
                                        <tbody class="text-center">
                                            <tr>
                                                <td class="align-middle dark-blue p-1">
                                                   健康状態・その他
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="p-0">
                                                    <textarea class="textarea-fit" style="height: 150px;" v-model="healthStatus" readonly>

                                                    </textarea>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-md-12">
                                    <table class="table table-bordered table-diary">
                                        <tbody class="text-center">
                                            <tr>
                                                <td class="align-middle dark-blue p-1">
                                                   備　　　　　考
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="p-0">
                                                    <textarea class="textarea-fit" style="height: 150px;" v-model="remarks" readonly>

                                                    </textarea>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="float-right d-flex align-items-center mt-2">
                                <button class="btn btn-primary float-right mr-2" @click="exportExcel">Excel出力</button>
                                <button class="btn btn-primary float-right" type="button" @click="onEdit">編集</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>
<script>
import Datepicker from "vuejs-datepicker";
import moment from 'moment-timezone';
import { ja } from 'vuejs-datepicker/dist/locale';
import { mapState } from 'vuex';
import api, { apiErrorHandler } from '../global/api';
import actionLoading from '../mixin/actionLoading';
import { showSuccess } from '../helpers/error';
import LocalStorage from '../helpers/localStorage';

export default {
    components: {
        Datepicker
    },
    mixins: [actionLoading],
    data() {
        return {
            date: moment(),
            todayDate: "",
            selectedDate: new Date(),
            childrenClassId: null,
            weather: '',
            reasonForAbsence: '',
            aim: '',
            activityContent: '',
            consideration: '',
            evaluationReflection: '',
            healthStatus: '',
            remarks: '',
            specialNote: '',
            ja: ja,
            office: {},
            stat: {
                all: '',
                attend: '',
                absent: ''
            }
        }
    },
    computed: {
        ...mapState({
            childrenClasses: state => state.constants.childrenClasses,
        }),
        classLabel() {
            const claz = this.childrenClasses.find(item => item.id == this.childrenClassId)
            if (claz) return claz.name;
            return '';
        }
    },
    mounted() {
        if (this.$route.query.date) {
            this.date = moment(this.$route.query.date, 'YYYY-MM-DD');
            this.todayDate = moment(this.$route.query.date, 'YYYY-MM-DD').format('YYYY年 M月 D日 (ddd)');
            this.selectedDate = this.$route.query.date;
        }
        this.childrenClassId = this.$route.params.classId;
        this.fetchData();
        this.fetchOffice();
        this.fetchStat();
    },
    methods: {
        onEdit() {
            this.$router.push({name: 'childcare-diary', params: {classId: this.$route.params.classId}, query: {date: this.$route.query.date}});
        },
        fetchData() {
            this.setActionLoading();
            api.get('child-diary', null, { childrenClassId: this.childrenClassId, date: this.date.format('YYYY-MM-DD')})
            .then(response => {
                this.weather = response.weather;
                this.reasonForAbsence = response.reasonForAbsence;
                this.aim = response.aim;
                this.activityContent = response.activityContent;
                this.consideration = response.consideration;
                this.evaluationReflection = response.evaluationReflection;
                this.healthStatus = response.healthStatus;
                this.specialNote = response.specialNote;
                this.remarks = response.remarks;
            })
            .catch(apiErrorHandler)
            .finally(this.unsetActionLoading);
        },
        fetchOffice() {
            api.get('current-office')
            .then(response => this.office = response)
            .catch(apiErrorHandler)
        },
        fetchStat() {
            api.get('attendance-daily-stat', null, { date: this.date.format('YYYY-MM-DD' ), childrenClassId: this.childrenClassId})
            .then(response => {this.stat = response})
            .catch(apiErrorHandler);
        },
        openDatePicker(){
            this.$refs.programaticOpen.showCalendar();
        },
        customFormatter(date) {
            return moment(date).format('YYYY/M/D(ddd)');
        },
        exportExcel() {
            location.href = process.env.MIX_APP_BASE_URL + 'childcare-diary/excel?children_class_id=' + this.childrenClassId + '&date=' + this.date.format('YYYY-MM-DD') + '&token=' + LocalStorage.getToken();
        },
        getDiaryRead(date) {
            if(date){
                this.date = date;
            } else {
                this.date = new Date();
            }
            this.date = moment(this.date);
            this.fetchData();
            this.fetchStat();
        },
        onNext() {
            this.date = moment(this.date.add(1, 'days').format('YYYY-MM-DD'), 'YYYY-MM-DD');
            this.selectedDate = moment(this.date).format('YYYY-MM-DD');
            this.fetchData();
            this.fetchStat();
        },
        onPrev() {
            this.date = moment(this.date.add(-1, 'days').format('YYYY-MM-DD'), 'YYYY-MM-DD');
            this.selectedDate = moment(this.date).format('YYYY-MM-DD');
            this.fetchData();
            this.fetchStat();
        },
        onCurrentMonth(){
            this.date = moment();
            this.selectedDate = moment(this.date).format('YYYY-MM-DD');
            this.fetchData();
            this.fetchStat();
        },
    }
};
</script>
<style scoped>
    .input-fit {
        border: none;
        outline: none;
    }
    .calendar-left {
        display: flex;
        justify-content: left;
        align-items: center;
    }
    .calendar-center {
        display: flex;
        justify-content: center;
        align-items: center;
    }
    .calendar-title {
        display: flex;
        align-items: center;
    }
</style>
