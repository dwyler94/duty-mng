<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header calendar-title">
                        <h3 class="card-title mb-0">勤務時間マスタ</h3>
                        <h3 class="card-title mb-0 ml-5">{{session.office ? session.office.name : ''}}</h3>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <button type="submit" class="btn btn-sm btn-primary" @click="onNewClick()">
                                        新規登録
                                </button>
                            </div>
                            <div class="input-group w-auto">
                                <!-- <input type="search" class="form-control form-control-sm" placeholder="事業所名" v-model="officeName"> -->
                                <v-select label="name" :options="offices" v-model="officeName" @input="searchOffice()">
                                    <template v-slot:option="option">
                                        {{option.name}}
                                    </template>
                                </v-select>
                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-sm btn-default" @click="searchOffice()">
                                        <i class="fa fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <br>
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
                            <div class="tab-pane fade show active" id="full-time-table" role="tabpanel" aria-labelledby="full-time-table">
                                <div class="table-responsive p-0">
                                    <table
                                        class="table table-bordered table-striped table-master table-hover"
                                    >
                                        <thead class="text-center">
                                            <tr class="dark-grey text-white">
                                                <th>
                                                    時間帯名
                                                </th>
                                                <th>
                                                    開始
                                                </th>
                                                <th>
                                                    終了
                                                </th>
                                                <th></th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody class="text-center">
                                            <tr v-for="workingHour in workingHours['real']" :key="workingHour.id">
                                                <td>
                                                    {{workingHour.name}}
                                                </td>
                                                <td>
                                                    {{workingHour.startTime}}
                                                </td>
                                                <td>
                                                    {{workingHour.endTime}}
                                                </td>
                                                <td style="width: 200px;">
                                                    <enable-display-item :enable="workingHour.enable" :id="workingHour.id" v-on:success="onWorkingHourEnabled"/>
                                                </td>
                                                <td>
                                                    <a href="#" class="mx-2" @click="onEditClicked(workingHour.id)">
                                                        <i class="far fa-edit fa-lg"></i>
                                                    </a>
                                                    <a href="#" @click="onWorkingHourDeleteClick(workingHour.id)">
                                                        <i class="far fa-trash-alt fa-lg"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="short-time-table" role="tabpanel" aria-labelledby="short-time-table">
                                <div class="table-responsive p-0">
                                    <table
                                        class="table table-bordered table-striped table-master table-hover"
                                    >
                                        <thead class="text-center">
                                            <tr class="dark-grey text-white">
                                                <th>
                                                    時間帯名
                                                </th>
                                                <th>
                                                    開始
                                                </th>
                                                <th>
                                                    終了
                                                </th>
                                                <th></th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody class="text-center">
                                            <tr v-for="workingHour in workingHours['time']" :key="workingHour.id">
                                                <td>
                                                    {{workingHour.name}}
                                                </td>
                                                <td>
                                                    {{workingHour.startTime}}
                                                </td>
                                                <td>
                                                    {{workingHour.endTime}}
                                                </td>
                                                <td style="width: 200px;">
                                                    <enable-display-item :enable="workingHour.enable" :id="workingHour.id" v-on:success="onWorkingHourEnabled"/>
                                                </td>
                                                <td>
                                                    <a href="#" class="mx-2" @click="onEditClicked(workingHour.id)">
                                                        <i class="far fa-edit fa-lg"></i>
                                                    </a>
                                                    <a href="#" @click="onWorkingHourDeleteClick(workingHour.id)">
                                                        <i class="far fa-trash-alt fa-lg"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="part-time-table" role="tabpanel" aria-labelledby="part-time-table">
                                <div class="table-responsive p-0">
                                    <table
                                        class="table table-bordered table-striped table-master table-hover"
                                    >
                                        <thead class="text-center">
                                            <tr class="dark-grey text-white">
                                                <th>
                                                    時間帯名
                                                </th>
                                                <th>
                                                    開始
                                                </th>
                                                <th>
                                                    終了
                                                </th>
                                                <th></th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody class="text-center">
                                            <tr v-for="workingHour in workingHours['part']" :key="workingHour.id">
                                                <td>
                                                    {{workingHour.name}}
                                                </td>
                                                <td>
                                                    {{workingHour.startTime}}
                                                </td>
                                                <td>
                                                    {{workingHour.endTime}}
                                                </td>
                                                <td style="width: 200px;">
                                                    <enable-display-item :enable="workingHour.enable" :id="workingHour.id" v-on:success="onWorkingHourEnabled"/>
                                                </td>
                                                <td>
                                                    <a href="#" class="mx-2" @click="onEditClicked(workingHour.id)">
                                                        <i class="far fa-edit fa-lg"></i>
                                                    </a>
                                                    <a href="#" @click="onWorkingHourDeleteClick(workingHour.id)">
                                                        <i class="far fa-trash-alt fa-lg"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <!-- Modal -->
                        <div class="modal fade" id="working-hours-master-form" tabindex="-1" role="dialog" aria-labelledby="working-hours-master-form" aria-hidden="true">
                            <div class="modal-dialog modal-huge" role="document">
                                <working-hours-master-form :data="masterFormData" :offices="offices" :show="masterFormShow" :editMode="editMode" v-on:success="onWorkingHoursSaved" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>

