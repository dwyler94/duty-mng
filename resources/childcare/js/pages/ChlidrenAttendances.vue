<template>
    <section class="content">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header calendar-title">
                            <h3 class="card-title mb-0">{{ officeName }}</h3>
                            <div class="card-tools calendar-center flex-grow-1">
                                <datepicker v-if="displayType == 2"
                                :language="ja"
                                :format="customFormatter"
                                ref="programaticOpen"
                                :placeholder="todayDate"
                                @selected="getAttendanceDataMonthly"
                                v-model="selectedDate">
                                </datepicker>
                                <datepicker v-else
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
                            </div>
                            <div class="card-tools col-md-3 flex-grow-1" v-if="displayType == 1">
                                <button class="btn btn-outline btn-sm" type="button" @click="onPrev">
                                    <i class="fas fa-caret-left fa-2x"></i>
                                </button>
                                <button class="btn btn-outline-primary btn-sm mx-2" type="button" @click="onToday">
                                    今日
                                </button>
                                <button class="btn btn-outline btn-sm" type="button" @click="onNext">
                                    <i class="fas fa-caret-right fa-2x"></i>
                                </button>
                            </div>
                            <div class="card-tools col-md-3 flex-grow-1" v-else>
                                <button class="btn btn-outline btn-sm" type="button" @click="onPrevMonth">
                                    <i class="fas fa-caret-left fa-2x"></i>
                                </button>
                                <button class="btn btn-outline-primary btn-sm mx-2" type="button" @click="onToday">
                                    今月
                                </button>
                                <button class="btn btn-outline btn-sm" type="button" @click="onNextMonth">
                                    <i class="fas fa-caret-right fa-2x"></i>
                                </button>
                            </div>
                            <div class="card-tools col-md-2 flex-grow-1" v-if="displayType == 2">
                                <select class="form-control" v-on:change="onSelectedClass" id="selectClass">
                                    <option :value="0">指定なし</option>
                                    <option v-for="n of 6" :key="n" :value="n">{{n-1}}歳児クラス</option>
                                </select>
                            </div>
                            <div class="card-tools col-md-2 flex-grow-1">
                                <select class="form-control" v-model="displayType" v-on:change="onSelected">
                                    <option :value="1">日次表示</option>
                                    <option :value="2">月間表示</option>
                                </select>
                            </div>
                            <div class="card-tools flex-grow-1" v-if="displayType == 2">
                                <button class="btn btn-primary float-right mt-2" @click="getExcel">Excel出力</button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive p-0 twrapper" style="height: 500px;" id="children_attendance_table2" v-if="displayType == 2">
                                <div class="align-middle text-gray" v-if="(attendances[0].length + attendances[1].length + attendances[2].length + attendances[3].length + attendances[4].length) == 0">
                                    該当するデータはありません
                                </div>
                                <table class="table table-bordered table-striped table-children table-head-fixed table-hover monthly_table dataTable" v-else>
                                    <thead class="text-center text-white">
                                        <tr class="dark-brown">
                                            <th class="align-middle" style="left: 0;z-index: 35 !important;outline: 1px solid white;">クラス</th>
                                            <th class="align-middle" style="left: 70px;z-index: 34 !important;outline: 1px solid white;">氏名</th>
                                            <th class="align-middle sorting" id="th-number" style="left: 270px;z-index: 33 !important;outline: 1px solid white;" @click="childSort(2)">園児ID</th>
                                            <th class="align-middle" style="left: 400px;z-index: 32 !important;outline: 1px solid white;"></th>
                                            <template v-for="n in endDate">
                                                <th v-if="getWeekEnd(n) > 1" class="bg-danger">{{ n }}<br>({{getWeekDay(n)}})</th>
                                                <th v-else-if="getWeekEnd(n) === 1" class="bg-info">{{ n }}<br>({{getWeekDay(n)}})</th>
                                                <th v-else>{{ n }}<br>({{getWeekDay(n)}})</th>
                                            </template>
                                            <th class="align-middle">合計</th>
                                        </tr>
                                    </thead>
                                    <tbody class="text-center">
                                        <template v-for="(children, index1) in attendances">
                                            <template v-for="(child, index2) in children">
                                                <tr :key="index1 + '_' + index2 + '_' + 0">
                                                    <td v-if="index2 == 0" :rowspan="children.length * 6" class="dark-brown align-middle fixcell">
                                                        {{index1}}歳児
                                                    </td>
                                                    <td :rowspan="6" class="align-middle fixcell2" style="border-bottom-width: 3px">
                                                        <router-link v-if="child && child.id" :to="{name: 'children-detail', params: {id: child.id}}">
                                                            {{child.name}}
                                                        </router-link>
                                                    </td>
                                                    <td :rowspan="6" class="align-middle fixcell3" style="border-bottom-width: 3px">
                                                        {{child.number}}
                                                    </td>
                                                    <td class="align-middle text-nowrap fixcell4">登園</td>
                                                    <template v-if="child && child.days" v-for="(day, index3) in child.days">
                                                        <td v-if="index3 <= endDate" class="align-middle" @click="onEditClickedMonthly(child.id, index3)">
                                                            <span v-if="day && day.commutingTime">{{changeTimeFormat(day.commutingTime)}}</span>
                                                        </td>
                                                    </template>
                                                    <td></td>
                                                </tr>
                                                <tr :key="index1 + '_' + index2 + '_' + 1">
                                                    <td class="align-middle text-nowrap fixcell4">降園</td>
                                                    <template v-if="child && child.days" v-for="(day, index3) in child.days">
                                                        <td v-if="index3 <= endDate" class="align-middle" @click="onEditClickedMonthly(child.id, index3)">
                                                            <span v-if="day && day.leaveTime">{{changeTimeFormat(day.leaveTime)}}</span>
                                                        </td>
                                                    </template>
                                                    <td></td>
                                                </tr>
                                                <tr :key="index1 + '_' + index2 + '_' + 2">
                                                    <td class="align-middle text-nowrap fixcell4">欠席</td>
                                                    <template v-if="child && child.days" v-for="(day, index3) in child.days">
                                                        <td v-if="index3 <= endDate" class="align-middle text-nowrap" @click="onEditClickedMonthly(child.id, index3)">
                                                            <span v-if="day && day.reasonForAbsenceId">{{getAbsenceRuby(day.reasonForAbsenceId, day.noSchedule)}}</span>
                                                        </td>
                                                    </template>
                                                    <td></td>
                                                </tr>
                                                <tr :key="index1 + '_' + index2 + '_' + 3">
                                                    <td class="align-middle text-nowrap fixcell4">前延長</td>
                                                    <template v-if="child && child.days" v-for="(day, index3) in child.days">
                                                        <td v-if="index3 <= endDate" class="align-middle" @click="onEditClickedMonthly(child.id, index3)">
                                                            <span v-if="day && day.previousExtension">{{changeExtensionFormat(day.previousExtension)}}</span>
                                                        </td>
                                                    </template>
                                                    <td class="align-middle">
                                                        <span v-if="child.days && child.days[32] && child.days[32].previousExtension">
                                                            {{child.days[32].previousExtension}}
                                                        </span>
                                                    </td>
                                                </tr>
                                                <tr :key="index1 + '_' + index2 + '_' + 4">
                                                    <td class="align-middle text-nowrap fixcell4">後延長</td>
                                                    <template v-if="child && child.days" v-for="(day, index3) in child.days">
                                                        <td v-if="index3 <= endDate" class="align-middle" @click="onEditClickedMonthly(child.id, index3)">
                                                            <span v-if="day && day.extension">{{changeExtensionFormat(day.extension)}}</span>
                                                        </td>
                                                    </template>
                                                    <td class="align-middle">
                                                        <span v-if="child.days && child.days[32] && child.days[32].extension">
                                                            {{child.days[32].extension}}
                                                        </span>
                                                    </td>
                                                </tr>
                                                <tr :key="index1 + '_' + index2 + '_' + 5">
                                                    <td class="align-middle text-nowrap fixcell4" style="border-bottom-width: 3px">延長合計</td>
                                                    <template v-if="child && child.days" v-for="(day, index3) in child.days">
                                                        <td v-if="index3 <= endDate" class="align-middle" @click="onEditClickedMonthly(child.id, index3)" style="border-bottom-width: 3px">
                                                            <span v-if="day && day.totalExtension && (day.extension || day.previousExtension)">{{changeExtensionFormat(day.totalExtension)}}</span>
                                                        </td>
                                                    </template>
                                                    <td class="align-middle">
                                                        <span v-if="child.days && child.days[32] && child.days[32].totalExtension && (child.days[32].extension || child.days[32].previousExtension)">
                                                            {{child.days[32].totalExtension}}
                                                        </span>
                                                    </td>
                                                </tr>
                                            </template>
                                        </template>
                                    </tbody>
                                </table>
                            </div>
                            <div class="table-responsive p-0" style="height: 500px;" id="children_attendance_table" v-else>
                                <table class="table table-bordered table-striped table-children table-head-fixed table-hover">
                                    <thead class="text-center text-white">
                                        <tr class="dark-brown">
                                            <th>クラス</th>
                                            <th>氏名</th>
                                            <th>登園時間</th>
                                            <th>降園時間</th>
                                            <th>欠席</th>
                                            <th>前延長</th>
                                            <th>後延長</th>
                                            <th>延長合計</th>
                                            <th>連絡帳</th>
                                            <th>編集</th>
                                            <th style="width: 120px;">日誌</th>
                                        </tr>
                                    </thead>
                                    <tbody class="text-center">
                                        <template v-for="(attendance, index1) in attendances">
                                            <template v-for="(indiAttendance, index2) in attendance">
                                                <tr :key="index1 + '_' + index2">
                                                    <td v-if="index2 == 0" :rowspan="attendance.length" class="dark-brown align-middle">
                                                        {{index1}}歳児
                                                    </td>
                                                    <td class="align-middle">
                                                        <router-link :to="{name: 'children-detail', params: {id: indiAttendance.id}}">
                                                            {{indiAttendance.name}}
                                                        </router-link>
                                                    </td>
                                                    <td class="align-middle">{{changeTimeFormat(indiAttendance.commutingTime)}}</td>
                                                    <td class="align-middle">{{changeTimeFormat(indiAttendance.leaveTime)}}</td>
                                                    <td class="align-middle">{{getAbsenceName(indiAttendance.reasonForAbsenceId, indiAttendance.noSchedule)}}</td>
                                                    <td class="align-middle">{{changeExtensionFormat(indiAttendance.previousExtension)}}</td>
                                                    <td class="align-middle">{{changeExtensionFormat(indiAttendance.extension)}}</td>
                                                    <td class="align-middle">
                                                        <span v-if="indiAttendance.previousExtension || indiAttendance.extension">{{changeExtensionFormat(indiAttendance.totalExtension)}}</span>
                                                    </td>
                                                    <td class="align-middle">
                                                        <router-link :to="{name: 'contact-book', params: {id: indiAttendance.id, date: selectedDate}, query: {date: selectedDateLabel}}">
                                                            <template v-if="indiAttendance.contactStatus == 0">未入力</template>
                                                            <template v-else-if="indiAttendance.contactStatus == 1">一時保存</template>
                                                            <template v-else-if="indiAttendance.contactStatus == 2">完了</template>
                                                        </router-link>
                                                    </td>
                                                    <td class="align-middle">
                                                        <a href="javascript:void(0)" @click="onEditClicked(indiAttendance.id)">編集</a>
                                                        <div class="tooltip3" v-if="indiAttendance.notificationChildId">
                                                            <i v-if="indiAttendance.processFlag == 0" class="fas fa-bell text-danger" @click="onFinishNotice(indiAttendance.notificationChildId, 1)"></i>
                                                            <i v-else-if="indiAttendance.processFlag == 1" class="fas fa-bell-slash text-gray" @click="onFinishNotice(indiAttendance.notificationChildId, 0)"></i>
                                                            <div class="description3">{{ indiAttendance.notificationMessage }}</div>
                                                        </div>
                                                    </td>
                                                    <td v-if="index2 == 0" :rowspan="attendance.length" class="align-middle">
                                                        <div>
                                                            <router-link class="btn btn-primary mb-1"
                                                                :to="{name: 'childcare-diary-read', params: {classId: index1 + 1}, query: {date: selectedDateLabel}}">
                                                                日誌閲覧
                                                            </router-link>
                                                        </div>
                                                        <div>
                                                            <router-link class="btn btn-primary mb-1"
                                                                :to="{name: 'childcare-diary', params: {classId: index1 + 1}, query: {date: selectedDateLabel}}">
                                                                日誌作成
                                                            </router-link>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </template>
                                        </template>
                                    </tbody>
                                </table>
                            </div>
                            <!-- Modal -->
                            <div class="modal fade" id="attend-edit-form" tabindex="-1" role="dialog" aria-labelledby="attend-edit-form" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <edit-form :editData="editFormData" :date="selectedDate" v-on:success="onAttendSaved"></edit-form>
                                </div>
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
import EditForm from './ChildrenAttendances/EditForm.vue';
import api, { apiErrorHandler } from '../global/api';
import LocalStorage from "../../../js/helpers/localStorage";

