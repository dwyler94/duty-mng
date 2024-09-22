<template>
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">{{getFullDate(date)}}</h5>
            <!-- <h5 class="modal-title" v-show="editmode">再申請</h5> -->
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="table-responsive p-0" style="width:100%">
                <table class="table table-bordered shifttable-bordered shift-time-table">
                    <thead class="text-center">
                        <tr class="light-green">
                            <th width="18%" class="p-0 align-middle"></th>
                            <th width="14%" class="p-0 align-middle">タイムテーブル</th>
                            <th colspan="2" class="one-digit-time">7</th>
                            <th colspan="2" class="one-digit-time">8</th>
                            <th colspan="2" class="one-digit-time">9</th>
                            <th colspan="2">10</th>
                            <th colspan="2">11</th>
                            <th colspan="2">12</th>
                            <th colspan="2">13</th>
                            <th colspan="2">14</th>
                            <th colspan="2">15</th>
                            <th colspan="2">16</th>
                            <th colspan="2">17</th>
                            <th colspan="2">18</th>
                            <th colspan="2">19</th>
                            <th colspan="2">20</th>
                            <th colspan="2">21</th>
                        </tr>
                    </thead>
                        <tbody class="text-center">
                            <tr>
                                <td></td>
                                <td>
                                    0歳児園児数
                                </td>
                                <td v-for="(child, key) in childShift.children0" :key="'children0'+key">
                                    {{child}}
                                </td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>
                                    必要職員数
                                </td>
                                <td v-for="(nurse, key) in childShift.neededNurse0" :key="'nurse0'+key">
                                    {{nurse}}
                                </td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>
                                    1歳児園児数
                                </td>
                                <td v-for="(child, key) in childShift.children1" :key="'children1'+key">
                                    {{child}}
                                </td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>
                                    必要職員数
                                </td>
                                <td v-for="(nurse, key) in childShift.neededNurse1" :key="'nurse1'+key">
                                    {{nurse}}
                                </td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>
                                    2歳児園児数
                                </td>
                                <td v-for="(child, key) in childShift.children2" :key="'children2'+key">
                                    {{child}}
                                </td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>
                                    必要職員数
                                </td>
                                <td v-for="(nurse, key) in childShift.neededNurse2" :key="'nurse2'+key">
                                    {{nurse}}
                                </td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>
                                    3歳児園児数
                                </td>
                                <td v-for="(child, key) in childShift.children3" :key="'children3'+key">
                                    {{child}}
                                </td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>
                                    必要職員数
                                </td>
                                <td v-for="(nurse, key) in childShift.neededNurse3" :key="'nurse3'+key">
                                    {{nurse}}
                                </td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>
                                    4歳児園児数
                                </td>
                                <td v-for="(child, key) in childShift.children4" :key="'children4'+key">
                                    {{child}}
                                </td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>
                                    必要職員数
                                </td>
                                <td v-for="(nurse, key) in childShift.neededNurse4" :key="'nurse4'+key">
                                    {{nurse}}
                                </td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>
                                    5歳児園児数
                                </td>
                                <td v-for="(child, key) in childShift.children5" :key="'children5'+key">
                                    {{child}}
                                </td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>
                                    必要職員数
                                </td>
                                <td v-for="(nurse, key) in childShift.neededNurse5" :key="'nurse5'+key">
                                    {{nurse}}
                                </td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>
                                    合計
                                </td>
                                <td v-for="(sum, key) in childShift.sumPrecise" :key="'sum'+key">
                                    {{Math.round(sum)}}
                                </td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>全体必要職員数</td>
                                <td v-for="(round, key) in childShift.sumRound" :key="'round'+key">
                                    {{round}}
                                </td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>実働職員数</td>
                                <td v-for="(worker, index) in childShift.actualWorkers" :key="'realworker'+index" :class="getBackColor(childShift.sumRound[index], worker)">
                                    {{worker}}
                                </td>
                            </tr>
                        </tbody>
                </table>
            </div>

            <div class="table-responsive p-0" style="width:100%">
                <table class="table table-bordered shifttable-bordered shift-time-table">
                    <thead class="text-center">
                        <tr class="light-green">
                            <th width="18%" colspan="2" class="p-0 align-middle">タイムテーブル</th>
                            <th width="7%" class="p-0 align-middle">勤務時間</th>
                            <th width="7%" class="p-0 align-middle">休憩時間</th>
                            <th colspan="2" class="one-digit-time">7</th>
                            <th colspan="2" class="one-digit-time">8</th>
                            <th colspan="2" class="one-digit-time">9</th>
                            <th colspan="2">10</th>
                            <th colspan="2">11</th>
                            <th colspan="2">12</th>
                            <th colspan="2">13</th>
                            <th colspan="2">14</th>
                            <th colspan="2">15</th>
                            <th colspan="2">16</th>
                            <th colspan="2">17</th>
                            <th colspan="2">18</th>
                            <th colspan="2">19</th>
                            <th colspan="2">20</th>
                            <th colspan="2">21</th>
                        </tr>
                    </thead>
                        <tbody class="text-center">
                            <tr v-for="shift in shifts" :key="shift.id">
                                <td>
                                    {{shift.name}}
                                </td>
                                <td>
                                    <template v-if="shift.shifts[0] && shift.shifts[0].vacationReasonId">
                                        <label class="ml-auto font-weight-normal mb-0">{{getVacationName(shift.shifts[0].vacationReasonId)}}</label>
                                    </template>
                                    <template v-else v-for="shiftItem in shift.shifts">
                                        <div class="mb-0" :key="shiftItem.id">
                                            <div class="ml-auto font-weight-normal mb-0">{{shiftItem.workingHourName}}</div>
                                        </div>
                                    </template>
                                </td>
                                <td v-if="shift.shifts[0] && shift.shifts[0].vacationReasonId">-</td>
                                <td v-else class="fixed-width-40">
                                    {{getWorkingHours(shift)}}
                                </td>
                                <td v-if="shift.shifts[0] && shift.shifts[0].vacationReasonId">-</td>
                                <td v-else class="fixed-width-40">
                                    {{getRestHours(shift)}}
                                </td>
                                <td v-for="time in dayHours" :key="time" :class="getColor(time, shift)"></td>
                            </tr>
                        </tbody>
                </table>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">閉じる</button>
            <!-- <button type="submit" class="btn btn-primary">登録</button> -->
        </div>

    </div>
