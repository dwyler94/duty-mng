<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title mb-0">事業所マスタ</h3>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <button type="submit" class="btn btn-sm btn-primary" @click="onNewClick()">
                                        新規登録
                                </button>
                            </div>
                            <div class="input-group w-auto">
                                <input type="search" class="form-control form-control-sm" placeholder="事業所名" v-model="searchName">
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-sm btn-default" @click="getOffices()">
                                        <i class="fa fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="table-responsive p-0" v-if="offices.length > 0">
                            <table
                                class="table table-bordered table-striped table-master table-hover"
                            >
                                <thead class="text-center">
                                    <tr class="dark-grey text-white">
                                        <th width="30%">
                                            事業所No
                                        </th>
                                        <th width="45%">
                                            事業所名
                                        </th>
                                        <th rowspan="2">
                                            所定労働日数
                                        </th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody class="text-center">
                                    <template v-for="office in offices">
                                        <template v-if="office.type == 2">
                                            <tr :key="office.id">
                                                <td>
                                                    {{ office.number }}
                                                </td>
                                                <td>
                                                    {{ office.name }}
                                                </td>
                                                <td class="align-middle" rowspan="5">
                                                    <a href="#" class="mx-2" @click="onScheduleEditClick(office.id)">
                                                        <i class="far fa-edit fa-lg"></i>
                                                    </a>
                                                </td>
                                                <td class="align-middle" rowspan="5">
                                                    <a href="#" class="mx-2" @click="onEditClicked(office.id)">
                                                        <i class="far fa-edit fa-lg"></i>
                                                    </a>
                                                    <a href="#" @click="onOfficeDeleteClick(office.id)">
                                                        <i class="far fa-trash-alt fa-lg"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                            <tr :key="office.id + '_rest_type'">
                                                <td colspan="2">
                                                <!-- 休憩時間の控除：6時間以上の勤務で1時間を自動控除 -->
                                                {{ getRestDeductionLabel(office.restDeductionId) }}
                                                </td>
                                            </tr>
                                            <tr :key="office.id + '_open_time'">
                                                <td>
                                                    保育標準時間：{{setTime(office.openTime)}}～{{setTime(office.closeTime)}}
                                                </td>
                                                <td>
                                                    定員数：{{office.capacity}}
                                                </td>
                                            </tr>
                                            <tr :key="office.id + '_open_time_short'">
                                                <td>
                                                    <span v-if="office.businessTypeId == 2 || office.businessTypeId == 3">
                                                        保育短時間：{{setTime(office.openTimeShort)}}～{{setTime(office.closeTimeShort)}}
                                                    </span>
                                                </td>
                                                <td>
                                                </td>
                                            </tr>
                                            <tr :key="office.id + '_members'">
                                                <td>
                                                    <div class="form-row">
                                                        <div class="col-md-4">
                                                            適正職員数：<br>
                                                            (一人当たり)
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="d-flex mb-1 align-middle">
                                                                <div class="mr-1 align-self-center">0歳児</div>
                                                                <div>{{office.appropriateNumber0}}</div>
                                                                <div class="ml-1 align-self-center">名</div>
                                                            </div>
                                                            <div class="d-flex mb-1 align-middle">
                                                                <div class="mr-1 align-self-center">2歳児</div>
                                                                <div>{{office.appropriateNumber1}}</div>
                                                                <div class="ml-1 align-self-center">名</div>
                                                            </div>
                                                            <div class="d-flex mb-1 align-middle">
                                                                <div class="mr-1 align-self-center">4歳児</div>
                                                                <div>{{office.appropriateNumber3}}</div>
                                                                <div class="ml-1 align-self-center">名</div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="d-flex mb-1 align-middle">
                                                                <div class="mr-1 align-self-center">1歳児</div>
                                                                <div>{{office.appropriateNumber2}}</div>
                                                                <div class="ml-1 align-self-center">名</div>
                                                            </div>
                                                            <div class="d-flex mb-1 align-middle">
                                                                <div class="mr-1 align-self-center">2歳児</div>
                                                                <div>{{office.appropriateNumber4}}</div>
                                                                <div class="ml-1 align-self-center">名</div>
                                                            </div>
                                                            <div class="d-flex mb-1 align-middle">
                                                                <div class="mr-1 align-self-center">5歳児</div>
                                                                <div>{{office.appropriateNumber5}}</div>
                                                                <div class="ml-1 align-self-center">名</div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="d-flex mb-1 align-middle">
                                                        <div class="mr-1 align-self-center">事業種別：</div>
                                                        <div>{{getBusineesTypeName(office.businessTypeId)}}</div>
                                                    </div>
                                                </td>
                                            </tr>
                                        </template>
                                        <template v-else>
                                            <tr :key="office.id">
                                                <td>
                                                    {{ office.number }}
                                                </td>
                                                <td>
                                                    {{ office.name }}
                                                </td>
                                                <td class="align-middle" rowspan="2">
                                                    <a href="#" class="mx-2" @click="onScheduleEditClick(office.id)">
                                                        <i class="far fa-edit fa-lg"></i>
                                                    </a>
                                                </td>
                                                <td class="align-middle" rowspan="2">
                                                    <a href="#" class="mx-2" @click="onEditClicked(office.id)">
                                                        <i class="far fa-edit fa-lg"></i>
                                                    </a>
                                                    <a href="#" @click="onOfficeDeleteClick(office.id)">
                                                        <i class="far fa-trash-alt fa-lg"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                            <tr :key="office.id + '_rest_type'">
                                                <td colspan="2">
                                                <!-- 休憩時間の控除：6時間以上の勤務で1時間を自動控除 -->
                                                {{ getRestDeductionLabel(office.restDeductionId) }}
                                                </td>
                                            </tr>
                                        </template>
                                    </template>
                                </tbody>
                            </table>
                            <div class="pager-wrapper">
                                <pagination v-model="pager.current" :records="pager.total" :per-page="pager.size" :options="{texts: pager.texts}" @paginate="getOffices"/>
                                <div class="pager-per-page">
                                    <select class="form-control" @change="onPerPageChange">
                                        <option v-for="(page, index) in perPages" :key="index" :value="page">{{ page }}</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="" v-else>
                            {{ $t("No Offices") }}
                        </div>
                        <!-- Modal -->
                        <div class="modal fade" id="addNewSchedule" tabindex="-1" role="dialog" aria-labelledby="addNewSchedule" aria-hidden="true">
                            <div class="modal-dialog modal-huge" role="document">
                                <schedule-working-form
                                    :officeId="selectedScheduledWorkings.officeId"
                                    :current="selectedScheduledWorkings.current"
                                    :next="selectedScheduledWorkings.next" />
                            </div>
                        </div>

                        <!-- Modal -->
                        <div class="modal fade" id="office-master-form" tabindex="-1" role="dialog" aria-labelledby="office-master-form" aria-hidden="true">
                            <div class="modal-dialog modal-huge" role="document">
                                <office-master-form :data="masterFormData" :show="masterFormShow" :editMode="editMode" :modalOpen="modalOpen" v-on:success="onOfficeSaved" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import moment from 'moment';
