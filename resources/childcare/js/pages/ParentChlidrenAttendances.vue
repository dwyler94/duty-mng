<template>
    <section class="content">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header calendar-title parent-attendance">
                            <h3 class="card-title mb-0">{{ officeName }}</h3>
                            <div class="card-title mb-0 ml-3 child-name">{{ childName }}</div>
                            <div class="card-tools calendar-center flex-grow-1">
                                <button type="button" class="btn btn-sm btn-outline" @click="onPrev">
                                    <i class="fas fa-caret-left fa-2x"></i>
                                </button>
                                <div class="mx-2">{{displayDate}}</div>
                                <button type="button" class="btn btn-sm btn-outline-primary mx-2" @click="onCurrentMonth">
                                    今月
                                </button>
                                <button type="button" class="btn btn-sm btn-outline" :hidden="isCurrentMonth" @click="onNext">
                                    <i class="fas fa-caret-right fa-2x"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive p-0" style="height: 500px;" id="children_attendance_table">
                                <table class="table table-bordered table-striped table-children table-head-fixed table-hover">
                                    <thead class="text-center text-white">
                                        <tr class="dark-brown">
                                            <th class="children-present-fix" style="left: 0px; z-index: 12 !important; outline: white solid 1px;">日付</th>
                                            <th class="children-present-fix-60" style="z-index: 12 !important; outline: white solid 1px;">曜日</th>
                                            <th class="attend-time">登園時間</th>
                                            <th class="attend-time">降園時間</th>
                                            <th>欠席</th>
                                            <th>延長</th>
                                            <th>連絡帳</th>
                                        </tr>
                                    </thead>
                                        <tbody class="text-center children-present-tr">
                                            <tr v-for="(item, index) in attendances" :key="index">
                                                <td class="children-present-fix">
                                                    {{ item.day }}
                                                </td>
                                                <td class="children-present-fix-60">
                                                    <div v-if="getDayOfWeek(item.day) === 6" class="blue">{{getDayOfWeekLabel(item.day)}}</div>
                                                    <div v-else-if="getDayOfWeek(item.day) === 0" class="red">{{getDayOfWeekLabel(item.day)}}</div>
                                                    <div v-else>{{getDayOfWeekLabel(item.day)}}</div>
                                                </td>
                                                <td>{{ formatTime(item.commutingTime) }}</td>
                                                <td>{{ formatTime(item.leaveTime) }}</td>
                                                <td>{{ getAbsenceName(item.reasonForAbsenceId, item.noSchedule) }}</td>
                                                <td>{{ item.extension ? item.extension : '' }}</td>
                                                <td>
                                                    <a href="javascript:void(0)" class="mx-2" @click="openContactBook(item.day)" v-if="isAfterAdmission(item.day)">
                                                        <!-- <template v-if="item.contactStatus == 0">未入力</template> -->
                                                        <!-- <template v-else>確認</template> -->
                                                        確認
                                                    </a>
                                                </td>
                                            </tr>
                                        </tbody>
                                </table>
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
import { ja } from 'vuejs-datepicker/dist/locale';
import moment from 'moment-timezone';
import { mapState } from 'vuex';
import actionLoading from '../mixin/actionLoading';
import api, { apiErrorHandler } from '../global/api';

const defaultAttendance = {
    id: null,
    commutingTimeHour: '',
    commutingTimMin: '',
    leaveTimeHour: '',
    leaveTimeMin: '',
    reasonForAbsenceId: null,
    extension: '',
};

export default {
    components: {
        Datepicker
    },
    mixins: [actionLoading],
    data () {
        return {
            currentDate: moment(),
            childId: null,
            attendances: [],
            selectedDate: new Date(),
        }
    },
    computed: {
        displayDate() {
            return this.currentDate.format('YYYY年 MM月');
        },
        isCurrentMonth() {
            return this.currentDate.format('YYYY-MM') === moment().format('YYYY-MM');
        },
        ...mapState({
            officeName: state =>  {
                if (state.session.info.office) return state.session.info.office.name;
                return '';
            },
            childName: state => {
                if (state.session.info.name) return state.session.info.name;
                return '';
            },
            sessionChildId: state => {
                if (state.session.info.id) return state.session.info.id;
                return '';
            },
            reasonForAbsences: state => state.constants.reasonForAbsences,
            session: state => state.session.info,
        }),
    },
    methods: {
        onNext() {
            this.currentDate = moment(this.currentDate.add(1, 'months').format('YYYY-MM-DD'), 'YYYY-MM-DD');
            this.fetchData();
        },
        onPrev() {
            this.currentDate = moment(this.currentDate.add(-1, 'months').format('YYYY-MM-DD'), 'YYYY-MM-DD');
            this.fetchData();
        },
        onCurrentMonth(){
            this.currentDate = moment();
            this.fetchData();
        },
        openContactBook(day) {
            this.selectedDate = moment(this.currentDate.format('YYYY-MM-') + String(day).padStart(2, '0')).toDate();
            const queryDate = moment(this.selectedDate).format('YYYY-MM-DD');
            this.$router.push({name: 'parent-contact-book', params: {date: queryDate}});
        },
        isAfterAdmission(day) {
            const selectedDate = moment(this.currentDate.format('YYYY-MM-') + String(day).padStart(2, '0')).toDate();
            const queryDate = moment(selectedDate).add(1, "days").format('YYYY-MM-DD');
            if(this.session.admissionDate) {
                if (queryDate >= this.session.admissionDate) {
                    return true;
                }
            }
            return false;
        },
        fetchData() {
            this.setActionLoading();
            api.get(`monthly-attendance/${this.childId}`, null, { month: this.currentDate.format('YYYY-MM') })
            .then(this.unpack)
            .catch(apiErrorHandler)
            .finally(this.unsetActionLoading)
        },
        unpack(attendances) {
            this.attendances = [];
            const daysInMonth = this.currentDate.daysInMonth();
            for (let day = 1; day <= daysInMonth; day++) {
                const attendance = attendances.find(item => item.day === day);
                if (attendance) {
                    this.attendances.push(attendance);
                } else {
                    this.attendances.push({...defaultAttendance, day});
                }
            }
        },
        getDayOfWeek(day) {
            const newDate = moment(this.currentDate.format('YYYY-MM-DD'), 'YYYY-MM-DD');
            newDate.set('date', day);
            return newDate.day();
        },
        getDayOfWeekLabel(day){
            const newDate = moment(this.currentDate.format('YYYY-MM-DD'), 'YYYY-MM-DD');
            newDate.set('date', day);
            return newDate.format('ddd');
        },
        formatTime(time) {
            return time ? moment(time).format('HH:mm') : '';
        },
        getAbsenceName(reasonForAbsenceId, noSchedule) {
            if(reasonForAbsenceId){
                if(noSchedule) {
                    if(this.reasonForAbsences.find(item => item.id === reasonForAbsenceId)) {
                        if(reasonForAbsenceId == 7)
                            return '（予）' + this.reasonForAbsences.find(item => item.id === reasonForAbsenceId).name;
                        else
                            return '（予）欠席';
                    } else {
                        return null;
                    }
                } else {
                    if(this.reasonForAbsences.find(item => item.id === reasonForAbsenceId)) {
                        if (reasonForAbsenceId == 7)
                            return this.reasonForAbsences.find(item => item.id === reasonForAbsenceId).name;
                        else
                            return '欠席';
                    }else {
                        return null;
                    }
                }

            } else {
                if(noSchedule) return '託児計画なし';
                return null;
            }
        },
    },
    mounted() {
        this.childId = this.sessionChildId;
        this.fetchData();
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
