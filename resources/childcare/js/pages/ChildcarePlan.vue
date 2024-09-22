<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header calendar-title">
                        <div class="row">
                            <div class="col-md-3">
                                <h3 class="card-title mb-0">保育システム</h3>
                            </div>
                            <div class="col-md-3">
                                <label>{{ child.name }}</label>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive p-0 mb-2" v-for="(plan, index) in plans" :key="index">
                            <table class="table table-bordered table-hover mb-0">
                                <tbody>
                                    <tr>
                                        <td class="text-center text-white dark-pink">
                                            曜日指定
                                        </td>
                                        <td class="light-pink text-center" style="width:80%; height:50px;">
                                            <div class="d-flex align-items-center justify-content-center">
                                                    <input type="checkbox" class="align-middle" :value="1" v-model="plans[index].dayOfWeeks" :disabled="!isWeekDayEnabled(1, index)"  @change="calculateWeekFilled"/>
                                                    <div class="ml-1 mr-4">月曜</div>
                                                    <input type="checkbox" class="align-middle" :value="2" v-model="plans[index].dayOfWeeks" :disabled="!isWeekDayEnabled(2, index)" @change="calculateWeekFilled"/>
                                                    <div class="ml-1 mr-4">火曜</div>
                                                    <input type="checkbox" class="align-middle" :value="3" v-model="plans[index].dayOfWeeks" :disabled="!isWeekDayEnabled(3, index)" @change="calculateWeekFilled"/>
                                                    <div class="ml-1 mr-4">水曜</div>
                                                    <input type="checkbox" class="align-middle" :value="4" v-model="plans[index].dayOfWeeks" :disabled="!isWeekDayEnabled(4, index)" @change="calculateWeekFilled"/>
                                                    <div class="ml-1 mr-4">木曜</div>
                                                    <input type="checkbox" class="align-middle" :value="5" v-model="plans[index].dayOfWeeks" :disabled="!isWeekDayEnabled(5, index)" @change="calculateWeekFilled"/>
                                                    <div class="ml-1 mr-4">金曜</div>
                                                    <input type="checkbox" class="align-middle" :value="6" v-model="plans[index].dayOfWeeks" :disabled="!isWeekDayEnabled(6, index)" @change="calculateWeekFilled"/>
                                                    <div class="ml-1 mr-4">土曜</div>
                                                    <input type="checkbox" class="align-middle" v-model="plans[index].excludingHolidays" />
                                                    <div class="ml-1">祝・祭日を除く</div>

                                            </div>
                                            <div v-if="planErrors[index].dayOfWeeks" class="error-msg">
                                                {{ planErrors[index].dayOfWeeks }}
                                            </div>
                                        </td>
                                    </tr>
                                    <tr class="text-center">
                                        <td class="dark-pink text-white">時間指定</td>
                                        <td class="light-pink">
                                            <div class="form-row justify-content-center">
                                                <hour-minute-input v-model="plans[index].startTime" :error="planErrors[index].startTime"/>
                                                ~
                                                <hour-minute-input v-model="plans[index].endTime"  :error="planErrors[index].endTime"/>
                                            </div>
                                            <div v-if="planErrors[index].time" class="error-msg">
                                                {{ planErrors[index].time }}
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <button v-if="plans.length > 1" class="btn btn-primary float-right" @click="onDel(index)">削除</button>
                        </div>
                        <div class="float-left mt-4">
                            <button class="btn btn-primary float-left" @click="calendarRegister"><i class="fa fa-calendar fa-lg"></i> カレンダから登録</button>
                        </div>
                        <div class="float-right d-flex align-items-center mt-4">
                            <button class="btn btn-primary float-right" @click="onAdd">+追加</button>
                            <button class="btn btn-primary float-right ml-2" @click="onSubmit">登録</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import moment from 'moment';
import HourMinuteInput from '../components/HourMinuteInput.vue';
import api, { apiErrorHandler } from '../global/api';
import actionLoading from '../mixin/actionLoading';
import { validateHhMm } from '../helpers/datetime';
import { showSuccess } from '../helpers/error';

