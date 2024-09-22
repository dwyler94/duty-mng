<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title mb-0">エリアマスタ</h3>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <button type="submit" class="btn btn-sm btn-primary" @click="onNewClick()">
                                        新規登録
                                </button>
                            </div>
                            <div class="input-group w-auto">
                                <input type="search" class="form-control form-control-sm" placeholder="エリア名" v-model="searchName">
                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-sm btn-default" @click="getRegions()">
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
                                        <th width="20%">
                                            エリア名
                                        </th>
                                        <th>
                                            所轄事業所
                                        </th>
                                        <th width="10%"></th>
                                    </tr>
                                </thead>
                                <tbody class="text-center">
                                    <template v-for="region in regions">
                                        <tr :key="region.id">
                                            <td>
                                                {{region.name}}
                                            </td>
                                            <td>
                                                {{ region.offices.map((item) => item.name).join('、')}}
                                            </td>
                                            <td class="align-middle">
                                                <a href="#" class="mx-2" @click="onEditClicked(region.id)">
                                                    <i class="far fa-edit fa-lg"></i>
                                                </a>
                                                <a href="#" @click="onRegionDeleteClick(region.id)">
                                                    <i class="far fa-trash-alt fa-lg"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>
                        <!-- Modal -->
                        <div class="modal fade" id="region-master-form" tabindex="-1" role="dialog" aria-labelledby="region-master-form" aria-hidden="true">
                            <div class="modal-dialog modal-huge" role="document">
                                <region-master-form :data="masterFormData" :offices="offices" :selectedOffices="selectedOffices"
                                :editMode="editMode" v-on:success="onRegionSaved" />
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
import RegionMasterForm from './RegionMaster/RegionMasterForm.vue';
import actionLoading from '../mixin/actionLoading';
import { showSuccess } from '../helpers/error';

export default {
    mixins: [actionLoading],
  components: { RegionMasterForm },
        data() {
            return {
                masterFormData: {
                    name: '',
                    offices: []
                },
                regions: [],
                offices: [],
                selectedOffices: [],
                editMode: false,
                searchName: '',
            }
        },
        methods: {
            getRegions() {
                if (this.actionLoading) return;
                this.setActionLoading();
                const query = {};
                if (this.searchName) query.name = this.searchName;
                api.get('region', null, query)
                    .then(response => {
                        this.unsetActionLoading();
                        this.regions = response;
                        this.selectedOffices = response.reduce((accumulator, currentValue) => [...accumulator, ...currentValue.offices], []);
                    })
                    .catch(e => {
                        apiErrorHandler(e);
                        this.unsetActionLoading();
                    });
            },
            getOffices() {
                api.get('office-master')
                    .then(response => {
                        this.offices = response;
                    })
                    .catch(e => apiErrorHandler(e));
            },
            onEditClicked(regionId) {
                const region = this.regions.find(({id}) => regionId === id);
                if (!region) return;
                const offices = region.offices.map(({id}) => id);
                this.masterFormData = {...region, offices};
                this.editMode = true;
                this.showMasterForm();
            },
            onRegionDeleteClick(regionId){
                if (this.actionLoading) return;
                if (!confirm(this.$t("Are you sure you want to delete?"))) return;
                this.setActionLoading();
                api.delete('region/' + regionId)
                    .then(() => {
                        this.unsetActionLoading();
                        showSuccess(this.$t('Successfully deleted'));
                        this.getRegions();
                    })
                    .catch(e => {
                        this.unsetActionLoading();
                        apiErrorHandler(e)
                    })
            },
            onNewClick() {
                this.masterFormData = {
                    name: '',
                    offices: []
                };
                this.editMode = false;
                this.showMasterForm();
            },
            showMasterForm() {
                $("#region-master-form").modal('show');
            },
            onRegionSaved() {
                this.getRegions();
                $("#region-master-form").modal('hide');
            },
            addArea(){
                $("#addNew").modal("show");
            }
        },
        mounted() {
            this.getRegions();
            this.getOffices();
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
