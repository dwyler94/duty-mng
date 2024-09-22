<template>
    <section class="content">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header calendar-title">
                            <h3 class="card-title mb-0">{{officeName}}</h3>
                            <div>
                                <table class="table table-bordered table-black-bordered mb-0 ml-5">
                                    <thead class="text-center">
                                        <tr>
                                            <th colspan="2" class="padding-1px">所定労働</th>
                                        </tr>
                                    </thead>
                                    <tbody class="text-center">
                                        <tr>
                                            <td class="padding-1px">日数</td>
                                            <td class="padding-1px">{{shoteiTime}}日</td>
                                        </tr>
                                        <tr>
                                            <td class="padding-1px">時間</td>
                                            <td class="padding-1px">{{session.workingHours*shoteiTime}}時間</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="card-tools calendar-center flex-grow-1">
                                <button type="button" class="btn btn-sm btn-outline" @click="getResults(getPrevMonthDate())">
                                    <i class="fas fa-caret-left fa-2x"></i>
                                </button>
                                <div class="mx-2">{{displayDate}}</div>
                                <button type="button" class="btn btn-sm btn-outline-primary mx-2" @click="getResults(getThisMonthDate())">
                                    今月
                                </button>
                                <button type="button" class="btn btn-sm btn-outline" @click="getResults(getNextMonthDate())">
                                    <i class="fas fa-caret-right fa-2x"></i>
                                </button>
                                <div class="form-group mx-4 mb-0">
                                    <select class="form-control" v-model="officeId" @change="getShifts">
                                        <option v-for="office in offices" :key="office.id" :value="office.id">{{office.name}}</option>
                                    </select>
                                </div>
                            </div>
                            <button class="btn btn-primary float-right mt-2" @click="copyShift()">コピーして作成</button>
                        </div>
                        <div class="card-body">
                        <div class="p-0 overflow-scroll-x">
                            <table class="table table-bordered shifttable-bordered table-hover w-2500-px">
                                <thead class="text-center">
                                    <tr class="light-green">
                                        <th width="4%" class="align-middle shift-th-x">日付</th>
                                        <th v-for="(day, index) in days" :key="day.getDate()" class="align-middle">
                                            <a href="#" @click="openDayShiftPopup(index)">
                                                {{day.getDate()}}日
                                            </a>
                                        </th>
                                        <th class="align-middle heavy-green" rowspan="4">勤務予定<br>日数</th>
                                        <th class="align-middle heavy-green" rowspan="4">実働予定<br>時間</th>
                                    </tr>
                                    <tr class="light-green">
                                        <th class="align-middle shift-th-x">曜日</th>
                                        <th v-for="day in days" :key="day.getDate()" class="align-middle">
                                            <div v-if="getWeekEnd(day) === 1" class="blue">{{day|formatWeek}}</div>
                                            <div v-else-if="getWeekEnd(day) === 2" class="red">{{day|formatWeek}}</div>
                                            <div v-else>{{day|formatWeek}}</div>
                                        </th>
                                    </tr>
                                    <tr v-if="isHoikuen(officeName)" class="light-green">
                                        <th width="4%" class="align-middle shift-th-x">園児数</th>
                                        <th v-for="(child, index) in childcarePlans" :key="'children' + index" class="align-middle">
                                            {{child.children}}
                                        </th>
                                    </tr>
                                    <tr v-if="isHoikuen(officeName)" class="light-green">
                                        <th class="align-middle shift-th-x">必要職員数</th>
                                        <th v-for="(user, index) in childcarePlans" :key="'needUser' + index" class="align-middle">
                                            {{user.needUser}}
                                        </th>
                                    </tr>
                                </thead>
                                    <tbody class="text-center">

                                        <tr v-for="shift in shifts" :key="shift.id">
                                            <td class="shift-fix-x">
                                                {{shift.name}}
                                            </td>
                                            <td v-for="(dayShift, index) in shift.shifts" :key="index">
                                                <template v-if="dayShift.length">
                                                    <a href="#" @click="openShiftPopup(shift.id, index)" v-for="shiftItem in dayShift" :key="shiftItem.id">
                                                        <template v-if="shiftItem.workingHourName">
                                                            {{shiftItem.workingHourName}}
                                                        </template>
                                                        <template v-else-if="shiftItem.vacationReasonId">
                                                            休
                                                        </template>
                                                        <template v-else>
                                                            custom
                                                        </template>
                                                        <br>
                                                    </a>
                                                </template>
                                                <template v-else>
                                                    <a href="#" @click="openShiftPopup(shift.id, index)">
                                                        <i class="fa fa-edit black"></i>
                                                    </a>
                                                </template>
                                            </td>
                                            <td>{{getWorkingDays(shift.shifts)}}</td>
                                            <td>{{getRealWorkingTimes(shift.shifts)}}</td>
                                        </tr>
                                    </tbody>
                            </table>
                        </div>
                        <button class="btn btn-primary float-right mt-2" @click="getCShift">Excel出力</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal -->
            <div class="modal fade" id="shiftForm" tabindex="-1" role="dialog" aria-labelledby="shiftForm" aria-hidden="true" style="z-index: 10000;">
                <div class="modal-dialog" role="document">
                    <shift-form :employee="selectedEmployee"
                                :workingHours="capableWorkingHours"
                                :vacations="enabledVacations"
                                :shifts="selectedShift"
                                :date="selectedDate"
                                :officeId="officeId"
                                v-on:success="onShiftSaved"
                    />
                </div>
            </div>

            <!-- Modal -->
            <div class="modal fade" id="shiftDateForm" tabindex="-1" role="dialog" aria-labelledby="shiftDateForm" aria-hidden="true">
                <div class="modal-dialog modal-grand" role="document">
                    <shift-date-form
                        :date="selectedDate"
                        :shifts="dateSelectedShift"
                        :childShift="dateSelectedCShift"
                        v-on:success="onShiftSaved"/>
                </div>
            </div>

        </div>
    </section>
