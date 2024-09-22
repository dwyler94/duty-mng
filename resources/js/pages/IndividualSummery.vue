<template>
    <section class="content">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-12">
                    <div class="card">
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
                                <div class="form-group mx-4 mb-0">
                                    <select class="form-control" v-model="userId" @change="selectUser()">
                                        <option v-for="user in users" :key="user.id" :value="user.id">{{user.name}}</option>
                                    </select>
                                </div>
                                <div class="form-group mx-4 mb-0">
                                    <select class="form-control" v-model="officeId" @change="selectOffice()">
                                        <option v-for="office in offices" :key="office.id" :value="office.id">{{office.name}}</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                            <individual-summery-a v-if="selectedUser.employmentStatusId == 1 && !isHonShya(officeName)" :attendance="attendance" :total="total" :userMeta="userMeta" :month="month"
                            :userId="userId" :isShowApplyBtn="isShowApplyBtn" v-on:success="onEditSaved" v-on:output="output" />
                            <individual-summery-b v-else-if="selectedUser.employmentStatusId == 1 && isHonShya(officeName)" :attendance="attendance" :total="total" :userMeta="userMeta" :month="month"
                            :userId="userId" :isShowApplyBtn="isShowApplyBtn" v-on:success="onEditSaved" v-on:output="output" />
                            <individual-summery-c v-else-if="selectedUser.employmentStatusId == 3 && !isHonShya(officeName)" :attendance="attendance" :total="total" :userMeta="userMeta" :month="month"
                            :userId="userId" :isShowApplyBtn="isShowApplyBtn" v-on:success="onEditSaved" v-on:output="output" />
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
import IndividualSummeryA from './IndividualSummery/IndividualSummeryA.vue';
import IndividualSummeryB from './IndividualSummery/IndividualSummeryB.vue';
import IndividualSummeryC from './IndividualSummery/IndividualSummeryC.vue';
import actionLoading from '../mixin/actionLoading';
import LocalStorage from '../helpers/localStorage';

