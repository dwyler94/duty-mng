<template>
    <section class="content">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header calendar-title">
                            <h3 class="card-title mb-0">{{ office.name }}</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4 text-center">
                                    <div>{{ office.name }}</div>
                                    <h3>保育日誌</h3>
                                    <div>{{ classLabel }}</div>
                                </div>
                                <div class="col-md-4">
                                    <div>{{ date.format('YYYY年 MM月 DD日（ddd）') }}</div>
                                    <div>天気：<input type="text" class="input-fit" v-model="weather" :class="{'is-invalid': errors.weather}" @change="errors.weather = null;inputError = false;"/>
                                        <span v-if="errors.weather" class="error invalid-feedback">
                                            {{ errors.weather }}
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <table class="table table-bordered table-diary">
                                        <thead class="text-center">
                                            <tr class="light-green">
                                                <th class="align-middle">園長</th>
                                                <th class="align-middle">主任・副主任<br>保育リーダー</th>
                                                <th class="align-middle">担任</th>
                                            </tr>
                                        </thead>
                                        <tbody class="text-center">
                                            <tr>
                                                <td class="p-0">
                                                    <div style="height: 50px;">

                                                    </div>
                                                </td>
                                                <td class="p-0">
                                                    <div style="height: 50px;">

                                                    </div>
                                                </td>
                                                <td class="p-0">
                                                    <div style="height: 50px;">

                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-md-3 text-center">
                                    <table class="table table-bordered table-diary">
                                        <thead class="text-center">
                                            <tr class="dark-blue">
                                                <th>在籍</th>
                                                <th>出席</th>
                                                <th>欠席</th>
                                            </tr>
                                        </thead>
                                        <tbody class="text-center">
                                            <tr>
                                                <td class="p-0 align-middle">
                                                    <div style="height: 100px;display:flex;align-items:center;justify-content:center;">
                                                        {{ stat.all }}
                                                    </div>
                                                </td>
                                                <td class="p-0 align-middle">
                                                    <div style="height: 100px;display:flex;align-items:center;justify-content:center;">
                                                        {{ stat.attend }}
                                                    </div>
                                                </td>
                                                <td class="p-0 align-middle">
                                                    <div style="height: 100px;display:flex;align-items:center;justify-content:center;">
                                                        {{ stat.absent }}
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="col-md-9">
                                    <table class="table table-bordered table-diary">
                                        <thead class="text-center">
                                            <tr class="dark-blue">
                                                <th>欠席児とその理由</th>
                                                <th>特記事項・行事等</th>
                                            </tr>
                                        </thead>
                                        <tbody class="text-center">
                                            <tr>
                                                <td class="p-0">
                                                    <textarea class="textarea-fit" v-model="reasonForAbsence">
                                                    </textarea>
                                                </td>
                                                <td class="p-0">
                                                    <textarea class="textarea-fit" v-model="specialNote">
                                                    </textarea>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-md-12">
                                    <table class="table table-bordered table-diary">
                                        <tbody class="text-center">
                                            <tr>
                                                <td rowspan="2" class="align-middle dark-blue p-1">
                                                   日案
                                                </td>
                                                <td class="dark-yellow">
                                                    ねらい
                                                </td>
                                                <td class="dark-yellow">
                                                    活動内容
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="p-0">
                                                    <textarea class="textarea-fit" v-model="aim">

                                                    </textarea>
                                                </td>
                                                <td class="p-0">
                                                    <textarea class="textarea-fit" v-model="activityContent">

                                                    </textarea>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="dark-blue p-1 align-middle">
                                                    援助<br>配慮事項
                                                </td>
                                                <td colspan="2" class="p-0">
                                                    <textarea class="textarea-fit" v-model="consideration">
                                                    </textarea>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="dark-blue p-1 align-middle">
                                                    保育の実際<br>評価・反省
                                                </td>
                                                <td colspan="2" class="p-0">
                                                    <textarea class="textarea-fit" v-model="evaluationReflection">
                                                    </textarea>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-md-12">
                                    <table class="table table-bordered table-diary">
                                        <tbody class="text-center">
                                            <tr>
                                                <td class="align-middle dark-blue p-1">
                                                   健康状態・その他
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="p-0">
                                                    <textarea class="textarea-fit" style="height: 100px;" v-model="healthStatus">

                                                    </textarea>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-md-12">
                                    <table class="table table-bordered table-diary">
                                        <tbody class="text-center">
                                            <tr>
                                                <td class="align-middle dark-blue p-1">
                                                   備　　　　　考
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="p-0">
                                                    <textarea class="textarea-fit" style="height: 100px;" v-model="remarks">

                                                    </textarea>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="float-right d-flex align-items-center mt-2" :class="{'is-invalid': inputError}">
                                <button class="btn btn-primary float-right" type="button" @click="onSubmit">登録</button>
                            </div>
                            <div v-if="inputError" class="error invalid-feedback text-center" style="margin-top: 60px;">
                                {{$t("Input error")}}
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
import api, { apiErrorHandler } from '../global/api';
import actionLoading from '../mixin/actionLoading';
import { showSuccess } from '../helpers/error';

