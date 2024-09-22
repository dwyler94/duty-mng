<template>
    <section class="content">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header calendar-title">
                            <h3 class="card-title mb-0">{{ officeName }}</h3>
                        </div>
                        <div class="card-body" style="background-color: #F7EAB4">
                            <div class="form-group row">
                                <div class="col-md-1"></div>
                                <label for="inputEmail3" class="col-form-label col-md-1 text-justify-content">宛先</label>
                                <div class="col-md-5">
                                    <input type="text" class="form-control" id="inputEmail3" placeholder="保育園保護者宛メール">
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-1"></div>
                                <label for="inputEmail3" class="col-form-label col-md-1 text-justify-content">件名</label>
                                <div class="col-md-5">
                                    <input type="text" class="form-control" id="inputEmail3" placeholder="">
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-1"></div>
                                <div class="col-md-6">
                                    <textarea class="form-control" rows="20" v-model="content">
                                    </textarea>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-2"></div>
                                <div class="col-md-5">
                                    <button class="btn btn-primary float-right" @click="sendMail">送信</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>
<script>
import { mapState } from 'vuex';
import api, { apiErrorHandler } from '../global/api';
import actionLoading from '../mixin/actionLoading';

export default {
    mixins: [actionLoading],
    data() {
        return {
            content: ''
        }
    },
    mounted() {
        this.fetchData();
    },
    computed: {
        ...mapState({
            officeName: state =>  {
                if (state.session.info.office) return state.session.info.office.name;
                return '';
            }
        }),
    },
    methods: {
        sendMail() {
            if (!confirm(this.$t("Are you sure you want to send mail?"))) return;
        },
        fetchData() {
            this.setActionLoading();
            api.get('mail-template', null, {type: 0})
            .then((response) => {
                this.content = response.content;
            })
            .catch(apiErrorHandler)
            .finally(() => {
                this.unsetActionLoading();
            })
        }
    }
};
</script>
