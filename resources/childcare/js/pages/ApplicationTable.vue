<template>
    <section class="content">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header calendar-title">
                            <h3 class="card-title mb-0">{{officeName}}</h3>
                            <div class="mx-5">申請用帳票</div>
                            <div class="form-group mx-4 mb-0" style="width: 250px;" v-if="session.roleId < 2">
                                <select class="form-control" v-model="officeId" @change="selectOffice()">
                                    <option v-for="office in offices" :key="office.id" :value="office.id">{{office.name}}</option>
                                </select>
                            </div>
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
                            </div>
                            <button type="button" class="btn btn-primary float-right" @click="exportExcel()">
                                Excel出力
                            </button>
                        </div>
                        <div class="card-body">
                            <div class="d-flex">
                                <div class="m-2">
                                    [{{displayDate}}]
                                </div>
                                <div class="m-2">
                                    {{total.officeName}}
                                </div>
                                <div class="m-2">
                                    定員
                                </div>
                                <div class="m-2">
                                    {{total.capacity}}
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2">
                                    <table class="table table-bordered table-diary text-center h-100">
                                        <tbody>
                                            <tr>
                                                <td class="dark-blue align-middle" style="width:70px!important;">
                                                    0歳児
                                                </td>
                                                <td class="align-middle" style="width:70px!important;">
                                                    {{total.childrenStat[1]}}名
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="dark-blue align-middle">
                                                    1歳児
                                                </td>
                                                <td class="align-middle">
                                                    {{total.childrenStat[2]}}名
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="dark-blue align-middle">
                                                    2歳児
                                                </td>
                                                <td class="align-middle">
                                                    {{total.childrenStat[3]}}名
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="dark-blue align-middle">
                                                    3歳児
                                                </td>
                                                <td class="align-middle">
                                                    {{total.childrenStat[4]}}名
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="dark-blue align-middle">
                                                    4歳児
                                                </td>
                                                <td class="align-middle">
                                                    {{total.childrenStat[5]}}名
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="dark-blue align-middle">
                                                    5歳児
                                                </td>
                                                <td class="align-middle">
                                                    {{total.childrenStat[6]}}名
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="col-md-3">
                                    <table class="table table-bordered table-diary text-center h-100">
                                        <tbody>
                                            <tr>
                                                <td class="dark-blue align-middle">
                                                    従業員枠
                                                </td>
                                                <td class="align-middle">
                                                    {{total.childrenTypeStat.employeeQuota}}名
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="dark-blue align-middle">
                                                    地域枠
                                                </td>
                                                <td class="align-middle">
                                                    {{total.childrenTypeStat.regional}}名
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="dark-blue align-middle">
                                                    従業員枠割合
                                                </td>
                                                <td class="align-middle">
                                                    {{total.childrenTypeStat.employeeQuotaRatio}}%
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="dark-blue align-middle">
                                                    地域枠割合
                                                </td>
                                                <td class="align-middle">
                                                    {{total.childrenTypeStat.regionalRatio}}%
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="col-md-7">
                                    <div class="w-100 overflow-scroll-x">
                                        <table class="table table-bordered table-diary text-center w-2200-px">
                                            <thead>
                                                <tr class="text-center" style="font-size:12px;">
                                                    <th class="dark-blue header-fix-sub" style="width:10%; left: 0; z-index: 12 !important;outline: 1px;" colspan="3">
                                                        日付
                                                    </th>
                                                    <th v-for="(extension, dayIndex) in total.childrenStat.extensionStat" :key="dayIndex" class="align-middle light-blue p-1" style="left: 77px;z-index: 13 !important;outline: 1px; width:55px;">
                                                        {{dayIndex}}
                                                    </th>
                                                    <th rowspan="2" class="align-middle">
                                                        合計
                                                    </th>
                                                </tr>
                                                <tr style="font-size:12px;">
                                                    <th class="align-middle dark-blue header-fix-sub" colspan="3">曜日</th>
                                                    <th v-for="(extension, dayIndex) in total.childrenStat.extensionStat" :key="dayIndex" class="align-middle light-blue p-1" style="font-size:11px;">
                                                        <div v-if="getWeekEnd(dayIndex) === 1" class="blue">{{getDay(dayIndex)|formatWeek}}</div>
                                                        <div v-else-if="getWeekEnd(dayIndex) === 2" class="red">{{getDay(dayIndex)|formatWeek}}</div>
                                                        <div v-else>{{getDay(dayIndex)|formatWeek}}</div>
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody class="text-center">
                                                <tr style="font-size:12px;">
                                                    <td class="dark-blue align-middle header-fix-sub" rowspan="5">
                                                        延長時間
                                                    </td>
                                                    <td class="light-blue header-fix-sub-77">
                                                        通常開所時間
                                                    </td>
                                                    <td class="light-blue header-fix-sub-183">
                                                        A
                                                    </td>
                                                    <td v-for="(extension, dayIndex) in total.childrenStat.extensionStat" :key="dayIndex+'A'" class="align-middle">
                                                        {{extension.a}}
                                                    </td>
                                                    <td>{{total.childrenStat.extensionStatSum.a}}</td>
                                                </tr>
                                                <tr style="font-size:12px;">
                                                    <td class="light-blue header-fix-sub-77">
                                                        <template v-if="total.office.closeTime">
                                                            {{setExtensionTime(total.office.closeTime, 0)}}
                                                        </template>
                                                        <template v-else>
                                                            18:31～19:00
                                                        </template>
                                                    </td>
                                                    <td class="light-blue header-fix-sub-183">
                                                        B
                                                    </td>
                                                    <td v-for="(extension, dayIndex) in total.childrenStat.extensionStat" :key="dayIndex+'B'" class="align-middle">
                                                        {{extension.b}}
                                                    </td>
                                                    <td>{{total.childrenStat.extensionStatSum.b}}</td>
                                                </tr>
                                                <tr style="font-size:12px;">
                                                    <td class="light-blue header-fix-sub-77">
                                                        <template v-if="total.office.closeTime">
                                                            {{setExtensionTime(total.office.closeTime, 1)}}
                                                        </template>
                                                        <template v-else>
                                                            19:01～19:30
                                                        </template>
                                                    </td>
                                                    <td class="light-blue header-fix-sub-183">
                                                        C
                                                    </td>
                                                    <td v-for="(extension, dayIndex) in total.childrenStat.extensionStat" :key="dayIndex+'C'" class="align-middle">
                                                        {{extension.c}}
                                                    </td>
                                                    <td>{{total.childrenStat.extensionStatSum.c}}</td>
                                                </tr>
                                                <tr style="font-size:12px;">
                                                    <td class="light-blue header-fix-sub-77">
                                                        <template v-if="total.office.closeTime">
                                                            {{setExtensionTime(total.office.closeTime, 2)}}
                                                        </template>
                                                        <template v-else>
                                                            19:31～20:00
                                                        </template>
                                                    </td>
                                                    <td class="light-blue header-fix-sub-183">
                                                        D
                                                    </td>
                                                    <td v-for="(extension, dayIndex) in total.childrenStat.extensionStat" :key="dayIndex+'D'" class="align-middle">
                                                        {{extension.d}}
                                                    </td>
                                                    <td>{{total.childrenStat.extensionStatSum.d}}</td>
                                                </tr>
                                                <tr style="font-size:12px;">
                                                    <td class="light-blue header-fix-sub-77">
                                                        それ以外
                                                    </td>
                                                    <td class="light-blue header-fix-sub-183">
                                                        E
                                                    </td>
                                                    <td v-for="(extension, dayIndex) in total.childrenStat.extensionStat" :key="dayIndex+'E'" class="align-middle">
                                                        {{extension.e}}
                                                    </td>
                                                    <td>{{total.childrenStat.extensionStatSum.e}}</td>
                                                </tr>
                                            </tbody>
                                    </table>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="w-100 overflow-scroll-x">
                                <table v-for="(childrenClass, index) in total.childrenTable" :key="index" class="table table-bordered table-diary text-center w-3395-px">
                                    <thead>

                                    </thead>
                                    <tbody>
                                        <tr class="dark-yellow" style="font-size: 12px;">
                                            <td :rowspan="childrenClass.length  + 2" class="light-green header-fix-sub align-middle p-1" style="width: 25px;">
                                            {{index - 1}}歳児
                                            </td>
                                            <td rowspan="2" class="header-fix-sub-25 dark-yellow align-middle p-1" style="width:35px;">
                                                No.
                                            </td>
                                            <td rowspan="2" class="header-fix-sub-60 dark-yellow align-middle p-1" style="width:110px;">
                                                入園日
                                            </td>
                                            <td rowspan="2" class="header-fix-sub-170 dark-yellow align-middle p-1" style="width:120px;">
                                                氏名
                                            </td>
                                            <td rowspan="2" class="align-middle p-1" style="width:110px;">
                                                生年月日
                                            </td>
                                            <td rowspan="2" class="align-middle p-1" style="width:80px;">
                                                年齢
                                            </td>
                                            <td rowspan="2" class="align-middle p-1" style="width:120px;">
                                                区分
                                            </td>
                                            <td rowspan="2" class="align-middle p-1" style="width:150px;">
                                                従業員枠企業名
                                            </td>
                                            <td rowspan="2" class="align-middle p-1" style="width:60px;">
                                                無償化
                                            </td>
                                            <td rowspan="2" class="align-middle p-1" style="width:75px;">支給認定証</td>
                                            <td rowspan="2" class="align-middle p-1" style="width:100px;">認定証有効期限</td>
                                            <td rowspan="2" class="align-middle p-1" style="width:75px;">非課税世帯</td>
                                            <td v-for="day in days" :key="day+'0child'" class="align-middle" style="width:40px;">
                                                {{day.getDate()}}
                                            </td>
                                            <td rowspan="2" class="align-middle p-1" style="width:75px;">
                                                登園日数
                                            </td>
                                            <td rowspan="2" class="align-middle p-1" style="width:55px;">コロナ</td>
                                            <td rowspan="2" class="align-middle p-1" style="width:45px;">都合</td>
                                            <td rowspan="2" class="align-middle p-1" style="width:45px;">忌引</td>
                                            <td rowspan="2" class="align-middle p-1" style="width:45px;">病欠</td>
                                            <td rowspan="2" class="align-middle p-1" style="width:45px;">停止</td>
                                            <td rowspan="2" class="align-middle p-1" style="width:45px;">休園</td>
                                            <td rowspan="2" class="align-middle p-1" style="width:45px;">災害</td>
                                            <td rowspan="2" class="align-middle p-1" style="width:60px;">退園日</td>
                                            <td rowspan="2" class="align-middle p-1" style="width:60px;">規定日数</td>
                                            <td rowspan="2" class="align-middle p-1" style="width:500px;">備考</td>
                                        </tr>
                                        <tr class="light-yellow">
                                            <td v-for="day in days" :key="day + '-childtable'" class="align-middle p-1">
                                                <div v-if="getWeekEnd(day) === 1" class="blue">{{day|formatWeek}}</div>
                                                <div v-else-if="getWeekEnd(day) === 2" class="red">{{day|formatWeek}}</div>
                                                <div v-else>{{day|formatWeek}}</div>
                                            </td>
                                        </tr>
                                        <tr v-for="(child, index) in childrenClass" :key="index" style="font-size: 11px;">
                                            <td class="header-fix-sub-25 bg-white p-1">
                                                {{index + 1}}
                                            </td>
                                            <td class="header-fix-sub-60 bg-white p-1">
                                                {{changeBirthFormat(child.admissionDate)}}
                                            </td>
                                            <td class="header-fix-sub-170 bg-white p-1" style="width:120px;">
                                                {{child.name}}
                                            </td>
                                            <td class="p-1">
                                                {{changeBirthFormat(child.birthday)}}
                                            </td>
                                            <td class="p-1">{{getAge(child.birthday)}}</td>
                                            <td class="p-1">{{child.type}}</td>
                                            <td class="p-1" :class="{'background-grey': child.type == '地域枠（弾力）' || child.type == '地域枠（通常）'}">{{child.companyName}}</td>
                                            <td class="p-1">{{child.freeOfCharge}}</td>
                                            <td class="p-1" :class="{'background-grey': child.freeOfCharge == '対象外'}">{{child.certificateOfPayment}}</td>
                                            <td class="p-1" :class="{'background-grey': child.freeOfCharge == '対象外'}">{{child.certificateExpirationDate}}</td>
                                            <td class="p-1" v-if="child.taxExemptHousehold" :class="{'background-grey': child.freeOfCharge == '対象外'}">{{child.taxExemptHousehold}}</td>
                                            <td class="p-1" v-else :class="{'background-grey': child.freeOfCharge == '対象外'}">X</td>
                                            <td v-for="(stat,dayIndex) in child.extensionState" :key="dayIndex+'AB'" class="align-middle p-1">
                                                {{stat}}
                                            </td>
                                            <td class="p-1">{{child.attendCount}}</td>
                                            <td class="p-1">{{child.absentState[1]}}</td>
                                            <td class="p-1">{{child.absentState[2]}}</td>
                                            <td class="p-1">{{child.absentState[3]}}</td>
                                            <td class="p-1">{{child.absentState[4]}}</td>
                                            <td class="p-1">{{child.absentState[5]}}</td>
                                            <td class="p-1">{{child.absentState[6]}}</td>
                                            <td class="p-1">{{child.absentState[7]}}</td>
                                            <td class="p-1">{{child.exitDate}}</td>
                                            <td class="p-1" v-if="child.regulationDays >= 16">●</td>
                                            <td class="p-1" v-else>-</td>
                                            <td>{{child.remarks}}</td>
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
import moment from 'moment';
import { mapState } from 'vuex';
import actionLoading from '../mixin/actionLoading';
import { showSuccess } from '../helpers/error';
import api, { apiErrorHandler } from '../global/api';
import LocalStorage from '../helpers/localStorage';