import api, { apiErrorHandler } from '../global/api';
import actionLoading from '../mixin/actionLoading';
import { showSuccess } from '../helpers/error';
import { mapState } from 'vuex';
import WorkingHoursMasterForm from './WorkingHoursMaster/WorkingHoursMasterForm.vue';
import EnableDisplayItem from './WorkingHoursMaster/EnableDisplayItem.vue';
import vSelect from 'vue-select';
import 'vue-select/dist/vue-select.css';

export default {
        mixins: [actionLoading],
  components: { WorkingHoursMasterForm, EnableDisplayItem, vSelect},
        data() {
            return {
                masterFormData: {
                    name: '',
                    officeId: null,
                    employmentStatusId: 1,
                    startTimeHour: null,
                    startTimeMinute: null,
                    endTimeHour: null,
                    endTimeMinute: null,
                },
                masterFormShow: false,
                workingHours: {
                    'real': [],
                    'time': [],
                    'part': [],
                },
                offices: [],
                officeName: '',
                editMode: false
            }
        },
        computed: {
            ...mapState({
                session: state => state.session.info
            })
        },
        methods: {
            onEditClicked(workingHourId) {
                const workingHourArray = Object.values(this.workingHours);
                let workingHour = null;
                for (var i=0; i< workingHourArray.length; i++) {
                    workingHour = workingHourArray[i].find(
                       ({id}) => workingHourId === id
                    );
                    if(workingHour) break;
                }
                if (!workingHour) return;
                this.masterFormData = {
                    startTimeHour: workingHour.startTime.split(':')[0],
                    startTimeMinute: workingHour.startTime.split(':')[1],
                    endTimeHour: workingHour.endTime.split(':')[0],
                    endTimeMinute: workingHour.endTime.split(':')[1],
                    ...workingHour,
                };
                this.editMode = true;
                this.showMasterForm();
            },
            onWorkingHoursSaved() {
                this.getWorkingHours();
                $("#working-hours-master-form").modal('hide');
            },
            getOffices() {
                api.get('office-master')
                    .then(response => {
                        this.offices = response;
                        // this.officeName = this.offices[0];
                        this.getWorkingHours();
                    })
                    .catch(e => apiErrorHandler(e));
            },
            onWorkingHourDeleteClick(workingHourId){
                if (this.actionLoading) return;
                if (!confirm(this.$t("Are you sure you want to delete?"))) return;
                this.setActionLoading();
                api.delete('working-hours/' + workingHourId)
                    .then(() => {
                        this.unsetActionLoading();
                        showSuccess(this.$t('Successfully deleted'));
                        this.getWorkingHours();
                    })
                    .catch(e => {
                        this.unsetActionLoading();
                        apiErrorHandler(e)
                    });
            },
            onNewClick() {
                this.masterFormData = {
                    name: '',
                    officeId: null,
                    employmentStatusId: 1,
                    startTimeHour: null,
                    startTimeMinute: null,
                    endTimeHour: null,
                    endTimeMinute: null,
                };
                this.editMode = false;
                this.showMasterForm();
            },
            showMasterForm() {
                $("#working-hours-master-form").modal('show');
            },
            getWorkingHours() {
                if (this.actionLoading) return;
                this.setActionLoading();
                api.get('working-hours', null, this.officeName ? {office_id : this.officeName.id} : null)
                    .then(response => {
                        this.unsetActionLoading();
                        this.workingHours['real'] = [];
                        this.workingHours['time'] = [];
                        this.workingHours['part'] = [];
                        response.forEach(element => {
                            if(element.employmentStatusId == 1) {
                                this.workingHours['real'].push(element);
                            } else if(element.employmentStatusId == 2) {
                                this.workingHours['time'].push(element);
                            } else if(element.employmentStatusId == 3) {
                                this.workingHours['part'].push(element);
                            }
                        });
                    })
                    .catch(e => {
                        this.unsetActionLoading();
                        apiErrorHandler(e);
                    });
            },
            searchOffice() {
                api.get('working-hours', null, this.officeName ? {office_id : this.officeName.id} : null)
                    .then(response => {
                        this.workingHours['real'] = [];
                        this.workingHours['part'] = [];
                        this.workingHours['time'] = [];
                        response.forEach(element => {
                            if(element.employmentStatusId == 1) {
                                this.workingHours['real'].push(element);
                            } else if(element.employmentStatusId == 2) {
                                this.workingHours['time'].push(element);
                            } else if(element.employmentStatusId == 3) {
                                this.workingHours['part'].push(element);
                            }
                        });
                    })
                    .catch(e => apiErrorHandler(e));
            },
            onWorkingHourEnabled() {
                this.getWorkingHours();
            },
            dragover(){
                var e = event;
                e.preventDefault();

                let children= Array.from(e.target.parentNode.parentNode.children);

                if(children.indexOf(e.target.parentNode)>children.indexOf(this.row))
                    e.target.parentNode.after(this.row);
                else
                    e.target.parentNode.before(this.row);
            }
        },
        mounted() {
            this.getOffices();
            // this.getWorkingHours();
        }
    }
</script>
<style scoped>
    .v-select {
        width: 220px;
    }
</style>
