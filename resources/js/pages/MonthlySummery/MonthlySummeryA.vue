<template>
    <div class="card-body">
        <div class="float-right">
            <label>一括承認する</label>
            <input type="checkbox" class="align-middle" @click="selectAllDates()" v-model="selectAllFlag">
        </div>
        <div class="table-responsive p-0 overflow-scroll-x" style="height: 500px;" @scroll="handleScroll()">
            <table class="table table-bordered table-striped table-kintai table-head-fixed table-hover w-3100-px">
                <thead class="text-center text-white">
                    <tr>
                        <th rowspan="2" class="align-middle p-0" style="left: 0;z-index: 12 !important;outline: 1px solid white;">日付</th>
                        <th rowspan="2" class="align-middle p-0" style="left: 68px;z-index: 13 !important;outline: 1px solid white;">曜日</th>
                        <th rowspan="2" class="align-middle">出勤①</th>
                        <th rowspan="2" class="align-middle">退勤①</th>
                        <th rowspan="2" class="align-middle p-0">遅刻</th>
                        <th rowspan="2" class="align-middle p-0">早退</th>
                        <th rowspan="2" class="align-middle">出勤②</th>
                        <th rowspan="2" class="align-middle">退勤②</th>
                        <th rowspan="2" class="align-middle p-0">遅刻</th>
                        <th rowspan="2" class="align-middle p-0">早退</th>
                        <th rowspan="2" class="align-middle">実働<br>時間</th>
                        <th rowspan="2" class="align-middle">休憩<br>時間</th>
                        <th rowspan="2" class="align-middle">残業外<br>労働時間</th>
                        <th rowspan="2" class="align-middle">法定内<br>残業時間</th>
                        <th rowspan="2" class="align-middle">法定外<br>残業時間</th>
                        <th rowspan="2" class="align-middle">深夜<br>時間</th>
                        <th rowspan="2" class="align-middle p-0">シフト外出勤</th>
                        <th colspan="2" class="align-middle">代休日</th>
                        <th rowspan="2" class="align-middle">連勤日</th>
                        <th colspan="2">年次有休</th>
                        <th colspan="2">特休有給</th>
                        <th colspan="2">特休無給</th>
                        <th colspan="2">その他無給</th>
                        <th rowspan="2" class="align-middle">欠勤日</th>
                        <th rowspan="2" class="align-middle">休暇・欠勤理由</th>
                        <th rowspan="2" class="align-middle">備考</th>
                        <th rowspan="2" class="align-middle p-0">編集</th>
                        <th rowspan="2" class="align-middle p-0">承認</th>
                    </tr>
                    <tr class="heavy-green header-fix-y">
                        <th>時間</th>
                        <th>日付</th>
                        <th>時間</th>
                        <th>日</th>
                        <th>時間</th>
                        <th>日</th>
                        <th>時間</th>
                        <th>日</th>
                        <th>時間</th>
                        <th>日</th>
                    </tr>
                </thead>
                    <tbody class="text-center header-fix-x-tr">
                        <tr v-for="(dayAttendance, index) in attendance" :key="dayAttendance.id">
                            <template  v-if="editMode && selectedIndex == index">
                                <td class="header-fix-x">{{index}}日</td>
                                <td v-if="getWeekEnd(index) === 1" class="blue header-fix-x-68">{{getDate(index)|formatWeek}}</td>
                                <td v-else-if="getWeekEnd(index) === 2" class="red header-fix-x-68">{{getDate(index)|formatWeek}}</td>
                                <td v-else class="header-fix-x-68">{{getDate(index)|formatWeek}}</td>
                                <td>
                                    <input type="text" v-model="editData.startWorkTime1" :class="{'is-invalid' : errors.startWorkTime1}" class="fixed-width-70 form-control" @keyup="errors.startWorkTime1 = null"/>
                                    <span v-if="editMode && errors.startWorkTime1" class="error invalid-feedback">
                                        {{ errors.startWorkTime1 }}
                                    </span>
                                </td>
                                <td>
                                    <input type="text" v-model="editData.endWorkTime1" :class="{'is-invalid' : errors.endWorkTime1}" class="fixed-width-70 form-control" @keyup="errors.endWorkTime1 = null"/>
                                    <span v-if="editMode && errors.endWorkTime1" class="error invalid-feedback">
                                        {{ errors.endWorkTime1 }}
                                    </span>
                                </td>
                                <td>{{notZero(dayAttendance.behindTime1)}}</td>
                                <td>{{notZero(dayAttendance.leaveEarly1)}}</td>
                                <td>
                                    <input type="text" v-model="editData.startWorkTime2" :class="{'is-invalid' : errors.startWorkTime2}" class="fixed-width-70 form-control" @keyup="errors.startWorkTime2 = null"/>
                                    <span v-if="editMode && errors.startWorkTime2" class="error invalid-feedback">
                                        {{ errors.startWorkTime2 }}
                                    </span>
                                </td>
                                <td>
                                    <input type="text" v-model="editData.endWorkTime2" :class="{'is-invalid' : errors.endWorkTime2}" class="fixed-width-70 form-control" @keyup="errors.endWorkTime2 = null"/>
                                    <span v-if="editMode && errors.endWorkTime2" class="error invalid-feedback">
                                        {{ errors.endWorkTime2 }}
                                    </span>
                                </td>
                                <td>{{notZero(dayAttendance.behindTime2)}}</td>
                                <td>{{notZero(dayAttendance.leaveEarly2)}}</td>
                                <td>{{dayAttendance.workingHours ? (dayAttendance.workingHours / 60).toFixed(2) : '-'}}</td>
                                <td>{{dayAttendance.restHours ? (dayAttendance.restHours / 60).toFixed(2) : '-'}}</td>
                                <td>{{dayAttendance.actualWorkingHours ? (dayAttendance.actualWorkingHours / 60).toFixed(2) : '-'}}</td>
                                <td>{{dayAttendance.overtimeHoursStatutory ? (dayAttendance.overtimeHoursStatutory / 60).toFixed(2) : '-'}}</td>
                                <td>{{dayAttendance.overtimeHoursNonStatutory ? (dayAttendance.overtimeHoursNonStatutory / 60).toFixed(2) : '-'}}</td>
                                <td>{{dayAttendance.midnightOvertime ? (dayAttendance.midnightOvertime / 60).toFixed(2) : '-'}}</td>
                                <td>{{dayAttendance.offShiftWorkingHours ? (dayAttendance.offShiftWorkingHours / 60).toFixed(2) : '-'}}</td>
                                <td>
                                    <input type="text" v-model="editData.substituteTime" :class="{'is-invalid' : errors.substituteTime}" class="fixed-width-60 form-control"/>
                                    <span v-if="editMode && errors.substituteTime" class="error invalid-feedback">
                                        {{ errors.substituteTime }}
                                    </span>
                                </td>
                                <td>
                                    <select v-model="editData.substituteDay">
                                        <option value="0"></option>
                                        <option v-for="day in days" :key="day" :value="day">{{day}}日</option>
                                    </select>
                                </td>
                                <td>
                                    <template v-if="dayAttendance.consecutiveWork">●</template>
                                    <template v-else>-</template>
                                </td>
                                <td>
                                    <input type="text" v-model="editData.annualPaidTime" :class="{'is-invalid' : errors.annualPaidTime}" class="fixed-width-60 form-control"/>
                                    <span v-if="editMode && errors.annualPaidTime" class="error invalid-feedback">
                                        {{ errors.annualPaidTime }}
                                    </span>
                                </td>
                                <td ><i v-if="dayAttendance.annualPaidTime" class="fas fa-check fa-lg"></i></td>
                                <td>
                                    <input type="text" v-model="editData.specialPaidTime" :class="{'is-invalid' : errors.specialPaidTime}" class="fixed-width-60 form-control"/>
                                    <span v-if="editMode && errors.specialPaidTime" class="error invalid-feedback">
                                        {{ errors.specialPaidTime }}
                                    </span>
                                </td>
                                <td><i v-if="dayAttendance.specialPaidTime" class="fas fa-check fa-lg"></i></td>
                                <td>
                                    <input type="text" v-model="editData.specialUnpaidTime" :class="{'is-invalid' : errors.specialUnpaidTime}" class="fixed-width-60 form-control"/>
                                    <span v-if="editMode && errors.specialUnpaidTime" class="error invalid-feedback">
                                        {{ errors.specialUnpaidTime }}
                                    </span>
                                </td>
                                <td><i  v-if="dayAttendance.specialUnpaidTime" class="fas fa-check fa-lg"></i></td>
                                <td>
                                    <input type="text" v-model="editData.otherUnpaidTime" :class="{'is-invalid' : errors.otherUnpaidTime}" class="fixed-width-60 form-control"/>
                                    <span v-if="editMode && errors.otherUnpaidTime" class="error invalid-feedback">
                                        {{ errors.otherUnpaidTime }}
                                    </span>
                                </td>
                                <td><i v-if="dayAttendance.otherUnpaidTime" class="fas fa-check fa-lg"></i></td>
                                <td><i v-if="dayAttendance.absent" class="fas fa-check fa-lg"></i></td>
                                <td>
                                    <select v-model="editData.reasonForVacationId">
                                        <option value="0"></option>
                                        <option v-for="item in reasonForVacations" :key="item.id" :value="item.id">{{item.name}}</option>
                                    </select>
                                </td>
                                <td>
                                    <input type="text" v-model="editData.remark">
                                </td>
                                <td>
                                    <a href="#" class="mx-2" @click="editOk(index)">
                                        <i class="far fa-check-circle fa-lg"></i>
                                    </a>
                                    <a href="#" class="mx-2" @click="editCancel">
                                        <i class="far fa-window-close fa-lg"></i>
                                    </a>
                                </td>
                                <td><i v-if="dayAttendance.isApproved" class="fas fa-check fa-lg"></i>
                                    <input v-else type="checkbox" v-model="approveStatus[index]" :value="approveStatus[index]">
                                </td>
                            </template>
                            <template v-else>
                                <td class="header-fix-x">{{index}}日</td>
                                <td v-if="getWeekEnd(index) === 1" class="blue header-fix-x-68">{{getDate(index)|formatWeek}}</td>
                                <td v-else-if="getWeekEnd(index) === 2" class="red header-fix-x-68">{{getDate(index)|formatWeek}}</td>
                                <td v-else class="header-fix-x-68">{{getDate(index)|formatWeek}}</td>
                                <td>{{changeTimeFormat(dayAttendance.commutingTime1)}}</td>
                                <td>{{changeTimeFormat(dayAttendance.leaveTime1)}}</td>
                                <td>{{notZero(dayAttendance.behindTime1)}}</td>
                                <td>{{notZero(dayAttendance.leaveEarly1)}}</td>
                                <td>{{changeTimeFormat(dayAttendance.commutingTime2)}}</td>
                                <td>{{changeTimeFormat(dayAttendance.leaveTime2)}}</td>
                                <td>{{notZero(dayAttendance.behindTime2)}}</td>
                                <td>{{notZero(dayAttendance.leaveEarly2)}}</td>
                                <td>{{dayAttendance.workingHours ? (dayAttendance.workingHours / 60).toFixed(2) : '-'}}</td>
                                <td>{{dayAttendance.restHours ? (dayAttendance.restHours / 60).toFixed(2) : '-'}}</td>
                                <td>{{dayAttendance.actualWorkingHours ? (dayAttendance.actualWorkingHours / 60).toFixed(2) : '-'}}</td>
                                <td>{{dayAttendance.overtimeHoursStatutory ? (dayAttendance.overtimeHoursStatutory / 60).toFixed(2) : '-'}}</td>
                                <td>{{dayAttendance.overtimeHoursNonStatutory ? (dayAttendance.overtimeHoursNonStatutory / 60).toFixed(2) : '-'}}</td>
                                <td>{{dayAttendance.midnightOvertime ? (dayAttendance.midnightOvertime / 60).toFixed(2) : '-'}}</td>
                                <td>{{dayAttendance.offShiftWorkingHours ? (dayAttendance.offShiftWorkingHours / 60).toFixed(2) : '-'}}</td>
                                <td>{{dayAttendance.substituteTime ? (dayAttendance.substituteTime / 60).toFixed(2) : '-'}}</td>
                                <td>{{getSubstituteDay(dayAttendance.substituteDay)}}</td>
                                <td>
                                    <template v-if="dayAttendance.consecutiveWork">●</template>
                                    <template v-else>-</template>
                                </td>
                                <td>{{dayAttendance.annualPaidTime ? (dayAttendance.annualPaidTime / 60).toFixed(2) : '-'}}</td>
                                <td><i v-if="dayAttendance.annualPaidTime" class="fas fa-check fa-lg"></i></td>
                                <td>{{dayAttendance.specialPaidTime ? (dayAttendance.specialPaidTime / 60).toFixed(2) : '-'}}</td>
                                <td><i v-if="dayAttendance.specialPaidTime" class="fas fa-check fa-lg"></i></td>
                                <td>{{dayAttendance.specialUnpaidTime ? (dayAttendance.specialUnpaidTime / 60).toFixed(2) : '-'}}</td>
                                <td><i v-if="dayAttendance.specialUnpaidTime" class="fas fa-check fa-lg"></i></td>
                                <td>{{dayAttendance.otherUnpaidTime ? (dayAttendance.otherUnpaidTime / 60).toFixed(2) : '-'}}</td>
                                <td><i v-if="dayAttendance.otherUnpaidTime" class="fas fa-check fa-lg"></i></td>
                                <td><i v-if="dayAttendance.absent" class="fas fa-check fa-lg"></i></td>
                                <td>
                                    {{getVacationName(dayAttendance.reasonForVacationId)}}
                                </td>
                                <td>
                                    <input type="text" :value="dayAttendance.remark" readonly>
                                </td>
                                <td>
                                    <a v-if="!dayAttendance.isApproved" @click="editRow(index, dayAttendance)">
                                        <i class="fa fa-edit fa-lg blue"></i>
                                    </a>
                                </td>
                                <td><i v-if="dayAttendance.isApproved" class="fas fa-check fa-lg"></i>
                                    <input v-else type="checkbox" v-model="approveStatus[index]" :value="approveStatus[index]">
                                </td>
                            </template>
                        </tr>
                    </tbody>
            </table>
        </div>
        <br>
        <div class="table-responsive p-0" id="sumTable1">
            <table class="table table-bordered">
                <tbody class="text-center">
                    <tr class="top-green text-white">
                        <th class="align-middle">勤務日数</th>
                        <th class="align-middle">勤務割合</th>
                        <th class="align-middle">実働時間</th>
                        <th class="align-middle">残業外<br>労働時間</th>
                        <th class="align-middle">所定労働<br>時間-A</th>
                        <th class="align-middle">所定労働<br>時間-B</th>
                        <th class="align-middle">過不足<br>時間</th>
                        <th class="align-middle">法定内<br>残業時間</th>
                        <th class="align-middle">法定外<br>残業時間</th>
                        <th class="align-middle">深夜時間</th>
                        <th class="align-middle">遅刻時間</th>
                        <th class="align-middle">早退時間</th>
                        <th class="align-middle">シフト外勤務時間</th>
                        <th class="align-middle">代休時間</th>
                        <th class="align-middle">連勤時間</th>
                    </tr>
                    <tr class="heavy-green text-white">
                        <td>{{total.workingDays}}日</td>
                        <td>{{userMeta.scheduledDays ? (total.workingDays * 100 / userMeta.scheduledDays) : ''}}%</td>
                        <td>{{(total.totalWorkingHours / 60).toFixed(2)}}時間</td>
                        <td>{{((total.actualWorkingHoursWeekdays + total.actualWorkingHoursSaturday) / 60).toFixed(2)}}時間</td>
                        <td>{{(total.scheduledWorkingHoursA / 60).toFixed(2)}}時間</td>
                        <td>{{(total.scheduledWorkingHoursB / 60).toFixed(2)}}時間</td>
                        <td>{{(total.excessAndDeficiencyTime / 60).toFixed(2)}}時間</td>
                        <td>{{(total.overtimeHoursStatutory / 60).toFixed(2)}}時間</td>
                        <td>{{(total.overtimeHoursNonStatutory / 60).toFixed(2)}}時間</td>
                        <td>{{(total.midnightOvertime / 60).toFixed(2)}}時間</td>
                        <td>{{(total.behindTime / 60).toFixed(2)}}時間</td>
                        <td>{{(total.leaveEarly / 60).toFixed(2)}}時間</td>
                        <td>{{(total.offShiftWorkingHours / 60).toFixed(2)}}時間</td>
                        <td>{{(total.substituteHolidayTime / 60).toFixed(2)}}時間</td>
                        <td>{{(total.consecutiveWorkingHours / 60).toFixed(2)}}時間</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="table-responsive p-0" id="sumTable2">
            <table class="table table-bordered">
                <tbody class="text-center">
                    <tr class="top-green text-white">
                        <th class="align-middle" colspan="2">年次有休</th>
                        <th class="align-middle" colspan="2">特休有給</th>
                        <th class="align-middle" colspan="2">特休無給</th>
                        <th class="align-middle" colspan="2">その他無給</th>
                        <th class="align-middle" rowspan="2">欠勤日数</th>
                    </tr>
                    <tr class="top-green text-white">
                        <th class="align-middle">時間</th>
                        <th class="align-middle">日数</th>
                        <th class="align-middle">時間</th>
                        <th class="align-middle">日数</th>
                        <th class="align-middle">時間</th>
                        <th class="align-middle">日数</th>
                        <th class="align-middle">時間</th>
                        <th class="align-middle">日数</th>
                    </tr>
                    <tr class="heavy-green text-white">
                        <td>{{(total.annualPaidTime / 60).toFixed(2)}}時間</td>
                        <td>{{total.annualPaidDays}}日</td>
                        <td>{{(total.specialPaidTime / 60).toFixed(2)}}時間</td>
                        <td>{{total.specialPaidDays}}日</td>
                        <td>{{(total.specialUnpaidTime / 60).toFixed(2)}}時間</td>
                        <td>{{total.specialUnpaidDays}}日</td>
                        <td>{{(total.otherUnpaidTime / 60).toFixed(2)}}時間</td>
                        <td>{{total.otherUnpaidDays}}日</td>
                        <td>{{total.absenceDays}}日</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <button v-if="isShowApplyBtn" class="btn btn-primary float-right" @click="approve">承認済み</button>
    </div>
