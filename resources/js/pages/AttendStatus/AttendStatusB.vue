<template>
    <div class="card-body">
        <div class="table-responsive p-0" style="height: 500px;" @scroll="handleScroll()">
            <table class="table table-head-fixed table-bordered table-striped table-kintai table-hover">
                <thead class="text-center text-white">
                    <tr class="heavy-green">
                        <th>日付</th>
                        <th>曜日</th>
                        <th>出勤①</th>
                        <th>退勤①</th>
                        <th>出勤②</th>
                        <th>退勤②</th>
                    </tr>
                </thead>
                    <tbody class="text-center">
                        <tr v-for="(dayAttendance, index) in attendance" :key="dayAttendance.id">
                            <td>{{index}}日</td>
                            <td v-if="getWeekEnd(index) === 1" class="blue">{{getDate(index)|formatWeek}}</td>
                            <td v-else-if="getWeekEnd(index) === 2" class="red">{{getDate(index)|formatWeek}}</td>
                            <td v-else>{{getDate(index)|formatWeek}}</td>
                            <td>{{changeTimeFormat(dayAttendance.commutingTime1)}}</td>
                            <td>{{changeTimeFormat(dayAttendance.leaveTime1)}}</td>
                            <td>{{changeTimeFormat(dayAttendance.commutingTime2)}}</td>
                            <td>{{changeTimeFormat(dayAttendance.leaveTime2)}}</td>
                        </tr>
                    </tbody>
            </table>
        </div>
        <br>
        <div class="table-responsive p-0" id="sumTable">
            <table class="table table-bordered text-white">
                <thead class="text-center">
                    <tr class="top-green">
                        <th>実働時間合計</th>
                        <th>残業外労働時間</th>
                        <th>所定労働時間-A</th>
                        <th>所定労働時間-B</th>
                        <th>過不足時間</th>
                        <th>残業時間</th>
                    </tr>
                </thead>
                <tbody class="text-center">
                    <tr class="heavy-green">
                        <td>{{(total.totalWorkingHours / 60).toFixed(2)}}</td>
                        <td>{{(total.actualWorkingHoursWeekdays / 60).toFixed(2)}}</td>
                        <td>{{(total.scheduledWorkingHoursA / 60).toFixed(2)}}</td>
                        <td>{{(total.scheduledWorkingHoursB / 60).toFixed(2)}}</td>
                        <td>{{(total.excessAndDeficiencyTime / 60).toFixed(2)}}</td>
                        <td>{{((total.overtimeHoursWeekdays + total.overtimeHoursSaturday) / 60).toFixed(2)}}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <!-- Modal -->
        <div class="modal fade" id="appForm" tabindex="-1" role="dialog" aria-labelledby="appForm" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <application-form :applications="application" :attendanceId="selectedAttendanceId" :dayIndex="selectedDayIndex" :month="month" v-on:success="onAppSaved"></application-form>
            </div>
        </div>
    </div>
</template>
<script>
import moment from 'moment';
import { mapState } from 'vuex';
import ApplicationForm from './ApplicationForm.vue';
    export default {
  components: { ApplicationForm },
        props: {
            attendance: {},
            total: {},
            month: '',
        },
        data () {
            return {
                scrolling: false,
                timer: null,
                application: [],
                selectedAttendanceId: null,
                selectedDayIndex: null,
            }
        },
        computed: {
            ...mapState({
                applicationClasses: state => state.constants.applicationClasses
            })
        },
        methods: {
            updateRequest(){
                $('#addNew').modal('hide');
                //TODO: this.form.post
                this.loadRequests();
            },
            requestApp(application, selectedAttendanceId, dayIndex){
                // this.editmode = true;
                // this.firstdate = this.enddate;
                this.application = application;
                this.selectedAttendanceId = selectedAttendanceId;
                this.selectedDayIndex = dayIndex;
                $('#appForm').modal('show');
            },
            getDate(index) {
                const date = this.month + '-' + ('0' + index).slice(-2);
                return date;
            },
            getWeekEnd(index) {
                const weekDay = moment(this.month + '-' + ('0' + index).slice(-2)).format('ddd');
                if (weekDay === '土'){
                    return 1;
                } else if(weekDay === '日'){
                    return 2;
                } else {
                    return 0;
                }
            },
            notZero(number) {
                if(number > 0) {
                    return Math.floor(number).toString() + '分';
                } else {
                    return '-';
                }
            },
            currentTime(){
                var today = new Date();
                var month = today.getMonth() + 1;
                var day = today.getDate();
                return month + "月" + day + "日 "
                + today.getHours() + ":"
                + today.getMinutes();
            },
            handleScroll() {
                if(this.timer !== null) {
                    clearTimeout(this.timer);
                    $("#sumTable").fadeOut(400);
                }
                this.timer = setTimeout(function() {
                    $("#sumTable").fadeIn(400);
                }, 150);
            },
            changeTimeFormat(date) {
                if(date) {
                    return moment(date).tz('Asia/Tokyo').format('HH:mm');
                } else {
                    return "-";
                }
            },
            onAppSaved() {

                $('#appForm').modal('hide');
            }
        },
        created() {
            this.timer = null;
        },
        mounted() {
            window.addEventListener('scroll', this.handleScroll);
        },
        destroyed() {
            window.removeEventListener('scroll', this.handleScroll);
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
