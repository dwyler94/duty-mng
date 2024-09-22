<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10 home-panel">
                <div class="card home-panel">
                    <div class="card-header" v-if="session.office">
                        {{session.office.name}} - {{session.name}} {{ thisDate }}
                    </div>
                    <div class="card-header" v-else>
                        {{session.name}} {{ thisDate }}
                    </div>
                    <br>
                    <div class="text-center mt-3">
                        <h4>現在の時刻</h4>
                        <h1 class="font-weight-bold mb-0">
                            {{ timeStamp }}
                        </h1>
                    </div>
                    <div
                        class="card-body d-flex flex-column justify-content-center align-items-center"
                    >
                        <div class="form-group">
                            <button
                                type="button"
                                class="btn btn-large px-5"
                                :disabled="!data.commuteEnabled"
                                :class="[
                                    !data.commuteEnabled
                                        ? 'btn-secondary'
                                        : 'btn-danger'
                                ]"
                                @click="commute()"
                            >
                                出　　勤
                            </button>
                        </div>
                        <br>
                        <div class="form-group">
                            <button
                                type="button"
                                class="btn btn-large px-5"
                                :disabled="!data.leaveEnabled || data.commuteEnabled"
                                :class="
                                    !data.commuteEnabled || data.leaveEnabled
                                        ? 'btn-secondary'
                                        : 'btn-danger'
                                "
                                @click="leave()"
                            >
                                退　　勤
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import moment from 'moment';
import { mapState } from 'vuex';
import api, { apiErrorHandler } from '../global/api';
import { showSuccess } from '../helpers/error';

export default {
    data() {
        return {
            data: {
                commuteEnabled: false,
                leaveEnabled: false,
            },
            timeStamp: '',
            thisDate: '',
            timestampInterval: null,
            timeNowInterval: null,
        };
    },
    computed: {
        ...mapState({
            session: state =>  state.session.info,
         }),
    },
    methods: {
        formatDate(date, showWeek = true) {
            var year = date.getFullYear();
            var month = date.getMonth() + 1;
            var day = date.getDate();
            var strDate = year + "年" + month + "月" + day + "日";
            if (showWeek) {
                strDate += "（" + this.weeks[date.getDay()] + "）";
            }
            return strDate;
        },
        formatTime() {
            var hour = ('0' + new Date().getHours()).slice(-2);
            var minute = ('0' + new Date().getMinutes()).slice(-2);
            var strTime = hour + ":" + minute;
            return strTime;
        },
        getNow() {
            this.timeStamp = this.formatTime();
        },
        updateStatus() {
            const stampTime = moment(new Date()).format("YYYY-MM-DD hh:mm:ss");
            api.get('stamp/status', null)
                .then(response => {
                    this.data = response;
                })
                .catch(e => apiErrorHandler(e));
        },
        commute() {
            const stampTime = moment(new Date()).format("YYYY-MM-DD hh:mm:ss");
            api.post('stamp/commute', null)
                .then(response => {
                   showSuccess(this.$t('Successfully stamped'));
                    //this.updateStatus();
                    this.data.commuteEnabled = false;
                    this.data.leaveEnabled = true;
                })
                .catch(e => apiErrorHandler(e));
        },
        leave() {
            const stampTime = moment(new Date()).format("YYYY-MM-DD hh:mm:ss");
            api.post('stamp/leave', null)
                .then(response => {
                    showSuccess(this.$t('Successfully stamped'));
                    //this.updateStatus();
                    this.data.leaveEnabled = false;
                })
                .catch(e => apiErrorHandler(e));
        }
    },
    mounted() {
        this.weeks = ["日", "月", "火", "水", "木", "金", "土"];
        this.thisDate = this.formatDate(new Date());
        this.getNow();
        this.updateStatus();
        this.timestampInterval = setInterval(this.getNow, 1000);
        //this.timeNowInterval = setInterval(this.updateStatus, 60000);
    },
    destroyed() {
        clearInterval(this.timestampInterval);
        //clearInterval(this.timeNowInterval);
    }
};
</script>
<style scoped>
.home-panel {
    min-height: 400px;
}
</style>
