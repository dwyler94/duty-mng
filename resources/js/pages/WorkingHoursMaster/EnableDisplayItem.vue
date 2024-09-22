<template>
    <div class="d-flex align-items-center justify-content-center">
        <input type="radio" v-model="enabled" :value="1">
        <label class="mr-2 mb-0">表示</label>
        <input type="radio" v-model="enabled" :value="0">
        <label class="mr-2 mb-0">非表示</label>
    </div>
</template>
<script>
import api, { apiErrorHandler } from '../../global/api';
import actionLoading from '../../mixin/actionLoading';
import { showSuccess } from '../../helpers/error';

    export default {
        mixins: [actionLoading],
        props: {
            enable: true,
            id: null,
        },
        data() {
            return {
                enabled: this.enable,
            }
        },
        watch: {
            'enabled': 'setStatus'
        },
        methods: {
            setStatus(value, oldValue) {
                if(value == oldValue) return;
                if (this.actionLoading) return;
                this.setActionLoading();
                api.put('working-hours/status/' + this.id, null, {enable: this.enabled})
                    .then(() => {
                        this.unsetActionLoading();
                        showSuccess(this.$t("Successfully saved"));
                        this.$emit('success');
                    })
                    .catch(e => {
                        apiErrorHandler(e);
                        this.unsetActionLoading();
                    });
            }
        }
    }
</script>
