<template>
    <section class="content">
        <template v-if="child">
            <div class="container-fluid mb-2 text-right" v-if="canTypeEdit">
                <a href="javascript:void(0)" @click="showTypeEditForm">連絡帳種別を変更</a>
            </div>
            <contact-book-0 v-if="type == 1"
                :contact="contactBook"
                :date="selectedDate"
                :child="child"
                v-on:success="onSaved">
            </contact-book-0>
            <contact-book-1 v-else-if="type == 2"
                :contact="contactBook"
                :date="selectedDate"
                :child="child"
                v-on:success="onSaved">
            </contact-book-1>
            <contact-book-2 v-else-if="type == 3"
                :contact="contactBook"
                :date="selectedDate"
                :child="child"
                v-on:success="onSaved">
            </contact-book-2>
            <!-- Modal -->
            <div class="fade modal" id="contact-book-type-edit-form">
                <div class="modal-dialog">
                    <type-edit-form
                        ref="typeEditForm"
                        :child-id="childId"
                        :date="selectedDate"
                        :type="type"
                        @success="onTypeSaved"
                    ></type-edit-form>
                </div>
            </div>
        </template>
    </section>
</template>
<script>
import Datepicker from "vuejs-datepicker";
import { ja } from 'vuejs-datepicker/dist/locale';
import moment from 'moment-timezone';
import { mapState } from 'vuex';
import actionLoading from '../mixin/actionLoading';
import api, { apiErrorHandler } from '../global/api';
import ContactBook0 from './ContactBook/ContactBook0.vue';
import ContactBook1 from './ContactBook/ContactBook1.vue';
import ContactBook2 from './ContactBook/ContactBook2.vue';
import TypeEditForm from './ContactBook/TypeEditForm.vue';

export default {
    components: {
        Datepicker, ContactBook0, ContactBook1, ContactBook2, TypeEditForm
    },
    mixins: [actionLoading],
    data () {
        return {
            editmode: false,
            currentDate: new Date(),
            days: [],
            contactBook : {},
            selectedUser: null,
            requests : [],
            offices: [],
            officeName: '',
            officeId: 1,
            selectedDate: new Date(),
            ja: ja,
            selectedApp: {},
            selectedAppUserName: '',
            childId: null,
            child: null,
            type: null
        }
    },
    computed: {
        canTypeEdit() {
            if (!this.child) {
                return false;
            }

            if (this.contactBook && this.contactBook.guardian) {
                return false;
            }

            if (moment(this.selectedDate).isBefore(moment(), 'day')) {
                return false;
            }

            return true;
        },
    },
    methods: {
        setHour(hourIndex, number) {
            if(number == 1)
                this.hours[hourIndex].enabled1 = !this.hours[hourIndex].enabled1;
            else if(number == 2)
                this.hours[hourIndex].enabled2 = !this.hours[hourIndex].enabled2;
        },
        onEditClicked(attend, userId) {
            if(!attend) return;
            this.selectedAttend = attend;
            this.selectedUser = userId;
            this.showEditForm();
        },
        showEditForm() {
            $("#attend-edit-form").modal('show');
        },
        isHonShya(officeId) {
            const office = this.offices.find(office => office.id === officeId);
            let name = office ? office.name : '';
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
                return Math.floor(number).toString() + '分';
            } else {
                return '-';
            }
        },
        getContactBook(date) {
            if(this.actionLoading) return;
            this.setActionLoading();
            if(date){
                this.selectedDate = date;
            } else {
                this.selectedDate = new Date();
            }
            this.selectedDate = moment(this.selectedDate).format('YYYY-MM-DD');
            api.get('contact-book/child/' + this.childId, null, {date: this.selectedDate})
                .then(response => {
                    this.unsetActionLoading();
                    this.contactBook = response.contactBook;
                    const child = response.child;
                    this.child = child ? child : null;
                    this.type = response.contactBookType;
                })
                .catch(e => {
                    this.unsetActionLoading();
                    apiErrorHandler(e);
                });
        },
        onWorkStatusSaved() {
            this.getAttendanceData(this.selectedDate);
            $("#attend-edit-form").modal('hide');
        },
        onSaved() {
            // this.getContactBook(this.selectedDate);
            // $("#app-aprove-form").modal('hide');
        },
        onTypeSaved(response) {
            this.contactBook = response.contactBook;
            this.type = response.contactBookType;

            $('#contact-book-type-edit-form').modal('hide');
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
            // this.loadAttends(month_date);
            // this.loadRequests(month_date);
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
        onApprove(application, userName){
            this.editmode = true;
            this.selectedApp = application;
            this.selectedAppUserName = userName;
            $('#app-aprove-form').modal('show');
        },
        getCurrentDate(){
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
            return moment(date).format('YYYY/M/D(ddd)');
        },
        changeTimeFormat(date) {
            if(date) {
                return moment(date).tz('Asia/Tokyo').format('HH:mm');
            } else {
                return "-";
            }
        },
        openDatePicker(){
            this.$refs.programaticOpen.showCalendar();
        },
        showTypeEditForm() {
            this.$refs.typeEditForm.show();

            $("#contact-book-type-edit-form").modal('show');
        },
    },
    created() {

    },
    mounted() {
        const childId = this.$route.params.id;
        const paramDate = this.$route.params.date;
        this.childId = parseInt(childId);
        this.currentDate = paramDate;
        this.getContactBook(this.currentDate);
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
@media (max-width: 500px) {
       h5.card-title {
           font-size: 13px!important;
       }
    }
</style>