</template>
<script>
import moment from 'moment-timezone';
import { mapState } from 'vuex';
import api, { apiErrorHandler } from '../../global/api';
import actionLoading from '../../mixin/actionLoading';
import { showSuccess } from '../../helpers/error';

    export default {
        mixins: [actionLoading],
        props: {
            attendance: {},
            total: {},
            userMeta: {},
            month: '',
            userId: null,
            isShowApplyBtn: true,
        },
        watch: {
            ['userId']: function () {
                this.editMode = false;
            }
        },
        data () {
            return {
                editMode: false,
                currentDate: new Date(),
                displayDate: new Date(),
                scrolling: false,
                timer: null,
                selectedIndex: null,
                days: [],
                selectedDates: [],
                approveStatus: Array.from({length: 32}, () => false),
                selectAllFlag: false,
                editData: {
                    startWorkTime1: '',
                    endWorkTime1: '',
                    startWorkTime2: '',
                    endWorkTime2: '',
                    annualPaidTime: null,
                    annualPaidHoliday: false,
                    specialPaidTime: null,
                    specialPaidHoliday: false,
                    specialUnpaidTime: null,
                    specialUnPaidHoliday: false,
                    otherUnpaidTime: null,
                    otherUnPaidHoliday: false,
                    substituteTime: null,
                    substituteDay: null,
                    absenceDay: false,
                    reason: null,
                    remark: '',
                    isApproved: null,
                },
                errors: {
                    startWorkTime1: '',
                    endWorkTime1: '',
                    startWorkTime2: '',
                    endWorkTime2: '',
                    annualPaidTime: null,
                    annualPaidHoliday: false,
                    specialPaidTime: null,
                    specialPaidHoliday: false,
                    specialUnpaidTime: null,
                    specialUnPaidHoliday: false,
                    substituteTime: null,
                    otherUnpaidTime: null,
                    otherUnPaidHoliday: false,
                    absenceDay: false,
                    reason: null,
                    remark: '',
                }
            }
        },
        computed: {
            ...mapState({
                reasonForVacations: state => state.constants.reasonForVacations
            })
        },
        methods: {
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
            getDate(index) {
                const date = this.month + '-' + ('0' + index).slice(-2);
                return date;
            },
            notZero(number) {
                if(number > 0) {
                    return number + "分";
                } else {
                    return '';
                }
            },
            editRow(index, dayAttendance) {
                this.getDays();
                this.selectedIndex = index;
                this.editMode = !this.editMode;
                this.errors = {
                    startWorkTime1: '',
                    endWorkTime1: '',
                    startWorkTime2: '',
                    endWorkTime2: '',
                    annualPaidTime: null,
                    annualPaidHoliday: false,
                    specialPaidTime: null,
                    specialPaidHoliday: false,
                    specialUnpaidTime: null,
                    specialUnPaidHoliday: false,
                    otherUnpaidTime: null,
                    otherUnPaidHoliday: false,
                    absenceDay: false,
                    reason: null,
                    remark: '',
                }
                this.editData.startWorkTime1 = this.changeTimeFormat(dayAttendance.commutingTime1);
                this.editData.endWorkTime1 = this.changeTimeFormat(dayAttendance.leaveTime1);
                this.editData.startWorkTime2 = this.changeTimeFormat(dayAttendance.commutingTime2);
                this.editData.endWorkTime2 = this.changeTimeFormat(dayAttendance.leaveTime2);
                this.editData.annualPaidTime = dayAttendance.annualPaidTime ? (dayAttendance.annualPaidTime / 60).toFixed(2) : 0;
                this.editData.specialPaidTime = dayAttendance.specialPaidTime ? (dayAttendance.specialPaidTime / 60).toFixed(2) : 0;
                this.editData.specialUnpaidTime = dayAttendance.specialUnpaidTime ? (dayAttendance.specialUnpaidTime / 60).toFixed(2) : 0;
                this.editData.otherUnpaidTime = dayAttendance.otherUnpaidTime ? (dayAttendance.otherUnpaidTime / 60).toFixed(2) : 0;
                this.editData.substituteTime = dayAttendance.substituteTime ? (dayAttendance.substituteTime / 60).toFixed(2) : 0;
                this.editData.substituteDay = dayAttendance.substituteDay;
                this.editData.userId = this.userId;
                this.editData.reasonForVacationId = dayAttendance.reasonForVacationId;
                this.editData.remark = dayAttendance.remark;
            },
            validate() {
                let valid = true;
                var timeRegex = /^([0-1]?[0-9]|2[0-3]):[0-5][0-9]$/;
                if (this.editData.startWorkTime1 && !this.editData.startWorkTime1.match(timeRegex)) {
                    this.errors.startWorkTime1 = this.$t('Please input correct format');                                 // need trans
                    valid = false;
                }
                if (this.editData.endWorkTime1 && !this.editData.endWorkTime1.match(timeRegex)) {
                    this.errors.endWorkTime1 = this.$t('Please input correct format');                              //need trans
                    valid = false;
                }
                if (this.editData.startWorkTime2 && !this.editData.startWorkTime2.match(timeRegex)) {
                    this.errors.startWorkTime2 = this.$t('Please input correct format');
                    valid = false;
                }
                if (this.editData.endWorkTime2 && !this.editData.endWorkTime2.match(timeRegex)) {
                    this.errors.endWorkTime2 = this.$t('Please input correct format');
                    valid = false;
                }
                if (this.editData.startWorkTime1 && !this.editData.endWorkTime1) {
                    this.errors.endWorkTime1 = this.$t('Please input time');                                 // need trans
                    valid = false;
                }
                if (!this.editData.startWorkTime1 && this.editData.endWorkTime1) {
                    this.errors.startWorkTime1 = this.$t('Please input time');                                 // need trans
                    valid = false;
                }
                if (this.editData.startWorkTime2 && !this.editData.endWorkTime2) {
                    this.errors.endWorkTime2 = this.$t('Please input time');                                 // need trans
                    valid = false;
                }
                if (!this.editData.startWorkTime2 && this.editData.endWorkTime2) {
                    this.errors.startWorkTime2 = this.$t('Please input time');                                 // need trans
                    valid = false;
                }
                if (this.editData.startWorkTime1 && this.editData.startWorkTime2 && this.changeTimeString(this.editData.startWorkTime1) >= this.changeTimeString(this.editData.startWorkTime2)) {
                    this.errors.startWorkTime1 = this.$t('Please avoid time overlapped');
                    valid = false;
                }
                if (this.editData.startWorkTime2 && this.editData.endWorkTime1 && this.changeTimeString(this.editData.startWorkTime2) <= this.changeTimeString(this.editData.endWorkTime1)) {
                    this.errors.endWorkTime1 = this.$t('Please avoid time overlapped');
                    valid = false;
                }
                if (this.editData.startWorkTime1 && this.editData.endWorkTime2 && this.changeTimeString(this.editData.startWorkTime1) >= this.changeTimeString(this.editData.endWorkTime2)) {
                    this.errors.endWorkTime1 = this.$t('Please avoid time overlapped');
                    valid = false;
                }
                if (this.editData.endWorkTime1 && this.editData.endWorkTime2 && this.changeTimeString(this.editData.endWorkTime1) >= this.changeTimeString(this.editData.endWorkTime2)) {
                    this.errors.endWorkTime2 = this.$t('Please avoid time overlapped');
                    valid = false;
                }
                if (this.editData.annualPaidTime && !Number.isInteger(parseFloat(this.editData.annualPaidTime))) {
                    this.errors.annualPaidTime = this.$t('Please select number');
                    valid = false;
                }
                if (this.editData.specialPaidTime && !Number.isInteger(parseFloat(this.editData.specialPaidTime))) {
                    this.errors.specialPaidTime = this.$t('Please select number');
                    valid = false;
                }
                if (this.editData.specialUnpaidTime && !Number.isInteger(parseFloat(this.editData.specialUnpaidTime))) {
                    this.errors.specialUnpaidTime = this.$t('Please input number');
                    valid = false;
                }
                if (this.editData.otherUnpaidTime && !Number.isInteger(parseFloat(this.editData.otherUnpaidTime))) {
                    this.errors.otherUnpaidTime = this.$t('Please input number');
                    valid = false;
                }
                if (this.editData.substituteTime && !Number.isInteger(parseFloat(this.editData.substituteTime))) {
                    this.errors.substituteTime = this.$t('Please input number');
                    valid = false;
                }
                return valid;
            },
            editOk(index) {
                if (this.actionLoading) return;
                if (!this.validate()) return;
                const requestData = {
                    'user_id': this.editData.userId,
                    'date': this.month + "-" + ('0' + this.selectedIndex).slice(-2),
                    'commuting_time_1': this.changeTimeString(this.editData.startWorkTime1),
                    'leave_time_1': this.changeTimeString(this.editData.endWorkTime1),
                    'commuting_time_2': this.changeTimeString(this.editData.startWorkTime2),
                    'leave_time_2': this.changeTimeString(this.editData.endWorkTime2),
                    'substitute_time': this.editData.substituteTime * 60,
                    'substitute_day' : this.editData.substituteDay,
                    'annual_paid_time': this.editData.annualPaidTime * 60,
                    'special_paid_time': this.editData.specialPaidTime * 60,
                    'special_unpaid_time': this.editData.specialUnpaidTime * 60,
                    'other_unpaid_time': this.editData.otherUnpaidTime * 60,
                    'reason_for_vacation_id': this.editData.reasonForVacationId,
                    'remark': this.editData.remark,
                };
                this.setActionLoading();
                let request;
                request = api.post('monthly-summary/attendances', null, requestData);
                request.then(() => {
                        this.unsetActionLoading();
                        showSuccess(this.$t("Successfully saved"));
                        this.$emit('success');
                        this.editMode = false;
                    })
                    .catch(e => {
                        apiErrorHandler(e);
                        this.unsetActionLoading();
                    });
                this.editMode = false;
            },
            editCancel() {
                this.editMode = false;
                this.errors = {
                    startWorkTime1: '',
                    endWorkTime1: '',
                    startWorkTime2: '',
                    endWorkTime2: '',
                    annualPaidTime: null,
                    annualPaidHoliday: false,
                    specialPaidTime: null,
                    specialPaidHoliday: false,
                    specialUnpaidTime: null,
                    specialUnPaidHoliday: false,
                    otherUnpaidTime: null,
                    otherUnPaidHoliday: false,
                    absenceDay: false,
                    reason: null,
                    remark: '',
                }
            },
            selectDate(dayIndex) {

            },
            selectAllDates() {
                this.selectAllFlag = !this.selectAllFlag;
                this.approveStatus = this.approveStatus.map(item => (this.selectAllFlag));
            },
            currentTime(){
                var today = new Date();
                var month = today.getMonth() + 1;
                var day = today.getDate();
                return month + "月" + day + "日 "
                + today.getHours() + ":"
                + today.getMinutes();
            },
            changeTimeFormat(date) {
                if(date) {
                    return moment(date).tz('Asia/Tokyo').format('HH:mm');
                } else {
                    return "";
                }
            },
            changeTimeString(timeString) {
                if(timeString) {
                    var time = timeString.split(':');
                    var time1 = ('0' + time[0]).slice(-2);
                    var time2 = time[1];
                    return time1 + ":" + time2;
                } else {
                    return "";
                }
            },
            getSubstituteDay(substituteDay){
                if(substituteDay) {
                    return moment(this.month + '-' + ('0' + substituteDay).slice(-2), 'YYYY-MM-DD').format('MM/DD/YYYY');
                } return '';
            },
            handleScroll() {
                if(this.timer !== null) {
                    clearTimeout(this.timer);
                    $("#sumTable1").fadeOut(400);
                    $("#sumTable2").fadeOut(400);
                }
                this.timer = setTimeout(function() {
                    $("#sumTable1").fadeIn(400);
                    $("#sumTable2").fadeIn(400);
                }, 150);
            },
            getDays() {
                this.days = [];
                const dayCounts = moment(this.month, "YYYY-MM").daysInMonth();
                for (let day = 1; day <= dayCounts; day++) {
                    this.days.push(day);
                }
            },
            getVacationName(reasonForVacationId) {
                if(reasonForVacationId){
                    if(this.reasonForVacations.find(item => item.id === reasonForVacationId))
                        return this.reasonForVacations.find(item => item.id === reasonForVacationId).name;
                    else
                        return null;
                } else {
                    return null;
                }
            },
            approve() {
                this.selectedDates = this.approveStatus.map((item, i) => item === true ? i : 0);
                this.selectedDates = this.selectedDates.filter(item => item > 0);

                const requestData = {
                    'user_id': this.userId,
                    'month': moment(this.month, "YYYY-MM").format("YYYYMM"),
                    'days': this.selectedDates,
                };

                this.setActionLoading();
                let request;
                request = api.put('monthly-summary/attendances/approve', null, requestData);
                request.then(() => {
                        this.unsetActionLoading();
                        showSuccess(this.$t("Successfully saved"));
                        this.$emit('success');
                        this.editMode = false;
                    })
                    .catch(e => {
                        apiErrorHandler(e);
                        this.unsetActionLoading();
                    });
            }
        },
        created() {
            this.timer = null;
        },
        mounted() {
            window.addEventListener('scroll', this.handleScroll);
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
