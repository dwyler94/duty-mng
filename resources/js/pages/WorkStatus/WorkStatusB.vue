<template>
    <section class="content">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header calendar-title">
                            <h3 class="card-title mb-0">{{officeName}}</h3>
                            <div class="card-tools calendar-center flex-grow-1">
                                <datepicker
                                :language="ja"
                                :format="customFormatter"
                                ref="programaticOpen"
                                :placeholder="todayDate"
                                @selected="getAttendanceData"
                                v-model="selectedDate">
                                </datepicker>
                                <button type="button" class="btn btn-sm btn-outline mx-2" @click="openDatePicker()">
                                    <i class="fas fa-calendar-alt fa-2x"></i>
                                </button>
                                <div class="form-group mx-4 mb-0">
                                    <select class="form-control" v-model="officeId" @change="getAttendanceData(selectedDate)">
                                        <option v-for="office in offices" :key="office.id" :value="office.id">{{office.name}}</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                        <div class="table-responsive p-0" style="height: 500px;">
                            <table class="table table-bordered table-striped table-kintai table-head-fixed table-hover">
                                <thead class="text-center text-white">
                                    <tr class="heavy-green">
                                        <th>氏名</th>
                                        <th>出勤①</th>
                                        <th>退勤①</th>
                                        <th>遅刻</th>
                                        <th>早退</th>
                                        <th>出勤②</th>
                                        <th>退勤②</th>
                                        <th>遅刻</th>
                                        <th>早退</th>
                                        <th>編集</th>
                                        <th>申請</th>
                                    </tr>
                                </thead>
                                    <tbody class="text-center">
                                        <tr v-for="attend in attends" :key="attend.id">
                                            <td>{{attend.user.name}}</td>
                                            <td>{{attend.attend ? changeTimeFormat(attend.attend.commutingTime1) : '-'}}</td>
                                            <td>{{attend.attend ? changeTimeFormat(attend.attend.leaveTime1) : '-'}}</td>
                                            <td>{{attend.attend && isHonShya(attend.user.officeId) && isNormalStaff(attend.user.employmentStatusId) ? '-' : notZero(attend.attend.behindTime1)}}</td>
                                            <td>{{attend.attend && isHonShya(attend.user.officeId) && isNormalStaff(attend.user.employmentStatusId) ? '-' : notZero(attend.attend.leaveEarly1)}}</td>
                                            <td>{{attend.attend ? changeTimeFormat(attend.attend.commutingTime2) : '-'}}</td>
                                            <td>{{attend.attend ? changeTimeFormat(attend.attend.leaveTime2) : '-'}}</td>
                                            <td>{{attend.attend && isHonShya(attend.user.officeId) && isNormalStaff(attend.user.employmentStatusId) ? '-' : notZero(attend.attend.behindTime2)}}</td>
                                            <td>{{attend.attend && isHonShya(attend.user.officeId) && isNormalStaff(attend.user.employmentStatusId) ? '-' : notZero(attend.attend.leaveEarly2)}}</td>
                                            <td>
                                                <a href="#" @click="onEditClicked(attend.attend, attend.user.id)">
                                                    <i class="fa fa-edit fa-lg blue"></i>
                                                </a>
                                            </td>
                                            <template v-if="attend.attend.applications && attend.attend.applications.length > 0">
                                                <td>
                                                    <a href="#" @click="onApprove(attend.attend.applications[0], attend.user.name)">
                                                        <i class="far fa-envelope fa-lg orange"></i>
                                                    </a>
                                                </td>
                                            </template>
                                            <template v-else>
                                                <td></td>
                                            </template>
                                        </tr>
                                    </tbody>
                            </table>
                        </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal -->
            <div class="modal fade" id="attend-edit-form" tabindex="-1" role="dialog" aria-labelledby="attend-edit-form" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <edit-form :workStatus="selectedAttend" :userId="selectedUser" :date="selectedDate" v-on:success="onWorkStatusSaved"></edit-form>
                </div>
            </div>
            <!--Modal -->
            <div class="modal fade" id="app-aprove-form" tabindex="-1" role="dialog" aria-labelledby="app-aprove-form" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <apply-form :application="selectedApp" :userName="selectedAppUserName" v-on:success="onAppSaved"></apply-form>
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
    import actionLoading from '../../mixin/actionLoading';
    import api, { apiErrorHandler } from '../../global/api';
    import EditForm from './EditForm.vue';
    import ApplyForm from './ApplyForm.vue';

    export default {
        components: {
            Datepicker, EditForm, ApplyForm,
        },
        mixins: [actionLoading],
        data () {
            return {
                editmode: false,
                currentDate: new Date(),
                todayDate: "",
                days: [],
                attends : [],
                selectedAttend : null,
                selectedUser: null,
                requests : [],
                offices: [],
                officeName: '',
                officeId: null,
                selectedDate: new Date(),
                ja: ja,
                selectedApp: {},
                selectedAppUserName: '',
            }
        },
        methods: {
            getOffices() {
                api.get('office/user-capable')
                    .then(response => {
                        this.offices = response;
                        const office = this.offices.find(office => office.id === this.officeId);
                        this.officeName = office ? office.name : '';
                        if(this.offices && this.offices.length > 0) {
                            this.officeId = this.offices[0].id;
                            this.getAttendanceData(this.currentDate);
                        }
                    })
                    .catch(e => apiErrorHandler(e))
            },
            onEditClicked(attend, userId) {
                if(!attend) return;
                this.selectedAttend = attend;
                this.selectedUser = userId;
                this.showEditForm();
            },
            showEditForm() {
                $("#attend-edit-form").modal('show');
            },
            isHonShya(officeId) {
                const office = this.offices.find(office => office.id === officeId);
                let name = office ? office.name : '';
                if(name.indexOf('本社') !== -1) {
                    return true;
                } else {
                    return false;
                }
            },
            isNormalStaff(employmentStatusId) {
                if(employmentStatusId === 1) {
                    return true;
                } else {
                    return false;
                }
            },
            notZero(number) {
                if(number > 0) {
                    return Math.floor(number).toString() + '分';
                } else {
                    return '-';
                }
            },
            getAttendanceData(date) {
                if(this.actionLoading) return;
                this.setActionLoading();
                if(date){
                    this.selectedDate = date;
                } else {
                    this.selectedDate = new Date();
                }
                this.selectedDate = moment(this.selectedDate).format('YYYY-MM-DD');
                api.get('attend/' + this.officeId, null, {date: this.selectedDate})
                    .then(response => {
                        this.unsetActionLoading();
                        this.attends = response;
                        const office = this.offices.find(office => office.id === this.officeId);
                        this.officeName = office ? office.name : '';
                    })
                    .catch(e => {
                        this.unsetActionLoading();
                        apiErrorHandler(e);
                    });
            },
            onWorkStatusSaved() {
              this.getAttendanceData(this.selectedDate);
              $("#attend-edit-form").modal('hide');
            },
            onAppSaved() {
              this.getAttendanceData(this.selectedDate);
              $("#app-aprove-form").modal('hide');
            },
            isThisMonth() {
                const today = new Date();
                return this.currentDate.getFullYear() == today.getFullYear() && this.currentDate.getMonth() == today.getMonth();
            },
            getNextMonthDate() {
                const date = this.currentDate;
                return new Date(date.getFullYear(), date.getMonth() + 1, 1);
            },
            getPrevMonthDate() {
                const date = this.currentDate;
                return new Date(date.getFullYear(), date.getMonth() - 1, 1);
            },
            getResults(month_date) {
                // this.$Progress.start();
                // this.loadAttends(month_date);
                // this.loadRequests(month_date);
                this.updateTable(month_date);
                // this.$Progress.finish();
            },
            createRequest(){
                $('#addNew').modal('hide');
                //TODO: this.form.post
                this.loadRequests();
            },
            updateRequest(){
                $('#addNew').modal('hide');
                //TODO: this.form.post
                this.loadRequests();
            },
            onApprove(application, userName){
                this.editmode = true;
                this.selectedApp = application;
                this.selectedAppUserName = userName;
                $('#app-aprove-form').modal('show');
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
            updateTable(date){
                this.currentDate = date;
                var firstDay = new Date(date.getFullYear(), date.getMonth(), 1).getDate();
                var lastDay = new Date(date.getFullYear(), date.getMonth() + 1, 0).getDate();
                for(let day = firstDay; day <= lastDay; day++) {
                    this.days.push(new Date(date.getFullYear(), date.getMonth(), day));
                }

            },
            customFormatter(date) {
                return moment(date).format('YYYY年 M月 D日 (ddd)');
            },
            changeTimeFormat(date) {
                if(date) {
                    return moment(date).tz('Asia/Tokyo').format('HH:mm');
                } else {
                    return "-";
                }
            },
            openDatePicker(){
                this.$refs.programaticOpen.showCalendar();
            }
        },
        created() {

        },
        mounted() {
            this.getResults(this.currentDate);
            this.todayDate = this.getCurrentDate().toString();
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
