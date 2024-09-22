<template>
    <div class="container-fluid has-fixed-btn parent-skin">
        <div v-if="inputError" class="error invalid-feedback error-top">
            {{ $t("Input error") }}
        </div>
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="align-items-center col-md-6 col-12 d-flex flex-column flex-md-row justify-content-center justify-content-md-start pb-1">
                                <h5 class="card-title mb-0 pr-5">{{ currentOfficeName }}</h5>
                            </div>
                            <div class="align-items-center col-md-6 col-12 d-flex justify-content-center justify-content-md-start">
                                <div class="d-flex align-items-center p-0">
                                    <datepicker
                                        ref="programaticOpen"
                                        v-model="selectedDate"
                                        :disabled-dates="disabledDates"
                                        :format="customFormatter"
                                        :language="ja"
                                        :placeholder="todayDate"
                                        @selected="getContact"
                                    >
                                    </datepicker>
                                    <button class="btn btn-outline btn-sm mx-0" type="button" @click="openDatePicker()">
                                        <i class="fas fa-calendar-alt fa-2x"></i>
                                    </button>
                                </div>
                                <div class="align-items-center d-flex pl-1 pl-md-3">
                                    <div class="form-label mr-2" for="weatherStauts">天気</div>
                                    <input
                                        class="fixed-width-80 form-control px-1"
                                        id="weatherStauts"
                                        type="text"
                                        v-model="formData.weather"
                                        :class="{'is-invalid' : errors.weather}"
                                        @change="dataChanged = true; errors.weather = null; inputError = false;"
                                    />
                                </div>
                                <span class="error invalid-feedback" v-if="errors.weather">
                                    {{ errors.weather }}
                                </span>
                            </div>
                            <div class="align-items-center col-md-6 col-12 d-flex"></div>
                            <div class="align-items-center col-md-6 col-12 d-flex">
                                <div class="calendar-left flex-grow-1 justify-content-center justify-content-md-start">
                                    <button class="btn btn-outline btn-sm" type="button" @click="onPrev">
                                        <i class="fas fa-caret-left fa-2x"></i>
                                    </button>
                                    <!-- <div class="mx-2">{{ displayDate }}</div> -->
                                    <button class="btn btn-outline-primary btn-sm mx-2" type="button" @click="onCurrentMonth">
                                        今日
                                    </button>
                                    <button class="btn btn-outline btn-sm" type="button" @click="onNext">
                                        <i class="fas fa-caret-right fa-2x"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="align-items-center col-md-5 col-12 d-flex mb-2">
                                <label class="mb-0" for="parentname" style="min-width: 100px;">記入者 保護者様名：</label>
                                <div class="d-flex flex-column justify-content-center" style="width: calc(90% - 130px);">
                                    <input
                                        class="form-control"
                                        id="parentname"
                                        type="text"
                                        v-model="formData.guardian"
                                        :class="{'is-invalid': errors.guardian}"
                                        @change="dataChanged = true; errors.guardian = null; inputError = false;"
                                    />
                                    <span class="error invalid-feedback" v-if="errors.guardian">
                                        {{ errors.guardian }}
                                    </span>
                                </div>
                            </div>
                            <div class="align-items-center d-flex col-md-5 col-12 mb-2">
                                <label class="mb-0" for="mindername" style="min-width: 80px;">保育士名：</label>
                                {{ formData.nurseName }}
                            </div>
                        </div>
                        <div class="row guardian-input-area" style="padding-left: 15px; padding-right: 15px;">
                            <div class="align-items-center border border-white col-md-2 col-3 dark-pink d-flex justify-content-center text-white">機嫌</div>
                            <div class="border border-white col-md-4 col-9 light-pink p-2 text-center">
                                <custom-radio
                                    name="radio1"
                                    :options="{'普通': 1, '良い': 2, '悪い': 3}"
                                    v-model="formData.mood"
                                    @input="dataChanged = true;"
                                ></custom-radio>
                            </div>
                            <div class="align-items-center border border-white col-md-2 col-3 dark-pink d-flex justify-content-center text-white">検温</div>
                            <div class="align-items-center border border-white col-md-2 col-5 d-flex justify-content-center light-pink py-2">
                                <hour-minute-input
                                    v-model="formData.temperatureTimeStd"
                                    :error="errors.temperatureTimeStd"
                                    @input="dataChanged = true; errors.temperatureTimeStd = null;inputError = false;"
                                />
                            </div>
                            <div class="border border-white col-md-2 col-4 light-pink guardian-input-temp">
                                <input
                                    class="form-control mr-md-1 mr-0"
                                    max="42"
                                    min="32"
                                    type="number"
                                    v-model="formData.temperatureStd" @change="dataChanged = true; errors.temperatureStd = null;inputError = false;"
                                    :class="{'is-invalid': errors.temperatureStd}"
                                />℃
                                <div v-if="errors.temperatureStd"  class="error invalid-feedback w-100">
                                    {{ errors.temperatureStd }}
                                </div>
                            </div>

                        </div>
                        <div class="row guardian-input-area" style="padding-left:15px; padding-right:15px;">
                            <div class="align-items-center border border-white col-md-2 col-3 dark-pink d-flex justify-content-center px-0 text-center text-white">お迎え<br class="d-md-none">予定</div>
                            <div class="align-items-center border border-white col-md-4 col-9 d-flex justify-content-center light-pink py-2">
                                <hour-minute-input
                                    v-model="formData.pickUpTime"
                                    :error="errors.pickUpTime"
                                    @input="dataChanged = true; errors.pickUpTime = null; inputError = false;"
                                />
                            </div>
                            <div class="align-items-center border border-white col-md-2 col-3 dark-pink d-flex justify-content-center px-0 text-center text-white">お迎え<br class="d-md-none">の方</div>
                            <div class="border border-white col-md-4 col-9 light-pink py-2 guardian-input-pickup">
                                <input
                                    class="form-control"
                                    style="max-width: 10rem"
                                    type="text"
                                    v-model="formData.pickUpPerson"
                                    :class="{'is-invalid': errors.pickUpPerson}"
                                    @change="dataChanged = true; errors.pickUpPerson = null; inputError = false;"
                                />
                                <span class="error invalid-feedback w-100" v-if="errors.pickUpPerson">
                                    {{ errors.pickUpPerson }}
                                </span>
                            </div>
                        </div>
                        <br>
                        <div class="mobile-tab-group d-md-none">
                            <div class="mobile-tab base" :class="[{'active': tab == BASE}, activeTabClass]" @click="tab = BASE">基本情報</div>
                            <div class="mobile-tab parent" :class="[{'active': tab == PARENT}, activeTabClass]" @click="tab = PARENT">家庭より</div>
                            <div class="mobile-tab school" :class="[{'active': tab == SCHOOL}, activeTabClass]" @click="tab = SCHOOL">保育園より</div>
                        </div>
                        <div class="table-responsive p-0 d-none d-md-block" id="contactbook" :class="{'d-block mt-1 mt-md-0': tab == BASE}">
                            <table class="mb-0 mb-md-3 table table-bordered table-children">
                                <thead class="text-center text-white">
                                    <tr class="dark-brown">
                                        <th class="contactbook-fix dark-brown">時間</th>
                                        <th>睡眠</th>
                                        <th>検温</th>
                                        <th>排便</th>
                                        <th class="d-none d-md-table-cell">食事・その他</th>
                                    </tr>
                                </thead>
                                <tbody class="text-center contactbook-tr">
                                    <template v-for="hour in hours">
                                        <tr v-if="hour.time == 18" :key="hour.time + 'previous_day'" style="background-color:#D9E1F2">
                                            <td colspan="4" class="contact-book-sleep-date contactbook-fix" style="background-color:#D9E1F2">
                                                {{ previousDay() }}
                                            </td>
                                            <td style="background-color:#D9E1F2" class="d-none d-md-table-cell"></td>
                                        </tr>
                                        <tr v-if="hour.time == 24" :key="hour.time + 'current_day'" style="background-color:#D9E1F2">
                                            <td colspan="4" class="contact-book-sleep-date contactbook-fix" style="background-color:#D9E1F2">
                                                {{ currentDay() }}
                                            </td>
                                            <td style="background-color:#D9E1F2" class="d-none d-md-table-cell"></td>
                                        </tr>
                                        <tr :key="hour.time+'hours'" style="background-color: #dcd5bc;">
                                            <td rowspan="2" class="align-middle contactbook-fix contactbook-display-time">{{ hour.time == 24 ? '0' : hour.time }}時</td>
                                            <td class="text-center contact-book-click" @click="setHour(hour.time, 1)">
                                                <div v-if="formData[`sleep${('0' + hour.time).slice(-2) + '00'}School`]" style="background-color: #EBCB42; width:50%; height: 100%; position:absolute;left: 25%;top:0;"></div>
                                                <div v-else-if="formData[`sleep${('0' + hour.time).slice(-2) + '00'}Home`]" style="background-color: #8BB3FC; width:50%; height: 100%; position:absolute;left: 25%;top:0;"></div>
                                            </td>
                                            <td :rowspan="rowspan" class="contactbook-td-temp">
                                                <div class="d-flex justify-content-center is-invalid m-0 m-md-auto">
                                                    <input
                                                        class="form-control p-1 table-input table-input-temp"
                                                        disabled
                                                        max="42"
                                                        min="32"
                                                        type="number"
                                                        v-if="formData[`temperature${('0' + hour.time).slice(-2)}School`]"
                                                        v-model="formData[`temperature${('0' + hour.time).slice(-2)}School`]"
                                                        :class="{'is-invalid': errors[`temperature${('0' + hour.time).slice(-2)}Home`]}"
                                                        @change="dataChanged = true; errors[`temperature${('0' + hour.time).slice(-2)}Home`] = null;inputError = false;"
                                                    />
                                                    <input
                                                        class="form-control p-1 table-input table-input-temp"
                                                        max="42"
                                                        min="32"
                                                        type="number"
                                                        v-else-if="formData[`temperature${('0' + hour.time).slice(-2)}Home`]"
                                                        v-model="formData[`temperature${('0' + hour.time).slice(-2)}Home`]"
                                                        :class="{'is-invalid': errors[`temperature${('0' + hour.time).slice(-2)}Home`]}"
                                                        @change="dataChanged = true; errors[`temperature${('0' + hour.time).slice(-2)}Home`] = null;inputError = false;"
                                                    />
                                                    <input
                                                        class="form-control p-1 table-input table-input-temp"
                                                        max="42"
                                                        min="32"
                                                        type="number"
                                                        v-else
                                                        v-model="formData[`temperature${('0' + hour.time).slice(-2)}Home`]"
                                                        :class="{'is-invalid': errors[`temperature${('0' + hour.time).slice(-2)}Home`]}"
                                                        @change="dataChanged = true; errors[`temperature${('0' + hour.time).slice(-2)}Home`] = null;inputError = false;"
                                                    />
                                                    <label class="align-self-center m-0 ml-1">℃</label>
                                                </div>
                                                <span v-if="errors[`temperature${('0' + hour.time).slice(-2)}Home`]" class="error invalid-feedback">
                                                    {{errors[`temperature${('0' + hour.time).slice(-2)}Home`]}}
                                                </span>
                                            </td>
                                            <td :rowspan="rowspan" class="contactbook-td-defecation">
                                                <select class="form-control table-input" v-if="formData[`defecation${hour.time}School`]" v-model="formData[`defecation${hour.time}School`]" disabled @change="dataChanged = true;">
                                                    <option :value="0">-</option>
                                                    <option :value="1">普</option>
                                                    <option :value="2">軟</option>
                                                    <option :value="3">固</option>
                                                </select>
                                                <select class="form-control table-input" v-else-if="formData[`defecation${hour.time}Home`]" v-model="formData[`defecation${hour.time}Home`]" @change="dataChanged = true;">
                                                    <option :value="0">-</option>
                                                    <option :value="1">普</option>
                                                    <option :value="2">軟</option>
                                                    <option :value="3">固</option>
                                                </select>
                                                <select class="form-control table-input" v-else v-model="formData[`defecation${hour.time}Home`]" @change="dataChanged = true;">
                                                    <option :value="0">-</option>
                                                    <option :value="1">普</option>
                                                    <option :value="2">軟</option>
                                                    <option :value="3">固</option>
                                                </select>
                                            </td>
                                            <td :rowspan="rowspan" class="d-none d-md-table-cell">
                                                <!-- MEMO:PC側メモ表示 -->
                                                <input type="text" class="form-control px-2 table-input" placeholder="食事・メモ" v-if="formData[`meal${hour.time}School`]" :class="{'is-invalid': errors[`meal${hour.time}Home`]}" v-model="formData[`meal${hour.time}School`]" disabled @change="dataChanged = true; errors[`meal${hour.time}Home`] = null;inputError = false;"/>
                                                <input type="text" class="form-control px-2 table-input" placeholder="食事・メモ" v-else-if="formData[`meal${hour.time}Home`]" :class="{'is-invalid': errors[`meal${hour.time}Home`]}" v-model="formData[`meal${hour.time}Home`]" @change="dataChanged = true; errors[`meal${hour.time}Home`] = null;inputError = false;"/>
                                                <input type="text" class="form-control px-2 table-input" placeholder="食事・メモ" v-else v-model="formData[`meal${hour.time}Home`]" :class="{'is-invalid': errors[`meal${hour.time}Home`]}" @change="dataChanged = true; errors[`meal${hour.time}Home`] = null;inputError = false;"/>
                                                <span v-if="errors[`meal${hour.time}Home`]" class="error invalid-feedback">
                                                    {{errors[`meal${hour.time}Home`]}}
                                                </span>
                                            </td>
                                        </tr>
                                        <tr :key="hour.time+'30mins'" style="background-color: #dcd5bc; height:25px;">
                                            <td class="text-center" style="position:relative;" @click="setHour(hour.time, 2)">
                                                <div v-if="formData[`sleep${('0' + hour.time).slice(-2) + '30'}School`]" style="background-color: #EBCB42; width:50%; height: 100%; position:absolute;left: 25%;top:0;"></div>
                                                <div v-else-if="formData[`sleep${('0' + hour.time).slice(-2) + '30'}Home`]" style="background-color: #8BB3FC; width:50%; height: 100%; position:absolute;left: 25%;top:0;"></div>
                                            </td>
                                            <td colspan="4" :rowspan="rowspan" class="d-table-cell d-md-none">
                                                <!-- MEMO:SP側メモ表示-->
                                                <input type="text" class="form-control px-2 table-input table-input-memo" v-if="formData[`meal${hour.time}School`]"
                                                    :class="{'is-invalid': errors[`meal${hour.time}Home`]}"
                                                    v-model="formData[`meal${hour.time}School`]"
                                                    placeholder="食事・メモ"
                                                    readonly
                                                    @click="showMemoEditForm(`meal${hour.time}School`, true)"
                                                    @change="dataChanged = true;
                                                    errors[`meal${hour.time}Home`] = null;
                                                    inputError = false;"/>
                                                <input type="text" class="form-control px-2 table-input table-input-memo" v-else-if="formData[`meal${hour.time}Home`]"
                                                    :class="{'is-invalid': errors[`meal${hour.time}Home`]}"
                                                    v-model="formData[`meal${hour.time}Home`]"
                                                    placeholder="食事・メモ"
                                                    @click="showMemoEditForm(`meal${hour.time}Home`)"
                                                    @change="dataChanged = true;
                                                    errors[`meal${hour.time}Home`] = null;
                                                    inputError = false;"/>
                                                <input type="text" class="form-control px-2 table-input table-input-memo" v-else
                                                    v-model="formData[`meal${hour.time}Home`]"
                                                    :class="{'is-invalid': errors[`meal${hour.time}Home`]}"
                                                    placeholder="食事・メモ"
                                                    @click="showMemoEditForm(`meal${hour.time}Home`)"
                                                    @change="dataChanged = true;
                                                    errors[`meal${hour.time}Home`] = null;
                                                    inputError = false;"/>
                                            </td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>
                        <div class="row d-none d-md-flex" :class="{'d-block mt-1 mt-md-0': tab != BASE}">
                            <div class="col-md-6 col-12 d-none d-md-block" :class="{'d-block mb-1': tab == PARENT}">
                                <div class="dark-blue py-2 text-center text-white">
                                    家庭での様子
                                </div>
                                <div class="light-blue mt-1 p-3 contact-area">
                                    <textarea class="form-control" v-model="formData.state0Home" @change="dataChanged = true;" />
                                </div>
                            </div>
                            <div class="col-md-6 col-12 d-none d-md-block" :class="{'d-block mb-1': tab == SCHOOL}">
                                <div class="dark-yellow py-2 text-center text-white">
                                    保育園での様子
                                </div>
                                <div class="light-yellow mt-1 p-3 contact-area"
                                    v-text="formData.state0School">
                                </div>
                            </div>
                        </div>
                        <div class="d-none d-md-flex mt-md-3 row" :class="{'d-block': tab != BASE}">
                            <div class="col-md-6 col-12 d-none d-md-block" :class="{'d-block': tab == PARENT}">
                                <div class="dark-blue py-2 text-center text-white">
                                    家庭からの連絡事項
                                </div>
                                <div class="light-blue mt-1 p-3 contact-area">
                                    <textarea class="form-control" v-model="formData.contact0Home" @change="dataChanged = true;" />
                                </div>
                            </div>
                            <div class="col-md-6 col-12 d-none d-md-block" :class="{'d-block': tab == SCHOOL}">
                                <div class="dark-yellow py-2 text-center text-white">
                                    保育園からの連絡事項
                                </div>
                                <div class="light-yellow mt-1 p-3 contact-area"
                                    v-text="formData.contact0School">
                                </div>
                            </div>
                        </div>
                        <div class="align-items-center d-flex fixed-btn-group float-right mt-3" :class="{'is-invalid': inputError}">
                            <button class="btn btn-primary float-right mr-md-2" @click="saveContact">登録</button>
                            <button class="btn btn-primary float-right" @click="exportExcel">Excel出力</button>
                        </div>
                        <div v-if="inputError" class="error invalid-feedback text-right" style="margin-top: 60px;">
                            {{ $t("Input error") }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal -->
        <memo-edit-form
            @replaceFormData="replaceFormData"
            ref="MemoEditForm"
        ></memo-edit-form>
        <scroll-button></scroll-button>
    </div>
</template>
<script>
import Datepicker from "vuejs-datepicker";
import { ja } from 'vuejs-datepicker/dist/locale';
import moment from 'moment-timezone';
import { mapState } from 'vuex';
import actionLoading from '../../mixin/actionLoading';
import api, { apiErrorHandler } from '../../global/api';
import HourMinuteInput from '../../components/HourMinuteInput.vue';
import { showSuccess } from '../../helpers/error';
import { changeToHhMm, validateHhMm } from '../../helpers/datetime';
import LocalStorage from '../../helpers/localStorage';
import { isMobile, scrollToTop } from '../../helpers/mobile';
import CustomRadio from '../../components/CustomRadio.vue';
import MemoEditForm from '../ContactBook/MemoEditForm.vue';
import ScrollButton from '../../components/ScrollButton.vue';

const initialFormData = {
    date: new Date(),
    weather: '',
    mood: null,
    nurseName: '',
    temperatureTimeStd: null,
    temperatureStd: null,
    pickUpPerson: null,
    pickUpTime: null,
    sleep0100Home: false,
    sleep0130Home: false,
    sleep0200Home: false,
    sleep0230Home: false,
    sleep0300Home: false,
    sleep0330Home: false,
    sleep0400Home: false,
    sleep0430Home: false,
    sleep0500Home: false,
    sleep0530Home: false,
    sleep0600Home: false,
    sleep0630Home: false,
    sleep0700Home: false,
    sleep0730Home: false,
    sleep0800Home: false,
    sleep0830Home: false,
    sleep0900Home: false,
    sleep0930Home: false,
    sleep1000Home: false,
    sleep1030Home: false,
    sleep1100Home: false,
    sleep1130Home: false,
    sleep1200Home: false,
    sleep1230Home: false,
    sleep1300Home: false,
    sleep1330Home: false,
    sleep1400Home: false,
    sleep1430Home: false,
    sleep1500Home: false,
    sleep1530Home: false,
    sleep1600Home: false,
    sleep1630Home: false,
    sleep1700Home: false,
    sleep1730Home: false,
    sleep1800Home: false,
    sleep1830Home: false,
    sleep1900Home: false,
    sleep1930Home: false,
    sleep2000Home: false,
    sleep2030Home: false,
    sleep2100Home: false,
    sleep2130Home: false,
    sleep2200Home: false,
    sleep2230Home: false,
    sleep2300Home: false,
    sleep2330Home: false,
    sleep2400Home: false,
    sleep2430Home: false,
    temperature01Home: null,
    temperature02Home: null,
    temperature03Home: null,
    temperature04Home: null,
    temperature05Home: null,
    temperature06Home: null,
    temperature07Home: null,
    temperature08Home: null,
    temperature09Home: null,
    temperature10Home: null,
    temperature11Home: null,
    temperature12Home: null,
    temperature13Home: null,
    temperature14Home: null,
    temperature15Home: null,
    temperature16Home: null,
    temperature17Home: null,
    temperature18Home: null,
    temperature19Home: null,
    temperature20Home: null,
    temperature21Home: null,
    temperature22Home: null,
    temperature23Home: null,
    temperature24Home: null,
    defecation1Home: 0,
    defecation2Home: 0,
    defecation3Home: 0,
    defecation4Home: 0,
    defecation5Home: 0,
    defecation6Home: 0,
    defecation7Home: 0,
    defecation8Home: 0,
    defecation9Home: 0,
    defecation10Home: 0,
    defecation11Home: 0,
    defecation12Home: 0,
    defecation13Home: 0,
    defecation14Home: 0,
    defecation15Home: 0,
    defecation16Home: 0,
    defecation17Home: 0,
    defecation18Home: 0,
    defecation19Home: 0,
    defecation20Home: 0,
    defecation21Home: 0,
    defecation22Home: 0,
    defecation23Home: 0,
    defecation24Home: 0,
    meal1Home: '',
    meal2Home: '',
    meal3Home: '',
    meal4Home: '',
    meal5Home: '',
    meal6Home: '',
    meal7Home: '',
    meal8Home: '',
    meal9Home: '',
    meal10Home: '',
    meal11Home: '',
    meal12Home: '',
    meal13Home: '',
    meal14Home: '',
    meal15Home: '',
    meal16Home: '',
    meal17Home: '',
    meal18Home: '',
    meal19Home: '',
    meal20Home: '',
    meal21Home: '',
    meal22Home: '',
    meal23Home: '',
    meal24Home: '',
    state0Home: '',
    contact0Home: '',
}

const PARENT = 1;
const SCHOOL = 2;
const BASE = 3;

export default {
    components: {
        CustomRadio,
        Datepicker,
        HourMinuteInput,
        MemoEditForm,
        ScrollButton,
    },
    mixins: [actionLoading],
    props: {
        contact: {},
        child: {},
        date: null,
    },
    computed: {
        activeTabClass(){
            switch (this.tab) {
                case PARENT:
                    return 'active-parent'
                case SCHOOL:
                    return 'active-school'
                case BASE:
                    return 'active-base'
                default:
                    break;
            }
        },
        rowspan() {
            return isMobile() ? 1 : 2;
        },
        ...mapState({
            currentOfficeName: state =>  {
                if (state.session.info.office) return state.session.info.office.name;
                return '';
            }
        })
    },
    data () {
        return {
            dataChanged: false,
            formData: {...initialFormData},
            currentDate: new Date(),
            todayDate: "",
            disabledDates: {
                to: null,
            },
            hours: [
                {
                    time:'18',
                    enabled1: true,
                    enabled2: true,
                },
                {
                    time:'19',
                    enabled1: true,
                    enabled2: true,
                },
                {
                    time:'20',
                    enabled1: true,
                    enabled2: true,
                },
                {
                    time:'21',
                    enabled1: true,
                    enabled2: true,
                },
                {
                    time:'22',
                    enabled1: true,
                    enabled2: true,
                },
                {
                    time:'23',
                    enabled1: true,
                    enabled2: true,
                },
                {
                    time:'24',
                    enabled1: true,
                    enabled2: true,
                },
                {
                    time:'1',
                    enabled1: true,
                    enabled2: true,
                },
                {
                    time:'2',
                    enabled1: true,
                    enabled2: true,
                },
                {
                    time:'3',
                    enabled1: true,
                    enabled2: true,
                },
                {
                    time:'4',
                    enabled1: true,
                    enabled2: true,
                },
                {
                    time:'5',
                    enabled1: true,
                    enabled2: true,
                },
                {
                    time:'6',
                    enabled1: true,
                    enabled2: true,
                },
                {
                    time:'7',
                    enabled1: true,
                    enabled2: true,
                },
                {
                    time:'8',
                    enabled1: true,
                    enabled2: true,
                },
                {
                    time:'9',
                    enabled1: true,
                    enabled2: true,
                },
                {
                    time:'10',
                    enabled1: true,
                    enabled2: true,
                },
                {
                    time:'11',
                    enabled1: true,
                    enabled2: true,
                },
                {
                    time:'12',
                    enabled1: true,
                    enabled2: true,
                },
                {
                    time:'13',
                    enabled1: true,
                    enabled2: true,
                },
                {
                    time:'14',
                    enabled1: true,
                    enabled2: true,
                },
                {
                    time:'15',
                    enabled1: true,
                    enabled2: true,
                },
                {
                    time:'16',
                    enabled1: true,
                    enabled2: true,
                },
                {
                    time:'17',
                    enabled1: true,
                    enabled2: true,
                }],
            errors: {
                weather: null,
            },
            inputError: false,
            selectedDate: new Date(),
            ja: ja,
            tab: BASE,
            BASE: BASE,
            PARENT: PARENT,
            SCHOOL: SCHOOL,
        }
    },
    methods: {
        convertToFormData() {
            //this.initializeFormData();
            if (this.contact) {
                this.formData = {...this.contact};
            }
        },
        initializeFormData() {
            this.formData = {

            };
        },
        initFormError() {
            this.errors = {
                weather: null,
                guardian: null,
                nurseName: null,
                temperatureTimeStd: null,
                temperatureStd: null,
                pickUpTime: null,
                pickUpPerson: null
            }
        },
        setHour(hourIndex, number) {
            this.dataChanged = true;
            if(number == 1) {
                if(this.formData[`sleep${('0' + hourIndex).slice(-2) + '00'}School`] != 1) {
                    this.formData[`sleep${('0' + hourIndex).slice(-2) + '00'}Home`] = 1 - this.formData[`sleep${('0' + hourIndex).slice(-2) + '00'}Home`];
                }
            }
            else if(number == 2) {
                if(this.formData[`sleep${('0' + hourIndex).slice(-2) + '30'}School`] != 1) {
                    this.formData[`sleep${('0' + hourIndex).slice(-2) + '30'}Home`] = 1 - this.formData[`sleep${('0' + hourIndex).slice(-2) + '30'}Home`];
                }
            }
        },
        exportExcel() {
            const date = moment(this.selectedDate).format('YYYY-MM-DD');
            location.href = process.env.MIX_APP_BASE_URL + 'childcare-contact-book/excel/' + this.child.id + '/?date=' + date + '&token=' + LocalStorage.getToken();
        },
        saveContact() {
            if(this.actionLoading) return;
            if (!this.validate()) {
                scrollToTop();
                return;
            }
            const requestData = this.formData;
            requestData['date'] = moment(this.selectedDate).format('YYYY-MM-DD');
            if(this.formData.pickUpTime)
                requestData['pick_up_time'] = moment(this.formData.pickUpTime, 'h:mm:ss').format('HH:mm');
            // if(this.formData.temperatureTimeStd)
                // requestData['temperature_time_std'] = moment(this.formData.temperatureTimeStd, 'h:mm:ss').format('HH:mm');
            this.setActionLoading();
            api.post('contact-book/child/' + this.child.id + '/home/0', null, requestData)
            .then(() => {
                this.unsetActionLoading();
                showSuccess(this.$t('Successfully saved'));
                this.dataChanged = false;
            })
            .catch(e => {
                this.dataChanged = false;
                apiErrorHandler(e);
                this.unsetActionLoading();
            });
        },
        validate() {
            let valid = true;
            // if(!this.formData.weather) {
            //     this.errors.weather = this.$t('Please input weather');
            //     valid = false;
            // }
            if(this.formData.weather && this.formData.weather.length > 10) {
                this.errors.weather = this.$t('Please enter 10 characters or less');
                valid = false;
            }
            if(!this.formData.guardian) {
                this.errors.guardian = this.$t('Please input name');
                valid = false;
            }
            if(this.formData.guardian && this.formData.guardian.length > 20) {
                this.errors.guardian = this.$t('Please enter 20 characters or less');
                valid = false;
            }
            // if(!this.formData.nurseName) {
            //     this.errors.nurseName = this.$t('Please input name');
            //     valid = false;
            // }
            // if(this.formData.nurseName && this.formData.nurseName.length > 20) {
            //     this.errors.nurseName = this.$t('Please enter 20 characters or less');
            //     valid = false;
            // }
            if(this.formData.temperatureStd && (this.formData.temperatureStd < 34 || this.formData.temperatureStd > 42)) {
                this.errors.temperatureStd = this.$t('Incorrect temperature value');
                valid = false;
            }
            if(!this.formData.pickUpPerson) {
                this.errors.pickUpPerson = this.$t('Please input');
                valid = false;
            }
            if(this.formData.pickUpPerson && this.formData.pickUpPerson.length > 20) {
                this.errors.pickUpPerson = this.$t('Please enter 20 characters or less');
                valid = false;
            }
            this.formData.temperatureTimeStd = changeToHhMm(this.formData.temperatureTimeStd);
            if(this.formData.temperatureTimeStd && !validateHhMm(this.formData.temperatureTimeStd)) {
                this.errors.temperatureTimeStd = this.$t('Invalid time format');
                valid = false;
            }
            this.formData.pickUpTime = changeToHhMm(this.formData.pickUpTime);

            if(!this.formData.pickUpTime) {
                this.errors.pickUpTime = this.$t('Please input time');
                valid = false;
            }

            if(this.formData.pickUpTime && !validateHhMm(this.formData.pickUpTime)) {
                this.errors.pickUpTime = this.$t('Invalid time format');
                valid = false;
            }
            this.hours.forEach(element => {
                if(this.formData[`temperature${('0' + element.time).slice(-2)}Home`] && this.formData[`temperature${('0' + element.time).slice(-2)}Home`] < 0) {
                    this.errors[`temperature${('0' + element.time).slice(-2)}Home`] = this.$t('Please input positive number');
                    valid = false;
                }
                if(this.formData[`temperature${('0' + element.time).slice(-2)}Home`] && (this.formData[`temperature${('0' + element.time).slice(-2)}Home`] < 34 || this.formData[`temperature${('0' + element.time).slice(-2)}Home`] > 42)) {
                    this.errors[`temperature${('0' + element.time).slice(-2)}Home`] = this.$t('Incorrect temperature value');
                    valid = false;
                }
            });
            this.hours.forEach(element => {
                if(this.formData[`meal${element.time}Home`] && this.formData[`meal${element.time}Home`].length > 200) {
                    this.errors[`meal${element.time}Home`] = this.$t('Please enter 50 characters or less');
                    valid = false;
                }
            });
            this.inputError = !valid;
            return valid;
        },
        getContact(date) {
            if(this.actionLoading) return;
            this.setActionLoading();
            if(date){
                this.selectedDate = date;
            } else {
                this.selectedDate = new Date();
            }
            this.selectedDate = moment(this.selectedDate).format('YYYY-MM-DD');
            api.get('contact-book/child/' + this.child.id, null, {date: this.selectedDate})
                .then(response => {
                    this.unsetActionLoading();
                    this.dataChanged = false;
                    if (response.contactBook) {
                        this.formData = response.contactBook;
                    } else {
                        this.formData = {...initialFormData};
                    }
                    this.$parent.getContactBook(this.selectedDate);
                })
                .catch(e => {
                    this.dataChanged = false;
                    this.unsetActionLoading();
                    apiErrorHandler(e);
                });
        },
        getCurrentDate(){
            return moment().format('YYYY年 M月 D日 (ddd)');
        },
        currentTime(){
            var today = new Date();
            var month = today.getMonth() + 1;
            var day = today.getDate();
            return month + "月" + day + "日 "
            + today.getHours() + ":"
            + today.getMinutes();
        },
        customFormatter(date) {
            return moment(date).format('YYYY/M/D(ddd)');
        },
        changeTimeFormat(date) {
            if(date) {
                return moment(date).tz('Asia/Tokyo').format('HH:mm');
            } else {
                return "-";
            }
        },
        openDatePicker(){
            if(this.dataChanged) {
                if(!confirm(this.$t('Are you sure moving to other date without saving data?'))) return;
            }
            this.$refs.programaticOpen.showCalendar();
        },
        formatTemperature(temperature){
            if(temperature) {
                return temperature + "℃";
            } else {
                return '';
            }
        },
        formatTimeStd(time) {
            if(time) return moment(time, 'HH:mm:ss').format('HH:mm');
            return null;
        },
        previousDay() {
            return moment(this.selectedDate).subtract(1, 'days').format('M月 D日 (ddd)');
        },
        currentDay() {
            return moment(this.selectedDate).format('M月 D日 (ddd)');
        },
        onNext() {
            var date = moment(this.selectedDate).add(1, 'days').format('YYYY-MM-DD');
            this.getContact(date);

            this.inputError = false;
            this.initFormError();
        },
        onPrev() {
            var date = moment(this.selectedDate).add(-1, 'days').format('YYYY-MM-DD');
            this.getContact(date);

            this.inputError = false;
            this.initFormError();
        },
        onCurrentMonth(){
            var date = moment().format('YYYY-MM-DD');
            this.getContact(date);
        },
        showMemoEditForm(key, _isDisabled = false) {
            this.$refs.MemoEditForm.showModal('食事 その他の確認', this.formData[key], key, _isDisabled);
        },
        replaceFormData(key, value) {
            this.formData[key] = value;
            $('#contact-book-meal-edit-form').modal('hide');
        },
    },
    created() {

    },
    mounted() {
        this.todayDate = this.getCurrentDate().toString();
        this.selectedDate = this.date;
        this.convertToFormData();
        this.initFormError();
        if(this.child.admissionDate) {
            this.disabledDates = {to: moment(this.child.admissionDate).subtract(1, "days").toDate()};
        }
    }
}
</script>
<style scoped>
    .calendar-center {
        display: flex;
        justify-content: center;
        align-items: center;
    }
    .calendar-title {
        display: flex;
        align-items: center;
    }
    .calendar-left {
        display: flex;
        justify-content: left;
        align-items: center;
    }

@media (max-width: 500px) {
       h5.card-title {
           font-size: 13px!important;
       }
    }
</style>