export default {
    mixins: [actionLoading],
    data () {
        return {
            editmode: false,
            currentDate: new Date(),
            displayDate: new Date(),
            days: [],
            applications: {},
            total: {
                childrenStat: {
                    extensionStatSum: {}
                },
                childrenTypeStat: {
                    employeeQuota: null,
                    regional: null,
                    employeeQuotaRatio: null,
                    regionalRatio: null
                },
                office: {
                }
            },
            selectedMonth: '',
            month: new Date('YYYY-MM'),
            officeName: '',
            officeId: 0,
            offices: [],
        }
    },
    computed: {
        ...mapState({
            session: state => state.session.info
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
        isThisMonth() {
            const today = new Date();
            return this.currentDate.getFullYear() == today.getFullYear() && this.currentDate.getMonth() == today.getMonth();
        },
        getThisMonthDate() {
            const date = new Date();
            this.displayDate = moment(new Date(date.getFullYear(), date.getMonth(), 1)).format('YYYY年 M月');
            this.selectedMonth = moment(new Date(date.getFullYear(), date.getMonth(), 1)).format('YYYYMM');
            this.month = moment(new Date(date.getFullYear(), date.getMonth(), 1)).format('YYYY-MM');
            this.getTotalData();
            return new Date(date.getFullYear(), date.getMonth(), 1);
        },
        getNextMonthDate() {
            const date = this.currentDate;
            this.displayDate = moment(new Date(date.getFullYear(), date.getMonth() + 1, 1)).format('YYYY年 M月');
            this.selectedMonth = moment(new Date(date.getFullYear(), date.getMonth() + 1, 1)).format('YYYYMM');
            this.month = moment(new Date(date.getFullYear(), date.getMonth() + 1, 1)).format('YYYY-MM');
            this.getTotalData();
            return new Date(date.getFullYear(), date.getMonth() + 1, 1);
        },
        getPrevMonthDate() {
            const date = this.currentDate;
            this.displayDate = moment(new Date(date.getFullYear(), date.getMonth() - 1, 1)).format('YYYY年 M月');
            this.selectedMonth = moment(new Date(date.getFullYear(), date.getMonth() - 1, 1)).format('YYYYMM');
            this.month = moment(new Date(date.getFullYear(), date.getMonth() - 1, 1)).format('YYYY-MM');
            this.getTotalData();
            return new Date(date.getFullYear(), date.getMonth() - 1, 1);
        },
        getResults(month_date) {
            this.updateTable(month_date);
        },
        currentTime(){
            var today = new Date();
            var month = today.getMonth() + 1;
            var day = today.getDate();
            return month + "月" + day + "日 "
            + today.getHours() + ":"
            + today.getMinutes();
        },
        updateTable(date){
            this.currentDate = date;
            var firstDay = new Date(date.getFullYear(), date.getMonth(), 1).getDate();
            var lastDay = new Date(date.getFullYear(), date.getMonth() + 1, 0).getDate();
            this.days = [];
            for(let day = firstDay; day <= lastDay; day++) {
                this.days.push(new Date(date.getFullYear(), date.getMonth(), day));
            }
        },
        getNurseryOffices() {
            api.get(process.env.MIX_API_BASE_URL + '/office-master-nursery')
                .then(response => {
                    this.offices = response;
                    const office = this.offices.find(office => office.id === this.officeId);
                    this.officeName = office ? office.name : '';
                    if(this.offices && this.offices.length > 0) {
                        this.officeId = this.offices[0].id;
                        this.getTotalData();
                    }
                })
                .catch(e => apiErrorHandler(e))
        },
        selectOffice() {
            const office = this.offices.find(office => office.id === this.officeId);
            if (!office) return;
            this.officeName = office ? office.name : '';
            this.getTotalData();
        },
        getTotalData() {
            if (!this.officeId) return;
            if(this.actionLoading) return;
            this.setActionLoading();
            api.get(process.env.MIX_API_BASE_URL + '/childcare-application-table/' + this.officeId, null, {month: this.month})
                .then(response => {
                    this.unsetActionLoading();
                    this.total = response;
                })
                .catch(e => {
                    this.unsetActionLoading();
                    apiErrorHandler(e);
                });
        },
        getDay(dayIndex) {
            return moment(this.month + '-' + ('0' + dayIndex).slice(-2)).format('YYYY-MM-DD');
        },
        getAge(birthDay) {
           if (!birthDay) return null;
           let ageInMonth;
           if (moment().format('YYYY-MM') === this.month) {
               ageInMonth = moment(this.month + '-01').diff(birthDay, 'months');
           } else {
               ageInMonth = moment(this.month + '-01').add(1, 'months').subtract(1, 'days').diff(birthDay, 'months');
           }
            const y = Math.floor(ageInMonth / 12);
            const m = ageInMonth % 12;
            return (y ? y + '歳' : '') + (m + 'ヶ月');
        },
        setExtensionTime(closeTime, index) {
            const time1 = moment(closeTime, 'hh:mm:ss').add(index * 30 + 1, 'minutes').format('HH:mm');
            const time2 = moment(closeTime, 'hh:mm:ss').add((index + 1) * 30, 'minutes').format('HH:mm');
            return time1 + "～" + time2;
        },
        changeBirthFormat(birthDay) {
            if(birthDay) {
                return moment(birthDay).format('YYYY年 M月 D日');
            }
            return null;
        },
        exportExcel() {
            location.href = process.env.MIX_APP_BASE_URL + 'childcare-application-table/excel/' + this.officeId + '?month=' + this.month + '&token=' + LocalStorage.getToken();
        }
    },
    mounted() {
        this.selectedMonth = moment(this.displayDate).format('YYYYMM');
        this.month = moment(this.displayDate).format('YYYY-MM');
        this.displayDate = moment(this.displayDate).format('YYYY年 M月');
        this.getResults(this.getThisMonthDate());
        if (this.session.roleId < 2)
            this.getNurseryOffices();
        else {
            this.officeName = this.session.office ? this.session.office.name : '';
            this.officeId = this.session.officeId;
            this.getTotalData();
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
    .background-grey {
        background-color: #bbb6b6;
    }
</style>
