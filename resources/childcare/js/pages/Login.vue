<template>
    <div class="login-page">
        <div class="login-box">
            <!-- /.login-logo -->
            <div class="card">
                <div class="card-header text-center p-0">
                    <a href="#" class="h1"
                        ><img src="/images/logo.png" class="w-100"
                    /></a>
                </div>
                <div class="card-body">
                    <form action="" method="post" autocomplete="off">
                        <div class="input-group mb-3">
                            <input
                                type="text"
                                class="form-control"
                                :class="{'is-invalid' : form.errors().has('number')}"
                                placeholder="社員No"
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
                        <div class="row d-flex justify-content-center mb-3">
                            <!-- /.col -->
                            <div class="col-4">
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
                    <p class="mb-1">
                        <a href="#" data-toggle="tooltip" data-placement="top" title="電話番号は＊＊＊＊＊です。">ログイン情報をお忘れですか？</a>
                    </p>

                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
    </div>
</template>
<script>
import form from 'vuejs-form';
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
                'number.required': 'Please input number',       // need trans
                'password.required': 'Please input password'    // need trans
            }),
            enableLogin: true,
            loginTime: null,
            loginTimeInterval: null,
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
            this.$router.push('/');
            // if (this.actionLoading || !this.enableLogin) return;
            // if (this.form.validate().errors().any()) return;
            // this.setActionLoading();
            // api.post('login', null, this.form.all())
            //     .then(({token, user}) => {
            //         this.unsetActionLoading();
            //         LocalStorage.removeLoginFailure();
            //         LocalStorage.removeLoginTimeStamp();
            //         clearInterval(this.loginTimeInterval);
            //         LocalStorage.saveToken(token);
            //         this.$store.commit('session/setSession', user);
            //         this.$router.push({ name: 'stamp' })
            //     })
            //     .catch(e => {
            //         this.unsetActionLoading();
            //         apiErrorHandler(e);
            //         LocalStorage.saveLoginFailure();
            //         LocalStorage.saveLoginTimeStamp();
            //         if(LocalStorage.getLoginFailure() >= 5) {
            //             this.enableLogin = false;
            //             this.loginTimeInterval = setInterval(this.enableLoginTimer, 1000);
            //         }
            //     });
        },
        onFormChange() {
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
