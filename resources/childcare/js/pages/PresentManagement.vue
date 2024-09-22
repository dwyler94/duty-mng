<template>
    <section class="content">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header calendar-title">
                            <h3 class="card-title mb-0">{{officeName}}</h3>
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
                            <div class="table-responsive p-0" style="height: 500px;" id="children_present_table">
                                <table class="table table-bordered table-striped table-children table-head-fixed table-hover">
                                    <thead class="text-center text-white">
                                        <tr class="dark-brown">
                                            <th class="children-present-fix" style="left: 0px; z-index: 12 !important; outline: white solid 1px;">日付</th>
                                            <th class="children-present-fix-140" style="z-index: 12 !important; outline: white solid 1px;">曜日</th>
                                            <th>登園時間</th>
                                            <th>降園時間</th>
                                            <th>欠席</th>
                                            <th>延長</th>
                                            <th>編集</th>
                                        </tr>
                                    </thead>
                                        <tbody class="text-center children-present-tr">
                                            <tr v-for="(item, index) in attendances" :key="index">
                                                <td class="children-present-fix">
                                                    {{ item.day }}
                                                </td>
                                                <td class="children-present-fix-140">
                                                    <div v-if="getDayOfWeek(item.day) === 6" class="blue">{{getDayOfWeekLabel(item.day)}}</div>
                                                    <div v-else-if="getDayOfWeek(item.day) === 0" class="red">{{getDayOfWeekLabel(item.day)}}</div>
                                                    <div v-else>{{getDayOfWeekLabel(item.day)}}</div>
                                                </td>
                                                <td>{{ formatTime(item.commutingTime) }}</td>
                                                <td>{{ formatTime(item.leaveTime) }}</td>
                                                <td class="align-middle">{{getAbsenceName(item.reasonForAbsenceId, item.noSchedule)}}</td>
                                                <td class="align-middle">{{changeExtensionFormat(item.extension)}}</td>
                                                <td>
                                                    <a href="javascript:void(0)" class="mx-2" @click="openEditForm(item.day)" v-if="!item.exclude">
                                                        <i class="far fa-edit fa-lg"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        </tbody>
                                </table>
                            </div>
                            <div class="float-right d-flex align-items-center mt-2">
                                <button class="btn btn-primary float-right" type="button" @click="onCsv">CSV出力</button>
                            </div>
                            <!-- Modal -->
                            <div class="modal fade" id="attend-edit-form" tabindex="-1" role="dialog" aria-labelledby="attend-edit-form" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <edit-form :editData="formData" :date="selectedDate" :childId="childId" v-on:success="onAttendSaved"></edit-form>
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

import moment from 'moment';
import { mapState } from 'vuex';
import actionLoading from '../mixin/actionLoading';
import { showSuccess } from '../helpers/error';
import EditForm from './ChildrenAttendances/EditForm.vue';
import api, { apiErrorHandler } from '../global/api';
import LocalStorage from '../helpers/localStorage';

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
        EditForm
    },
    mixins: [actionLoading],
    data() {
        return {
            editmode: false,
            currentDate: moment(),
            childId: null,
            attendances: [],
            selectedDate: new Date(),
            formData: { ...defaultAttendance }
        }
    },
    computed: {
        ...mapState({
                reasonForAbsences: state => state.constants.reasonForAbsences,
                session: state => state.session.info
        }),
        displayDate() {
            return this.currentDate.format('YYYY年 MM月');
        },
        isCurrentMonth() {
            return this.currentDate.format('YYYY-MM') === moment().format('YYYY-MM');
        },
        officeName() {
            if (!this.session.office) return '';
            return this.session.office.name;
        }
    },
    methods: {
        onCsv() {
            const url = process.env.MIX_APP_BASE_URL + 'child-monthly-attendance/csv/' + this.childId + '?token=' + LocalStorage.getToken() + '&month=' + this.currentDate.format('YYYY-MM');
            location.href=url;
        },
        onNext() {
            this.currentDate = moment(this.currentDate.add(1, 'months').format('YYYY-MM') + '-01', 'YYYY-MM-DD');
            this.fetchData();
        },
        onPrev() {

            this.currentDate = moment(this.currentDate.add(-1, 'months').format('YYYY-MM') + '-01', 'YYYY-MM-DD');
            this.fetchData();
        },
        onCurrentMonth(){
            this.currentDate = moment(new Date());
            this.fetchData();
        },
        openEditForm(day) {
            this.selectedDate = moment(this.currentDate.format('YYYY-MM-') + String(day).padStart(2, '0')).toDate();
            this.selectedDate = moment(this.selectedDate).format('YYYY-MM-DD');
            const formData = this.attendances.find(item => item.day === day);
            this.formData = {...formData, id: this.childId };
            $("#attend-edit-form").modal('show');
        },
        onAttendSaved() {
            $("#attend-edit-form").modal('hide');
            this.fetchData();
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
        changeExtensionFormat(extension) {
            if(extension) {
                return moment(extension, 'hh:mm:ss').format('HH:mm');
            }
            return null;
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
        }
    },
    mounted() {
        this.childId = this.$route.params.childId;
        this.fetchData();
    }
};
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
