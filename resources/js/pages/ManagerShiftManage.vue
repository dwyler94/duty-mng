<template>
    <section class="content">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header calendar-title">
                            <h3 class="card-title mb-0">ラテラル保育園</h3>
                            <div class="card-tools calendar-center flex-grow-1">
                                <datepicker
                                :language="ja"
                                :format="customFormatter"
                                ref="programaticOpen">
                                </datepicker>
                                <button type="button" class="btn btn-sm btn-outline mx-2" @click="openDatePicker()">
                                    <i class="fas fa-calendar-alt fa-2x"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-primary mx-2" @click="openWeekTable()">
                                    週
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive p-0" v-show="!showWeekTable">
                                <table class="table table-bordered table-kintai table-striped table-hover">
                                    <thead class="text-center">
                                        <tr class="heavy-green">
                                            <th>氏名</th>
                                            <th>出勤①</th>
                                            <th>退勤①</th>
                                            <th>出勤②</th>
                                            <th>退勤②</th>
                                            <th>勤務時間</th>
                                            <th>休憩時間</th>
                                        </tr>
                                    </thead>
                                        <tbody class="text-center">
                                            <tr>
                                            <td>阿部　一子</td>
                                            <td>8:00</td>
                                            <td>14:00</td>
                                            <td>-</td>
                                            <td>-</td>
                                            <td>6.00</td>
                                            <td>-</td>
                                            </tr>
                                            <tr>
                                            <td>伊藤　二子</td>
                                            <td>8:00</td>
                                            <td>16:00</td>
                                            <td>-</td>
                                            <td>-</td>
                                            <td>8.00</td>
                                            <td>1.00</td>
                                            </tr>
                                            <tr>
                                            <td>上野　三子</td>
                                            <td>7:30</td>
                                            <td>12:00</td>
                                            <td>14:00</td>
                                            <td>18:00</td>
                                            <td>8.5</td>
                                            <td>-</td>
                                            </tr>
                                            <tr>
                                            <td>遠藤　四子</td>
                                            <td>10:00</td>
                                            <td>18:30</td>
                                            <td></td>
                                            <td></td>
                                            <td>7.5</td>
                                            <td>1.00</td>
                                            </tr>
                                            <tr>
                                            <td>小野　五子</td>
                                            <td>10:00</td>
                                            <td>19:000</td>
                                            <td></td>
                                            <td></td>
                                            <td>9:00</td>
                                            <td>1.00</td>
                                            </tr>
                                        </tbody>
                                </table>
                            </div>
                            <div class="table-responsive p-0" v-show="showWeekTable">
                                <table class="table table-bordered table-striped table-kintai table-hover">
                                    <thead class="text-center">
                                        <tr class="heavy-green">
                                            <th rowspan="2"  class="align-middle">氏名</th>
                                            <th colspan="2">8月10日（火）</th>
                                            <th colspan="2">8月11日（水）</th>
                                            <th colspan="2">8月12日（木）</th>
                                            <th colspan="2">8月13日（金）</th>
                                            <th colspan="2">8月14日（土）</th>
                                            <th colspan="2">8月15日（日）</th>
                                            <th colspan="2">8月16日（月）</th>
                                        </tr>
                                        <tr class="light-green">
                                            <th>出勤</th>
                                            <th>退勤</th>
                                            <th>出勤</th>
                                            <th>退勤</th>
                                            <th>出勤</th>
                                            <th>退勤</th>
                                            <th>出勤</th>
                                            <th>退勤</th>
                                            <th>出勤</th>
                                            <th>退勤</th>
                                            <th>出勤</th>
                                            <th>退勤</th>
                                            <th>出勤</th>
                                            <th>退勤</th>
                                        </tr>
                                    </thead>
                                        <tbody class="text-center">
                                            <tr>
                                                <td>阿部　一子</td>
                                                <td>8:00</td>
                                                <td>14:00</td>
                                                <td>8:00</td>
                                                <td>15:00</td>
                                                <td>-</td>
                                                <td>-</td>
                                                <td>8:00</td>
                                                <td>15:00</td>
                                                <td>8:00</td>
                                                <td>15:00</td>
                                                <td>-</td>
                                                <td>-</td>
                                                <td>8:00</td>
                                                <td>14:00</td>
                                            </tr>
                                            <tr>
                                                <td>伊藤　二子</td>
                                                <td>8:00</td>
                                                <td>16:00</td>
                                                <td>-</td>
                                                <td>-</td>
                                                <td>8:00</td>
                                                <td>16:00</td>
                                                <td>-</td>
                                                <td>-</td>
                                                <td>-</td>
                                                <td>-</td>
                                                <td>-</td>
                                                <td>-</td>
                                                <td>8:00</td>
                                                <td>16:00</td>
                                            </tr>
                                            <tr>
                                                <td>上野　三子</td>
                                                <td>7:30</td>
                                                <td>18:00</td>
                                                <td>8:00</td>
                                                <td>18:00</td>
                                                <td>-</td>
                                                <td>-</td>
                                                <td>7:30</td>
                                                <td>19:00</td>
                                                <td>-</td>
                                                <td>-</td>
                                                <td>-</td>
                                                <td>-</td>
                                                <td>-</td>
                                                <td>-</td>
                                            </tr>
                                            <tr>
                                                <td>遠藤　四子</td>
                                                <td>10:00</td>
                                                <td>18:30</td>
                                                <td>10:00</td>
                                                <td>18:30</td>
                                                <td>10:00</td>
                                                <td>18:30</td>
                                                <td>10:00</td>
                                                <td>18:30</td>
                                                <td>10:00</td>
                                                <td>18:30</td>
                                                <td>-</td>
                                                <td>-</td>
                                                <td>10:00</td>
                                                <td>18:30</td>
                                            </tr>
                                            <tr>
                                                <td>小野　五子</td>
                                                <td>10:00</td>
                                                <td>19:00</td>
                                                <td>-</td>
                                                <td>-</td>
                                                <td>9:00</td>
                                                <td>18:00</td>
                                                <td>-</td>
                                                <td>-</td>
                                                <td>10:00</td>
                                                <td>19:00</td>
                                                <td>-</td>
                                                <td>-</td>
                                                <td>10:00</td>
                                                <td>19:00</td>
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
    import moment from 'moment'
    export default {
        components: {
            Datepicker,
        },
        data () {
            return {
                editmode: false,
                showWeekTable: false,
                currentDate: new Date(),
                days: [],
                attends : [],
                requests : [],
                form: new Form({
                    id : '',
                    date: '',
                    type : 0,
                    hour: '',
                    minute: '',
                    new_hour: '',
                    new_minute: '',
                    memo: '',
                }),
                ja: ja,
            }
        },
        methods: {
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
                this.$Progress.start();
                this.loadAttends(month_date);
                this.loadRequests(month_date);
                this.updateTable(month_date);
                this.$Progress.finish();
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
            },
            openWeekTable() {
                this.showWeekTable = !this.showWeekTable;
            }
        },
        created() {
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
