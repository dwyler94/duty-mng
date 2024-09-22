<template>
    <work-status-b /> <!-- 正社員で 出勤状況確認 -->
</template>

<script>
    import Datepicker from "vuejs-datepicker";
    import { ja } from 'vuejs-datepicker/dist/locale';
    import moment from 'moment'
    import { mapState } from 'vuex';
    import WorkStatusA from './WorkStatus/WorkStatusA.vue';
    import WorkStatusB from './WorkStatus/WorkStatusB.vue';
    export default {
        components: {
            Datepicker, WorkStatusA, WorkStatusB
        },
        data () {
            return {
                editmode: false,
                currentDate: new Date(),
                todayDate: "",
                days: [],
                attends : [],
                requests : [],
                ja: ja,
            }
        },
        computed: {
            ...mapState({
                session: state =>  state.session.info
            }),
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
