<template>
    <div class="mail-history-table">
        <div class="d-flex w-100 mail-row" v-for="mailHistory in mailHistories" :key="mailHistory.id">
            <div class="thead p-2 border border-top-0 border-white" style="flex:1; ">日時</div>
            <div class="tbody p-2 border border-top-0 border-left-0 border-white" style="flex:3;">
                {{ mailHistory.createdAt }}
            </div>
            <div class="thead p-2 border border-top-0 border-left-0 border-white" style="flex:1;">件名</div>
            <div class="tbody p-2 border border-top-0 border-left-0 border-white" style="flex:6;">
                {{ mailHistory.subject }}
            </div>
        </div>
    </div>
</template>

<script>
import moment from 'moment';
import api, { apiErrorHandler } from '../../global/api';

export default {
    props: {
        childId: Number | String
    },
    data() {
        return {
            mailHistories: []
        }
    },
    mounted() {
        this.fetchData();
    },
    methods: {
        fetchData() {
            api.get(`child/${this.childId}/mail-history`)
            .then(response => {
                this.mailHistories = response
                this.mailHistories.forEach(item => {
                    if (item.createdAt)
                    {
                        item.createdAt = moment(item.createdAt).format('YYYY-MM-DD HH:mm:ss')
                    }
                })
            })
            .catch(apiErrorHandler)
        }
    }
}
</script>

<style scoped>
.mail-history-table {
    margin-top: 20px;
}
.thead {
    background: #a89557;
}
.tbody {
    background: #dcd5bc;
}
.mail-row > div{
    border-width: 2px !important;
}
</style>
