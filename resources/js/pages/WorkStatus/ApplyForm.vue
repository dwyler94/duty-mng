<template>
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">申請</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="form-group">
                <i class="fas fa-square-full"></i>
                <label>申請者</label>
                <div>{{userName}}</div>
            </div>
            <div class="form-group">
                <i class="fas fa-square-full"></i>
                <label>申請日時</label>
                <div>{{getAppDate()}}</div>
            </div>
            <div class="form-group">
                <i class="fas fa-square-full"></i>
                <label>修正時間</label>
                <div>{{getAppName()}}</div>
                <div class="form-row align-items-center">
                    <div class="col-md-1 mr-2">
                        <div>{{getTime(application.timeBeforeCorrection)}}</div>
                    </div>
                    <div class="form-control-label">⇒</div>
                    <div class="col-md-1">
                        <div>{{getTime(application.timeAfterCorrection)}}</div>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <i class="fas fa-square-full"></i>
                <label>申請理由</label>
                <div>{{application.reason}}</div>
            </div>

        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-primary" @click="approve()" v-if="!application.isApproved && !application.isRejected">承認</button>
            <button type="submit" class="btn btn-warning" @click="reject()"  v-if="!application.isApproved && !application.isRejected">却下</button>
        </div>
    </div>
</template>
<script>

import moment from 'moment-timezone';
import { mapState } from 'vuex';
import api, { apiErrorHandler } from '../../global/api';
import actionLoading from '../../mixin/actionLoading';
import { showSuccess } from '../../helpers/error';

    export default {
        mixins: [actionLoading],
        props: {
            application: {},
            userName: '',
        },
        computed: {
            ...mapState({
                applicationClasses: state => state.constants.applicationClasses
            }),
        },
        watch: {

        },
        data() {
            return {
                errors: {
                },
                offices: [],
            }
        },
        methods: {
            approve() {
                if (this.actionLoading) return;
                // if (!this.validate()) return;
                this.setActionLoading();
                let request;
                request = api.put('application/approve/' + this.application.id);
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
            getAppName() {
                let app;
                app = this.applicationClasses.find(app => app.id === this.application.applicationClassId);
                if(app) {
                    return app.name;
                }
                return null;
            },
            getAppDate() {
                return moment(this.application.applicationDatetime).format("MM月DD日 HH:mm");
            },
            getTime(time) {
                return moment(time).tz('Asia/Tokyo').format('HH:mm');
            },
            reject() {
                if (this.actionLoading) return;
                // if (!this.validate()) return;
                this.setActionLoading();
                let request;
                request = api.put('application/reject/' + this.application.id);
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
                return valid;
            },
        }
    }
</script>
