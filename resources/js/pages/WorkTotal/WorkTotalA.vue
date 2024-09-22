<template>
    <section class="content">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header calendar-title">
                            <h3 class="card-title mb-0">{{officeName}}</h3>
                            <div class="card-tools calendar-center flex-grow-1">
                                <button
                                    type="button"
                                    class="btn btn-sm btn-outline"
                                    @click="getResults(getPrevMonthDate())"
                                >
                                    <i class="fas fa-caret-left fa-2x"></i>
                                </button>
                                <div class="mx-2">{{displayDate}}</div>
                                <button
                                    type="button"
                                    class="btn btn-sm btn-outline-primary mx-2"
                                    @click="getResults(getThisMonthDate())"
                                >
                                    今月
                                </button>
                                <button
                                    type="button"
                                    class="btn btn-sm btn-outline"
                                    :hidden="isThisMonth()"
                                    @click="getResults(getNextMonthDate())"
                                >
                                    <i class="fas fa-caret-right fa-2x"></i>
                                </button>
                                <div class="form-group mx-4 mb-0" style="width: 250px;">
                                    <select class="form-control" v-model="officeId" @change="selectOffice()">
                                        <option v-for="office in offices" :key="office.id" :value="office.id">{{office.name}}</option>
                                        <option :value="0">全ての事業所</option>
                                    </select>
                                </div>
                                <div class="d-flex align-items-center">
                                    <input type="checkbox" class="align-middle" :value="1" v-model="retiredDisplay" @change="getTotalData">
                                    <div class="ml-1">退職者を表示</div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <ul class="nav nav-tabs" id="custom-content-below-tab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="full-time-tab" data-toggle="pill" href="#full-time-table" role="tab" aria-controls="full-time-table" aria-selected="true">正社員</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="short-time-tab" data-toggle="pill" href="#short-time-table" role="tab" aria-controls="short-time-table" aria-selected="false">時短社員</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="part-time-tab" data-toggle="pill" href="#part-time-table" role="tab" aria-controls="part-time-table" aria-selected="false">パート</a>
                                </li>
                            </ul>
                            <div class="tab-content" id="custom-content-below-tabContent">
                            <div class="tab-pane  fade show active" id="full-time-table" role="tabpanel" aria-labelledby="full-time-table">
                                <div class="table-responsive p-0 overflow-scroll-x" style="height: 500px;">
                                    <table
                                        class="table table-bordered table-striped table-kintai table-head-fixed table-hover w-2500-px"
                                    >
                                        <thead class="text-center text-white">
                                            <tr class="heavy-green">
                                                <th
                                                    width="3%"
                                                    rowspan="2"
                                                    class="align-middle"
                                                    style="left: 0;z-index: 12 !important;outline: 1px solid white;"
                                                >
                                                    社員No
                                                </th>
                                                <th
                                                    rowspan="2"
                                                    class="align-middle"
                                                    style="left: 77px;z-index: 13 !important;outline: 1px solid white;"
                                                >
                                                    氏名
                                                </th>
                                                <th
                                                    rowspan="2"
                                                    class="align-middle"
                                                >
                                                    勤務日数
                                                </th>
                                                <th
                                                    rowspan="2"
                                                    class="align-middle"
                                                >
                                                    実働時間
                                                </th>
                                                <th
                                                    rowspan="2"
                                                    class="align-middle"
                                                >
                                                    遅刻
                                                </th>
                                                <th
                                                    rowspan="2"
                                                    class="align-middle"
                                                >
                                                    早退
                                                </th>
                                                <th
                                                    rowspan="2"
                                                    class="align-middle"
                                                >
                                                    休憩時間
                                                </th>
                                                <th
                                                    rowspan="2"
                                                    class="align-middle"
                                                >
                                                    残業外<br>労働時間
                                                </th>
                                                <th
                                                    rowspan="2"
                                                    class="align-middle"
                                                >
                                                    残業外<br>労働時間[平日]
                                                </th>
                                                <th
                                                    rowspan="2"
                                                    class="align-middle"
                                                >
                                                    残業外<br>労働時間[土曜]
                                                </th>
                                                <th
                                                    rowspan="2"
                                                    class="align-middle"
                                                >
                                                    残業時間
                                                </th>
                                                <th
                                                    rowspan="2"
                                                    class="align-middle"
                                                >
                                                    残業時間<br>[平日]
                                                </th>
                                                <th
                                                    rowspan="2"
                                                    class="align-middle"
                                                >
                                                    残業時間<br>[土曜]
                                                </th>
                                                <th
                                                    rowspan="2"
                                                    class="align-middle"
                                                >
                                                    法定内<br>残業時間
                                                </th>
                                                <th
                                                    rowspan="2"
                                                    class="align-middle"
                                                >
                                                    法定外<br>残業時間
                                                </th>
                                                <th
                                                    rowspan="2"
                                                    class="align-middle"
                                                >
                                                    深夜時間
                                                </th>
                                                <th
                                                    rowspan="2"
                                                    class="align-middle"
                                                >
                                                    シフト外勤務時間
                                                </th>
                                                <th
                                                    rowspan="2"
                                                    class="align-middle"
                                                >
                                                    代休時間
                                                </th>
                                                <th
                                                    rowspan="2"
                                                    class="align-middle"
                                                >
                                                    連勤時間
                                                </th>
                                                <th colspan="2">年次有休</th>
                                                <th colspan="2">特休有給</th>
                                                <th colspan="2">特休無給</th>
                                                <th colspan="2">その他無給</th>
                                                <th
                                                    rowspan="2"
                                                    class="align-middle"
                                                >
                                                    欠勤日数
                                                </th>
                                            </tr>
                                            <tr class="heavy-green header-fix-y">
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
                                            <tr v-for="member in total['real']" :key="member.id">
                                                <td class="header-fix-x">{{member.number}}</td>
                                                <td class="header-fix-x-77">
                                                    <router-link
                                                        :to="{name: 'individual-summary', query: {userId: member.id, officeId: officeId, month: month}}"
                                                        >{{member.name}}
                                                    </router-link>
                                                </td>
                                                <td>{{member.total.workingDays}}日</td>
                                                <td>{{(member.total.totalWorkingHours / 60).toFixed(2)}}</td>
                                                <template v-if="isHonShya(member.office.name) && isNormalStaff(member.employmentStatusId)">
                                                    <td>-</td>
                                                    <td>-</td>
                                                    <td>{{member.total.totalRestHours}}</td>
                                                    <td>{{((member.total.actualWorkingHoursWeekdays + member.total.actualWorkingHoursSaturday) / 60).toFixed(2)}}</td>
                                                    <td>-</td>
                                                    <td>-</td>
                                                    <td>-</td>
                                                    <td>-</td>
                                                    <td>-</td>
                                                    <td>-</td>
                                                    <td>-</td>
                                                    <td>{{(member.total.midnightOvertime / 60).toFixed(2)}}</td>
                                                    <td>-</td>
                                                    <td>-</td>
                                                    <td>-</td>
                                                    <td>{{(member.total.annualPaidTime / 60).toFixed(2)}}</td>
                                                    <td>{{member.total.annualPaidDays}}日</td>
                                                    <td>{{(member.total.specialPaidTime / 60).toFixed(2)}}</td>
                                                    <td>{{member.total.specialPaidDays}}日</td>
                                                    <td>{{(member.total.specialUnpaidTime / 60).toFixed(2)}}</td>
                                                    <td>{{member.total.specialUnpaidDays}}日</td>
                                                    <td>{{(member.total.otherUnpaidTime / 60).toFixed(2)}}</td>
                                                    <td>{{member.total.otherUnpaidDays}}日</td>
                                                    <td>{{member.total.absenceDays}}日</td>
                                                </template>
                                                <template v-else-if="isHonShya(member.office.name) && !isNormalStaff(member.employmentStatusId)">
                                                    <td>{{member.total.behindTime}}分</td>
                                                    <td>{{member.total.leaveEarly}}分</td>
                                                    <td>{{member.total.totalRestHours}}</td>
                                                    <td>-</td>
                                                    <td>{{(member.total.actualWorkingHoursWeekdays / 60).toFixed(2) }}</td>
                                                    <td>{{(member.total.actualWorkingHoursSaturday / 60).toFixed(2)}}</td>
                                                    <td>-</td>
                                                    <td>{{(member.total.overtimeHoursWeekdays / 60).toFixed(2)}}</td>
                                                    <td>{{(member.total.overtimeHoursSaturday / 60).toFixed(2)}}</td>
                                                    <td>-</td>
                                                    <td>-</td>
                                                    <td>{{(member.total.midnightOvertime / 60).toFixed(2)}}</td>
                                                    <td>-</td>
                                                    <td>-</td>
                                                    <td>{{(member.total.consecutiveWorkingHours / 60).toFixed(2)}}</td>
                                                    <td>{{(member.total.annualPaidTime / 60).toFixed(2)}}</td>
                                                    <td>{{member.total.annualPaidDays}}日</td>
                                                    <td>{{(member.total.specialPaidTime / 60).toFixed(2)}}</td>
                                                    <td>{{member.total.specialPaidDays}}日</td>
                                                    <td>{{(member.total.specialUnpaidTime / 60).toFixed(2)}}</td>
                                                    <td>{{member.total.specialUnpaidDays}}日</td>
                                                    <td>{{(member.total.otherUnpaidTime / 60).toFixed(2)}}</td>
                                                    <td>{{member.total.otherUnpaidDays}}日</td>
                                                    <td>{{member.total.absenceDays}}日</td>
                                                </template>
                                                <template v-else-if="!isHonShya(member.office.name) && !isNormalStaff(member.employmentStatusId)">
                                                    <td>{{member.total.behindTime}}分</td>
                                                    <td>{{member.total.leaveEarly}}分</td>
                                                    <td>{{member.total.totalRestHours}}</td>
                                                    <td>-</td>
                                                    <td>{{(member.total.actualWorkingHoursWeekdays / 60).toFixed(2) }}</td>
                                                    <td>{{(member.total.actualWorkingHoursSaturday / 60).toFixed(2)}}</td>
                                                    <td>-</td>
                                                    <td>{{(member.total.overtimeHoursWeekdays / 60).toFixed(2)}}</td>
                                                    <td>{{(member.total.overtimeHoursSaturday / 60).toFixed(2)}}</td>
                                                    <td>-</td>
                                                    <td>-</td>
                                                    <td>{{(member.total.midnightOvertime / 60).toFixed(2)}}</td>
                                                    <td>-</td>
                                                    <td>-</td>
                                                    <td>{{(member.total.consecutiveWorkingHours / 60).toFixed(2)}}</td>
                                                    <td>{{(member.total.annualPaidTime / 60).toFixed(2)}}</td>
                                                    <td>{{member.total.annualPaidDays}}日</td>
                                                    <td>{{(member.total.specialPaidTime / 60).toFixed(2)}}</td>
                                                    <td>{{member.total.specialPaidDays}}日</td>
                                                    <td>{{(member.total.specialUnpaidTime / 60).toFixed(2)}}</td>
                                                    <td>{{member.total.specialUnpaidDays}}日</td>
                                                    <td>{{(member.total.otherUnpaidTime / 60).toFixed(2)}}</td>
                                                    <td>{{member.total.otherUnpaidDays}}日</td>
                                                    <td>{{member.total.absenceDays}}日</td>
                                                </template>
                                                <template v-else-if="!isHonShya(member.office.name) && isNormalStaff(member.employmentStatusId)">
                                                    <td>{{member.total.behindTime}}分</td>
                                                    <td>{{member.total.leaveEarly}}分</td>
                                                    <td>{{member.total.totalRestHours}}</td>
                                                    <td>{{((member.total.actualWorkingHoursWeekdays + member.total.actualWorkingHoursSaturday) / 60).toFixed(2)}}</td>
                                                    <td>-</td>
                                                    <td>-</td>
                                                    <td>-</td>
                                                    <td>-</td>
                                                    <td>-</td>
                                                    <td>{{(member.total.overtimeHoursNonStatutory / 60).toFixed(2)}}</td>
                                                    <td>{{(member.total.overtimeHoursStatutory / 60).toFixed(2)}}</td>
                                                    <td>{{(member.total.midnightOvertime / 60).toFixed(2)}}</td>
                                                    <td>{{(member.total.offShiftWorkingHours / 60).toFixed(2)}}</td>
                                                    <td>{{(member.total.substituteHolidayTime / 60).toFixed(2)}}</td>
                                                    <td>{{(member.total.consecutiveWorkingHours / 60).toFixed(2)}}</td>
                                                    <td>{{(member.total.annualPaidTime / 60).toFixed(2)}}</td>
                                                    <td>{{member.total.annualPaidDays}}日</td>
                                                    <td>{{(member.total.specialPaidTime / 60).toFixed(2)}}</td>
                                                    <td>{{member.total.specialPaidDays}}日</td>
                                                    <td>{{(member.total.specialUnpaidTime / 60).toFixed(2)}}</td>
                                                    <td>{{member.total.specialUnpaidDays}}日</td>
                                                    <td>{{(member.total.otherUnpaidTime / 60).toFixed(2)}}</td>
                                                    <td>{{member.total.otherUnpaidDays}}日</td>
                                                    <td>{{member.total.absenceDays}}日</td>
                                                </template>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="short-time-table" role="tabpanel" aria-labelledby="short-time-table">
                                <div class="table-responsive p-0 overflow-scroll-x" style="height: 500px;">
                                    <table
                                        class="table table-bordered table-striped table-kintai table-head-fixed table-hover w-2500-px"
                                    >
                                        <thead class="text-center text-white">
                                            <tr class="heavy-green">
                                                <th
                                                    width="3%"
                                                    rowspan="2"
                                                    class="align-middle"
                                                    style="left: 0;z-index: 12 !important;outline: 1px solid white;"
                                                >
                                                    社員No
                                                </th>
                                                <th
                                                    rowspan="2"
                                                    class="align-middle"
                                                    style="left: 77px;z-index: 13 !important;outline: 1px solid white;"
                                                >
                                                    氏名
                                                </th>
                                                <th
                                                    rowspan="2"
                                                    class="align-middle"
                                                >
                                                    勤務日数
                                                </th>
                                                <th
                                                    rowspan="2"
                                                    class="align-middle"
                                                >
                                                    実働時間
                                                </th>
                                                <th
                                                    rowspan="2"
                                                    class="align-middle"
                                                >
                                                    遅刻
                                                </th>
                                                <th
                                                    rowspan="2"
                                                    class="align-middle"
                                                >
                                                    早退
                                                </th>
                                                <th
                                                    rowspan="2"
                                                    class="align-middle"
                                                >
                                                    休憩時間
                                                </th>
                                                <th
                                                    rowspan="2"
                                                    class="align-middle"
                                                >
                                                    残業外<br>労働時間
                                                </th>
                                                <th
                                                    rowspan="2"
                                                    class="align-middle"
                                                >
                                                    残業外<br>労働時間[平日]
                                                </th>
                                                <th
                                                    rowspan="2"
                                                    class="align-middle"
                                                >
                                                    残業外<br>労働時間[土曜]
                                                </th>
                                                <th
                                                    rowspan="2"
                                                    class="align-middle"
                                                >
                                                    残業時間
                                                </th>
                                                <th
                                                    rowspan="2"
                                                    class="align-middle"
                                                >
                                                    残業時間<br>[平日]
                                                </th>
                                                <th
                                                    rowspan="2"
                                                    class="align-middle"
                                                >
                                                    残業時間<br>[土曜]
                                                </th>
                                                <th
                                                    rowspan="2"
                                                    class="align-middle"
                                                >
                                                    法定内<br>残業時間
                                                </th>
                                                <th
                                                    rowspan="2"
                                                    class="align-middle"
                                                >
                                                    法定外<br>残業時間
                                                </th>
                                                <th
                                                    rowspan="2"
                                                    class="align-middle"
                                                >
                                                    深夜時間
                                                </th>
                                                <th
                                                    rowspan="2"
                                                    class="align-middle"
                                                >
                                                    シフト外勤務時間
                                                </th>
                                                <th
                                                    rowspan="2"
                                                    class="align-middle"
                                                >
                                                    代休時間
                                                </th>
                                                <th
                                                    rowspan="2"
                                                    class="align-middle"
                                                >
                                                    連勤時間
                                                </th>
                                                <th colspan="2">年次有休</th>
                                                <th colspan="2">特休有給</th>
                                                <th colspan="2">特休無給</th>
                                                <th colspan="2">その他無給</th>
                                                <th
                                                    rowspan="2"
                                                    class="align-middle"
                                                >
                                                    欠勤日数
                                                </th>
                                            </tr>
                                            <tr class="heavy-green header-fix-y">
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
                                            <tr v-for="member in total['time']" :key="member.id">
                                                <td class="header-fix-x">{{member.number}}</td>
                                                <td class="header-fix-x-77">
                                                    <router-link
                                                        :to="{name: 'individual-summary', query: {userId: member.id, officeId: officeId, month: month}}"
                                                        >{{member.name}}
                                                    </router-link>
                                                </td>
                                                <td>{{member.total.workingDays}}日</td>
                                                <td>{{(member.total.totalWorkingHours / 60).toFixed(2)}}</td>
                                                <template v-if="isHonShya(member.office.name) && isNormalStaff(member.employmentStatusId)">
                                                    <td>-</td>
                                                    <td>-</td>
                                                    <td>{{member.total.totalRestHours}}</td>
                                                    <td>{{((member.total.actualWorkingHoursWeekdays + member.total.actualWorkingHoursSaturday) / 60).toFixed(2)}}</td>
                                                    <td>-</td>
                                                    <td>-</td>
                                                    <td>-</td>
                                                    <td>-</td>
                                                    <td>-</td>
                                                    <td>-</td>
                                                    <td>-</td>
                                                    <td>{{(member.total.midnightOvertime / 60).toFixed(2)}}</td>
                                                    <td>-</td>
                                                    <td>-</td>
                                                    <td>-</td>
                                                    <td>{{(member.total.annualPaidTime / 60).toFixed(2)}}</td>
                                                    <td>{{member.total.annualPaidDays}}日</td>
                                                    <td>{{(member.total.specialPaidTime / 60).toFixed(2)}}</td>
                                                    <td>{{member.total.specialPaidDays}}日</td>
                                                    <td>{{(member.total.specialUnpaidTime / 60).toFixed(2)}}</td>
                                                    <td>{{member.total.specialUnpaidDays}}日</td>
                                                    <td>{{(member.total.otherUnpaidTime / 60).toFixed(2)}}</td>
                                                    <td>{{member.total.otherUnpaidDays}}日</td>
                                                    <td>{{member.total.absenceDays}}日</td>
                                                </template>
                                                <template v-else-if="isHonShya(member.office.name) && !isNormalStaff(member.employmentStatusId)">
                                                    <td>{{member.total.behindTime}}分</td>
                                                    <td>{{member.total.leaveEarly}}分</td>
                                                    <td>{{member.total.totalRestHours}}</td>
                                                    <td>-</td>
                                                    <td>{{(member.total.actualWorkingHoursWeekdays / 60).toFixed(2) }}</td>
                                                    <td>{{(member.total.actualWorkingHoursSaturday / 60).toFixed(2)}}</td>
                                                    <td>-</td>
                                                    <td>{{(member.total.overtimeHoursWeekdays / 60).toFixed(2)}}</td>
                                                    <td>{{(member.total.overtimeHoursSaturday / 60).toFixed(2)}}</td>
                                                    <td>-</td>
                                                    <td>-</td>
                                                    <td>{{(member.total.midnightOvertime / 60).toFixed(2)}}</td>
                                                    <td>-</td>
                                                    <td>-</td>
                                                    <td>{{(member.total.consecutiveWorkingHours / 60).toFixed(2)}}</td>
                                                    <td>{{(member.total.annualPaidTime / 60).toFixed(2)}}</td>
                                                    <td>{{member.total.annualPaidDays}}日</td>
                                                    <td>{{(member.total.specialPaidTime / 60).toFixed(2)}}</td>
                                                    <td>{{member.total.specialPaidDays}}日</td>
                                                    <td>{{(member.total.specialUnpaidTime / 60).toFixed(2)}}</td>
                                                    <td>{{member.total.specialUnpaidDays}}日</td>
                                                    <td>{{(member.total.otherUnpaidTime / 60).toFixed(2)}}</td>
                                                    <td>{{member.total.otherUnpaidDays}}日</td>
                                                    <td>{{member.total.absenceDays}}日</td>
                                                </template>
                                                <template v-else-if="!isHonShya(member.office.name) && !isNormalStaff(member.employmentStatusId)">
                                                    <td>{{member.total.behindTime}}分</td>
                                                    <td>{{member.total.leaveEarly}}分</td>
                                                    <td>{{member.total.totalRestHours}}</td>
                                                    <td>-</td>
                                                    <td>{{(member.total.actualWorkingHoursWeekdays / 60).toFixed(2) }}</td>
                                                    <td>{{(member.total.actualWorkingHoursSaturday / 60).toFixed(2)}}</td>
                                                    <td>-</td>
                                                    <td>{{(member.total.overtimeHoursWeekdays / 60).toFixed(2)}}</td>
                                                    <td>{{(member.total.overtimeHoursSaturday / 60).toFixed(2)}}</td>
                                                    <td>-</td>
                                                    <td>-</td>
                                                    <td>{{(member.total.midnightOvertime / 60).toFixed(2)}}</td>
                                                    <td>-</td>
                                                    <td>-</td>
                                                    <td>{{(member.total.consecutiveWorkingHours / 60).toFixed(2)}}</td>
                                                    <td>{{(member.total.annualPaidTime / 60).toFixed(2)}}</td>
                                                    <td>{{member.total.annualPaidDays}}日</td>
                                                    <td>{{(member.total.specialPaidTime / 60).toFixed(2)}}</td>
                                                    <td>{{member.total.specialPaidDays}}日</td>
                                                    <td>{{(member.total.specialUnpaidTime / 60).toFixed(2)}}</td>
                                                    <td>{{member.total.specialUnpaidDays}}日</td>
                                                    <td>{{(member.total.otherUnpaidTime / 60).toFixed(2)}}</td>
                                                    <td>{{member.total.otherUnpaidDays}}日</td>
                                                    <td>{{member.total.absenceDays}}日</td>
                                                </template>
                                                <template v-else-if="!isHonShya(member.office.name) && isNormalStaff(member.employmentStatusId)">
                                                    <td>{{member.total.behindTime}}分</td>
                                                    <td>{{member.total.leaveEarly}}分</td>
                                                    <td>{{member.total.totalRestHours}}</td>
                                                    <td>{{((member.total.actualWorkingHoursWeekdays + member.total.actualWorkingHoursSaturday) / 60).toFixed(2)}}</td>
                                                    <td>-</td>
                                                    <td>-</td>
                                                    <td>-</td>
                                                    <td>-</td>
                                                    <td>-</td>
                                                    <td>{{(member.total.overtimeHoursNonStatutory / 60).toFixed(2)}}</td>
                                                    <td>{{(member.total.overtimeHoursStatutory / 60).toFixed(2)}}</td>
                                                    <td>{{(member.total.midnightOvertime / 60).toFixed(2)}}</td>
                                                    <td>{{(member.total.offShiftWorkingHours / 60).toFixed(2)}}</td>
                                                    <td>{{(member.total.substituteHolidayTime / 60).toFixed(2)}}</td>
                                                    <td>{{(member.total.consecutiveWorkingHours / 60).toFixed(2)}}</td>
                                                    <td>{{(member.total.annualPaidTime / 60).toFixed(2)}}</td>
                                                    <td>{{member.total.annualPaidDays}}日</td>
                                                    <td>{{(member.total.specialPaidTime / 60).toFixed(2)}}</td>
                                                    <td>{{member.total.specialPaidDays}}日</td>
                                                    <td>{{(member.total.specialUnpaidTime / 60).toFixed(2)}}</td>
                                                    <td>{{member.total.specialUnpaidDays}}日</td>
                                                    <td>{{(member.total.otherUnpaidTime / 60).toFixed(2)}}</td>
                                                    <td>{{member.total.otherUnpaidDays}}日</td>
                                                    <td>{{member.total.absenceDays}}日</td>
                                                </template>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="part-time-table" role="tabpanel" aria-labelledby="part-time-table">
                                <div class="table-responsive p-0 overflow-scroll-x" style="height: 500px;">
                                    <table
                                        class="table table-bordered table-striped table-kintai table-head-fixed table-hover w-2500-px"
                                    >
                                        <thead class="text-center text-white">
                                            <tr class="heavy-green">
                                                <th
                                                    width="3%"
                                                    rowspan="2"
                                                    class="align-middle"
                                                    style="left: 0;z-index: 12 !important;outline: 1px solid white;"
                                                >
                                                    社員No
                                                </th>
                                                <th
                                                    rowspan="2"
                                                    class="align-middle"
                                                    style="left: 77px;z-index: 13 !important;outline: 1px solid white;"
                                                >
                                                    氏名
                                                </th>
                                                <th
                                                    rowspan="2"
                                                    class="align-middle"
                                                >
                                                    勤務日数
                                                </th>
                                                <th
                                                    rowspan="2"
                                                    class="align-middle"
                                                >
                                                    実働時間
                                                </th>
                                                <th
                                                    rowspan="2"
                                                    class="align-middle"
                                                >
                                                    遅刻
                                                </th>
                                                <th
                                                    rowspan="2"
                                                    class="align-middle"
                                                >
                                                    早退
                                                </th>
                                                <th
                                                    rowspan="2"
                                                    class="align-middle"
                                                >
                                                    休憩時間
                                                </th>
                                                <th
                                                    rowspan="2"
                                                    class="align-middle"
                                                >
                                                    残業外<br>労働時間
                                                </th>
                                                <th
                                                    rowspan="2"
                                                    class="align-middle"
                                                >
                                                    残業外<br>労働時間[平日]
                                                </th>
                                                <th
                                                    rowspan="2"
                                                    class="align-middle"
                                                >
                                                    残業外<br>労働時間[土曜]
                                                </th>
                                                <th
                                                    rowspan="2"
                                                    class="align-middle"
                                                >
                                                    残業時間
                                                </th>
                                                <th
                                                    rowspan="2"
                                                    class="align-middle"
                                                >
                                                    残業時間<br>[平日]
                                                </th>
                                                <th
                                                    rowspan="2"
                                                    class="align-middle"
                                                >
                                                    残業時間<br>[土曜]
                                                </th>
                                                <th
                                                    rowspan="2"
                                                    class="align-middle"
                                                >
                                                    法定内<br>残業時間
                                                </th>
                                                <th
                                                    rowspan="2"
                                                    class="align-middle"
                                                >
                                                    法定外<br>残業時間
                                                </th>
                                                <th
                                                    rowspan="2"
                                                    class="align-middle"
                                                >
                                                    深夜時間
                                                </th>
                                                <th
                                                    rowspan="2"
                                                    class="align-middle"
                                                >
                                                    シフト外勤務時間
                                                </th>
                                                <th
                                                    rowspan="2"
                                                    class="align-middle"
                                                >
                                                    代休時間
                                                </th>
                                                <th
                                                    rowspan="2"
                                                    class="align-middle"
                                                >
                                                    連勤時間
                                                </th>
                                                <th colspan="2">年次有休</th>
                                                <th colspan="2">特休有給</th>
                                                <th colspan="2">特休無給</th>
                                                <th colspan="2">その他無給</th>
                                                <th
                                                    rowspan="2"
                                                    class="align-middle"
                                                >
                                                    欠勤日数
                                                </th>
                                            </tr>
                                            <tr class="heavy-green header-fix-y">
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
                                            <tr v-for="member in total['part']" :key="member.id">
                                                <td class="header-fix-x">{{member.number}}</td>
                                                <td class="header-fix-x-77">
                                                    <router-link
                                                        :to="{name: 'individual-summary', query: {userId: member.id, officeId: officeId, month: month}}"
                                                        >{{member.name}}
                                                    </router-link>
                                                </td>
                                                <td>{{member.total.workingDays}}日</td>
                                                <td>{{(member.total.totalWorkingHours / 60).toFixed(2)}}</td>
                                                <template v-if="isHonShya(member.office.name) && isNormalStaff(member.employmentStatusId)">
                                                    <td>-</td>
                                                    <td>-</td>
                                                    <td>{{member.total.totalRestHours}}</td>
                                                    <td>{{((member.total.actualWorkingHoursWeekdays + member.total.actualWorkingHoursSaturday) / 60).toFixed(2)}}</td>
                                                    <td>-</td>
                                                    <td>-</td>
                                                    <td>-</td>
                                                    <td>-</td>
                                                    <td>-</td>
                                                    <td>-</td>
                                                    <td>-</td>
                                                    <td>{{(member.total.midnightOvertime / 60).toFixed(2)}}</td>
                                                    <td>-</td>
                                                    <td>-</td>
                                                    <td>-</td>
                                                    <td>{{(member.total.annualPaidTime / 60).toFixed(2)}}</td>
                                                    <td>{{member.total.annualPaidDays}}日</td>
                                                    <td>{{(member.total.specialPaidTime / 60).toFixed(2)}}</td>
                                                    <td>{{member.total.specialPaidDays}}日</td>
                                                    <td>{{(member.total.specialUnpaidTime / 60).toFixed(2)}}</td>
                                                    <td>{{member.total.specialUnpaidDays}}日</td>
                                                    <td>{{(member.total.otherUnpaidTime / 60).toFixed(2)}}</td>
                                                    <td>{{member.total.otherUnpaidDays}}日</td>
                                                    <td>{{member.total.absenceDays}}日</td>
                                                </template>
                                                <template v-else-if="isHonShya(member.office.name) && !isNormalStaff(member.employmentStatusId)">
                                                    <td>{{member.total.behindTime}}分</td>
                                                    <td>{{member.total.leaveEarly}}分</td>
                                                    <td>{{member.total.totalRestHours}}</td>
                                                    <td>-</td>
                                                    <td>{{(member.total.actualWorkingHoursWeekdays / 60).toFixed(2) }}</td>
                                                    <td>{{(member.total.actualWorkingHoursSaturday / 60).toFixed(2)}}</td>
                                                    <td>-</td>
                                                    <td>{{(member.total.overtimeHoursWeekdays / 60).toFixed(2)}}</td>
                                                    <td>{{(member.total.overtimeHoursSaturday / 60).toFixed(2)}}</td>
                                                    <td>-</td>
                                                    <td>-</td>
                                                    <td>{{(member.total.midnightOvertime / 60).toFixed(2)}}</td>
                                                    <td>-</td>
                                                    <td>-</td>
                                                    <td>{{(member.total.consecutiveWorkingHours / 60).toFixed(2)}}</td>
                                                    <td>{{(member.total.annualPaidTime / 60).toFixed(2)}}</td>
                                                    <td>{{member.total.annualPaidDays}}日</td>
                                                    <td>{{(member.total.specialPaidTime / 60).toFixed(2)}}</td>
                                                    <td>{{member.total.specialPaidDays}}日</td>
                                                    <td>{{(member.total.specialUnpaidTime / 60).toFixed(2)}}</td>
                                                    <td>{{member.total.specialUnpaidDays}}日</td>
                                                    <td>{{(member.total.otherUnpaidTime / 60).toFixed(2)}}</td>
                                                    <td>{{member.total.otherUnpaidDays}}日</td>
                                                    <td>{{member.total.absenceDays}}日</td>
                                                </template>
                                                <template v-else-if="!isHonShya(member.office.name) && !isNormalStaff(member.employmentStatusId)">
                                                    <td>{{member.total.behindTime}}分</td>
                                                    <td>{{member.total.leaveEarly}}分</td>
                                                    <td>{{member.total.totalRestHours}}</td>
                                                    <td>-</td>
                                                    <td>{{(member.total.actualWorkingHoursWeekdays / 60).toFixed(2) }}</td>
                                                    <td>{{(member.total.actualWorkingHoursSaturday / 60).toFixed(2)}}</td>
                                                    <td>-</td>
                                                    <td>{{(member.total.overtimeHoursWeekdays / 60).toFixed(2)}}</td>
                                                    <td>{{(member.total.overtimeHoursSaturday / 60).toFixed(2)}}</td>
                                                    <td>-</td>
                                                    <td>-</td>
                                                    <td>{{(member.total.midnightOvertime / 60).toFixed(2)}}</td>
                                                    <td>-</td>
                                                    <td>-</td>
                                                    <td>{{(member.total.consecutiveWorkingHours / 60).toFixed(2)}}</td>
                                                    <td>{{(member.total.annualPaidTime / 60).toFixed(2)}}</td>
                                                    <td>{{member.total.annualPaidDays}}日</td>
                                                    <td>{{(member.total.specialPaidTime / 60).toFixed(2)}}</td>
                                                    <td>{{member.total.specialPaidDays}}日</td>
                                                    <td>{{(member.total.specialUnpaidTime / 60).toFixed(2)}}</td>
                                                    <td>{{member.total.specialUnpaidDays}}日</td>
                                                    <td>{{(member.total.otherUnpaidTime / 60).toFixed(2)}}</td>
                                                    <td>{{member.total.otherUnpaidDays}}日</td>
                                                    <td>{{member.total.absenceDays}}日</td>
                                                </template>
                                                <template v-else-if="!isHonShya(member.office.name) && isNormalStaff(member.employmentStatusId)">
                                                    <td>{{member.total.behindTime}}分</td>
                                                    <td>{{member.total.leaveEarly}}分</td>
                                                    <td>{{member.total.totalRestHours}}</td>
                                                    <td>{{((member.total.actualWorkingHoursWeekdays + member.total.actualWorkingHoursSaturday) / 60).toFixed(2)}}</td>
                                                    <td>-</td>
                                                    <td>-</td>
                                                    <td>-</td>
                                                    <td>-</td>
                                                    <td>-</td>
                                                    <td>{{(member.total.overtimeHoursNonStatutory / 60).toFixed(2)}}</td>
                                                    <td>{{(member.total.overtimeHoursStatutory / 60).toFixed(2)}}</td>
                                                    <td>{{(member.total.midnightOvertime / 60).toFixed(2)}}</td>
                                                    <td>{{(member.total.offShiftWorkingHours / 60).toFixed(2)}}</td>
                                                    <td>{{(member.total.substituteHolidayTime / 60).toFixed(2)}}</td>
                                                    <td>{{(member.total.consecutiveWorkingHours / 60).toFixed(2)}}</td>
                                                    <td>{{(member.total.annualPaidTime / 60).toFixed(2)}}</td>
                                                    <td>{{member.total.annualPaidDays}}日</td>
                                                    <td>{{(member.total.specialPaidTime / 60).toFixed(2)}}</td>
                                                    <td>{{member.total.specialPaidDays}}日</td>
                                                    <td>{{(member.total.specialUnpaidTime / 60).toFixed(2)}}</td>
                                                    <td>{{member.total.specialUnpaidDays}}日</td>
                                                    <td>{{(member.total.otherUnpaidTime / 60).toFixed(2)}}</td>
                                                    <td>{{member.total.otherUnpaidDays}}日</td>
                                                    <td>{{member.total.absenceDays}}日</td>
                                                </template>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            </div>
                            <div class="float-right d-flex align-items-center mt-2">
                                <div class="mx-3">
                                    <label>全データ出力</label>
                                    <input
                                        type="checkbox"
                                        class="align-middle"
                                    />
                                </div>
                                <button class="btn btn-primary float-right mr-2"  @click="openExcelForm()">Excel出力</button>
                                <!-- <button class="btn btn-primary float-right">
                                    CSV出力
                                </button> -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--Modal -->
            <div class="modal fade" id="excel-output-form" tabindex="-1" role="dialog" aria-labelledby="excel-output-form" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <excel-form :csvMonths="csvMonths" :currentMonth="month" v-on:success="closeExcelForm" :officeId="officeId"></excel-form>
                </div>
            </div>
        </div>
    </section>
