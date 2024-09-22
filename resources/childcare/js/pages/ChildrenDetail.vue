<template>
    <div class="container child-detail-container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header calendar-title">
                        <div class="row">
                            <div class="col-md-6">
                                <h3 class="card-title mb-0">{{session.office.name}}</h3>
                            </div>
                            <div class="col-md-2"></div>
                            <div class="col-md-4">
                                <router-link :disabled="this.childInfor.canceledAt" tag="button" class="btn btn-sm btn-primary float-right" :to="{ name: 'childcare-plan', params: {childId}}">
                                    託児計画作成
                                </router-link>
                                <button type="button" class="btn btn-sm btn-primary float-right mr-2" @click="onQrSend" :disabled="this.childInfor.canceledAt">
                                    <i class="fas fa-qrcode fa-lg"></i>
                                    QRコード発行
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive p-0">
                            <table
                                class="table table-bordered table-children table-hover mb-0"
                            >
                                <thead class="text-center">
                                    <tr class="dark-brown text-white">
                                        <th>
                                            園児ID
                                        </th>
                                        <th>
                                            園児氏名
                                        </th>
                                        <th>
                                            性別
                                        </th>
                                        <th>
                                            生年月日
                                        </th>
                                        <th>
                                            年齢
                                        </th>
                                        <th>
                                            クラス
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="text-center">
                                    <tr>
                                        <td>
                                            {{childInfor.number}}
                                        </td>
                                        <td>
                                            {{childInfor.name}}
                                        </td>
                                        <td v-if="childInfor.gender == 1">
                                            男
                                        </td>
                                        <td v-else>
                                            女
                                        </td>
                                        <td>
                                            {{getDateFormat(childInfor.birthday)}}
                                        </td>
                                        <td>
                                            {{getAge(childInfor.birthday)}}
                                        </td>
                                        <td>
                                            {{getChildClass()}}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <table
                                class="table table-bordered table-children table-hover"
                            >
                                <thead class="text-center">
                                    <tr class="dark-brown text-white">
                                        <th>
                                            入園日
                                        </th>
                                        <th>
                                            退園日
                                        </th>
                                        <th>
                                            メールアドレス
                                        </th>
                                        <th>
                                            パスワード
                                        </th>
                                        <th>
                                            QRコード
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="text-center">
                                    <tr>
                                        <td>
                                            {{getDateFormat(childInfor.admissionDate)}}
                                        </td>
                                        <td>
                                            {{getDateFormat(childInfor.exitDate)}}
                                        </td>
                                        <td>
                                            {{childInfor.email}}
                                        </td>
                                        <td>
                                            ******
                                        </td>
                                        <td>
                                            <a href="javascript:void(0)" @click="viewQRModal()">
                                                表示
                                            </a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="p-0" v-if="isCertifCategory()">
                            <table class="table table-bordered mb-2" style="outline: 1px solid #FAEAF5;">
                                <tbody class="text-center">
                                    <tr>
                                        <td class="light-pink align-middle" style="width:200px;">
                                            認定区分
                                        </td>
                                        <td class="align-middle" v-if="childInfor.certificationType">短時間</td>
                                        <td class="align-middle" v-else>標準時間</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="p-0 mb-2">
                            <table
                                class="table table-bordered table-hover mb-0" style="outline: 1px solid #e7effe;"
                            >
                                <tbody class="text-center">
                                    <tr>
                                        <td class="light-blue align-middle" style="width: 200px">
                                            備考欄
                                        </td>
                                        <td class="p-0 bg-white align-middle">
                                            {{childInfor.remarks}}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="table-responsive p-0 overflow-hidden" v-if="plans.length > 0">
                            <table class="table table-bordered table-hover">
                                <tbody>
                                    <tr>
                                        <td :rowspan="plans.length + 1" class="text-center text-white dark-pink align-middle" style="width: 200px">
                                            登園予定
                                        </td>
                                    </tr>
                                    <tr v-for="(plan, index) in plans" :key="index">
                                        <td class="light-pink text-center">{{ getDayOfWeeksString(plan) }}</td>
                                        <td class="light-pink text-center">{{ getPeriodOfPlan(plan) }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="table-responsive p-0" v-if="isCompanyled()">
                            <table
                                class="table table-bordered table-registry table-hover mb-0"
                            >
                                <thead class="text-center">
                                    <tr class="dark-yellow text-white">
                                        <th>
                                            区分
                                        </th>
                                        <th>
                                            従業員枠企業名
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="text-center">
                                    <tr>
                                        <td>
                                            {{getChildType()}}
                                        </td>
                                        <td>
                                            {{childInfor.companyName}}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <table
                                class="table table-bordered table-registry table-hover"
                            >
                                <thead class="text-center">
                                    <tr class="dark-yellow text-white">
                                        <th>
                                            無償化
                                        </th>
                                        <th>
                                            支給認定証
                                        </th>
                                        <th>
                                            支給認定証有効期限
                                        </th>
                                        <th>
                                            非課税世帯
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="text-center">
                                    <tr>
                                        <td v-if="this.childInfor.freeOfCharge">
                                            対象
                                        </td>
                                        <td v-else>非対象</td>
                                        <td v-if="this.childInfor.certificateOfPayment">
                                            有り
                                        </td>
                                        <td v-else>
                                            無し
                                        </td>
                                        <td>
                                            {{getDateFormat(this.childInfor.certificateExpirationDate)}}
                                        </td>
                                        <td v-if="this.childInfor.taxExemptHousehold">
                                            〇
                                        </td>
                                        <td v-else>X</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="modal fade" id="qr-view-form" tabindex="-1" role="dialog" aria-labelledby="qr-view-form" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <qr-view-form :childId="childId"></qr-view-form>
                            </div>
                        </div>
                        <div class="float-right d-flex align-items-center mt-2">
                            <button class="btn btn-danger mr-2" @click="childCancel()" :disabled="!this.childInfor.canCancel">入園キャンセル</button>
                            <router-link class="btn btn-primary float-right mr-2" tag="button" :to="{name: 'present-management', params: {childId}}" :disabled="this.childInfor.canceledAt">登降園管理</router-link>
                            <button class="btn btn-primary float-right" @click="editChild()" >編集</button>
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
import actionLoading from '../mixin/actionLoading';
import { showSuccess } from '../helpers/error';
import QrViewForm from './ChildrenDetail/QrViewForm.vue';

export default {
  components: { QrViewForm },
    mixins: [actionLoading],
    data() {
        return {
            retiredDisplay: false,
            childId: 0,
            childInfor: {
                number: '',
                name: '',
                gender: null,
                birthday: null,
                classId: null,
                type: null,
                companyName: '',
                freeOfCharge: null,
                certificateOfPayment: null,
                certificateExpirationDate: null,
                taxExemptHousehold: null,
                certificationType: null,
                remarks: null,
            },
            plans: []
        }
    },
    computed: {
        ...mapState({
            childrenClasses: state => state.constants.childrenClasses,
            childTypes: state => state.constants.childTypes,
            session: state =>  state.session.info,
        }),
    },
    methods: {
        onQrSend() {
            if (!this.childId) return;
            this.setActionLoading();
            api.put(`/child/${this.childId}}/qr-mail`)
            .then(() => {
                showSuccess(this.$t('Successfully sent'));
            })
            .catch(apiErrorHandler)
            .finally(() => {
                this.unsetActionLoading();
            })
        },
        getChildInfor() {
            if(this.actionLoading) return;
            this.setActionLoading();
            api.get('child/' + this.childId, null)
                .then(response => {
                    this.childInfor = response;
                    this.unsetActionLoading();
                })
                .catch(e => {
                    apiErrorHandler(e);
                    this.unsetActionLoading();
                });
        },
        getChildClass() {
            if(this.childInfor != null) {
                const result = this.childrenClasses.find(element => {
                return element.id == this.childInfor.classId;
                });
                if(result)
                    return result.name;
                else
                    return null;
            } else {
                return null;
            }
        },
        getDateFormat(date) {
            if(!date) return null;
            return moment(date).format('YYYY年 MM月 DD日');
        },
        getChildType() {
            if(this.childInfor != null) {
                const result = Object.values(this.childTypes).find(element => {
                    return element.key == this.childInfor.type;
                });
                if(result)
                    return result.value;
                else
                    return null;
            } else {
                return null;
            }
        },
        editChild() {
            this.$router.push({name: 'children-edit', params: {id: this.childId}});
        },
        getAge(birthDay) {
           if (!birthDay) return null;
            const ageInMonth = moment().diff(birthDay, 'months');
            const y = Math.floor(ageInMonth / 12);
            const m = ageInMonth % 12;
            return (y ? y + '歳' : '') + (m + 'ヶ月');
        },
        getPlan() {
            api.get(`plan/${this.childId}`)
            .then(response => {
                this.plans = response
            })
            .catch (e => apiErrorHandler(e))
        },
        getDayOfWeeksString(plan) {
            const dayStrings = ['日曜', '月曜','火曜','水曜','木曜','金曜','土曜'];
            return plan.dayOfWeeks.map(d => dayStrings[d]).join('・')
        },
        getPeriodOfPlan(plan) {
            return moment(plan.startTime, 'HH:mm:ss').format('HH:mm') + '～' + moment(plan.endTime, 'HH:mm:ss').format('HH:mm')
        },
        isCertifCategory(){
            return (this.session) ? this.session.office.certifTypeEnabled : false;
        },
        isCompanyled() {
            return (this.session) ? this.session.office.businessTypeId == 1 : false;
        },
        viewQRModal() {
            $("#qr-view-form").modal('show');
        },
        childCancel() {
            if(!confirm(this.$t('Are you sure you want to cancel?'))) return;
            if(!this.childInfor.canCancel) return;
            if(this.actionLoading) return;
            this.setActionLoading();
            api.put('cancel/' + this.childId)
            .then(response => {
                    this.unsetActionLoading();
                    showSuccess(this.$t('Successfully saved'));
                    this.getChildInfor();
                })
                .catch(e => {
                    apiErrorHandler(e);
                    this.unsetActionLoading();
                });
        }
    },
    mounted() {
        const childId = this.$route.params.id;
        this.childId = parseInt(childId);
        this.getChildInfor();
        this.getPlan();
    }
};
</script>
<style scoped>
.child-detail-container table td {
    height: 38px;
}
</style>
