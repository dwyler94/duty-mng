<template>
    <div>
    <div class="d-flex justify-content-center">
        <div>
            <select class="form-control" v-model="selectedYear" @change="selectYear"
            :class="{'is-invalid' : error && !light, 'is-invalid-light': error && light}">
                <option v-for="(year, number) in years" :key="number" :value="year">{{year}}</option>
            </select>
        </div>
        <span class="p-1">年</span>
        <div>
            <select class="form-control" v-model="selectedMonth" @change="selectMonth"
            :class="{'is-invalid' : error && !light, 'is-invalid-light': error && light}">
                <option v-for="(month, number) in months" :key="number" :value="month">{{month}}</option>
            </select>
        </div>
        <span class="p-1">月</span>
        <div>
            <select class="form-control" v-model="selectedDay" @change="selectDay"
            :class="{'is-invalid' : error && !light, 'is-invalid-light': error && light}">
                <option v-for="(day, number) in days" :key="number" :value="day">{{day}}</option>
            </select>
        </div>
        <span class="p-1">日</span>
    </div>
    <div v-if="error" class="home-minute-input-error">
        {{ error }}
    </div>
    </div>
</template>
<script>
import moment from 'moment';
export default {
    props: {
        value: String,
        error: String,
        type: {
            type: String,
            default: 'number'
        },
        // disabled: {
        //     type: Boolean,
        //     default: false
        // },
        light: {
            type: Boolean,
            default: false
        }
    },
    data() {
        return {
            years: [],
            months: ['01','02','03','04','05','06','07','08','09','10','11','12'],
            days: [],
        }
    },
    // watch: {
    //     ['value']: function(){
    //         if (!this.value) {
    //         this.selectedYear = '';
    //         this.selectedMonth = '';
    //         this.selectedDay = '';
    //         } else {
    //             let arr = this.value.split('-');
    //             this.selectedYear = arr[0];
    //             this.selectedMonth = arr[1];
    //             this.selectedDay = arr[2];
    //         }
    //     }
    // },
    computed: {
        selectedYear: {
            get: function() {
                if (!this.value) return '';
                let arr = this.value.split('-');
                return arr[0];
            },
            set: function(value) {
                if(!value) {
                    return value;
                }
            }
        },
        selectedMonth: {
            get: function() {
                if (!this.value) return '';
                let arr = this.value.split('-');
                return arr[1];
            },
            set: function(value) {
                if(!value) {
                    return value;
                }
            }
        },
        selectedDay: {
            get: function() {
                if (!this.value) return '';
                let arr = this.value.split('-');
                return arr[2];
            },
            set: function(value) {
                if(!value) {
                    return value;
                }
            }
        }
    },
    methods: {
        selectYear(e) {
            let year = e.target.value;
            const dayCount = moment(year + "-" + this.selectedMonth, "YYYY-MM").daysInMonth();
            this.days = [];
            for (let count = 1; count <= dayCount; count++) {
                this.days.push(('0' + count).slice(-2));
            }
            this.$emit('input', year + '-' + this.selectedMonth + '-' + this.selectedDay);
        },
        selectMonth(e) {
            let month = e.target.value;
            const dayCount = moment(this.selectedYear + "-" + month, "YYYY-MM").daysInMonth();
            this.days = [];
            for (let count = 1; count <= dayCount; count++) {
                this.days.push(('0' + count).slice(-2));
            }
            this.$emit('input', this.selectedYear + '-' + month + '-' + this.selectedDay);
        },
        selectDay(e) {
            let day = e.target.value;
            this.$emit('input', this.selectedYear + '-' + this.selectedMonth + '-' + day);
        }
    },
    mounted() {
        for(let year = 2016; year <= 2050; year++) {
            this.years.push(year);
        }
        for(let day = 1; day<= 31; day++) {
            this.days.push(('0' + day).slice(-2));
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
    display: flex;
}
.home-minute-input {
    margin-right: 5px;
    margin-left: 5px;
}
.home-minute-input-error {
    margin-top: 5px;
    color: #f00;
}
</style>
