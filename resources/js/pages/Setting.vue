<template>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        設定管理
                    </div>
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-square-full"></i>
                            <div class="font-size-25 ml-2">端数設定</div>
                        </div>
                        <br>
                        <div class="form-group">
                            <div class="form-row align-items-center">
                                <div>出勤時間［</div>
                                <input type="text" class="fixed-width-30" v-model="form.fractionCommutingTime" @change="errors.fractionCommutingTime = null"/>
                                <div>］ 分</div>
                                <div class="d-flex align-items-center ml-5 mr-2">
                                    <!-- <input type="radio" name="shukkin-minutes" value="1" v-model="form.roundUpCommutingTime"/> -->
                                    <div class="mx-1">切り上げ</div>
                                </div>
                            </div>
                            <div>
                                <span v-if="errors.fractionCommutingTime" class="error invalid-feedback d-block">
                                    {{ errors.fractionCommutingTime }}
                                </span>
                            </div>
                            <br>
                            <div class="form-row align-items-center">
                                <div>退勤時間［</div>
                                <input type="text" class="fixed-width-30" v-model="form.fractionLeaveTime" @change="errors.fractionLeaveTime = null"/>
                                <div>］ 分</div>

                                <div class="d-flex align-items-center ml-5 mr-2">
                                    <!-- <input type="radio" name="taikin-minutes" value="1" v-model="form.truncateLeaveTime"/> -->
                                    <div class="mx-1">切り捨て</div>
                                </div>
                            </div>
                            <div>
                                <span v-if="errors.fractionLeaveTime" class="error invalid-feedback d-block">
                                    {{ errors.fractionLeaveTime }}
                                </span>
                            </div>
                            <br>
                            <div class="form-row align-items-center">
                                <div>遅刻時間［</div>
                                <input type="text" class="fixed-width-30" v-model="form.fractionBehindTime" @change="errors.fractionBehindTime = null"/>
                                <div>］ 分</div>
                                <div class="d-flex align-items-center ml-5 mr-2">
                                    <!-- <input type="radio" name="chikoku-minutes" value="1" v-model="form.roundUpBehindTime"/> -->
                                    <div class="mx-1">切り上げ</div>
                                </div>
                            </div>
                            <div>
                                <span v-if="errors.fractionBehindTime" class="error invalid-feedback d-block">
                                    {{ errors.fractionBehindTime }}
                                </span>
                            </div>
                            <br>
                            <div class="form-row align-items-center">
                                <div>早退時間［</div>
                                <input type="text" class="fixed-width-30" v-model="form.fractionLeaveEarly" @change="errors.fractionLeaveEarly = null"/>
                                <div>］ 分</div>

                                <div class="d-flex align-items-center ml-5 mr-2">
                                    <!-- <input type="radio" name="sotai-minutes" value="1" v-model="form.truncateLeaveEarly"/> -->
                                    <div class="mx-1">切り捨て</div>
                                </div>
                            </div>
                            <div>
                                <span v-if="errors.fractionLeaveEarly" class="error invalid-feedback d-block">
                                    {{ errors.fractionLeaveEarly }}
                                </span>
                            </div>
                        </div>
                        <br>
                        <div class="d-flex align-items-center">
                            <i class="fas fa-square-full"></i>
                            <div class="font-size-25 ml-2">連勤設定</div>
                        </div>
                        <br>
                        <div class="form-group">
                            <div class="form-row align-items-center">
                                <div>日曜日を起算日として［</div>
                                <input type="text" class="fixed-width-80" placeholder="７" v-model="form.consecutiveWork" @change="errors.consecutiveWork = null"/>
                                <div>］日目を手当対象日とする。</div>
                            </div>
                        </div>
                        <div>
                            <span v-if="errors.consecutiveWork" class="error invalid-feedback d-block">
                                {{ errors.consecutiveWork }}
                            </span>
                        </div>
                        <div class="float-right mt-5">
                            <button class="btn btn-primary float-right" type="button" @click="onSubmit">
                                保存
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import api, { apiErrorHandler } from '../global/api'
import { showSuccess } from '../helpers/error';
import actionLoading from '../mixin/actionLoading';
export default {
    mixins: [actionLoading],
    data() {
        return {
            form: {
                fractionCommutingTime: null,
                fractionLeaveTime: null,
                fractionBehindTime: null,
                fractionLeaveEarly: null,
                roundUpCommutingTime: 1,
                truncateLeaveTime: 1,
                roundUpBehindTime: 1,
                truncateLeaveEarly: 1,
                consecutiveWork: null,
            },
            errors: {
                fractionCommutingTime: null,
                fractionLeaveTime: null,
                fractionBehindTime: null,
                fractionLeaveEarly: null,
                roundUpCommutingTime: null,
                truncateLeaveTime: null,
                roundUpBehindTime: null,
                truncateLeaveEarly: null,
                consecutiveWork: null,
            }
        }
    },
    mounted() {
        this.fetchSetting();
    },
    methods: {
        onSubmit() {
            if (this.actionLoading) return;
            if (!this.validate()) return;
            this.setActionLoading();
            api.post('setting', null, this.form)
                .then(() => {
                    this.unsetActionLoading();
                    showSuccess(this.$t("Successfully saved"));
                })
                .catch(e => {
                    apiErrorHandler(e);
                    this.unsetActionLoading();
                });
        },
        fetchSetting() {
            if (this.actionLoading) return;
            this.setActionLoading();
            api.get('setting')
                .then(response => {
                    this.unsetActionLoading();
                    this.form = response;
                })
                .catch(e => {
                    this.unsetActionLoading();
                    apiErrorHandler(e)
                });
        },
        validate() {
            let valid = true;
            if (this.form.fractionCommutingTime !== 0 && !this.form.fractionCommutingTime ) {
                valid = false;
                this.errors.fractionCommutingTime = this.$t("Please input fraction commuting time");
            }
            if (this.form.fractionCommutingTime > 60 || this.form.fractionCommutingTime < 0) {
                valid = false;
                this.errors.fractionCommutingTime = this.$t("Please input valid fraction commuting time");
            }

            if (this.form.fractionLeaveTime !== 0 && !this.form.fractionLeaveTime) {
                valid = false;
                this.errors.fractionLeaveTime = this.$t("Please input fraction leave time");
            }
            if (this.form.fractionLeaveTime > 60 || this.form.fractionLeaveTime < 0) {
                valid = false;
                this.errors.fractionLeaveTime = this.$t("Please input valid fraction leave time");
            }

            if (this.form.fractionBehindTime !== 0 && !this.form.fractionBehindTime) {
                valid = false;
                this.errors.fractionBehindTime = this.$t("Please input fraction behind time");
            }
            if (this.form.fractionBehindTime > 60 || this.form.fractionBehindTime < 0) {
                valid = false;
                this.errors.fractionBehindTime = this.$t("Please input valid fraction behind time");
            }

            if (this.form.fractionLeaveEarly !== 0 && !this.form.fractionLeaveEarly) {
                valid = false;
                this.errors.fractionLeaveEarly = this.$t("Please input fraction leave early time");
            }
            if (this.form.fractionLeaveEarly > 60 || this.form.fractionLeaveEarly < 0) {
                valid = false;
                this.errors.fractionLeaveEarly = this.$t("Please input valid fraction leave early time");
            }
            if (!this.form.consecutiveWork) {
                valid = false;
                this.errors.consecutiveWork = this.$t("Please input consecutive work");
            }
            if (this.form.consecutiveWork < 1 || this.form.consecutiveWork > 7) {
                valid = false;
                this.errors.consecutiveWork = this.$t("Please input valid consecutive work value");
            }
            return valid;
        },
    }
}
</script>
