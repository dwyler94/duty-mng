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
                                :placeholder="todayDate">
                                </datepicker>
                                <button type="button" class="btn btn-sm btn-outline mx-2" @click="openDatePicker()">
                                    <i class="fas fa-calendar-alt fa-2x"></i>
                                </button>
                                <div class="form-group mx-4 mb-0">
                                    <select class="form-control" v-model="officeId">
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
                                        <!-- <tr v-for="day in days" :key="day.getDate()">
                                            <td>{{day.getDate()}}日</td>
                                            <td>{{day|formatWeek}}</td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td>
                                                <a href="#" @click="requestModal(row)">
                                                    <i class="fa fa-edit blue"></i>
                                                </a>
                                            </td>
                                        </tr> -->
                                        <tr>
                                          <td>阿部　一子</td>
                                          <td>8:05</td>
                                          <td>14:05</td>
                                          <td class="red">15分</td>
                                          <td>-</td>
                                          <td>-</td>
                                          <td>-</td>
                                          <td>-</td>
                                          <td>-</td>
                                          <td>
                                            <a href="#">
                                                <i class="fa fa-edit fa-lg black"></i>
                                            </a>
                                          </td>
                                          <td>
                                            <a href="#" @click="requestModal()">
                                                <i class="far fa-envelope fa-lg orange"></i>
                                            </a>
                                          </td>
                                        </tr>
                                        <tr>
                                          <td>伊藤　二子</td>
                                          <td>7:55</td>
                                          <td>16:00</td>
                                          <td>-</td>
                                          <td>-</td>
                                          <td>-</td>
                                          <td>-</td>
                                          <td>-</td>
                                          <td>-</td>
                                          <td>
                                            <a href="#">
                                                <i class="fa fa-edit fa-lg blue"></i>
                                            </a>
                                          </td>
                                          <td>
                                            <!-- <a href="#" @click="requestModal()">
                                                <i class="far fa-envelope fa-lg orange"></i>
                                            </a> -->
                                          </td>
                                        </tr>
                                        <tr>
                                          <td>上野　三子</td>
                                          <td>7:29</td>
                                          <td>12:03</td>
                                          <td>-</td>
                                          <td>-</td>
                                          <td>14:04</td>
                                          <td>-</td>
                                          <td class="red">15分</td>
                                          <td>-</td>
                                          <td>
                                            <a href="#">
                                                <i class="fa fa-edit fa-lg blue"></i>
                                            </a>
                                          </td>
                                          <td>
                                            <!-- <a href="#" @click="requestModal()">
                                                <i class="far fa-envelope fa-lg orange"></i>
                                            </a> -->
                                          </td>
                                        </tr>
                                        <tr>
                                          <td>遠藤　四子</td>
                                          <td>9:55</td>
                                          <td>17:35</td>
                                          <td>-</td>
                                          <td>30分</td>
                                          <td>-</td>
                                          <td>-</td>
                                          <td>-</td>
                                          <td>-</td>
                                          <td>
                                            <a href="#">
                                                <i class="fa fa-edit fa-lg blue"></i>
                                            </a>
                                          </td>
                                          <td>
                                            <!-- <a href="#" @click="requestModal()">
                                                <i class="far fa-envelope fa-lg orange"></i>
                                            </a> -->
                                          </td>
                                        </tr>
                                        <tr>
                                          <td>小野　五子</td>
                                          <td class="red">欠勤</td>
                                          <td></td>
                                          <td></td>
                                          <td></td>
                                          <td></td>
                                          <td></td>
                                          <td></td>
                                          <td></td>
                                          <td>
                                            <a href="#">
                                                <i class="fa fa-edit fa-lg blue"></i>
                                            </a>
                                          </td>
                                          <td>
                                            <a href="#" @click="requestModal()">
                                                <i class="far fa-envelope fa-lg orange"></i>
                                            </a>
                                          </td>
                                        </tr>
                                    </tbody>
                            </table>
                        </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal -->
            <div class="modal fade" id="addNew" tabindex="-1" role="dialog" aria-labelledby="addNew" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">申請</h5>
                            <!-- <h5 class="modal-title" v-show="editmode">再申請</h5> -->
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <i class="fas fa-square-full"></i>
                                <label>申請者</label>
                                <div>阿部 一子</div>
                            </div>
                            <div class="form-group">
                                <i class="fas fa-square-full"></i>
                                <label>申請日時</label>
                                <div>8月2日 8:11</div>
                            </div>
                            <div class="form-group">
                                <i class="fas fa-square-full"></i>
                                <label>修正時間</label>
                                <div>遅刻</div>
                                <div class="form-row align-items-center">
                                    <div class="col-md-1">
                                        <div>8:05</div>
                                    </div>
                                    <div class="form-control-label">⇒</div>
                                    <div class="col-md-1">
                                        <div>8:00</div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <i class="fas fa-square-full"></i>
                                <label>申請理由</label>
                                <div>雨天によるJR遅延のため</div>
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary">承認</button>
                            <button type="submit" class="btn btn-warning">却下</button>
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
    import moment from 'moment';
    import { mapState } from 'vuex';
    import actionLoading from '../../mixin/actionLoading';
    import api, { apiErrorHandler } from '../../global/api';

    export default {
        components: {
            Datepicker,
        },
        data () {
            return {
                editmode: false,
                currentDate: new Date(),
                todayDate: "",
                days: [],
                attends : [],
                requests : [],
                offices: [],
                officeName: '',
                officeId: null,
                selectedDate: '',
                ja: ja,
            }
        },
        methods: {
            getOffices() {
                api.get('office/user-capable')
                    .then(response => {
                        this.offices = response;
                        const office = this.offices.find(office => office.id === this.officeId);
                        this.officeName = office ? office.name : '';
                    })
                    .catch(e => apiErrorHandler(e))
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
                this.loadAttends(month_date);
                this.loadRequests(month_date);
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
            requestModal(){
                this.editmode = true;
                this.firstdate = this.enddate;
                // if(row = this.requests.find(request => request.date.getTime() == date.getTime())) {
                //     this.editmode = true;
                //     this.form.fill(row);
                // } else {
                //     this.editmode = false;
                //     this.form.reset();
                // }
                $('#addNew').modal('show');
            },
            getCurrentDate(){
                // var today = new Date();
                // var year = today.getFullYear();
                // var month = today.getMonth();
                // var day = today.getDate();
                // var dayweek = today.getDay();
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
            openDatePicker(){
                this.$refs.programaticOpen.showCalendar();
            }
        },
        created() {
            this.getResults(this.currentDate);
            this.todayDate = this.getCurrentDate().toString();
        },
        mounted() {
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
