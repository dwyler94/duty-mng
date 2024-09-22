<template>
    <section class="content">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-12">
                    <div class="card" v-if="session.office">
                        <div class="card-header calendar-title">
                            <h3 class="card-title mb-0">{{officeName}}</h3>
                            <div class="card-tools calendar-center flex-grow-1">
                                <button type="button" class="btn btn-sm btn-outline" @click="getResults(getPrevMonthDate())">
                                    <i class="fas fa-caret-left fa-2x"></i>
                                </button>
                                <div class="mx-2">{{displayDate}}</div>
                                <button type="button" class="btn btn-sm btn-outline-primary mx-2" @click="getResults(getThisMonthDate())">
                                    今月
                                </button>
                                <button type="button" class="btn btn-sm btn-outline" :hidden="isThisMonth()" @click="getResults(getNextMonthDate())">
                                    <i class="fas fa-caret-right fa-2x"></i>
                                </button>
                            </div>
                        </div>
                        <attend-status-a v-if="session.employmentStatusId == 1 && !isHonShya(session.office.name)" :attendance="attendance" :total="total" :month="month"/>
                        <attend-status-b v-else-if="session.employmentStatusId == 1 && isHonShya(session.office.name)" :attendance="attendance" :total="total" :month="month"/>
                        <attend-status-c v-else-if="session.employmentStatusId == 3" :attendance="attendance" :total="total" :month="month"/>
                    </div>
                    <div v-else>
                        <p class="h3 text-center">
                            あなたは現在どの事業所にも登録されていません。事業所に登録してください。
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<script>
import moment from 'moment';
import { mapState } from 'vuex';
import api, { apiErrorHandler } from '../global/api';
import AttendStatusA from './AttendStatus/AttendStatusA.vue';
import AttendStatusB from './AttendStatus/AttendStatusB.vue';
import AttendStatusC from './AttendStatus/AttendStatusC.vue';
import actionLoading from '../mixin/actionLoading';
import { showSuccess } from '../helpers/error';

export default {
    components: { AttendStatusA, AttendStatusB, AttendStatusC },
    mixins: [actionLoading],
    data () {
        return {
            editmode: false,
            currentDate: new Date(),
            displayDate: new Date(),
            days: [],
            attendance: {},
            total: {},
            selectedMonth: '',
            month: new Date('YYYY-MM'),
            officeName: '',
            offices: [],
        }
    },
    computed: {
        ...mapState({
            applicationClasses: state => state.constants.applicationClasses,
            session: state =>  state.session.info
        }),
    },
    methods: {
        getAttendance() {
            if (this.actionLoading) return;
                this.setActionLoading();
                api.get('attendance/status', null, {month: this.selectedMonth})
                    .then(response => {
                        this.unsetActionLoading();
                        this.attendance = response.attendance;
                        this.total = response.total;
                    })
                    .catch(e => {
                        apiErrorHandler(e);
                        this.unsetActionLoading();
                    });
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
        isHonShya(officeName) {
            if(officeName.indexOf('本社') !== -1) {
                return true;
            } else {
                return false;
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
            this.month = moment(new Date(date.getFullYear(), date.getMonth(), 1)).format('YYYY-MM');
            this.getAttendance();
            return new Date(date.getFullYear(), date.getMonth(), 1);
        },
        getNextMonthDate() {
            const date = this.currentDate;
            this.displayDate = moment(new Date(date.getFullYear(), date.getMonth() + 1, 1)).format('YYYY年 M月');
            this.selectedMonth = moment(new Date(date.getFullYear(), date.getMonth() + 1, 1)).format('YYYYMM');
            this.month = moment(new Date(date.getFullYear(), date.getMonth() + 1, 1)).format('YYYY-MM');
            this.getAttendance();
            return new Date(date.getFullYear(), date.getMonth() + 1, 1);
        },
        getPrevMonthDate() {
            const date = this.currentDate;
            this.displayDate = moment(new Date(date.getFullYear(), date.getMonth() - 1, 1)).format('YYYY年 M月');
            this.selectedMonth = moment(new Date(date.getFullYear(), date.getMonth() - 1, 1)).format('YYYYMM');
            this.month = moment(new Date(date.getFullYear(), date.getMonth() - 1, 1)).format('YYYY-MM');
            this.getAttendance();
            return new Date(date.getFullYear(), date.getMonth() - 1, 1);
        },
        getResults(month_date) {
            this.updateTable(month_date);
        },
        currentTime(){
            var today = new Date();
            var month = today.getMonth() + 1;
            var day = today.getDate();
            return month + "月" + day + "日 "
            + today.getHours() + ":"
            + today.getMinutes();
        },
        updateTable(date){
            this.currentDate = date;
            var firstDay = new Date(date.getFullYear(), date.getMonth(), 1).getDate();
            var lastDay = new Date(date.getFullYear(), date.getMonth() + 1, 0).getDate();
            this.days = [];
            for(let day = firstDay; day <= lastDay; day++) {
                this.days.push(new Date(date.getFullYear(), date.getMonth(), day));
            }
        },

    },
    created() {
        this.selectedMonth = moment(this.displayDate).format('YYYYMM');
        this.month = moment(this.displayDate).format('YYYY-MM');
        this.displayDate = moment(this.displayDate).format('YYYY年 M月');
    },
    mounted() {
        this.getAttendance();
        this.getResults(this.currentDate);
        this.officeName = this.session.office ? this.session.office.name : null;
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