</template>
<script>
import moment from 'moment';
import { mapState } from 'vuex';
import actionLoading from '../../mixin/actionLoading';
import api, { apiErrorHandler } from '../../global/api';
import ExcelForm from './ExcelForm.vue';
export default {
  components: { ExcelForm },
    mixins: [actionLoading],
    data() {
        return {
            editmode: false,
            currentDate: new Date(),
            displayDate: new Date(),
            total: {
                'real': [],
                'time': [],
                'part': [],
            },
            selectedMonth: '',
            month: new Date('YYYY-MM'),
            offices: [],
            officeName: '',
            officeId: null,
            csvMonths: [],
            retiredDisplay: false,
        };
    },
    computed: {
        ...mapState({
            applicationClasses: state => state.constants.applicationClasses,
        })
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
                            this.getTotalData();
                        }
                    })
                    .catch(e => apiErrorHandler(e))
        },
        getTotalData() {
                if(this.actionLoading) return;
                this.setActionLoading();
                api.get('work-total', null, {officeId: this.officeId ? this.officeId : '', month: this.selectedMonth, retireIncluded: this.retiredDisplay ? 1 : ''})
                    .then(response => {
                        this.unsetActionLoading();
                        // this.total = response;
                        this.total['real'] = [];
                        this.total['time'] = [];
                        this.total['part'] = [];
                        response.forEach(element => {
                            if(element.employmentStatusId == 1) {
                                this.total['real'].push(element);
                            } else if(element.employmentStatusId == 2) {
                                this.total['time'].push(element);
                            } else if(element.employmentStatusId == 3) {
                                this.total['part'].push(element);
                            }
                        });
                    })
                    .catch(e => {
                        this.unsetActionLoading();
                        apiErrorHandler(e);
                    });
        },
        selectOffice() {
            const office = this.offices.find(office => office.id === this.officeId);
            this.officeName = office ? office.name : '';
            this.getTotalData();
        },
        isThisMonth() {
            const today = new Date();
            return (
                this.currentDate.getFullYear() == today.getFullYear() &&
                this.currentDate.getMonth() == today.getMonth()
            );
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
        getFiveYearMonths() {
            const date = this.currentDate;
            var firstMonth = new Date(date.getFullYear(), date.getMonth() - 60, 1);
            var lastMonth = new Date(date.getFullYear(), date.getMonth(), 0);
            this.csvMonths = [];
            for(let month = 60; month >= 0; month--) {
                this.csvMonths.push(moment(new Date(date.getFullYear(), date.getMonth()-month, 0)).format('YYYY-MM'));
            }
        },
        openExcelForm() {
            $("#excel-output-form").modal("show");
        },
        closeExcelForm() {
            $("#excel-output-form").modal("hide");
        },
        getResults(month_date) {
            this.updateTable(month_date);
            this.getFiveYearMonths();
        },
        currentTime() {
            var today = new Date();
            var month = today.getMonth() + 1;
            var day = today.getDate();
            return (
                month +
                "月" +
                day +
                "日 " +
                today.getHours() +
                ":" +
                today.getMinutes()
            );
        },
        isHonShya(name) {
            //let name = this.offices[officeId - 1].name;
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
                return number;
            } else {
                return '-';
            }
        },
        updateTable(date) {
            this.currentDate = date;
            var firstDay = new Date(
                date.getFullYear(),
                date.getMonth(),
                1
            ).getDate();
            var lastDay = new Date(
                date.getFullYear(),
                date.getMonth() + 1,
                0
            ).getDate();
            this.days = [];
            for (let day = firstDay; day <= lastDay; day++) {
                this.days.push(
                    new Date(date.getFullYear(), date.getMonth(), day)
                );
            }
        }
    },
    created() {

    },
    mounted() {
        this.selectedMonth = moment(this.displayDate).format('YYYYMM');
        this.month = moment(this.displayDate).format('YYYY-MM');
        this.displayDate = moment(this.displayDate).format('YYYY年 M月');
        this.getResults(this.currentDate);
        this.getOffices();
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
