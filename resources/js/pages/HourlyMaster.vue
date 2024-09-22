<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header calendar-title">
                        <h3 class="card-title mb-0">時給テーブルマスタ</h3>
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
                                <!-- <input type="search" class="form-control form-control-sm" placeholder="事業所名"> -->
                                <v-select label="name" :options="offices" v-model="officeName" @input="getHourlys()">
                                    <template v-slot:option="option">
                                        {{option.name}}
                                    </template>
                                </v-select>
                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-sm btn-default" @click="getHourlys()">
                                        <i class="fa fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <br>
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
                                    </tr>
                                </thead>
                                <tbody class="text-center">
                                    <tr v-for="hourly in hourlys" :key="hourly.id">
                                        <td>
                                            {{ hourly.name }}
                                        </td>
                                        <td>
                                            {{ hourly.startTime }}
                                        </td>
                                        <td>
                                            {{ hourly.endTime }}
                                        </td>
                                        <td>
                                            <a href="#" class="mx-2" @click="onEditClicked(hourly.id)">
                                                <i class="far fa-edit fa-lg"></i>
                                            </a>
                                            <a href="#" @click="onHourlyDeleteClick(hourly.id)">
                                                <i class="far fa-trash-alt fa-lg"></i>
                                            </a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <!-- Modal -->
                        <div class="modal fade" id="hourly-master-form" tabindex="-1" role="dialog" aria-labelledby="hourly-master-form" aria-hidden="true">
                            <div class="modal-dialog modal-huge" role="document">
                                <hourly-master-form :data="masterFormData" :offices="offices" :editMode="editMode" v-on:success="onHourlySaved" />
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
import HourlyMasterForm from './HourlyMaster/HourlyMasterForm.vue';
import vSelect from 'vue-select';
import { mapState } from 'vuex';
import 'vue-select/dist/vue-select.css';

export default {
    mixins: [actionLoading],
  components: { HourlyMasterForm, vSelect },
        data() {
            return {
                hourlys: [],
                masterFormData: {
                    name: '',
                    officeId: null,
                    startTimeHour: null,
                    startTimeMinute: null,
                    endTimeHour: null,
                    endTimeMinute: null,
                },
                offices: [],
                officeName: '',
                editMode: false,
            }
        },
        computed: {
            ...mapState({
                session: state => state.session.info
            })
        },
        methods: {
            onEditClicked(HourlyId) {
                const hourly = this.hourlys.find(({id}) => HourlyId === id);
                if (!hourly) return;
                this.masterFormData = {
                    startTimeHour:      hourly.startTime.split(':')[0],
                    startTimeMinute:    hourly.startTime.split(':')[1],
                    endTimeHour:        hourly.endTime.split(':')[0],
                    endTimeMinute:      hourly.endTime.split(':')[1],
                    ...hourly};
                this.editMode = true;
                this.showMasterForm();
            },
            onHourlySaved() {
                this.getHourlys();
                $("#hourly-master-form").modal('hide');
            },
            getOffices() {
                api.get('office-master')
                    .then(response => {
                        this.offices = response;
                        // this.officeName = this.offices[0];
                        this.getHourlys();
                    })
                    .catch(e => apiErrorHandler(e));
            },
            onHourlyDeleteClick(hourlyId){
                if (this.actionLoading) return;
                if (!confirm(this.$t("Are you sure you want to delete?"))) return;
                this.setActionLoading();
                api.delete('hourly-wage/' + hourlyId)
                    .then(() => {
                        this.unsetActionLoading();
                        showSuccess(this.$t('Successfully deleted'));
                        this.getHourlys();
                    })
                    .catch(e => {
                        apiErrorHandler(e);
                        this.unsetActionLoading();
                    });
            },
            onNewClick() {
                this.masterFormData = {
                    name: '',
                    officeId: null,
                    startTimeHour: null,
                    startTimeMinute: null,
                    endTimeHour: null,
                    endTimeMinute: null,
                };
                this.editMode = false;
                this.showMasterForm();
            },
            showMasterForm() {
                $("#hourly-master-form").modal('show');
            },
            getHourlys() {
                if (this.actionLoading) return;
                this.setActionLoading();
                api.get('hourly-wage', null, this.officeName ? {office_id : this.officeName.id} : null)
                    .then(response => {
                        this.unsetActionLoading();
                        this.hourlys = response;
                    })
                    .catch(e => {
                        apiErrorHandler(e);
                        this.unsetActionLoading();
                    });
            }
        },
        mounted() {
            //this.getHourlys();
            this.getOffices();
        }
    }
</script>
<style scoped>
    .v-select {
        width: 220px;
    }
</style>
