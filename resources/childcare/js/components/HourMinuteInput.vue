<template>
    <div class="home-minute-input-container">
        <div class="home-minute-input-wrapper">
            <div class="home-minute-input">
                <input
                    class="form-control"
                    inputmode="numeric"
                    max="23"
                    min="0"
                    :class="{'is-invalid': error && !light, 'is-invalid-light': error && light, 'light': light}"
                    :disabled="disabled"
                    :type="type"
                    :value="hour"
                    @input="inputHour"
                />
            </div>
            :
            <div class="home-minute-input">
                <input
                    class="form-control"
                    inputmode="numeric"
                    max="59"
                    min="0"
                    :class="{'is-invalid': error && !light, 'is-invalid-light': error && light, 'light': light}"
                    :disabled="disabled"
                    :type="type"
                    :value="minute"
                    @input="inputMinute"
                />
            </div>
        </div>
        <div v-if="error" class="home-minute-input-error">
            {{ error }}
        </div>
    </div>
</template>
<script>
export default {
    props: {
        value: String,
        error: String,
        type: {
            type: String,
            default: 'number'
        },
        disabled: {
            type: Boolean,
            default: false
        },
        light: {
            type: Boolean,
            default: false
        }
    },
    computed: {
        hour() {
            if (!this.value) return '';
            let arr = this.value.split(':');
            return arr[0];
        },
        minute() {
            if (!this.value) return '';
            let arr = this.value.split(':');
            return arr[1];
        }
    },
    methods: {
        inputHour(e) {
            e.target.value = e.target.value.slice(0, 2);
            let hour = e.target.value;
            if (!hour && !this.minute) {
                this.$emit('input', '');
                return;
            }
            this.$emit('input', hour + ':' + this.minute);
        },
        inputMinute(e) {
            e.target.value = e.target.value.slice(0, 2);
            let minute = e.target.value;
            if (!minute && !this.hour) {
                this.$emit('input', '');
                return;
            }
            this.$emit('input', this.hour + ':' + minute);
        }
    }
}
</script>
<style scoped>
.home-minute-input-container {
    display: flex;
    flex-direction: column;
    column-gap: 5px;
}
.home-minute-input-wrapper {
    align-items: center;
    display: flex;
}
.home-minute-input {
    margin-right: 5px;
    margin-left: 5px;
}
.home-minute-input-error {
    color: #e3342f;
    font-size: 80%;
    margin-top: 0.25rem;
    text-align: center;
}
.light {
    padding: 0.2rem 0.2rem;
    text-align: center;
}
input {
    min-width: 44px;
}
</style>
