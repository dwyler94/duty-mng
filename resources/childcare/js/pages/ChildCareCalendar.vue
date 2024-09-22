<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="card">
                    <div class="card-header calendar-title">
                        <div class="row">
                            <div class="col-md-3">
                                <h3 class="card-title mb-0">保育システム</h3>
                            </div>
                            <div class="col-md-3">
                                {{ child.name }}
                            </div>
                            <div class="col-md-3">
                                <div class="calendar-center flex-grow-1">
                                    <button type="button" class="btn btn-sm btn-outline" @click="onPrev">
                                        <i class="fas fa-caret-left fa-2x"></i>
                                    </button>
                                    <div class="mx-2">{{ displayDate }}</div>
                                    <button type="button" class="btn btn-sm btn-outline-primary mx-2" @click="onCurrentMonth">
                                        今月
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline" @click="onNext">
                                        <i class="fas fa-caret-right fa-2x"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive p-0">
                            <table
                                class="table table-bordered table-striped table-calendar table-head-fixed table-hover mb-0"
                            >
                                <tbody class="text-center" v-for="(week, weekIndex) in planIndices" :key="weekIndex">
                                    <tr class="dark-yellow">
                                        <td width="8%">
                                            日付
                                        </td>
                                        <td width="13%" v-for="(item, dayIndex) in week" :key="dayIndex" :class="{'red': dayIndex === 6 || (item && item.holiday)}">
                                            <template v-if="item">
                                                {{ item.label }}
                                            </template>
                                            <template v-else>
                                            -
                                            </template>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            登園
                                        </td>
                                        <td width="13%" v-for="(item, dayIndex) in week" :key="dayIndex">
                                            <template v-if="item">
                                                <hour-minute-input v-model="planDays[item.index].startTime" type="text"
                                                    :disabled="planDays[item.index].absentId > 0"
                                                    :error="planDayErrors[item.index].startTime"
                                                    @input="() => {planDayErrors[item.index].startTime = null;  editing = true;}"
                                                    :light="true"
                                                />
                                            </template>
                                            <template v-else>
                                            -
                                            </template>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            降園
                                        </td>
                                        <td width="13%" v-for="(item, dayIndex) in week" :key="dayIndex">
                                            <template v-if="item">
                                                <hour-minute-input v-model="planDays[item.index].endTime"
                                                    type="text" :disabled="planDays[item.index].absentId > 0"
                                                    :error="planDayErrors[item.index].endTime"
                                                    @input="() => {planDayErrors[item.index].endTime = null; editing = true;}"
                                                    :light="true"
                                                />
                                            </template>
                                            <template v-else>
                                            -
                                            </template>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            欠席
                                        </td>
                                        <td width="13%" v-for="(item, dayIndex) in week" :key="dayIndex">
                                            <template v-if="item">
                                                <select class="form-control" v-model="planDays[item.index].absentId">
                                                    <option></option>
                                                    <option v-for="item in reasonForAbsences" :key="item.id" :value="item.id">{{ item.name }}</option>
                                                </select>
                                                <!-- <input type="checkbox" v-model="planDays[item.index].absent"
                                                    @change="() => {planDayErrors[item.index].endTime = null; planDayErrors[item.index].startTime = null; editing = true;}"
                                                /> -->
                                            </template>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="float-right d-flex align-items-center mt-2">
                            <button class="btn btn-primary float-right" type="button" @click="onSubmit">登録</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import moment from 'moment';
import api, { apiErrorHandler } from '../global/api';
import actionLoading from '../mixin/actionLoading';
import HourMinuteInput from '../components/HourMinuteInput.vue';
import { validateHhMm } from '../helpers/datetime';
import { showSuccess } from '../helpers/error';
import { mapState } from 'vuex';

const defaultPlanItem = {
    absentId: null,
    startTime: '',
    endTime: '',
    time: '',
}
const defaultPlanDayError = {
    startTime: null,
    endTime: null
}