export default {
  components: { IndividualSummeryA, IndividualSummeryB, IndividualSummeryC },
  mixins: [actionLoading],
        data () {
            return {
                editmode: false,
                currentDate: new Date(),
                displayDate: new Date(),
                days: [],
                attends : [],
                attendance: {},
                total: {},
                userMeta: {},
                selectedMonth: '',
                month: new Date(),
                officeName: '',
                offices: [],
                officeId: null,
                users: [],
                selectedUser: {},
                userId: null,
                isShowApplyBtn: true,
            }
        },
        computed: {
            ...mapState({
                session: state => state.session.info
            }),

        },
        methods: {
            getAttendance() {
                if (this.actionLoading) return;
                    this.setActionLoading();
                    api.get('monthly-summary/' + this.userId, null, {month: this.selectedMonth})
                        .then(response => {
                            this.unsetActionLoading();
                            this.attendance = response.attendance;
                            const checkCount = Object.values(this.attendance).reduce((sum, item) => (sum + (item && item.isApproved ? 1 : 0)), 0);
                            this.getDays();
                            if (checkCount == this.days.length) {
                                this.isShowApplyBtn = false;
                            } else {
                                this.isShowApplyBtn = true;
                            }
                            this.total = response.total;
                            this.userMeta = response.userMeta;
                        })
                        .catch(e => {
                            apiErrorHandler(e);
                            this.unsetActionLoading();
                        });
            },
            getDays() {
                this.days = [];
                const dayCounts = moment(this.month, "YYYY-MM").daysInMonth();
                for (let day = 1; day <= dayCounts; day++) {
                    this.days.push(day);
                }
            },
            getOffices() {
                api.get('office/user-capable')
                    .then(response => {
                        this.offices = response;
                        if (!this.offices.length) return;

                        let office = this.offices.find(office => office.id === this.officeId)
                        if (!office) {
                            this.officeId = this.offices[0].id;
                            office = this.offices[0]
                        }
                        this.officeName = office.name;
                        this.getUsers();
                    })
                    .catch(e => apiErrorHandler(e))
            },
            selectOffice() {
                this.getUsers();
                let office = this.offices.find(office => office.id === this.officeId)
                if(!office) {
                    return;
                }
                this.officeName = office.name;
            },
            selectUser() {
                this.selectedUser = this.users.find(item => item.id === this.userId);
                this.getAttendance();
            },
            getUsers() {
                api.get('office/' + this.officeId + '/users')
                    .then(response => {
                        this.users = response;
                        if(!this.users || this.users.length === 0) return;
                        let user = this.users.find(user => user.id === this.userId);
                        if(!user) {
                            this.userId = this.users[0].id;
                            this.selectedUser = this.users[0];
                        } else {
                            this.selectedUser = user;
                        }
                        this.getAttendance();
                    })
                    .catch(e => apiErrorHandler(e))
            },
            isHonShya(officeName) {
                if(officeName.indexOf('本社') !== -1) {
                    return true;
                } else {
                    return false;
                }
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
                this.displayDate = moment(new Date(date.getFullYear(), date.getMonth(), 1)).format('YYYY年 MM月');
                this.selectedMonth = moment(new Date(date.getFullYear(), date.getMonth(), 1)).format('YYYYMM');
                this.month = moment(new Date(date.getFullYear(), date.getMonth(), 1)).format('YYYY-MM');
                this.getAttendance();
                return new Date(date.getFullYear(), date.getMonth(), 1);
            },
            getNextMonthDate() {
                const date = this.currentDate;
                this.displayDate = moment(new Date(date.getFullYear(), date.getMonth() + 1, 1)).format('YYYY年 MM月');
                this.selectedMonth = moment(new Date(date.getFullYear(), date.getMonth() + 1, 1)).format('YYYYMM');
                this.month = moment(new Date(date.getFullYear(), date.getMonth() + 1, 1)).format('YYYY-MM');
                this.getAttendance();
                return new Date(date.getFullYear(), date.getMonth() + 1, 1);
            },
            getPrevMonthDate() {
                const date = this.currentDate;
                this.displayDate = moment(new Date(date.getFullYear(), date.getMonth() - 1, 1)).format('YYYY年 MM月');
                this.selectedMonth = moment(new Date(date.getFullYear(), date.getMonth() - 1, 1)).format('YYYYMM');
                this.month = moment(new Date(date.getFullYear(), date.getMonth() - 1, 1)).format('YYYY-MM');
                this.getAttendance();
                return new Date(date.getFullYear(), date.getMonth() - 1, 1);
            },
            getResults(month_date) {
                this.updateTable(month_date);
            },
            updateTable(date){
                this.currentDate = date;
            },
            onEditSaved() {
                this.getAttendance();
            },
            output(type) {
                if (this.userId && this.officeId && this.selectedMonth) {
                    location.href = "/individual-summary/excel?token=" + LocalStorage.getToken() + "&month=" + this.selectedMonth + "&user_id=" + this.userId + '&type=' + type;
                    return;
                }
                return;
            }
        },
        mounted() {
            this.month = moment().format('YYYY') + '-' + moment().format('MM');
            const userId = this.$route.query.userId;
            const officeId = this.$route.query.officeId;
            const month = this.$route.query.month;
            this.userId = parseInt(userId);
            this.officeId = parseInt(officeId);
            if(month) {
                this.month = month;
            }
            this.currentDate = new Date(this.month.split('-')[0], this.month.split('-')[1], 0);
            this.selectedMonth = moment(this.month, "YYYY-MM").format('YYYYMM');
            this.month = moment(this.month, "YYYY-MM").format('YYYY-MM');
            this.displayDate = moment(this.month, "YYYY-MM").format('YYYY年 MM月');
            this.getOffices();

            this.getResults(this.currentDate);
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
