<template>
    <div class="login-page">
        <div class="login-box">
            <!-- /.login-logo -->
            <div class="card">
                <div class="card-header text-center p-2">
                    <a href="#" class="h1"
                        ><img src="/images/logo1.png" style="width:90%; height:90%"
                    /></a>
                </div>
                <div class="card-body">
                    <form action="" method="post" autocomplete="off">
                        <div class="input-group mb-3">
                            <input
                                type="text"
                                class="form-control"
                                :class="{'is-invalid' : form.errors().has('number')}"
                                placeholder="ＩＤまたはメールアドレス"
                                v-model="form.number"
                                autocomplete="off"
                                name="test"
                            />
                            <span v-if="form.errors().has('number')" class="error invalid-feedback">
                                {{ form.errors().get('number') }}
                            </span>
                        </div>
                        <div class="input-group mb-3">
                            <input
                                type="password"
                                class="form-control"
                                :class="{'is-invalid' : form.errors().has('password')}"
                                placeholder="パスワード"
                                v-model="form.password"
                                autocomplete="off"
                                name="test2"
                                v-on:keyup.enter = "onSubmit"
                            />
                            <span v-if="form.errors().has('password')" class="error invalid-feedback">
                                {{ form.errors().get('password') }}
                            </span>
                        </div>
                        <div class="text-center red mb-2" v-if="loginFailure">
                            {{$t('LoginId or Password dismatch')}}
                        </div>
                        <div class="text-center red mb-2" v-if="fullWidthText">
                            {{$t('Please input half-width text')}}
                        </div>
                        <div class="row d-flex justify-content-center mb-3">
                            <!-- /.col -->
                            <div>
                                <button
                                    type="button"
                                    class="btn btn-primary btn-block"
                                    @click="onSubmit"
                                    :disabled="!enableLogin"
                                >
                                    ログイン
                                </button>
                            </div>
                            <!-- /.col -->
                        </div>
                    </form>
                    <!-- <p class="mb-1">
                        <a href="#" data-toggle="tooltip" data-placement="top" title="電話番号は＊＊＊＊＊です。">ログイン情報をお忘れですか？</a>
                    </p> -->

                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
    </div>
</template>
<script>
import form from 'vuejs-form';
import { Guards } from '../../childcare/js/global/consts';
import api, { apiErrorHandler } from '../global/api';
import LocalStorage from '../helpers/localStorage';
import actionLoading from '../mixin/actionLoading';

export default {
    mixins: [actionLoading],
    data() {
        return {
            form: form({
                number: '',
                password: ''
            })
            .rules({
                number: 'required',
                password: 'required'
            })
            .messages({
                'number.required': this.$t('Please input employeeNo'),       // need trans
                'password.required': this.$t('Please input password')    // need trans
            }),
            enableLogin: true,
            loginTime: null,
            loginTimeInterval: null,
            loginFailure: false,
            fullWidthText: false,
        }
    },
    watch: {
        ['form.data'] : {
            deep: true,
            immediate: false,
            handler: 'onFormChange'
        }
    },
    methods: {
        onSubmit() {
            if (this.actionLoading || !this.enableLogin) return;
            if (this.form.validate().errors().any()) return;
            if (this.validateFullWidthText(this.form.number)) return;
            if (!this.validateEmail(this.form.number)) {
                this.setActionLoading();
                api.post('login', null, this.form.all())
                    .then(({token, user}) => {
                        this.unsetActionLoading();
                        LocalStorage.removeLoginFailure();
                        LocalStorage.removeLoginTimeStamp();
                        clearInterval(this.loginTimeInterval);
                        LocalStorage.saveToken(token);
                        this.$store.commit('session/setSession', user);
                        if(user.office && user.office.isNursery) {
                            location.href = "/child/";
                        } else if(user.roleId == Guards.ADMIN) {
                            location.href = "/child/application-table";
                        } else {
                            this.$router.push({ name: 'stamp' })
                        }
                    })
                    .catch(e => {
                        this.unsetActionLoading();
                        //apiErrorHandler(e);
                        LocalStorage.saveLoginFailure();
                        LocalStorage.saveLoginTimeStamp();
                        this.loginFailure = true;
                        // setTimeout(() => {
                        //     this.loginFailure = false;
                        // }, 3000);
                        if(LocalStorage.getLoginFailure() >= 5) {
                            this.enableLogin = false;
                            this.loginTimeInterval = setInterval(this.enableLoginTimer, 1000);
                        }
                    });
            } else {
                this.setActionLoading();
                this.form.email = this.form.number;
                api.post(process.env.MIX_APP_BASE_URL + 'child-api/login', null, this.form.all())
                    .then(({token, user}) => {
                        this.unsetActionLoading();
                        LocalStorage.removeLoginFailure();
                        LocalStorage.removeLoginTimeStamp();
                        clearInterval(this.loginTimeInterval);
                        LocalStorage.saveToken(token);
                        this.$store.commit('session/setSession', user);
                        location.href = "/child/parent/";
                    })
                    .catch(e => {
                        this.unsetActionLoading();
                        //apiErrorHandler(e);
                        LocalStorage.saveLoginFailure();
                        LocalStorage.saveLoginTimeStamp();
                        this.loginFailure = true;
                        // setTimeout(() => {
                        //     this.loginFailure = false;
                        // }, 3000);
                        if(LocalStorage.getLoginFailure() >= 5) {
                            this.enableLogin = false;
                            this.loginTimeInterval = setInterval(this.enableLoginTimer, 1000);
                        }
                    });
            }
        },
        validateEmail(inputText) {
            var mailFormat = /^[\w\-._]+@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
            if(String(inputText).toLowerCase().match(mailFormat)) {
                return true;
            } else {
                return false;
            }
        },
        validateFullWidthText(inputText) {
            var fullTextFormat = /[\x01-\x7E\uFF65-\uFF9F]/;
            if(String(inputText).match(fullTextFormat)) {
                this.fullWidthText = false;
                return false;
            } else {
                this.fullWidthText = true;
                return true;
            }
        },
        onFormChange() {
            this.loginFailure = false;
            this.form.validate();
        },
        enableLoginTimer() {
            const lastFailure = LocalStorage.getLoginTimeStamp();
            if((Date.now() - lastFailure) / 1000 >= 30) {
                this.enableLogin = true;
                LocalStorage.removeLoginFailure();
                LocalStorage.removeLoginTimeStamp();
            }
        }
    },
    mounted() {
        if(LocalStorage.getLoginFailure() >= 5) {
            this.enableLogin = false;
            this.loginTimeInterval = setInterval(this.enableLoginTimer, 1000);
        }
    },
    destroyed() {
        clearInterval(this.loginTimeInterval);
    }
};
</script>