const defaultPlan = {
    dayOfWeeks: [],
    startTime: null,
    endTime: null,
    excludingHolidays: 1
}
const defaultPlanError = {
    dayOfWeeks: null,
    startTime: null,
    endTime: null,
    time: null
}

export default {
  components: { HourMinuteInput },
    mixins: [actionLoading],
    data() {
        return {
            errors: [],
            childId: null,
            child: {},
            plans: [
                {
                    dayOfWeeks: [],
                    startTime: null,
                    endTime: null,
                    excludingHolidays: 1
                },
            ],
            weekFilled: [],
            planErrors: [{...defaultPlanError}]
        }
    },
    mounted() {
        this.childId = this.$route.params.childId;
        this.fetchData();
    },
    methods: {
        calendarRegister() {
            this.$router.push("childcare-calendar");
        },
        fetchData() {
            this.setActionLoading();
            api.get(`plan/${this.childId}`)
            .then(response => {
                if (response.length > 0) {
                    this.plans = response.map(item => {
                        let startTime = item.startTime ? moment(item.startTime, 'HH:mm:ss').format('HH:mm') : null;
                        let endTime = item.endTime ? moment(item.endTime, 'HH:mm:ss').format('HH:mm') : null;
                        return {...item, startTime, endTime, dayOfWeeks: item.dayOfWeeks || []};
                    });
                    this.planErrors = this.plans.map(plan => ({...defaultPlanError}));
                    this.calculateWeekFilled();
                }
            })
            .catch(e => apiErrorHandler(e))
            .finally(() => {
                this.unsetActionLoading();
            });

            api.get(`child/${this.childId}`)
            .then(response => {
                this.child = response
            })
            .catch(e => apiErrorHandler(e));
        },
        onAdd() {
            this.plans = [...this.plans, {...defaultPlan}];
            this.planErrors.push({...defaultPlanError});
        },
        onDel(index){
            this.plans.splice(index, 1);
        },
        onSubmit() {
            if (!this.validate()) return;
            this.setActionLoading();
            api.post('plan/' + this.childId, null, {data: this.plans})
            .then(() => {
                showSuccess(this.$t('Successfully saved'));
            })
            .catch(e => apiErrorHandler(e))
            .finally(() => this.unsetActionLoading());
        },
        validate() {
            if (this.plans.length === 0) {
                alert(this.$t('Please add at least one plan'));
                return;
            }
            this.planErrors = this.plans.map(() => ({...defaultPlanError}));
            let valid = true;
            this.plans.forEach((plan, index) => {
                if (!plan.dayOfWeeks || plan.dayOfWeeks.length === 0) {
                    valid = false;
                    this.planErrors[index].dayOfWeeks = this.$t('Please select at least a day');
                }
                if (plan.startTime === plan.endTime) {
                    valid = false;
                    this.planErrors[index].time = this.$t('end time must be later than start time');
                }
                if (!plan.startTime) {
                    valid = false;
                    this.planErrors[index].startTime = this.$t('Please input start time');
                }
                if (!plan.endTime) {
                    valid = false;
                    this.planErrors[index].endTime = this.$t('Please input end time');
                }
                plan.startTime = this.fomatTime(plan.startTime);
                plan.endTime = this.fomatTime(plan.endTime);
                if (!validateHhMm(plan.startTime)) {
                    valid = false;
                    this.planErrors[index].startTime = this.$t('Invalid time format');
                }
                if (!validateHhMm(plan.endTime)) {
                    valid = false;
                    this.planErrors[index].endTime = this.$t('Invalid time format');
                }
                if (plan.endTime < plan.startTime) {
                    valid = false;
                    this.planErrors[index].time = this.$t('end time must be later than start time');
                }
            });
            return valid
        },
        calculateWeekFilled() {
            this.weekFilled =  this.plans.reduce((prev, current) => [...current.dayOfWeeks, ...prev], []);
        },
        isWeekDayEnabled(dayOfWeek, index) {
            return this.plans[index].dayOfWeeks.indexOf(dayOfWeek) !== -1 || this.weekFilled.indexOf(dayOfWeek) === -1;
        },
        fomatTime(time) {
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
.error-msg {
    color: #f00;
    margin-top: 5px;
}
</style>