</template>
<script>
import moment from 'moment';
import { mapState } from 'vuex';
import actionLoading from '../mixin/actionLoading';
import api, { apiErrorHandler } from '../global/api';
import ShiftDateForm from './ShiftCreate/ShiftDateForm.vue';
import ShiftForm from './ShiftCreate/ShiftForm.vue';
import LocalStorage from '../helpers/localStorage';
import { showSuccess } from '../helpers/error';

export default {
  components: { ShiftDateForm, ShiftForm },
        mixins: [actionLoading],
        data () {
            return {
                editmode: false,
                currentDate: new Date(),
                displayDate: new Date(),
                days: [],
                attends : [],
                requests : [],
                officeId: null,
                officeName: '',
                shoteiTime: null,
                offices: [],
                shifts: [],
                selectedMonth: '202112',
                selectedEmployee: {},
                selectedDate: '',
                capableWorkingHours: [],
                selectedShift: [],
                enabledVacations:[],
                dateSelectedShift: [],
                dateSelectedCShift: {},
                children: [],
                needUser: [],
                childcarePlans: [],
            }
        },
        watch: {
            // officeId: function() {
            //     this.officeName = this.offices[officeId].name;
            // }
        },
        computed: {
            ...mapState({
                employmentStatuses: state => state.constants.employmentStatuses,
                reasonForVacations: state => state.constants.reasonForVacations,
                session: state =>  state.session.info,
            })
        },
        methods: {
            getShifts() {
                if(this.actionLoading) return;
                this.setActionLoading();
                api.get('shift/' + this.officeId, null, {month: this.selectedMonth})
                    .then(response => {
                        this.unsetActionLoading();
                        this.shifts = response.employeeShifts;
                        this.childcarePlans = response.childcarePlans;
                        const office = this.offices.find(office => office.id === this.officeId);
                        this.officeName = office ? office.name : '';
                        this.getShoteiTime();
                    })
                    .catch(e => {
                        this.unsetActionLoading();
                        apiErrorHandler(e);
                    });
            },
            getCShift() {
                const office = this.offices.find(office => office.id === this.officeId);
                if (!office) return;
                location.href = "/shift/csv/" + office.id + "?token=" + LocalStorage.getToken() + "&month=" + this.selectedMonth;
            },
            getOffices() {
                api.get('office/user-capable')
                    .then(response => {
                        this.offices = response;
                        if(this.offices.length === 0) return;
                        let office;
                        if(!this.officeId) {
                            office = this.offices[0];
                            this.officeId = office.id;
                        } else {
                            office = this.offices.find(office => office.id === this.officeId);
                        }
                        this.officeName = office ? office.name : '';
                        this.getShifts();
                        this.getShoteiTime();
                    })
                    .catch(e => apiErrorHandler(e))
            },
            getWorkingHours(employeeId) {
                api.get('working-hours/user/' + employeeId)
                    .then(response => {
                        this.capableWorkingHours = response;
                    })
                    .catch(e => apiErrorHandler(e))
            },
            getVacations() {
                api.get('reason-for-vacation/enable')
                    .then(response => {
                        this.enabledVacations = response;
                    })
                    .catch(e => apiErrorHandler(e))
            },
            getShoteiTime() {
                api.get('office/' + this.officeId + '/scheduled-working-month', null, {month: this.selectedMonth})
                    .then(response => {
                        if(response)
                            this.shoteiTime = response.days;
                        else
                            this.shoteiTime = 0;
                    })
                    .catch(e => apiErrorHandler(e))
            },
            getWorkingDays(shift) {
                let days = 0;
                for (let i=0; i<shift.length; i++) {
                    if(shift[i].length) {
                        for (let j=0; j<shift[i].length; j++) {
                            if(!shift[i][j].vacationReasonId)
                                days++;
                                break;
                        }
                    }
                }
                return days;
            },
            getRealWorkingTimes(shift) {
                let workingTimes = 0;
                for (let i=0; i<shift.length; i++) {
                    if(shift[i].length) {
                        for (let j=0; j<shift[i].length; j++) {
                            if(!shift[i][j].vacationReasonId) {
                                let dayTimes;
                                if(shift[i][j].endTime >= shift[i][j].startTime)
                                    dayTimes = (new Date('2021-01-01 ' + shift[i][j].endTime).getTime() - new Date('2021-01-01 ' + shift[i][j].startTime).getTime());
                                 else
                                    dayTimes = (new Date('2021-01-02 ' + shift[i][j].endTime).getTime() - new Date('2021-01-01 ' + shift[i][j].startTime).getTime());
                                if(shift[i][j].restEndTime && shift[i][j].restStartTime) {
                                    if(shift[i][j].restEndTime >= shift[i][j].restStartTime)
                                        dayTimes = dayTimes -(new Date('2021-01-01 ' + shift[i][j].restEndTime).getTime() - new Date('2021-01-01 ' + shift[i][j].restStartTime).getTime());
                                    else
                                        dayTimes = dayTimes -(new Date('2021-01-02 ' + shift[i][j].restEndTime).getTime() - new Date('2021-01-01 ' + shift[i][j].restStartTime).getTime());
                                }
                                workingTimes += dayTimes/(1000*60*60);
                            }
                        }
                    }
                }
                return workingTimes.toFixed(2);
            },
            getWeekEnd(day) {
                const weekDay = moment(day).format("ddd");;
                if (weekDay === '土'){
                    return 1;
                } else if(weekDay === '日'){
                    return 2;
                } else {
                    return 0;
                }
            },
            isThisMonth() {
                const today = new Date();
                return this.currentDate.getFullYear() == today.getFullYear() && this.currentDate.getMonth() == today.getMonth();
            },
            getThisMonthDate() {
                const date = new Date();
                this.displayDate = moment(new Date(date.getFullYear(), date.getMonth(), 1)).format('YYYY年 M月');
                this.selectedMonth = moment(new Date(date.getFullYear(), date.getMonth(), 1)).format('YYYYMM');
                this.getShoteiTime();
                this.getShifts();
                return new Date(date.getFullYear(), date.getMonth(), 1);
            },
            getNextMonthDate() {
                const date = this.currentDate;
                this.displayDate = moment(new Date(date.getFullYear(), date.getMonth() + 1, 1)).format('YYYY年 M月');
                this.selectedMonth = moment(new Date(date.getFullYear(), date.getMonth() + 1, 1)).format('YYYYMM');
                this.getShoteiTime();
                this.getShifts();
                return new Date(date.getFullYear(), date.getMonth() + 1, 1);
            },
            getPrevMonthDate() {
                const date = this.currentDate;
                this.displayDate = moment(new Date(date.getFullYear(), date.getMonth() - 1, 1)).format('YYYY年 M月');
                this.selectedMonth = moment(new Date(date.getFullYear(), date.getMonth() - 1, 1)).format('YYYYMM');
                this.getShoteiTime();
                this.getShifts();
                return new Date(date.getFullYear(), date.getMonth() - 1, 1);
            },
            getResults(month_date) {
                //this.$Progress.start();
                this.loadAttends(month_date);
                this.loadRequests(month_date);
                this.updateTable(month_date);
                //this.$Progress.finish();
            },
            openShiftPopup(employeeId, dateIndex){
                const employee = this.shifts.find(item => item.id === employeeId);
                if (!employee) return;
                const {id, name, employmentStatusId, roleId} = employee;
                this.selectedEmployee = {id, name, employmentStatusId, roleId};

                this.selectedDate = this.selectedMonth.slice(0,4) + '-' + this.selectedMonth.slice(4) + '-' + ('0' + (dateIndex + 1)).slice(-2);
                this.getWorkingHours(employeeId);
                this.selectedShift = this.shifts.filter(item => item.id == employeeId)[0].shifts[dateIndex];
                this.getVacations();
                $('#shiftForm').modal('show');
            },
            openDayShiftPopup(dayIndex){
                this.selectedDate = this.selectedMonth.slice(0,4) + '-' + this.selectedMonth.slice(4) + '-' + ('0' + (dayIndex + 1)).slice(-2);
                for(let em = 0; em < this.shifts.length; em++) {
                    this.dateSelectedShift[em] = {...this.shifts[em], shifts: [...this.shifts[em].shifts[dayIndex]]};
                }

                api.get('childcare-detail/' + this.officeId, null, {date: this.selectedDate})
                    .then(response => {
                        if(response) {
                            this.dateSelectedCShift = response;
                        } else {
                            this.dateSelectedCShift = {};
                        }
                        $('#shiftDateForm').modal('show');
                    })
                    .catch(e => apiErrorHandler(e))
            },
            copyShift() {
                if (this.actionLoading) return;
                if (!confirm(this.$t("Are you sure copying shift?"))) return;
                this.setActionLoading();
                api.post('shift/' + this.officeId + '/copy', null, {month: this.selectedMonth})
                    .then(() => {
                        this.unsetActionLoading();
                        showSuccess(this.$t('Successfully copied'));
                        this.getShifts();
                    })
                    .catch(e => {
                        this.unsetActionLoading();
                        apiErrorHandler(e);
                    });
            },
            currentTime(){
                var today = new Date();
                var month = today.getMonth() + 1;
                var day = today.getDate();
                return month + "月" + day + "日 "
                + today.getHours() + ":"
                + today.getMinutes();
            },
            loadAttends(date){
                //TODO: axios.get
                this.attends = [
                    {
                        date: new Date('2021/09/01'),
                    },
                ];
            },
            loadRequests(date){
                //TODO: axios.get
                this.requests = {

                };
            },
            isHoikuen(officeName) {
                if(officeName.indexOf('保育園') !== -1) {
                return true;
                } else {
                    return false;
                }
            },
            updateTable(date){
                this.currentDate = date;
                var firstDay = new Date(date.getFullYear(), date.getMonth(), 1).getDate();
                var lastDay = new Date(date.getFullYear(), date.getMonth() + 1, 0).getDate();
                this.days = [];
                this.children = [];
                this.needUser = [];
                for(let day = firstDay; day <= lastDay; day++) {
                    this.days.push(new Date(date.getFullYear(), date.getMonth(), day));
                    let random = Math.floor(Math.random() * 7) + 10;
                    this.children.push(random);
                    this.needUser.push(Math.floor(random / 3));
                }

            },
            onShiftSaved() {
                this.getShifts();
                $('#shiftForm').modal('hide');
            }
        },
        created() {
            this.selectedMonth = moment(this.displayDate).format('YYYYMM');
            this.displayDate = moment(this.displayDate).format('YYYY年 M月');
            this.getResults(this.currentDate);
        },
        mounted() {
            this.getOffices();
        }
    }
</script>

<style scoped>
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
