<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header calendar-title">
                        <h3 class="card-title mb-0">社員マスタ</h3>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <button type="submit" class="btn btn-sm btn-primary" @click="onNewClick()">
                                        新規登録
                                </button>
                            </div>
                            <div class="input-group w-auto">
                                <input type="search" class="form-control form-control-sm" placeholder="事業所名, 社員No, 氏名" v-model="searchName">
                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-sm btn-default" @click="onSearch">
                                        <i class="fa fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="table-responsive p-0" v-if="users.length > 0">
                            <table
                                class="table table-bordered table-master table-hover"
                            >
                                <thead class="text-center">
                                    <tr class="dark-grey text-white">
                                        <th>
                                            社員No
                                        </th>
                                        <th>
                                            氏　　名
                                        </th>
                                        <th>
                                            雇用形態
                                        </th>
                                        <th>
                                            在籍管理
                                        </th>
                                        <th rowspan="3"></th>
                                        <th rowspan="3"></th>
                                    </tr>
                                    <tr class="dark-grey text-white">
                                        <th>
                                            事業所
                                        </th>
                                        <th>
                                            エリア
                                        </th>
                                        <th>
                                            本社グループ
                                        </th>
                                        <th>
                                            勤務時間
                                        </th>
                                    </tr>
                                    <tr class="dark-grey text-white">
                                        <th colspan="2">
                                            メールアドレス
                                        </th>
                                        <th colspan="2">
                                            パスワード
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="text-center">
                                    <template v-for="user in users">
                                    <tr :key="user.id">
                                        <td>{{user.number}}</td>
                                        <td>{{user.name}}</td>
                                        <td>{{employmentStatuses[user.employmentStatusId - 1].name}}</td>
                                        <td>{{user.enrolled ? '在籍中': '退職'}}</td>
                                        <td rowspan="3" class="align-middle"><i class="fas fa-qrcode fa-lg"></i></td>
                                        <td rowspan="3" class="align-middle">
                                            <a class="mx-2" @click="onEditClicked(user.id)">
                                                <i class="far fa-edit fa-lg"></i>
                                            </a>
                                            <a @click="onUserDeleteClick(user.id)">
                                                <i class="far fa-trash-alt fa-lg"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <tr :key="user.id+'1'">
                                        <td>{{user.office? user.office.name: '未定'}}</td>
                                        <td>{{user.region? user.region.name: '未定'}}</td>
                                        <td>{{user.sort? user.sort: '未定'}}</td>
                                        <td>{{ user.workingHours ? (user.workingHours + '時間') : '未定' }}</td>
                                    </tr>
                                    <tr :key="user.id+'2'">
                                        <td colspan="2">{{user.email}}</td>
                                        <td colspan="2">*******</td>
                                    </tr>
                                    <tr :key="user.id+'3'">
                                        <td class="align-middle pl-4">権限タイプ</td>
                                        <td colspan="5" class="align-middle text-left">
                                            <div class="align-middle d-flex">
                                                <div class="d-flex align-items-center mr-3" @click="setRole(user.id, 1)">
                                                    <input class="mr-1" type="radio" :value="1" v-model="user.roleId"/>
                                                    <div class="mb-0">admin</div>
                                                </div>
                                                <div class="d-flex align-items-center mr-3" @click="setRole(user.id, 2)">
                                                    <input class="mr-1" type="radio" :value="2" v-model="user.roleId"/>
                                                    <div class="mb-0">エリアmgr</div>
                                                </div>
                                                <div class="d-flex align-items-center mr-3" @click="setRole(user.id, 3)">
                                                    <input class="mr-1" type="radio" :value="3" v-model="user.roleId"/>
                                                    <div class="mb-0">事業所管理者</div>
                                                </div>
                                                <div class="d-flex align-items-center mr-3" @click="setRole(user.id, 4)">
                                                    <input class="mr-1" type="radio" :value="4" v-model="user.roleId"/>
                                                    <div class="mb-0">一般A</div>
                                                </div>
                                                <div class="d-flex align-items-center mr-3" @click="setRole(user.id, 5)">
                                                    <input class="mr-1" type="radio" :value="5" v-model="user.roleId"/>
                                                    <div class="mb-0">一般B</div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr :key="user.id+'4'">
                                        <td class="align-middle">残業手当</td>
                                        <td colspan="5" class="align-middle text-left">
                                            <div class="align-middle d-flex">
                                                <template v-for="overtimePayOption in overtimePayOptions">
                                                    <div class="d-flex align-items-center mr-3" :key="overtimePayOption.key" @click="setStatus(user.id, 'overtime_pay', overtimePayOption.key)">
                                                        <input class="mr-1" type="radio" :value="overtimePayOption.key" v-model="user.setting.overtimePay"/>
                                                        <div class="mb-0">{{overtimePayOption.value}}</div>
                                                    </div>
                                                </template>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr :key="user.id+'5'">
                                        <td class="align-middle">給与控除</td>
                                        <td colspan="5" class="align-middle text-left">
                                            <div class="align-middle d-flex">
                                                <template v-for="salaryDeductionOption in salaryDeductionOptions">
                                                    <div class="d-flex align-items-center mr-3" :key="salaryDeductionOption.key" @click="setStatus(user.id, 'salary_deduction', salaryDeductionOption.key)">
                                                        <input class="mr-1" type="radio" :value="salaryDeductionOption.key" v-model="user.setting.salaryDeduction"/>
                                                        <div class="mb-0">{{salaryDeductionOption.value}}</div>
                                                    </div>
                                                </template>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr :key="user.id+'6'" class="employee-td">
                                        <td class="align-middle">申請期間</td>
                                        <td colspan="5" class="align-middle text-left">
                                            <div class="align-middle d-flex">
                                                <template v-for="applicationDeadlineOption in applicationDeadlineOptions">
                                                    <div class="d-flex align-items-center mr-3" :key="applicationDeadlineOption.key" @click="setStatus(user.id, 'application_deadline', applicationDeadlineOption.key)">
                                                        <input class="mr-1" type="radio" :value="applicationDeadlineOption.key" v-model="user.setting.applicationDeadline"/>
                                                        <div class="mb-0">{{applicationDeadlineOption.value}}</div>
                                                    </div>
                                                </template>
                                            </div>
                                        </td>
                                    </tr>
                                    </template>
                                </tbody>
                            </table>
                            <div class="pager-wrapper">
                                <pagination v-model="pager.current" :records="pager.total" :per-page="pager.size" :options="{texts: pager.texts}" @paginate="getUsers"/>
                                <div class="pager-per-page">
                                    <select class="form-control" @change="onPerPageChange">
                                        <option v-for="(page, index) in perPages" :key="index" :value="page">{{ page }}</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="" v-else>
                            {{ $t("No Users") }}
                        </div>
                        <button class="btn btn-primary float-right" @click="csvOutput">CSV取込み</button>
                        <!-- Modal -->
                        <div class="modal fade" id="user-master-form" tabindex="-1" role="dialog" aria-labelledby="user-master-form" aria-hidden="true">
                            <div class="modal-dialog modal-huge" role="document">
                                <employee-master-form :formData="masterFormData" :regions="regions" :editMode="editMode" v-on:success="onUserSaved" />
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
import EmployeeMasterForm from './EmployeeMaster/EmployeeMasterForm.vue';
import LocalStorage from '../helpers/localStorage';
import Pagination from 'vue-pagination-2';

    export default {
  components: { EmployeeMasterForm, Pagination },
        mixins: [actionLoading],
        data() {
            return {
                users: [],
                masterFormData: {
                    regionId: null,
                    sort: null,
                },
                offices: [],
                regions: [],
                editMode: false,
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
                queryName: '',
            }
        },
        computed: {
        ...mapState({
            session: state =>  state.session.info,
            employmentStatuses: state => state.constants.employmentStatuses,
            overtimePayOptions: state => state.constants.overtimePayOptions,
            salaryDeductionOptions: state => state.constants.salaryDeductionOptions,
            applicationDeadlineOptions: state=> state.constants.applicationDeadlineOptions
         }),
        },
        methods: {
            onEditClicked(userId) {
                const user = this.users.find(({id}) => userId === id);
                if (!user) return;
                this.masterFormData = {
                    regionId: user.region ? user.region.id: null,
                    sort: user.sort ? user.sort : null,
                    ...user
                    };
                this.editMode = true;
                this.showMasterForm();
            },
            onUserSaved() {
                this.getUsers();
                $("#user-master-form").modal('hide');
            },
            getRegions() {
                api.get('region')
                    .then(response => {
                        this.regions = response;
                    })
                    .catch(e => apiErrorHandler(e));
            },
            onUserDeleteClick(hourlyId){
                if (this.actionLoading) return;
                if (!confirm(this.$t("Are you sure you want to delete?"))) return;
                this.setActionLoading();
                api.delete('users/' + hourlyId)
                    .then(() => {
                        this.unsetActionLoading();
                        showSuccess(this.$t('Successfully deleted'));
                        this.getUsers();
                    })
                    .catch(e => {
                        apiErrorHandler(e);
                        this.unsetActionLoading();
                    });
            },
            onNewClick() {
                this.masterFormData = {
                    regionId: 1,
                    sort: null,
                };
                this.editMode = false;
                this.showMasterForm();
            },
            csvOutput() {
                window.location.href = "/user/csv?token=" + LocalStorage.getToken();
            },
            onPerPageChange(e) {
                this.pager.size = parseInt(e.target.value);
                this.getUsers();
            },
            showMasterForm() {
                $("#user-master-form").modal('show');
            },
            onSearch() {
                this.queryName = this.searchName;
                this.getUsers();
            },
            getUsers(page = 1) {
                if (this.actionLoading) return;
                this.setActionLoading();
                const query = {page, size: this.pager.size};
                if (this.queryName) query.office_name = this.queryName;
                api.get('users', null, query)
                    .then(response => {
                        this.unsetActionLoading();
                        this.users = response.data;
                        this.pager.current = response.currentPage;
                        this.pager.total = parseInt(response.total);
                        this.pager.size = parseInt(response.perPage);
                    })
                    .catch(e => {
                        apiErrorHandler(e);
                        this.unsetActionLoading();
                    });
            },
            setStatus(userId, statusKind, value) {
                if (this.actionLoading) return;
                this.setActionLoading();
                api.put('users/' + userId + '/setting', null, {[statusKind]: value})
                    .then(() => {
                        this.unsetActionLoading();
                        showSuccess(this.$t("Successfully saved"));
                        this.getUsers(this.pager.current);
                    })
                    .catch(e => {
                        apiErrorHandler(e);
                        this.unsetActionLoading();
                    });
            },
            setRole(userId, value) {
                if (this.actionLoading) return;
                this.setActionLoading();
                api.put('users/' + userId + '/role', null, {'role_id': value})
                    .then(() => {
                        this.unsetActionLoading();
                        showSuccess(this.$t("Successfully saved"));
                        this.getUsers(this.pager.current);
                    })
                    .catch(e => {
                        apiErrorHandler(e);
                        this.unsetActionLoading();
                    });
            }
        },
        mounted() {
            this.getUsers();
            this.getRegions();
        }
    }
</script>
<style scoped>
.pager-wrapper {
    display: flex;
    justify-content: center;
    gap: 20px;
}
.pager-per-page {
    max-width: 200px;
}
</style>