</template>
<script>
import moment from 'moment';
import api, { apiErrorHandler } from '../../global/api';
import actionLoading from '../../mixin/actionLoading';
import { showSuccess } from '../../helpers/error';
import ShiftForm from './ShiftForm.vue';

    export default {
        mixins: [actionLoading],
        components: { ShiftForm },
        props: {
            date: '',
            shifts: [],
            childShift: {},
        },
        watch: {

        },
        data() {
            return {
                errors: {
                    name: '',
                },
                dayHours: ["07:00", "07:30","08:00","08:30","09:00","09:30","10:00","10:30","11:00","11:30","12:00","12:30","13:00",
                "13:30","14:00","14:30","15:00","15:30","16:00","16:30","17:00","17:30","18:00","18:30","19:00","19:30","20:00",
                "20:30","21:00", "21:30"]
            }
        },

        methods: {
            getFullDate(date) {
                return moment(date, "YYYY-MM-DD").format("YYYY年MM月DD日(ddd)");
            },
            getWorkingHours(shift) {
                if(shift.shifts && shift.shifts.length > 0) {
                    return shift.shifts.reduce((sum, item) => (sum + parseFloat(this.getBetweenTimes(item.startTime, item.endTime))), 0)
                }
            },
            getBetweenTimes(startTime, endTime) {
                if(!startTime || !endTime) return 0;
                let t1,t2,t3;
                if(endTime >= startTime) {
                    t1 = moment(startTime, "hh:mm:ss");
                    t2 = moment(endTime, "hh:mm:ss");
                    t3 = moment.duration(t2.diff(t1, 'minutes'));
                } else {
                    t1 = moment(startTime, "hh:mm:ss");
                    t2 = moment(endTime, "hh:mm:ss").add(1, "days");
                    t3 = moment.duration(t2.diff(t1, 'minutes'));
                }
                return (t3 / 60).toFixed(2);
            },
            getRestHours(shift) {
                if(shift.shifts && shift.shifts.length > 0) {
                return shift.shifts.reduce((sum, item) => (sum + parseFloat(this.getBetweenTimes(item.restStartTime, item.restEndTime))), 0)}
            },
            getColor(hourIndex, shift) {
                let indexTime = hourIndex + ":00";
                let workTimeIn = shift.shifts.filter(item => (
                    item.startTime <= item.endTime ? indexTime >= item.startTime && indexTime < item.endTime : indexTime >= item.startTime && indexTime <= "22:00:00"
                    ));
                let restTimeIn = shift.shifts.filter(item => (indexTime >= item.restStartTime && indexTime < item.restEndTime));
                let backColor = "";
                if(workTimeIn && workTimeIn.length > 0) {
                    backColor = "background-orange";
                }
                if(restTimeIn && restTimeIn.length > 0) {
                    backColor = "background-grey";
                }
                return backColor;
            },
            getBackColor(neededUser, realUser) {
                let backColor = "";
                if(neededUser > realUser) {
                    backColor = "background-pink";
                }
                return backColor;
            },
            getVacationName(vacationId) {
                if(vacationId == this.$vacationId.ANNUAL_PAID) {
                    return "年次有休";
                } else if(vacationId == this.$vacationId.SPECIAL_PAID) {
                    return "特休有給";
                } else if(vacationId == this.$vacationId.SPECIAL_UNPAID) {
                    return "特休無給";
                } else if(vacationId == this.$vacationId.OTHER_UNPAID) {
                    return "その他無給";
                }
            },
            openShiftPopup(employeeId, dateIndex){
                $('#shiftEditForm').modal('show');
                const employee = this.shifts.find(item => item.id === employeeId);
                if (!employee) return;
                const {id, name, employmentStatusId, roleId} = employee;
                this.selectedEmployee = {id, name, employmentStatusId, roleId};

                this.selectedDate = this.selectedMonth.slice(0,4) + '-' + this.selectedMonth.slice(4) + '-' + ('0' + (dateIndex + 1)).slice(-2);
                this.getWorkingHours(employeeId);
                this.selectedShift = this.shifts.filter(item => item.id == employeeId)[0].shifts[dateIndex];
                this.getVacations();
            },
        }
    }
</script>
