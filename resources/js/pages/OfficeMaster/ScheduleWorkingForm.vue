<template>
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title"></h5>
            <!-- <h5 class="modal-title" v-show="editmode">再申請</h5> -->
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <ul class="nav nav-tabs" id="custom-content-below-tab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="this-year-tab"
                        data-toggle="pill"
                        href="#this-year-table"
                        role="tab"
                        aria-controls="this-year-table"
                        aria-selected="true"
                        @click="onTabChange('current')"
                    >
                        本年度
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link"
                        id="next-year-tab"
                        data-toggle="pill"
                        href="#next-year-table"
                        role="tab"
                        aria-controls="next-year-table"
                        aria-selected="false"
                        @click="onTabChange('next')"
                    >
                        来年度
                    </a>
                </li>
            </ul>
            <div class="tab-content" id="custom-content-below-tabContent">
                <div class="tab-pane fade show active" id="this-year-table" role="tabpanel" aria-labelledby="this-year-tab">
                    <div class="table-responsive p-0">
                        <table
                            class="table table-bordered table-striped table-master table-hover"
                        >
                            <thead class="text-center">
                                <tr class="dark-grey text-white">
                                    <th style="width: 25%;">
                                        年月
                                    </th>
                                    <th style="width: 50%;">
                                        所定労働日数
                                    </th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody class="text-center">
                                <schedule-working-item v-for="(schedule, index) in current" :key="index + '_' + officeId"
                                    :officeId="officeId"
                                    :schedule.sync="current[index]"
                                    :index="index"
                                    :current="1"
                                    v-on:onEditMode="onEditMode"/>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="tab-pane fade" id="next-year-table" role="tabpanel" aria-labelledby="next-year-tab">
                    <div class="table-responsive p-0">
                        <table
                            class="table table-bordered table-striped table-master table-hover"
                        >
                            <thead class="text-center">
                                <tr class="dark-grey text-white">
                                    <th style="width: 25%;">
                                        年月
                                    </th>
                                    <th style="width: 50%;">
                                        所定労働日数
                                    </th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody class="text-center">
                                <schedule-working-item v-for="(schedule, index) in next" :key="index + '_' + officeId"
                                    :officeId="officeId"
                                    :schedule.sync="next[index]"
                                    :index="index"
                                    :current="2"
                                    v-on:onEditMode="onEditMode"/>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">閉じる</button>
            <button type="button" class="btn btn-primary" @click="onSubmit" :disabled="editStatus === true">登録</button>
        </div>
    </div>
</template>
<script>
import api, { apiErrorHandler } from '../../global/api';
import { showSuccess } from '../../helpers/error';
import actionLoading from '../../mixin/actionLoading';
import ScheduleWorkingItem from './ScheduleWorkingItem.vue'
export default {
    mixins: [actionLoading],
  components: { ScheduleWorkingItem },
    props: {
        officeId: null,
        current: [],
        next: [],

    },
    data() {
        return {
            tab: 'current',
            currentEditStatus: [],
            nextEditStatus: [],
            editStatusError: '',
            editStatus: false,
        }
    },
    methods: {
        onTabChange(tab) {
            this.tab = tab;
        },
        onSubmit() {
            if (this.actionLoading) return;
            let data = this.tab === 'current' ? this.current : this.next;
            this.setActionLoading();
            api.post('office-master/' + this.officeId + '/scheduled-working', null, {
                yearId: data[0].yearId,
                schedules: data,
            })
            .then(() => {
                this.unsetActionLoading();
                showSuccess(this.$t('Successfully saved'));
            })
            .catch(e => {
                apiErrorHandler(e);
                this.unsetActionLoading();
            });
        },
        onEditMode(index, current, editMode) {
            if (current == 1)
                this.currentEditStatus[index] = editMode;
            else
                this.nextEditStatus[index] = editMode;

            const count1 = this.currentEditStatus.filter(Boolean).length;
            const count2 = this.nextEditStatus.filter(Boolean).length;

            if(count1 + count2 > 0) {
                this.editStatus = true;
            } else {
                this.editStatus = false;
            }
        }
    }
}
</script>
