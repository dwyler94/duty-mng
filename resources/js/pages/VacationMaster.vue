<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header calendar-title">
                        <h3 class="card-title mb-0">休暇理由マスタ</h3>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <button type="submit" class="btn btn-sm btn-primary" @click="onNewClicked()">
                                        新規登録
                                </button>
                            </div>
                            <div class="input-group w-auto">
                                <input type="search" class="form-control form-control-sm" placeholder="休暇理由" v-model="searchName">
                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-sm btn-default" @click="getVacations()">
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
                                            休暇理由
                                        </th>
                                        <th></th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody class="text-center">
                                    <template>
                                        <tr v-for="vacation in vacations" :key="vacation.id">
                                            <td>
                                                {{vacation.name}}
                                            </td>
                                            <td style="width: 200px;">
                                                <enable-display-item :enable="vacation.enable" :id="vacation.id" v-on:success="onVacationEnabled"/>
                                            </td>
                                            <td class="align-middle">
                                                <a href="#" class="mx-2" @click="onEditClicked(vacation.id)">
                                                    <i class="far fa-edit fa-lg"></i>
                                                </a>
                                                <a href="#" @click="onVacationDeleteClick(vacation.id)">
                                                    <i class="far fa-trash-alt fa-lg"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>
                        <!-- Modal -->
                        <div class="modal fade" id="vacation-master-form" tabindex="-1" role="dialog" aria-labelledby="vacation-master-form" aria-hidden="true">
                            <div class="modal-dialog modal-huge" role="document">
                                <vacation-master-form :data="masterFormData" :editMode="editMode" v-on:success="onVacationSaved"/>
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
import EnableDisplayItem from './VacationMaster/EnableDisplayItem.vue';
import VacationMasterForm from './VacationMaster/VacationMasterForm.vue';
import actionLoading from '../mixin/actionLoading';
import { showSuccess } from '../helpers/error';

export default {
    mixins: [actionLoading],
  components: { VacationMasterForm, EnableDisplayItem },
        data() {
            return {
                vacations: [],
                masterFormData: {},
                editMode: false,
                searchName: '',
            }
        },
        methods: {
            getVacations() {
                if (this.actionLoading) return;
                this.setActionLoading();
                const query = {};
                if (this.searchName) query.name = this.searchName;
                api.get('reason-for-vacation', null, query)
                    .then(response => {
                        this.unsetActionLoading();
                        this.vacations = response;
                    })
                    .catch(e => {
                        this.unsetActionLoading();
                        apiErrorHandler(e)
                });
            },
            onEditClicked(vacationId) {
                const vacation = this.vacations.find(({id}) => vacationId === id);
                if (!vacation) return;
                this.masterFormData = {...vacation};
                this.editMode = true;
                this.showMasterForm();
            },
            onVacationDeleteClick(vacationId){
                if (this.actionLoading) return;
                if (!confirm(this.$t("Are you sure you want to delete?"))) return;
                this.setActionLoading();
                api.delete('reason-for-vacation/' + vacationId)
                    .then(() => {
                        this.unsetActionLoading();
                        showSuccess(this.$t('Successfully deleted'));
                        this.getVacations();
                    })
                    .catch(e => {
                        this.unsetActionLoading();
                        apiErrorHandler(e);
                    });
            },
            onVacationSaved() {
                this.getVacations();
                $("#vacation-master-form").modal('hide');
            },
            onVacationEnabled() {
                this.getVacations();
            },
            onNewClicked() {
                this.masterFormData = {};
                this.editMode = false;
                this.showMasterForm();
            },
            showMasterForm() {
                $("#vacation-master-form").modal('show');
            }
        },
        mounted() {
            this.getVacations();
        }
    }
</script>