import OfficeMasterForm from './OfficeMaster/OfficeMasterForm.vue';
import api, { apiErrorHandler } from '../global/api';
import ScheduleWorkingForm from './OfficeMaster/ScheduleWorkingForm.vue';
import actionLoading from '../mixin/actionLoading';
import { showSuccess } from '../helpers/error';
import { mapState } from 'vuex';
import Pagination from 'vue-pagination-2';

export default {
    mixins: [actionLoading],
  components: { OfficeMasterForm, ScheduleWorkingForm, Pagination },
        data() {
            return {
                currentDate: new Date(),
                days: [],
                nextDays: [],
                months:[],
                nextMonths: [],
                attends : [],
                requests : [],
                scheduleDates : [],
                nextScheduleDates: [],
                masterFormData: {},
                masterFormShow: false,
                offices: [],

                selectedScheduledWorkings: {
                    current: [],
                    next: [],
                    officeId: null
                },
                editMode: false,
                modalOpen: false,
                pager: {
                    current: 1,
                    size: 10,
                    total: 100,
                    texts: {
                        count: '{from} から {to} / 全体　{count}||',
                        first: '&laquo;前へ',
                        last: '次へ &raquo;'
                    }
                },
                perPages: [
                    10, 20, 50, 100
                ],
                searchName: '',
            }
        },
        computed: {
            ... mapState({
                restDeductions: state =>  state.constants.restDeductions,
                businessTypes: state => state.constants.businessTypes
            }),
        },
        methods: {
            onEditClicked(officeId) {
                const office = this.offices.find(({id}) => officeId === id);
                if (!office) return;
                this.masterFormData = {...office};
                this.editMode = true;
                this.showMasterForm();
            },
            onOfficeSaved() {
                this.getOffices();
                $("#office-master-form").modal('hide');
            },
            onScheduleEditClick(officeId){
                if (this.actionLoading) return;
                    this.setActionLoading();
                    api.get('office-master/' + officeId + '/scheduled-working')
                        .then((response) => {
                            this.unsetActionLoading();
                            const {current, next} = response;
                            this.selectedScheduledWorkings = {current, next, officeId};
                            this.showScheduleForm();
                        })
                        .catch(e => {
                            apiErrorHandler(e);
                            this.unsetActionLoading();
                        })
            },
            onOfficeDeleteClick(officeId){
                if (this.actionLoading) return;
                if (!confirm(this.$t("Are you sure you want to delete?"))) return;
                this.setActionLoading();
                api.delete('office-master/' + officeId)
                    .then(() => {
                        this.unsetActionLoading();
                        showSuccess(this.$t('Successfully deleted'));
                        this.unsetActionLoading();
                        this.getOffices();
                    })
                    .catch(e => {
                        apiErrorHandler(e);
                        this.unsetActionLoading();
                    });
            },
            onNewClick () {
                this.masterFormData = {};
                this.editMode = false;
                this.showMasterForm();
            },
            onPerPageChange(e) {
                this.pager.size = parseInt(e.target.value);
                this.getOffices();
            },
            showMasterForm() {
                this.modalOpen = true;
                $("#office-master-form").modal('show');
            },
            showScheduleForm(){
                $("#addNewSchedule").modal("show");
            },
            getOffices(page = 1) {
                if (this.actionLoading) return;
                this.setActionLoading();
                const query = {page, size: this.pager.size};
                if (this.searchName) query.name = this.searchName;
                api.get('office-master', null, query)
                    .then(response => {
                        this.unsetActionLoading();
                        this.offices = response.data;
                        this.pager.current = response.currentPage;
                        this.pager.total = parseInt(response.total);
                        this.pager.size = parseInt(response.perPage);
                    })
                    .catch(e => {
                        apiErrorHandler(e);
                        this.unsetActionLoading();
                    });
            },
            getRestDeductionLabel(restDeductionId) {
                const restDeduction = this.restDeductions.find(({id}) => id === restDeductionId);
                if (!restDeduction) return '';
                return restDeduction.name;
            },
            getBusineesTypeName(businessTypeId){
                const businessType = this.businessTypes.find(({id}) => id === businessTypeId);
                if (!businessType) return '';
                return businessType.label;
            },
            modalClose() {
                this.modalOpen = false;
            },
            setTime(time) {
                if (!time) return '';
                return moment(time, 'hh:mm:ss').format('HH:mm');
            },
        },
        mounted() {
            this.getOffices();
            $("#office-master-form").on("hidden.bs.modal", this.modalClose)
        },
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
.pager-wrapper {
    display: flex;
    justify-content: center;
    gap: 20px;
}
.pager-per-page {
    max-width: 200px;
}
</style>