export default {
  components: { HourMinuteInput },
    mixins: [actionLoading],
    data() {
        return {
            retiredDisplay: false,
            errors: [],
            currentDate: moment(),
            childId: null,
            planIndices: [],
            planDays: [],
            planDayErrors: [],
            child: {},

            holidays: [],
            editing: false
        }
    },
    computed: {
        displayDate() {
            return this.currentDate.format('YYYY年 MM月');
        },
        ...mapState({
            reasonForAbsences: state => state.constants.reasonForAbsences
        })
    },
    mounted() {
        this.childId = this.$route.params.childId;
        if (this.$route.query.month) {
            this.currentDate = moment(this.$route.query.month + '-01');
        }
        this.fetchData();
        this.fetchChild();
    },
    beforeMount() {
        window.addEventListener("beforeunload", this.preventNav)
        this.$once("hook:beforeDestroy", () => {
            window.removeEventListener("beforeunload", this.preventNav);
        })
    },
    beforeRouteLeave (to, from, next) {
        if (this.editing) {
            if (confirm('変更内容が登録されていません。内容を破棄して年月を変更してよろしいですか？')) {
                next();
            } else {
                return;
            }
        } else {
            next();
        }
    },
    methods: {
        childcarePlan() {
            this.$router.push('childcare-plan');
        },
        onNext() {
            this.currentDate = moment(this.currentDate.add(1, 'months').format('YYYY-MM-DD'), 'YYYY-MM-DD');
            location.href = `/child/${this.childId}/childcare-calendar?month=${this.currentDate.format('YYYY-MM')}`;
        },
        onPrev() {
            this.currentDate = moment(this.currentDate.add(-1, 'months').format('YYYY-MM-DD'), 'YYYY-MM-DD');
            location.href = `/child/${this.childId}/childcare-calendar?month=${this.currentDate.format('YYYY-MM')}`;
        },
        onCurrentMonth(){
            this.currentDate = moment();
            location.href = `/child/${this.childId}/childcare-calendar?month=${this.currentDate.format('YYYY-MM')}`;
        },
        onSubmit() {
            if (!this.validate()) return;
            this.setActionLoading();
            api.post(`plan-days/${this.childId}`, null, {
                month: this.currentDate.format('YYYY-MM'),
                data: this.planDays.filter(item => item && (item.startTime || item.absentId))
            })
            .then(() =>{
                showSuccess(this.$t('Successfully saved'));
                this.editing = false;
            })
            .catch(apiErrorHandler)
            .finally(() => this.unsetActionLoading())
        },
        preventNav(event) {
            if (!this.editing) return
            event.preventDefault()
            event.returnValue = "";
            this.currentDate = moment(this.$route.query.month + '-01', 'YYYY-MM-DD');
        },
        async fetchData() {
            this.setActionLoading();
            await this.fetchHolidays();
            api.get(`plan-days/${this.childId}`, null, {month: this.currentDate.format('YYYY-MM')})
            .then(response => {
                this.unpack(response);
            })
            .catch(e => {
                apiErrorHandler(e);
                console.error(e)
            })
            .finally(() => {
                this.unsetActionLoading();
            })
        },
        fetchHolidays() {
            api.get(`holiday`, null, {month: this.currentDate.format('YYYY-MM')})
            .then(response => {
                this.holidays = response
            })
            .catch(apiErrorHandler)
        },
        fetchChild () {
            api.get(`child/${this.childId}`)
            .then(response => {
                this.child = response
            })
            .catch(e => apiErrorHandler(e));
        },
        validate() {
            let valid = true;
            this.planDays.forEach((plan, index) => {
                if (plan.absentId) {
                    plan.startTime = null;
                    plan.endTime = null;
                    return;
                }
                // if (!plan.startTime) {
                //     valid = false;
                //     this.planDayErrors[index].startTime = this.$t('Please input start time');
                // }
                // if (!plan.endTime) {
                //     valid = false;
                //     this.planDayErrors[index].endTime = this.$t('Please input end time');
                // }
                if (!plan.startTime && !plan.endTime) return;
                if ((plan.startTime && !plan.endTime) || (!plan.startTime && plan.endTime)) {
                    valid = false;
                    if (!plan.startTime)
                        this.planDayErrors[index].startTime = this.$t('Invalid time period');
                    else
                        this.planDayErrors[index].endTime = this.$t('Invalid time period');
                    return ;
                }
                if (index == 15)
                    console.log(plan.startTime);
                plan.startTime = this.fomatTime(plan.startTime);
                plan.endTime = this.fomatTime(plan.endTime);

                if (plan.startTime && !validateHhMm(plan.startTime)) {
                    valid = false;
                    this.planDayErrors[index].startTime = this.$t('Invalid time format');
                }
                if (plan.endTime && !validateHhMm(plan.endTime)) {
                    valid = false;
                    this.planDayErrors[index].endTime = this.$t('Invalid time format');
                }

                if (plan.startTime && (plan.startTime === plan.endTime || plan.startTime >= plan.endTime)) {
                    valid = false;
                    this.planDayErrors[index].endTime = this.$t('Invalid time period');
                }

                plan.date = this.currentDate.format('YYYY-MM') + '-' + String(index + 1).padStart(2, '0');
                return valid;
            })
            return valid;
        },
        unpack(data) {
            this.currentDate.set('date', 1)
            let firstDay = this.currentDate.day();
            this.planDays = [];
            this.planDayErrors = [];
            let daysInMonth = this.currentDate.daysInMonth();

            for (let day = 1; day < daysInMonth + 1; day++) {
                let planItem = data.find(item => item.day === day);
                if (planItem) {
                    let startTime = planItem.startTime ? moment(planItem.startTime, 'HH:mm:ss').format('HH:mm') : '';
                    let endTime = planItem.endTime ? moment(planItem.endTime, 'HH:mm:ss').format('HH:mm') : '';
                    this.planDays.push({...planItem, startTime, endTime});
                } else {
                    this.planDays.push({...defaultPlanItem, date: this.currentDate.set('date', day).format('YYYY-MM-DD')})
                }
                this.planDayErrors.push({...defaultPlanDayError});
            }
            this.planIndices = [];
            let weeks = Math.floor((daysInMonth + (firstDay - 1)) / 7);
            if ((daysInMonth + (firstDay - 1)) % 7 > 0) {
                weeks++;
            }
            let day = 0;
            let dayOfWeeks = ['月', '火', '水', '木', '金', '土', '日'];
            if (firstDay === 0) firstDay = 7;
            for (let i = 0; i < weeks; i++) {
                this.planIndices[i] = [];
                for (let dayOfWeek = 1; dayOfWeek < 8; dayOfWeek++) {
                    if ((i === 0 && dayOfWeek < firstDay) || (day >= daysInMonth)) {
                        this.planIndices[i].push(null);
                        continue;
                    } else {
                        day++;
                        const holiday = this.holidays.find(item => item.day === day);
                        this.planIndices[i].push({ index: day - 1, label: `${day}日[${dayOfWeeks[dayOfWeek - 1]}]`, holiday});
                    }
                }
            }
        },
        fomatTime(time) {
            if (!time) return '';
            const segs = time.split(':');
            let h = segs[0];
            let m = segs[1];
            if (h.length < 2) {
                h = h.padStart(2, '0');
            }
            if (m.length < 2) {
                m = m.padStart(2, '0');
            }
            return h + ':' + m;
        }
    }
};
</script>
<style scoped>
.calendar-center {
    display: flex;
    justify-content: center;
    align-items: center;
}
.table td {
    padding-left: 0.1rem;
    padding-right: 0.1rem;
}
</style>
