<template>
    <tr>
        <td>{{ schedule.year }}年 {{ schedule.month }}月</td>
        <td >
            <span v-if="!editMode && schedule.days">{{ schedule.days }}日</span>
            <input v-if="editMode" type="text" v-model="days" :class="{'is-invalid' : error}"/>
            <span v-if="editMode && error" class="error invalid-feedback">
                {{ error }}
            </span>
        </td>
        <td>
            <a v-if="!editMode" href="#" class="mx-2" @click="onEditClick">
                <i class="far fa-edit fa-lg"></i>
            </a>
            <div v-else class="d-flex justify-content-center mt-1">
                <a href="#" class="mx-2" @click="onOk">
                    <i class="far fa-check-circle fa-lg"></i>
                </a>
                <a href="#" class="mx-2" @click="onCancel">
                    <i class="far fa-window-close fa-lg"></i>
                </a>
            </div>
        </td>
    </tr>
</template>
<script>
import api from '../../global/api';
export default {
    props: {
        officeId: null,
        schedule: {},
        index: null,
        current: null,
    },
    data () {
        return {
            editMode: false,
            days: null,
            error: '',
        }
    },
    watch: {
        ['editMode'] : function() {
            // if (this.editMode) {
                this.$emit('onEditMode', this.index, this.current, this.editMode);
            // }
        }
    },
    mounted() {
        this.days = this.schedule.days;
    },
    methods: {
        onEditClick() {
            this.editMode = !this.editMode;
            if (this.editMode) {
                this.days = this.schedule.days;
            }
        },
        onOk() {
            if (!this.days) {
                this.error = this.$t('Please input days');
                return
            }
            const monthDates = this.getDaysOfMonth();
            if (this.days < 1 || this.days > 31 || this.days > monthDates) {
                this.error = this.$t('Please input valid days');
                return
            }
            this.schedule.days = this.days;
            this.editMode = false;
        },
        onCancel() {
            this.editMode = false;
            this.days = null;
            this.error = '';
        },
        getDaysOfMonth() {
            return new Date(this.schedule.year, this.schedule.month, 0).getDate();
        }
    }
}
</script>
