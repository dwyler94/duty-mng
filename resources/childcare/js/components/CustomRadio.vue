<template>
    <div class="custom-radio-group">
        <label class="form-check-label" :class="{selected: value == optionValue}" v-for="(optionValue, label, index) in options" :key="index">
            <input :name="name" type="radio" :value="optionValue" @click="click(optionValue)">
            {{ label }}
        </label>
    </div>
</template>

<script>
export default {
    methods: {
        click(value) {
            if (this.value != value) {
                this.$emit('input', value);
            } else {
                this.$emit('input', null);
            }
        },
    },
    props: {
        name: String,
        options: Object,
        value: Number,
    }
}
</script>

<style lang="scss" scoped>
.parent-skin .custom-radio-group label {
    &.selected {
        background-color: #8bb3fc;
        color: #fff;
    }
}

.custom-radio-group {
    display: flex;
    justify-content: center;

    input {
        display: none;
    }

    label {
        background-color: #fff;
        padding: 7px 0;
        position: relative;
        text-align: center;
        width: 100%;

        &:not(:last-child)::after {
            background-color: #ced4da;
            content: "";
            height: 100%;
            right: 0;
            position: absolute;
            top: 0;
            width: 2px;
        }

        &:first-child {
            border-radius: 5px 0 0 5px;
        }

        &:last-child {
            border-radius: 0 5px 5px 0;
        }

        &.selected {
            background-color: #ebcb42;
            color: #fff;
        }
    }
}
</style>
