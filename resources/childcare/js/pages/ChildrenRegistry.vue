<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header calendar-title">
                        <div class="row">
                            <div class="col-md-6">
                                <h3 class="card-title mb-0">{{session.office.name}}</h3>
                            </div>
                            <div class="col-md-3">

                            </div>
                            <div class="col-md-3">

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
                                        <th width="15%">
                                            園児ID
                                        </th>
                                        <th width="20%">
                                            園児氏名
                                        </th>
                                        <th width="10%">
                                            性別
                                        </th>
                                        <th>
                                            生年月日
                                        </th>
                                        <th>
                                            年齢
                                        </th>
                                        <th width="15%">
                                            クラス
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="text-center">
                                    <tr>
                                        <td>
                                            <input class="form-control" v-model="formData.childNumber" :class="{'is-invalid' : errors.childNumber}" @keyup="errors.childNumber = null"/>
                                            <span v-if="errors.childNumber" class="error invalid-feedback">
                                                {{ errors.childNumber }}
                                            </span>
                                        </td>
                                        <td>
                                            <input class="form-control" v-model="formData.childName" :class="{'is-invalid' : errors.childName}" @keyup="errors.childName = null"/>
                                            <span v-if="errors.childName" class="error invalid-feedback">
                                                {{ errors.childName }}
                                            </span>
                                        </td>
                                        <td>
                                            <select class="form-control" v-model="formData.gender" :class="{'is-invalid' : errors.gender}" @change="errors.gender = null;">
                                                <option value="1">男</option>
                                                <option value="2">女</option>
                                            </select>
                                            <span v-if="errors.gender" class="error invalid-feedback">
                                                {{ errors.gender }}
                                            </span>
                                        </td>
                                        <td>
                                            <year-month-day-select-box v-model="formData.birthday" :error="errors.birthday" @input="errors.birthday = null"></year-month-day-select-box>
                                        </td>
                                        <td>
                                            {{ age }}
                                        </td>
                                        <td>
                                            <select class="form-control" v-model="formData.classId">
                                                <option></option>
                                                <option v-for="childrenClass in childrenClasses" :key="childrenClass.id" :value="childrenClass.id">{{childrenClass.name}}</option>
                                            </select>
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
                                        <th width="15%">
                                            メールアドレス
                                        </th>
                                        <th width="10%">
                                            パスワード
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="text-center">
                                    <tr>
                                        <td>
                                            <year-month-day-select-box v-model="formData.admissionDate" :error="errors.admissionDate" @input="errors.admissionDate = null"></year-month-day-select-box>
                                        </td>
                                        <td>
                                            <year-month-day-select-box v-model="formData.exitDate" :error="errors.exitDate" @input="errors.exitDate = null"></year-month-day-select-box>
                                        </td>
                                        <td>
                                            <input class="form-control" v-model="formData.email" :class="{'is-invalid' : errors.email}" @keyup="errors.email = null"/>
                                            <span v-if="errors.email" class="error invalid-feedback">
                                                {{ errors.email }}
                                            </span>
                                        </td>
                                        <td>
                                            <input class="form-control" type="password" v-model="formData.password" :class="{'is-invalid' : errors.password}" :placeholder="childId ? '******':''" @keyup="errors.password = null"/>
                                            <span v-if="errors.password" class="error invalid-feedback">
                                                {{ errors.password }}
                                            </span>
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
                                        <td>
                                            <div class="d-flex align-items-center justify-content-center is-invalid">
                                                <input type="radio" class="align-middle" name="prediction" :value="1" v-model="formData.certificationType">
                                                <div class="ml-1 mr-5">短時間</div>
                                                <input type="radio" class="align-middle ml-5" name="prediction" :value="0" v-model="formData.certificationType">
                                                <div class="ml-1 mr-4">標準時間</div>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="p-0 mb-3">
                            <table
                                class="table table-bordered table-hover mb-0" style="outline: 1px solid #e7effe;"
                            >
                                <tbody class="text-center">
                                    <tr>
                                        <td class="light-blue align-middle" style="width:200px;">
                                            備考欄
                                        </td>
                                        <td class="p-0 bg-white">
                                            <textarea class="textarea-fit" v-model="formData.remarks" :class="{'is-invalid': errors.remarks}" @change="errors.remarks = null;">

                                            </textarea>
                                            <span v-if="errors.remarks" class="error invalid-feedback">
                                                {{errors.remarks}}
                                            </span>
                                        </td>
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
                                            従業員枠企業所名
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="text-center">
                                    <tr>
                                        <td>
                                            <div class="d-flex align-middle is-invalid">
                                                <div v-for="childType in childTypes" :key = "childType.key" class="d-flex align-items-center mr-3">
                                                    <input type="radio" class="align-middle" :value="childType.key" v-model="formData.type" @change="errors.type = null; errors.companyName = null">
                                                    <label class="ml-1 mr-4 mb-0">{{childType.value}}</label>
                                                </div>
                                            </div>
                                            <span v-if="errors.type" class="error invalid-feedback">
                                                {{ errors.type }}
                                            </span>
                                        </td>
                                        <td>
                                            <input class="form-control" v-model="formData.companyName" :class="{'is-invalid' : errors.companyName}" @keyup="errors.companyName = null"/>
                                            <span v-if="errors.companyName" class="error invalid-feedback">
                                                {{ errors.companyName }}
                                            </span>
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
                                        <td>
                                            <div class="d-flex align-items-center justify-content-center is-invalid">
                                                <input type="radio" class="align-middle" :value="1" v-model="formData.freeOfCharge" @change="errors.freeOfCharge = null">
                                                <div class="ml-1 mr-4">対象</div>
                                                <input type="radio" class="align-middle" :value="0" v-model="formData.freeOfCharge" @change="errors.freeOfCharge = null">
                                                <div class="ml-1 mr-4">対象外</div>
                                            </div>
                                            <span v-if="errors.freeOfCharge" class="error invalid-feedback">
                                                {{ errors.freeOfCharge }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center justify-content-center is-invalid">
                                                <input type="radio" class="align-middle" :value="1" v-model="formData.certificateOfPayment" :class="{'is-invalid' : errors.certificateOfPayment}" @change="errors.certificateOfPayment = null">
                                                <div class="ml-1 mr-4">有り</div>
                                                <input type="radio" class="align-middle" :value="0" v-model="formData.certificateOfPayment" @change="errors.certificateOfPayment = null; errors.certificateExpirationDate = null;">
                                                <div class="ml-1 mr-4">無し</div>
                                            </div>
                                            <span v-if="errors.certificateOfPayment" class="error invalid-feedback">
                                                {{ errors.certificateOfPayment }}
                                            </span>
                                        </td>
                                        <td>
                                            <year-month-day-select-box v-model="formData.certificateExpirationDate" :error="errors.certificateExpirationDate" @input="errors.certificateExpirationDate = null"></year-month-day-select-box>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center justify-content-center is-invalid">
                                                <input type="radio" class="align-middle" :value="1" v-model="formData.taxExemptHousehold" @change="errors.taxExemptHousehold = null">
                                                <div class="ml-1 mr-4">対象</div>
                                                <input type="radio" class="align-middle" :value="0" v-model="formData.taxExemptHousehold" @change="errors.taxExemptHousehold = null">
                                                <div class="ml-1 mr-4">対象外</div>
                                            </div>
                                            <span v-if="errors.taxExemptHousehold" class="error invalid-feedback">
                                                {{ errors.taxExemptHousehold }}
                                            </span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="float-right d-flex align-items-center mt-2">
                            <button class="btn btn-primary float-right" @click="saveChild">登録</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import moment from 'moment';
import api, { apiErrorHandler } from '../global/api';
import actionLoading from '../mixin/actionLoading';
import { mapState } from 'vuex';
import { showSuccess } from '../../../js/helpers/error';
import YearMonthDaySelectBox from '../components/YearMonthDaySelectBox.vue';
import { validateYYMMDD } from '../helpers/datetime';
import { validatePassword } from '../helpers/password';

export default {
  components: { YearMonthDaySelectBox },
    mixins: [actionLoading],
    data() {
        return {
            formData: {
                childNumber: '',
                childName: '',
                gender: null,
                birthday: null,
                classId: null,
                admissionDate: null,
                exitDate: null,
                email: null,
                password: null,
                type: null,
                companyName: '',
                freeOfCharge: null,
                certificateOfPayment: null,
                certificateExpirationDate: null,
                certificationType: 0,
                taxExemptHousehold: null,
                remarks: null
            },
            errors: {
                childNumber: '',
                childName: '',
                gender: null,
                birthday: null,
                classId: null,
                admissionDate: null,
                exitDate: null,
                email: null,
                password: null,
                type: null,
                companyName: '',
                freeOfCharge: null,
                certificateOfPayment: null,
                certificateExpirationDate: null,
                taxExemptHousehold: null,
                remarks: null
            },
            childId: null,
            childInfor: {},
        }
    },
    computed: {
        ...mapState({
            childrenClasses: state => state.constants.childrenClasses,
            childTypes: state => state.constants.childTypes,
            session: state => state.session.info,
        }),
        age() {
            if (!this.formData.birthday) return null;
            const birthday = moment(this.formData.birthday).format("YYYY-MM-DD");
            if (birthday === 'Invalid date') return null;
            const ageInMonth = moment().diff(birthday, 'months');
            const y = Math.floor(ageInMonth / 12);
            const m = ageInMonth % 12;
            return (y ? y + '歳' : '') + (m + 'ヶ月');
        }
    },
    methods: {
        getChildInfor() {
            if(this.actionLoading) return;
            this.setActionLoading();
            api.get('child/' + this.childId, null)
                .then(response => {
                    this.childInfor = response;
                    this.convertDataToFormData();
                    this.unsetActionLoading();
                })
                .catch(e => {
                    apiErrorHandler(e);
                    this.unsetActionLoading();
                });
        },
        registerChild() {
            this.$router.push({name: 'children-register'});
        },
        saveChild() {
            if (this.actionLoading) return;
            if (!this.validate()) return;
            const requestData = {
                'number': this.formData.childNumber,
                'name': this.formData.childName,
                'gender': this.formData.gender,
                'birthday': moment(this.formData.birthday).format("YYYY-MM-DD"),
                'class_id': this.formData.classId,
                'admission_date': moment(this.formData.admissionDate).format("YYYY-MM-DD"),
                'exit_date': this.formData.exitDate ? moment(this.formData.exitDate).format("YYYY-MM-DD") : null,
                'email': this.formData.email,
                'password': this.formData.password,
                'type': this.formData.type,
                'company_name': this.formData.companyName,
                'free_of_charge': this.formData.freeOfCharge,
                'certificate_of_payment': this.formData.certificateOfPayment,
                'certificate_expiration_date': this.formData.certificateExpirationDate ? moment(this.formData.certificateExpirationDate).format("YYYY-MM-DD") : null,
                'tax_exempt_household': this.formData.taxExemptHousehold,
                'remarks': this.formData.remarks,
                'certification_type': this.formData.certificationType
            };
            this.setActionLoading();
            let request;
            if(this.childId) {
                requestData['id'] = this.childId;
                request = api.put('register/' + this.childId, null, requestData);
            } else {
                request = api.post('register', null, requestData);
            }
            request.then(response => {
                this.unsetActionLoading();
                showSuccess(this.$t('Successfully saved'));
                // this.getChildInfor();
                let childId;
                childId = response.id;
                this.$router.push({name: 'children-detail', params: {id: childId}});
            })
            .catch(e => {
                apiErrorHandler(e);
                this.unsetActionLoading();
            });
        },
        convertDataToFormData() {
            this.formData.childNumber = this.childInfor.number;
            this.formData.childName = this.childInfor.name;
            this.formData.gender = this.childInfor.gender;
            if(this.childInfor.birthday){
                this.formData.birthday = this.childInfor.birthday;
            }
            this.formData.classId = this.childInfor.classId;
            if(this.childInfor.admissionDate) {
                this.formData.admissionDate = this.childInfor.admissionDate;
            }
            if(this.childInfor.exitDate) {
                this.formData.exitDate = this.childInfor.exitDate;
            }
            this.formData.email = this.childInfor.email;
            // this.formData.password = this.chi
            this.formData.type = this.childInfor.type;
            this.formData.companyName = this.childInfor.companyName;
            this.formData.freeOfCharge = this.childInfor.freeOfCharge;
            this.formData.certificateOfPayment = this.childInfor.certificateOfPayment;
            if(this.childInfor.certificateExpirationDate) {
                this.formData.certificateExpirationDate = this.childInfor.certificateExpirationDate;
            }
            this.formData.taxExemptHousehold = this.childInfor.taxExemptHousehold;
            this.formData.remarks = this.childInfor.remarks;
            this.formData.certificationType = this.childInfor.certificationType;
        },
        validate() {
            let valid = true;
            var alphanum_regex = /^[a-z0-9]+$/i;
            var fullTextFormat = /[\x01-\x7E\uFF65-\uFF9F]/;

            if (!this.formData.childNumber) {
                this.errors.childNumber = this.$t('Please input half-width alphanumerical');                                 // need trans
                valid = false;
            }
            if (this.formData.childNumber && !alphanum_regex.test(this.formData.childNumber)) {
                this.errors.childNumber = this.$t('Please input half-width alphanumerical');
                valid = false;
            }
            if (this.formData.childNumber && !String(this.formData.childNumber).match(fullTextFormat)) {
                this.errors.childNumber = this.$t('Please input half-width alphanumerical');
                valid = false;
            }
            if (this.formData.childNumber && this.formData.childNumber.length > 20) {
                this.errors.childNumber = this.$t('Please enter 20 characters or less');
                valid = false;
            }
            if (!this.formData.childName) {
                this.errors.childName = this.$t('Please input name');                                 // need trans
                valid = false;
            }
            if (this.formData.childName && this.formData.childName.length > 50) {
                this.errors.childName = this.$t('Please enter 50 characters or less');
                valid = false;
            }
            if (!this.formData.gender) {
                this.errors.gender = this.$t('Please input gender');                              //need trans
                valid = false;
            }

            if (!this.formData.birthday) {
                this.errors.birthday = this.$t('Please input birthday');                              //need trans
                valid = false;
            }

            if (this.formData.birthday && !validateYYMMDD(this.formData.birthday)) {
                this.errors.birthday = this.$t('Please input birthday');                              //need trans
                valid = false;
            }

            if (!this.formData.admissionDate) {
                this.errors.admissionDate = this.$t('Please input admissionDate');                              //need trans
                valid = false;
            }

            if (this.formData.admissionDate && !validateYYMMDD(this.formData.admissionDate)) {
                this.errors.admissionDate = this.$t('Please input admissionDate');                              //need trans
                valid = false;
            }

            if (this.formData.admissionDate > this.formData.exitDate) {
                this.errors.admissionDate = this.$t('Please input exitDate after admissionDate');                              //need trans
                valid = false;
            }

            if (this.formData.birthday > this.formData.admissionDate) {
                this.errors.admissionDate = this.$t('Please input admissionDate after birthday');
                valid = false;
            }
            // if (!this.formData.exitDate) {
            //     this.errors.exitDate = this.$t('Please input exitDate');                              //need trans
            //     valid = false;
            // }

            if (this.formData.exitDate && !validateYYMMDD(this.formData.exitDate)) {
                this.errors.exitDate = this.$t('Please input exitDate');                              //need trans
                valid = false;
            }

            // if (!this.formData.classId) {
            //     this.errors.classId = this.$t('Please input number');
            //     valid = false;
            // }
            if (!this.formData.email) {
                this.errors.email = this.$t('Please input email');
                valid = false;
            }
            if (this.formData.email && !String(this.formData.email).match(fullTextFormat)) {
                this.errors.email = this.$t('Please input half-width alphanumerical');
                valid = false;
            }
            if (this.formData.email && this.formData.email.length > 50) {
                this.errors.email = this.$t('Please enter 50 characters or less');
                valid = false;
            }
            if (!this.childId && !this.formData.password) {
                this.errors.password = this.$t('Please input password');
                valid = false;
            }

            if (this.formData.password && this.formData.password != "******" && !validatePassword(this.formData.password)) {
                this.errors.password = this.$t('Please input only English and number');
                valid = false;
            }

            if (this.formData.password && this.formData.password.length != 4) {
                this.errors.password = this.$t('Please enter 4 characters');
                valid = false;
            }
            if (this.isCompanyled()) {
                if (this.formData.certificateOfPayment == 1) {
                    if (!this.formData.certificateExpirationDate) {
                        this.errors.certificateExpirationDate = this.$t('Please input certificateExpirationDate');
                        valid = false;
                    }
                    if (this.formData.certificateExpirationDate && !validateYYMMDD(this.formData.certificateExpirationDate)) {
                        this.errors.certificateExpirationDate = this.$t('Please input certificateExpirationDate');                              //need trans
                        valid = false;
                    }
                }
                if (!this.formData.type) {
                    this.errors.type = this.$t('Please input type');
                    valid = false;
                }
                if (this.formData.type == 1 || this.formData.type == 2) {
                    if (!this.formData.companyName) {
                        this.errors.companyName = this.$t('Please input name');
                        valid = false;
                    }
                    if (this.formData.companyName && this.formData.companyName.length > 20) {
                        this.errors.companyName = this.$t('Please enter 20 characters or less');
                        valid = false;
                    }
                }
                if (this.formData.freeOfCharge === null || this.formData.freeOfCharge === undefined) {
                    this.errors.freeOfCharge = this.$t('Please select freeOfCharge');
                    valid = false;
                }
                if (this.formData.certificateOfPayment === null || this.formData.certificateOfPayment === undefined) {
                    this.errors.certificateOfPayment = this.$t('Please select certificateOfPayment');
                    valid = false;
                }
                if (this.formData.taxExemptHousehold === null || this.formData.taxExemptHousehold === undefined) {
                    this.errors.taxExemptHousehold = this.$t('Please select taxExemptHousehold');
                    valid = false;
                }
                if (this.formData.remarks && this.formData.remarks.length > 50) {
                    this.errors.remarks = this.$t('Please enter 50 characters or less');
                    valid = false;
                }
            }
            return valid;
        },
        isCertifCategory(){
            return (this.session) ? this.session.office.certifTypeEnabled : false;
        },
        isCompanyled() {
            return (this.session) ? this.session.office.businessTypeId == 1 : false;
        }
    },
    mounted() {
        const childId = this.$route.params.id;
        if(childId) {
            this.childId = parseInt(childId);
            this.getChildInfor();
        }
    }
};
</script>
