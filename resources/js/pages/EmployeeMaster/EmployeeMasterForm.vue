<template>
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" v-if="!editMode">新規登録</h5>
            <h5 class="modal-title" v-else>編集</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="form-group">
                <div class="form-row align-items-center">
                    <div class="col-md-3">
                        <input type="text" class="form-control" placeholder="社員No" v-model="formData.number" :class="{'is-invalid' : errors.number}" @keyup="errors.number = null">
                        <span v-if="errors.number" class="error invalid-feedback">
                            {{ errors.number }}
                        </span>
                    </div>
                    <div class="col-md-3">
                        <input type="text" class="form-control" placeholder="氏名" v-model="formData.name" :class="{'is-invalid' : errors.name}" @keyup="errors.name = null">
                        <span v-if="errors.name" class="error invalid-feedback">
                            {{ errors.name }}
                        </span>
                    </div>
                    <div class="col-md-3">
                        <input type="radio" :value="1" v-model="formData.employmentStatusId" :class="{'is-invalid' : errors.employmentStatusId}" @change="errors.employmentStatusId = null">
                        <label class="ml-auto">正社員</label>
                        <input type="radio" :value="2" v-model="formData.employmentStatusId" :class="{'is-invalid' : errors.employmentStatusId}" @change="errors.employmentStatusId = null">
                        <label class="ml-auto">時短社員</label>
                        <input type="radio" :value="3" v-model="formData.employmentStatusId" :class="{'is-invalid' : errors.employmentStatusId}" @change="errors.employmentStatusId = null">
                        <label class="ml-auto">パート</label>
                        <span v-if="errors.employmentStatusId" class="error invalid-feedback">
                            {{ errors.employmentStatusId }}
                        </span>
                    </div>
                    <div class="col-md-3">
                        <input type="radio" :value="1" v-model="formData.enrolled" :class="{'is-invalid' : errors.enrolled}" @change="errors.enrolled = null">
                        <label class="ml-auto">在籍中</label>
                        <input type="radio" :value="0" v-model="formData.enrolled" :class="{'is-invalid' : errors.enrolled}" @change="errors.enrolled = null">
                        <label class="ml-auto">退職</label>
                        <span v-if="errors.enrolled" class="error invalid-feedback">
                            {{ errors.enrolled }}
                        </span>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="form-row align-items-center">
                    <div class="col-md-3">
                        <select class="form-control" v-model="formData.regionId" :class="{'is-invalid' : errors.regionId}">
                            <option :value="undefined" disabled hidden>エリアを選択してください</option>
                            <option v-for="region in regions" :key="region.id" :value="region.id">{{region.name}}</option>
                        </select>
                        <span v-if="errors.regionId" class="error invalid-feedback">
                            {{ errors.regionId }}
                        </span>
                    </div>
                    <div class="col-md-3">
                        <select class="form-control" v-model="formData.officeId" :class="{'is-invalid' : errors.officeId}" @change="errors.officeId = null">
                            <option :value="undefined" disabled hidden>事業所を選択してください</option>
                            <option v-for="office in offices" :key="office.id" :value="office.id">{{office.name}}</option>
                        </select>
                        <span v-if="errors.officeId" class="error invalid-feedback">
                            {{ errors.officeId }}
                        </span>
                    </div>
                    <div class="col-md-3">
                        <input type="text" class="form-control" placeholder="本社グループ" v-model="formData.sort" :class="{'is-invalid' : errors.sort}" @keyup="errors.sort = null">
                        <span v-if="errors.sort" class="error invalid-feedback">
                            {{ errors.sort }}
                        </span>
                    </div>
                    <div class="col-md-3">
                        <select class="form-control" v-model="formData.workingHours" :class="{'is-invalid' : errors.workingHours}">
                            <option value="undefined" disabled hidden>勤務時間を選択してください</option>
                            <option value="8">8時間</option>
                            <option value="7">7時間</option>
                            <option value="6">6時間</option>
                        </select>
                        <span v-if="errors.workingHours" class="error invalid-feedback">
                            {{ errors.workingHours }}
                        </span>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="form-row align-items-center">
                    <div class="col-md-6">
                        <input type="text" class="form-control" placeholder="メールアドレス" v-model="formData.email" :class="{'is-invalid' : errors.email}" @keyup="errors.email = null">
                        <span v-if="errors.email" class="error invalid-feedback">
                            {{ errors.email }}
                        </span>
                    </div>
                    <div class="col-md-6">
                        <input type="password" class="form-control" placeholder="*****" v-model="formData.password" :class="{'is-invalid' : errors.password}" @keyup="errors.password = null">
                        <span v-if="errors.password" class="error invalid-feedback">
                            {{ errors.password }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">閉じる</button>
            <button type="submit" class="btn btn-primary" @click="saveUser()">登録</button>
        </div>
    </div>
</template>
<script>
import api, { apiErrorHandler } from '../../global/api';
import actionLoading from '../../mixin/actionLoading';
import { showSuccess } from '../../helpers/error';

    export default {
        mixins: [actionLoading],
        props: {
            formData: {
                name: '',
                number: '',
                employmentStatusId: null,
                enrolled: 0,
                regionId: null,
                officeId: null,
                email: '',
                password: '',
                sort: null,
                workingHours: null
            },
            regions: [],
            editMode: null,
        },
        watch: {
            ['formData.id'] : function (){
                this.errors = {
                    name: '',
                    number: '',
                    employmentStatusId: null,
                    enrolled: null,
                    regionId: null,
                    officeId: null,
                    email: '',
                    password: '',
                    sort: null,
                    workingHours: null
                };
                this.offices = [];
                this.setOffices();
            },
            ['formData.regionId']: function () {
                this.setOffices();
            }
        },
        data() {
            return {
                errors: {
                    name: '',
                    number: '',
                    employmentStatusId: null,
                    enrolled: null,
                    regionId: null,
                    officeId: null,
                    email: '',
                    password: '',
                    sort: null,
                },
                offices: [],
            }
        },
        methods: {
            saveUser() {
                if (this.actionLoading) return;
                if (!this.validate()) return;
                const requestData = {
                    'name': this.formData.name,
                    'number': this.formData.number,
                    'employment_status_id': this.formData.employmentStatusId,
                    'enrolled': this.formData.enrolled,
                    'office_id': this.formData.officeId,
                    'email': this.formData.email,
                    'password': this.formData.password,
                    'workingHours': this.formData.workingHours,
                    'sort': this.formData.sort
                };
                this.setActionLoading();
                let request;
                if (this.formData.id) {
                    request = api.put('users/' + this.formData.id, null, requestData);
                } else {
                    request = api.post('users', null, requestData);
                }
                request.then(() => {
                        this.unsetActionLoading();
                        showSuccess(this.$t("Successfully saved"));
                        this.$emit('success');
                    })
                    .catch(e => {
                        apiErrorHandler(e);
                        this.unsetActionLoading();
                    });
            },
            validate() {
                let valid = true;
                if (!this.formData.name) {
                    this.errors.name = this.$t('Please input name');                                 // need trans
                    valid = false;
                }
                if (this.formData.name && this.formData.name.length > 50) {
                    this.errors.name = this.$t('Please enter 50 characters or less');                                 // need trans
                    valid = false;
                }
                if (!this.formData.number) {
                    this.errors.number = this.$t('Please input number');                              //need trans
                    valid = false;
                }
                if (this.formData.number && this.formData.number.length > 20) {
                    this.errors.number = this.$t('Please enter 20 characters or less');                              //need trans
                    valid = false;
                }
                if (this.formData.enrolled === undefined || this.formData.enrolled === null) {
                    this.errors.enrolled = this.$t('Please select enrolled');
                    valid = false;
                }
                if (!this.formData.employmentStatusId) {
                    this.errors.employmentStatusId = this.$t('Please select employmentStatusId');
                    valid = false;
                }
                if (!this.formData.regionId) {
                    this.errors.regionId = this.$t('Please select region');
                    valid = false;
                }
                if (!this.formData.officeId) {
                    this.errors.officeId = this.$t('Please select office');
                    valid = false;
                }
                // if (!this.formData.sort) {
                //     this.errors.sort = this.$t('Please input sort');
                //     valid = false;
                // }
                if (!this.formData.email) {
                    this.errors.email = this.$t('Please input email');
                    valid = false;
                }
                if (this.formData.email && this.formData.email.length > 50) {
                    this.errors.email = this.$t('Please enter 50 characters or less');
                    valid = false;
                }
                if (!this.formData.password && !this.formData.id) {
                    this.errors.password = this.$t('Please input password');
                    valid = false;
                }
                if (this.formData.password && this.formData.password.length > 50) {
                    this.errors.password = this.$t('Please enter 50 characters or less');
                    valid = false;
                }
                // if (!this.formData.workingHours) {
                //     this.errors.workingHours = this.$t('Please select working hour');
                //     valid = false;
                // }
                return valid;
            },
            setOffices() {
                if(this.formData.regionId)  {
                    api.get('office-master', null, {'region_id': this.formData.regionId})
                    .then(response => {
                        this.offices = response;
                        this.setFirstOffice();
                    })
                    .catch(e => apiErrorHandler(e));
                }
            },
            setFirstOffice(){
                if(this.offices.length){
                    this.formData.officeId = this.offices[0].id;
                }
            }
        }
    }
</script>