export default {
    components: {
        Datepicker,
        EditForm
    },
    mixins: [actionLoading],
    props: {
        month: '',
    },
    data () {
        return {
            editmode: false,
            currentDate: new Date(),
            todayDate: "",
            endDate: 31,
            days: [],
            attendsAll : [],
            attends : [],
            attendances: [],
            selectedAttend : null,
            selectedUser: null,
            requests : [],
            offices: [],
            officeId: 1,
            selectedDate: new Date(),
            selectedClass: null,
            ja: ja,
            selectedApp: {},
            selectedAppUserName: '',
            editFormData: {},
            displayType: 1,
            selectedMonth: '',
        }
    },
    computed: {
        ...mapState({
                reasonForAbsences: state => state.constants.reasonForAbsences,
                session: state => state.session.info
            }),
        selectedDateLabel() {
            return moment(this.selectedDate).format('YYYY-MM-DD');
        },
        officeName() {
            if (!this.session.office) return '';
            return this.session.office.name;
        }
    },
    methods: {
        onSelected: function (e) {
            if (e.target.value == 2) {
                this.getAttendanceDataMonthly(new Date(this.selectedDate.toString()));
            } else {
                this.getAttendanceData(new Date(this.selectedDate.toString()));
            }
        },
        onSelectedClass: function (e) {
            this.attends = this.attendsAll;
            if (e.target.value > 0) {
                this.attends = this.attends.filter(function(x) {
                    return x.classId == e.target.value;
                });
            }
            this.initAttendanceDataMonthly();
        },
        childSort(index) {
            this.asc = !this.asc;
            if(this.asc)
                $('#th-number').removeClass('sorting_asc').addClass('sorting_desc');
            else
                $('#th-number').removeClass('sorting_desc').addClass('sorting_asc');

            if(index == 2) {
                if (this.asc) {
                    this.attends.sort((first, second) => (second.number > first.number) ? 1 : ((first.number > second.number) ? -1 : 0));
                } else {
                    this.attends.sort((first, second) => (first.number > second.number) ? 1 : ((second.number > first.number) ? -1 : 0));
                }
                this.initAttendanceDataMonthly();
            }
        },
        getExcel() {
            if (!this.session.office.id) return;
            location.href = "/child-monthly-attendance/monthly/excel/" + this.session.office.id + "?token=" + LocalStorage.getToken() + "&month=" + this.selectedMonth;
        },
        getWeekDay(index) {
            const year = moment(new Date(this.selectedDate.toString())).format('YYYY');
            const month = moment(new Date(this.selectedDate.toString())).format('MM');
            return moment(year + '-' + month + '-' + ('0' + index).slice(-2)).format('ddd');
        },
        getWeekEnd(index) {
            const year = moment(new Date(this.selectedDate.toString())).format('YYYY');
            const month = moment(new Date(this.selectedDate.toString())).format('MM');
            const weekDay = moment(year + '-' + month + '-' + ('0' + index).slice(-2)).format('ddd');
            const yyyymmdd = moment(year + '-' + month + '-' + ('0' + index).slice(-2)).format('YYYYMMDD');
            if (this.session.holidays && this.session.holidays[yyyymmdd]) {
                return 3;
            }
            if (weekDay === '土'){
                return 1;
            } else if(weekDay === '日'){
                return 2;
            } else {
                return 0;
            }
        },
        onEditClicked(childId) {
            const child = this.attends.find(({ id }) => childId === id);
            this.editFormData = {
                ...child
            }
            this.showEditForm();
        },
        onEditClickedMonthly(childId, day) {
            let child = this.attends.filter(function(x) {
                return x.childId == childId && x.day == day;
            });
            if (child[0]) {
                this.editFormData = {
                    ...child[0]
                }
            } else {
                child = this.attends.find(({ id }) => childId === id);
                this.editFormData = {
                    ...child
                }
            }
            const year = moment(this.selectedDate).format('YYYY');
            const month = moment(this.selectedDate).format('MM');
            this.selectedDate = moment(year + '-' + month + '-' + ('0' + day).slice(-2)).format('YYYY-MM-DD');
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
            this.selectedMonth = moment(new Date(date.getFullYear(), date.getMonth(), 1)).format('YYYYMM');
            this.setActionLoading();
            if(date){
                this.selectedDate = date;
            } else {
                this.selectedDate = new Date();
            }
            this.selectedDate = moment(this.selectedDate).format('YYYY-MM-DD');
            this.endDate = parseInt( moment(this.selectedDate).endOf('month').format("DD") );
            api.get('attendance', null, {date: this.selectedDate})
                .then(response => {
                    this.unsetActionLoading();
                    this.attends = response;
                    this.displayType = 1;

                    this.attendances = [];
                    for(let i = 0; i < 6; i++) {
                        this.attendances[i] = [];
                    }
                    this.attends.forEach(element => {
                        for(let i = 0; i < 6; i++) {
                            if(i === element.classId - 1) {
                                this.attendances[element.classId - 1] = [...this.attendances[element.classId - 1], element];
                            }
                        }
                    });
                })
                .catch(e => {
                    this.unsetActionLoading();
                    apiErrorHandler(e);
                });
        },
        getAttendanceDataMonthly(date, classId = null, sort = "") {
            if(this.actionLoading) return;
            this.selectedMonth = moment(new Date(date.getFullYear(), date.getMonth(), 1)).format('YYYYMM');
            this.setActionLoading();
            if(date){
                this.selectedDate = date;
            } else {
                this.selectedDate = new Date();
            }
            if(classId){
                this.selectedClass = classId;
            } else {
                this.selectedClass = null;
            }
            this.selectedDate = moment(this.selectedDate).format('YYYY-MM-DD');
            this.endDate = parseInt( moment(this.selectedDate).endOf('month').format("DD") );
            api.get('attendance/monthly', null, {date: this.selectedDate, class:this.selectedClass, sort:sort})
                .then(response => {
                    this.unsetActionLoading();
                    $('#th-number').removeClass('sorting_asc').removeClass('sorting_desc');
                    $('#selectClass').val(0);
                    this.attends = response;
                    this.attendsAll = response;
                    this.initAttendanceDataMonthly();
                })
                .catch(e => {
                    this.unsetActionLoading();
                    apiErrorHandler(e);
                });
        },
        initAttendanceDataMonthly() {
            this.displayType = 2;
            this.attendances = [];
            for(let i = 0; i < 6; i++) {
                this.attendances[i] = [];
            }
            let keys = { 0:[], 1:[], 2:[], 3:[], 4:[], 5:[] };
            this.attends.forEach(element => {
                let i = element.classId - 1;
                let key = element.childId;
                if ( !keys[i].includes(key) ) {
                    keys[i].push(key);
                }
                let index = keys[i].indexOf(key);
                if ( !this.attendances[i][index] ) {
                    this.attendances[i][index] = {id:element.id , name:element.name , number:element.number , days: {}};
                    for(let d = 1; d < 33; d++) {
                        this.attendances[i][index]['days'][d] = null;
                    }
                }
                if (element.day) {
                    this.attendances[i][index]['days'][element.day] = element;
                }
            });
        },
        getAbsenceName(reasonForAbsenceId, noSchedule) {
            if(reasonForAbsenceId){
                if(noSchedule) {
                    if(this.reasonForAbsences.find(item => item.id === reasonForAbsenceId))
                        return '（予）' + this.reasonForAbsences.find(item => item.id === reasonForAbsenceId).name;
                    else
                        return null;
                } else {
                    if(this.reasonForAbsences.find(item => item.id === reasonForAbsenceId))
                        return this.reasonForAbsences.find(item => item.id === reasonForAbsenceId).name;
                    else
                        return null;
                }

            } else {
                if(noSchedule) return '託児計画なし';
                return null;
            }
        },
        getAbsenceRuby(reasonForAbsenceId, noSchedule) {
            if(reasonForAbsenceId){
                if(noSchedule) {
                    if(this.reasonForAbsences.find(item => item.id === reasonForAbsenceId))
                        return '（予）' + this.reasonForAbsences.find(item => item.id === reasonForAbsenceId).ruby;
                    else
                        return null;
                } else {
                    if(this.reasonForAbsences.find(item => item.id === reasonForAbsenceId))
                        return this.reasonForAbsences.find(item => item.id === reasonForAbsenceId).ruby;
                    else
                        return null;
                }

            } else {
                if(noSchedule) return '託児計画なし';
                return null;
            }
        },
        onWorkStatusSaved() {
            this.getAttendanceData(this.selectedDate);
            $("#attend-edit-form").modal('hide');
        },
        onAttendSaved() {
            if (this.displayType == 2) {
                this.getAttendanceDataMonthly(new Date(this.selectedDate.toString()));
            } else {
                this.getAttendanceData(new Date(this.selectedDate.toString()));
            }
            $("#attend-edit-form").modal('hide');
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
        onNext() {
            var date = moment(this.selectedDate).add(1, 'days').format('YYYY-MM-DD');
            if (this.displayType == 2) {
                this.getAttendanceDataMonthly(new Date(date.toString()));
            } else {
                this.getAttendanceData(new Date(date.toString()));
            }
        },
        onPrev() {
            var date = moment(this.selectedDate).add(-1, 'days').format('YYYY-MM-DD');
            if (this.displayType == 2) {
                this.getAttendanceDataMonthly(new Date(date.toString()));
            } else {
                this.getAttendanceData(new Date(date.toString()));
            }
        },
        onNextMonth() {
            var date = moment(this.selectedDate).add(1, 'months').format('YYYY-MM-DD');
            if (this.displayType == 2) {
                this.getAttendanceDataMonthly(new Date(date.toString()));
            } else {
                this.getAttendanceData(new Date(date.toString()));
            }
        },
        onPrevMonth() {
            var date = moment(this.selectedDate).add(-1, 'months').format('YYYY-MM-DD');
            if (this.displayType == 2) {
                this.getAttendanceDataMonthly(new Date(date.toString()));
            } else {
                this.getAttendanceData(new Date(date.toString()));
            }
        },
        onToday(){
            var date = moment().format('YYYY-MM-DD');
            if (this.displayType == 2) {
                this.getAttendanceDataMonthly(new Date(date.toString()));
            } else {
                this.getAttendanceData(new Date(date.toString()));
            }
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
            return moment(date).format('YYYY/M/D(ddd)');
        },
        changeTimeFormat(date) {
            if(date) {
                return moment(date).format('HH:mm');
            } else {
                return "-";
            }
        },
        changeExtensionFormat(extension) {
            if(extension) {
                return moment(extension, 'hh:mm:ss').format('HH:mm');
            }
            return null;
        },
        openDatePicker(){
            this.$refs.programaticOpen.showCalendar();
        },
        openDiary() {
            this.$router.push('childcare-diary');
        },
        getParam(name, url) {
            if (!url) url = window.location.href;
            name = name.replace(/[\[\]]/g, "\\$&");
            var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
                results = regex.exec(url);
            if (!results) return null;
            if (!results[2]) return '';
            return decodeURIComponent(results[2].replace(/\+/g, " "));
        },
        onFinishNotice(childId, bool) {
            if (bool) {
                var result = window.confirm('アラートを終了します');
            } else {
                var result = window.confirm('アラートを戻します');
            }
            if( result ) {
                return window.location.href = "/notice-finish/"+childId+"/"+ moment(this.selectedDate).format('YYYY-MM-DD') +"/"+bool;
            }
        },
    },
    created() {

    },
    mounted() {
        var date = this.getParam('date');
        if (date && moment(date).isValid()) {
            this.todayDate = date.toString();
            this.getAttendanceData(new Date(date));
        } else {
            this.todayDate = this.getCurrentDate().toString();
            this.getAttendanceData(this.currentDate);
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
    .calendar-title {
        display: flex;
        align-items: center;
    }
    .tooltip3{
        position: relative;
        cursor: pointer;
        display: inline-block;
    }
    .tooltip3 i{
        margin:0;
        padding:0;
    }
    .description3 {
        display: none;
        position: absolute;
        padding: 10px;
        font-size: 12px;
        line-height: 1.6em;
        color: #fff;
        border-radius: 5px;
        background: #000;
        width: 250px;
        z-index:500;
    }
    .tooltip3:hover .description3{
        display: inline-block;
        top: 30px;
        left: -125px;
    }
    .monthly_table tr td:hover {
        background-color: #d9efff;
    }
    .twrapper{
        overflow: auto;
        white-space: nowrap;
    }
    .twrapper table{
        width: auto;
    }
    .twrapper table .fixcell {
        position: -webkit-sticky;
        position: sticky;
        left: 0px;
        width: 70px;
        min-width: 70px;
        max-width: 70px;
    }
    .twrapper table .fixcell2 {
        position: -webkit-sticky;
        position: sticky;
        left: 70px;
        width: 200px;
        min-width: 200px;
        max-width: 200px;
        background-color: #f5f5dc;
    }
    .twrapper table .fixcell3 {
        position: -webkit-sticky;
        position: sticky;
        left: 270px;
        width: 130px;
        min-width: 130px;
        max-width: 130px;
        box-shadow: inset -1px 0 0 white, inset 1px 0 0 white;
        background-color: #f5f5dc;
    }
    .twrapper table .fixcell4 {
        position: -webkit-sticky;
        position: sticky;
        left: 400px;
        width: 80px;
        min-width: 80px;
        max-width: 80px;
        box-shadow: inset -1px 0 0 white, inset 1px 0 0 white;
        background-color: #f5f5dc;
    }

    table.dataTable>thead .sorting, table.dataTable>thead .sorting_asc, table.dataTable>thead .sorting_desc {
        cursor: pointer;
        position: relative;
    }

    table.dataTable>thead .sorting:before {
        right: 1em;
        content: "↑";
        position: absolute;
        bottom: 0.5em;
        display: block;
        opacity: .3;
    }
    table.dataTable>thead .sorting_asc:before {
        opacity: 1;
    }
    table.dataTable>thead .sorting:after{
        right: 0.5em;
        content: "↓";
        position: absolute;
        bottom: 0.5em;
        display: block;
        opacity: .3;
    }
    table.dataTable>thead .sorting_desc:after{
        opacity: 1;
    }

    @media (max-width: 769px) {
        .calendar-title {
            flex-direction: column;
        }

        .card-tools {
            text-align: center;
        }
    }
</style>
