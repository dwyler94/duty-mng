<template>
    <div class="container">
        <br>
        <br>
        <div class="card">
            <div class="card-header calendar-title">
                <h3 class="card-title mb-0">{{ officeName }}</h3>
            </div>
            <div class="card-body">
                <div class="">
                    <table class="table table-bordered table-striped table-children table-head-fixed table-hover">
                        <thead>
                            <tr class="text-center">
                                <th>送信日時</th>
                                <th>送信先</th>
                                <th>送信数</th>
                                <th>件名</th>
                                <th>本文</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="mail in mails" :key="mail.id">
                                <td>{{getCreatedAt(mail.createdAt)}}</td>
                                <td>{{mail.officeName}} {{mail.childName ? mail.childName : getChildClassName(mail.childrenClassId)}}</td>
                                <td>{{mail.cnt}}</td>
                                <td>{{mail.subject}}</td>
                                <!-- <td>{{mail.content}}</td> -->
                                <td><a href="javascript:void(0)" @click="viewMailContent(mail)">確認</a></td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="pager-wrapper">
                        <pagination v-model="pager.current" :records="pager.total" :per-page="pager.size" :options="{texts: pager.texts}" @paginate="getMails"/>
                        <div class="pager-per-page">
                            <select class="form-control" @change="onPerPageChange">
                                <option v-for="(page, index) in perPages" :key="index" :value="page">{{ page }}</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal fade" id="mail-view-form" tabindex="-1" role="dialog" aria-labelledby="mail-view-form" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <view-form :mailContent="mailContent"></view-form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>

import moment from 'moment';
import { mapState } from 'vuex';
import api, { apiErrorHandler } from '../global/api';
import actionLoading from '../mixin/actionLoading';
import { showSuccess } from '../helpers/error';
import ViewForm from './Mail/ViewForm.vue';
import Pagination from 'vue-pagination-2';

export default {
    components: {Pagination, ViewForm},
    mixins: [actionLoading],
    data() {
        return {
            mails: [],
            pager: {
                current: 1,
                size: 10,
                total: 100,
                texts: {
                    count: '{from} から {to} / 全体　{count}||',
                    first: '&laquo;前へ',
                    last: '次へ &raquo;'
                },
            },
            perPages: [
                10, 20, 50, 100
            ],
            mailContent: {}
        }
    },
    computed: {
        ...mapState({
            session: state =>  state.session.info,
            childrenClasses: state => state.constants.childrenClasses,
            officeName: state =>  {
                if (state.session.info.office) return state.session.info.office.name;
                return '';
            }
         }),
    },
    methods: {
        getMails(page = 1) {
            this.setActionLoading();
            const query = {page, perPage: this.pager.size};
            api.get('mail-history', null, query)
            .then(response => {
                this.unsetActionLoading();
                this.mails = response.data;
                this.pager.current = response.currentPage;
                this.pager.total = parseInt(response.total);
                this.pager.size = parseInt(response.perPage);
            })
            .catch(e => {
                apiErrorHandler(e);
                this.unsetActionLoading();
            })
        },
        onPerPageChange(e) {
            this.pager.size = parseInt(e.target.value);
            this.getMails();
        },
        viewMailContent(viewMailContent) {
            this.mailContent = viewMailContent;
            $("#mail-view-form").modal('show');
        },
        getCreatedAt(createdAt) {
            return moment(createdAt).format("YYYY-MM-DD HH:mm");
        },
        getChildClassName(classId) {
            if(!classId) return null;
            const childClass = this.childrenClasses.find(item => classId == item.id);
            if(childClass) return childClass.name;
            return null;
        }
    },
    mounted() {
        this.getMails();
    }
};
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
