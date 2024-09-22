<template>
    <section class="content">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header calendar-title">
                            <h3 class="card-title mb-0">{{ officeName }}</h3>
                        </div>
                        <div class="card-body" style="background-color:#F9BFC8">
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
                                    <input type="text" class="form-control" id="inputEmail3" placeholder="【緊急連絡】大事なお知らせです。必ずお読み下さい。">
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-1"></div>
                                <div class="col-md-6">
                                    <textarea class="form-control" rows="20">
保護者様各位
保育園からのお知らせです。
----------------------------------------------
保育園
　仙台市青葉区花京院2-1
　TEL:022-797-7717
　FAX:022-797-7718
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

export default {
    data() {
        return {

        }
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
        }
    }
};
</script>