export default {
    mixins: [actionLoading],
    data() {
        return {
            date: moment(),
            childrenClassId: null,
            weather: '',
            reasonForAbsence: '',
            aim: '',
            activityContent: '',
            consideration: '',
            evaluationReflection: '',
            healthStatus: '',
            remarks: '',
            specialNote: '',

            stat: {
                all: '',
                attend: '',
                absent: ''
            },
            errors: {
                weather: null,
            },
            inputError: false,
        }
    },
    computed: {
        ...mapState({
            childrenClasses: state => state.constants.childrenClasses,
            office: state => state.session.info.office
        }),
        classLabel() {
            console.log(this.childrenClasses, this.childrenClassId)
            const claz = this.childrenClasses.find(item => item.id == this.childrenClassId)
            if (claz) return claz.name;
            return '';
        }
    },
    mounted() {
        if (this.$route.query.date) {
            this.date = moment(this.$route.query.date, 'YYYY-MM-DD');
        }
        this.childrenClassId = this.$route.params.classId;
        this.fetchData();
        this.fetchStat();
    },
    methods: {
        onSubmit() {
            if (this.actionLoading) return;
            if (!this.validate()) {
                this.scrollToTop();
                return;
            }
            this.setActionLoading();
            api.post('child-diary', null, {
                date: this.date.format('YYYY-MM-DD'),
                childrenClassId: this.childrenClassId,
                weather: this.weather,
                reasonForAbsence: this.reasonForAbsence,
                aim: this.aim,
                activityContent: this.activityContent,
                consideration: this.consideration,
                specialNote: this.specialNote,
                evaluationReflection: this.evaluationReflection,
                healthStatus: this.healthStatus,
                remarks: this.remarks
            })
            .then(() => {
                showSuccess(this.$t('Successfully saved'));
                this.$router.push({name: 'childcare-diary-read', params: {classId: this.$route.params.classId}, query: {date: this.$route.query.date}});
            })
            .catch(apiErrorHandler)
            .finally(this.unsetActionLoading)
        },
        validate() {
            let valid = true;
            if(!this.weather) {
                this.errors.weather = this.$t('Please input weather');
                valid = false;
            }
            if(this.weather && this.weather.length > 10) {
                this.errors.weather = this.$t('Please enter 10 characters or less');
                valid = false;
            }
            this.inputError = !valid;
            return valid;
        },
        fetchData() {
            this.setActionLoading();
            api.get('child-diary', null, { childrenClassId: this.childrenClassId, date: this.date.format('YYYY-MM-DD')})
            .then(response => {
                this.weather = response.weather;
                this.reasonForAbsence = response.reasonForAbsence;
                this.aim = response.aim;
                this.activityContent = response.activityContent;
                this.consideration = response.consideration;
                this.evaluationReflection = response.evaluationReflection;
                this.healthStatus = response.healthStatus;
                this.specialNote = response.specialNote;
                this.remarks = response.remarks;
            })
            .catch(apiErrorHandler)
            .finally(this.unsetActionLoading);
        },
        fetchStat() {
            api.get('attendance-daily-stat', null, { date: this.date.format('YYYY-MM-DD' ), childrenClassId: this.childrenClassId})
            .then(response => {this.stat = response})
            .catch(apiErrorHandler);
        },
        scrollToTop(){
            window.scrollTo({
                top: 0,
                behavior:'smooth'
            })
        }
    }
};
</script>
<style scoped>
.input-fit {
    border: 1px solid lightgrey;
    outline: none;
}

.is-invalid.light-pink {
    border: 1px solid red;
}

</style>
