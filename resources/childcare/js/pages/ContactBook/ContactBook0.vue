<template>
    <div class="container-fluid has-fixed-btn">
        <div v-if="inputError" class="error invalid-feedback error-top">
            {{ $t("Input error") }}
        </div>
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="align-items-center col-md-6 col-12 d-flex flex-column flex-md-row justify-content-center justify-content-md-start pb-1">
                                <h5 class="card-title mb-0 pr-md-5">{{ currentOfficeName }}</h5>
                                <div class="mb-0 pl-md-4 text-center">{{ child.name }}</div>
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
                                {{ formData.guardian }}
                            </div>
                            <div class="align-items-center col-md-5 col-12 d-flex mb-2">
                                <label class="mb-0" for="mindername" style="min-width: 80px;">保育士名：</label>
                                <div class="d-flex flex-column justify-content-center" style="width: calc(90% - 80px);">
                                    <input
                                        class="form-control"
                                        id="mindername"
                                        type="text"
                                        v-model="formData.nurseName"
                                        :class="{'is-invalid' : errors.nurseName}"
                                        @change="dataChanged = true; errors.nurseName = null; inputError = false;"
                                    />
                                    <span v-if="errors.nurseName" class="error invalid-feedback">
                                        {{ errors.nurseName }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="row" style="padding-left:15px; padding-right:15px;">
                            <div class="col-md-2 col-4" style="padding:1px;">
                                <div class="dark-pink text-center text-white py-2">
                                    機嫌
                                </div>
                            </div>
                            <div class="col-md-4 col-8" style="padding:1px;">
                                <div class="light-pink form-check text-center py-2" style="height:39px;">
                                    <!-- <input class="form-check-input" type="radio" name="radio1" v-model="formData.mood" :value="1" @change="dataChanged = true;">
                                    <label class="form-check-label mr-4">普通</label>
                                    <input class="form-check-input" type="radio" name="radio1" v-model="formData.mood" :value="2" @change="dataChanged = true;">
                                    <label class="form-check-label mr-4">良い</label>
                                    <input class="form-check-input" type="radio" name="radio1" v-model="formData.mood" :value="3" @change="dataChanged = true;">
                                    <label class="form-check-label mr-4">悪い</label> -->
                                    {{formatMoodStatus(formData.mood)}}
                                </div>
                            </div>
                            <div class="col-md-2 col-4" style="padding:1px;">
                                <div class="dark-pink text-center text-white py-2">
                                    検温
                                </div>
                            </div>
                            <div class="col-md-2 col-4" style="padding:1px;">
                                <div class="light-pink text-center d-flex justify-content-center py-2" style="height:39px;">
                                    <!-- <hour-minute-input v-model="formData.temperatureTimeStd" :error="errors.temperatureTimeStd" @input="dataChanged = true; errors.temperatureTimeStd = null;"/> -->
                                    {{formatTimeStd(formData.temperatureTimeStd)}}
                                </div>
                            </div>
                            <div class="col-md-2 col-4" style="padding:1px;">
                                <div class="light-pink text-center d-flex justify-content-center py-2" style="height:39px;">
                                    <!-- <input type="number" min="0" max="60" class="form-control" style="max-width: 55%;"  :class="{'is-invalid': errors.temperatureStd}" v-model="formData.temperatureStd" @change="dataChanged = true; errors.temperatureStd = null;"/>℃
                                    <span v-if="errors.temperatureStd"  class="error invalid-feedback">
                                        {{errors.temperatureStd}}
                                    </span> -->
                                    <template v-if="formData.temperatureStd">
                                        {{formData.temperatureStd}}℃
                                    </template>
                                </div>
                            </div>
                        </div>
                        <div class="row" style="padding-left:15px; padding-right:15px;">
                            <div class="col-md-2 col-4" style="padding:1px;">
                                <div class="dark-pink text-center text-white py-2">
                                    お迎え予定
                                </div>
                            </div>
                            <div class="col-md-4 col-8" style="padding:1px;">
                                <div class="light-pink text-center d-flex justify-content-center py-2" style="height:39px;">
                                    <!-- <hour-minute-input v-model="formData.pickUpTime" @input="dataChanged = true; errors.pickUpTime = null;" :error="errors.pickUpTime"/> -->
                                    {{formatTimeStd(formData.pickUpTime)}}
                                </div>
                            </div>
                            <div class="col-md-2 col-4" style="padding:1px;">
                                <div class="dark-pink text-center text-white py-2">
                                    お迎えの方
                                </div>
                            </div>
                            <div class="col-md-4 col-8" style="padding:1px;">
                                <div class="light-pink d-flex justify-content-center py-2" style="height:39px;">
                                    <!-- <input type="text" class="form-control" style="max-width: 55%;" v-model="formData.pickUpPerson" :class="{'is-invalid': errors.pickUpPerson}" @change="dataChanged = true;errors.pickUpPerson = null;"/>
                                    <span v-if="errors.pickUpPerson" class="error invalid-feedback">
                                        {{errors.pickUpPerson}}
                                    </span> -->
                                    {{ formData.pickUpPerson }}
                                </div>
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
                                                            max="42"
                                                            min="32"
                                                            type="number"
                                                            v-if="formData[`temperature${('0' + hour.time).slice(-2)}School`]"
                                                            v-model="formData[`temperature${('0' + hour.time).slice(-2)}School`]"
                                                            :class="{'is-invalid': errors[`temperature${('0' + hour.time).slice(-2)}School`]}"
                                                            @change="dataChanged = true; errors[`temperature${('0' + hour.time).slice(-2)}School`] = null; inputError = false;"
                                                        />
                                                        <input
                                                            class="form-control p-1 table-input table-input-temp"
                                                            disabled
                                                            max="42"
                                                            min="32"
                                                            type="number"
                                                            v-else-if="formData[`temperature${('0' + hour.time).slice(-2)}Home`]"
                                                            v-model="formData[`temperature${('0' + hour.time).slice(-2)}Home`]"
                                                            :class="{'is-invalid': errors[`temperature${('0' + hour.time).slice(-2)}School`]}"
                                                            @change="dataChanged = true; errors[`temperature${('0' + hour.time).slice(-2)}School`] = null; inputError = false;"
                                                        />
                                                        <input
                                                            class="form-control p-1 table-input table-input-temp"
                                                            max="42"
                                                            min="32"
                                                            type="number"
                                                            v-else
                                                            v-model="formData[`temperature${('0' + hour.time).slice(-2)}School`]"
                                                            :class="{'is-invalid': errors[`temperature${('0' + hour.time).slice(-2)}School`]}"
                                                            @change="dataChanged = true; errors[`temperature${('0' + hour.time).slice(-2)}School`] = null; inputError = false;"
                                                        />
                                                        <label class="align-self-center m-0 ml-1">℃</label>
                                                    </div>
                                                    <span v-if="errors[`temperature${('0' + hour.time).slice(-2)}School`]" class="error invalid-feedback">
                                                        {{errors[`temperature${('0' + hour.time).slice(-2)}School`]}}
                                                    </span>
                                                </td>
                                                <td :rowspan="rowspan" class="contactbook-td-defecation">
                                                    <select class="form-control table-input" v-if="formData[`defecation${hour.time}School`]" v-model="formData[`defecation${hour.time}School`]" @change="dataChanged = true;">
                                                        <option :value="0">-</option>
                                                        <option :value="1">普</option>
                                                        <option :value="2">軟</option>
                                                        <option :value="3">固</option>
                                                    </select>
                                                    <select class="form-control table-input" v-else-if="formData[`defecation${hour.time}Home`]" v-model="formData[`defecation${hour.time}Home`]" disabled @change="dataChanged = true;">
                                                        <option :value="0">-</option>
                                                        <option :value="1">普</option>
                                                        <option :value="2">軟</option>
                                                        <option :value="3">固</option>
                                                    </select>
                                                    <select class="form-control table-input" v-else v-model="formData[`defecation${hour.time}School`]" @change="dataChanged = true;">
                                                        <option :value="0">-</option>
                                                        <option :value="1">普</option>
                                                        <option :value="2">軟</option>
                                                        <option :value="3">固</option>
                                                    </select>
                                                </td>
                                                <td :rowspan="rowspan" class="d-none d-md-table-cell">
                                                    <!-- MEMO:PC側メモ表示 -->
                                                    <input type="text" class="form-control px-2 table-input" placeholder="食事・メモ" v-if="formData[`meal${hour.time}School`]" v-model="formData[`meal${hour.time}School`]" :class="{'is-invalid': errors[`meal${hour.time}School`]}" @change="dataChanged = true; errors[`meal${hour.time}School`] = null;  inputError = false;"/>
                                                    <input type="text" class="form-control px-2 table-input" placeholder="食事・メモ" v-else-if="formData[`meal${hour.time}Home`]" v-model="formData[`meal${hour.time}Home`]" :class="{'is-invalid': errors[`meal${hour.time}School`]}" disabled @change="dataChanged = true; errors[`meal${hour.time}School`] = null; inputError = false;"/>
                                                    <input type="text" class="form-control px-2 table-input" placeholder="食事・メモ" v-else v-model="formData[`meal${hour.time}School`]" :class="{'is-invalid': errors[`meal${hour.time}School`]}" @change="dataChanged = true; errors[`meal${hour.time}School`] = null; inputError = false;"/>
                                                    <span v-if="errors[`meal${hour.time}School`]" class="error invalid-feedback">
                                                        {{errors[`meal${hour.time}School`]}}
                                                    </span>
                                                </td>
                                            </tr>
                                            <tr :key="hour.time+'30mins'" style="background-color: #dcd5bc;">
                                                <td class="text-center" style="position:relative; height:25px; width:40px" @click="setHour(hour.time, 2)">
                                                    <div v-if="formData[`sleep${('0' + hour.time).slice(-2) + '30'}School`]" style="background-color: #EBCB42; width:50%; height: 100%; position:absolute;left: 25%;top:0;"></div>
                                                    <div v-else-if="formData[`sleep${('0' + hour.time).slice(-2) + '30'}Home`]" style="background-color: #8BB3FC; width:50%; height: 100%; position:absolute;left: 25%;top:0;"></div>
                                                </td>
                                                <td colspan="4" :rowspan="rowspan" class="d-table-cell d-md-none">
                                                    <!-- MEMO:SP側メモ表示-->
                                                    <input type="text" class="form-control px-2 table-input table-input-memo" v-if="formData[`meal${hour.time}School`]"
                                                        v-model="formData[`meal${hour.time}School`]"
                                                        :class="{'is-invalid': errors[`meal${hour.time}School`]}"
                                                        placeholder="食事・メモ"
                                                        @click="showMemoEditForm(`meal${hour.time}School`)"
                                                        @change="dataChanged = true;
                                                        errors[`meal${hour.time}School`] = null;
                                                         inputError = false;"/>
                                                    <input type="text" class="form-control px-2 table-input table-input-memo" v-else-if="formData[`meal${hour.time}Home`]"
                                                        v-model="formData[`meal${hour.time}Home`]"
                                                        :class="{'is-invalid': errors[`meal${hour.time}School`]}"
                                                        placeholder="食事・メモ"
                                                        readonly
                                                        @click="showMemoEditForm(`meal${hour.time}Home`, true)"
                                                        @change="dataChanged = true;
                                                        errors[`meal${hour.time}School`] = null;
                                                        inputError = false;"/>
                                                    <input type="text" class="form-control px-2 table-input table-input-memo" v-else
                                                        v-model="formData[`meal${hour.time}School`]"
                                                        :class="{'is-invalid': errors[`meal${hour.time}School`]}"
                                                        placeholder="食事・メモ"
                                                        @click="showMemoEditForm(`meal${hour.time}School`)"
                                                        @change="dataChanged = true;
                                                        errors[`meal${hour.time}School`] = null;
                                                        inputError = false;"/>
                                                    <span v-if="errors[`meal${hour.time}School`]" class="error invalid-feedback">
                                                        {{errors[`meal${hour.time}School`]}}
                                                    </span>
                                                </td>
                                            </tr>
                                        </template>
                                    </tbody>
                            </table>
                        </div>
                        <div class="row d-none d-md-flex" :class="{'d-block mt-1 mt-md-0': tab != BASE}">
                            <div class="col-md-6 col-12 d-none d-md-block" :class="{'d-block': tab == PARENT}">
                                <div class="dark-blue py-2 text-center text-white">
                                    家庭での様子
                                </div>
                                <div class="light-blue mt-1 p-3 contact-area"
                                    v-text="formData.state0Home">
                                </div>
                            </div>
                            <div class="col-md-6 col-12 d-none d-md-block" :class="{'d-block': tab == SCHOOL}">
                                <div class="dark-yellow py-2 text-center text-white">
                                    保育園での様子
                                </div>
                                <div class="light-yellow mt-1 p-3 contact-area">
                                    <textarea class="form-control" v-model="formData.state0School" @change="dataChanged = true;" />
                                </div>
                            </div>
                        </div>
                        <div class="d-none d-md-flex mt-md-3 row" :class="{'d-block mt-1': tab != BASE}">
                            <div class="col-md-6 col-12 d-none d-md-block" :class="{'d-block': tab == PARENT}">
                                <div class="dark-blue py-2 text-center text-white">
                                    家庭からの連絡事項
                                </div>
                                <div class="light-blue mt-1 p-3 contact-area" v-text="formData.contact0Home">
                                </div>
                            </div>
                            <div class="col-md-6 col-12 d-none d-md-block" :class="{'d-block': tab == SCHOOL}">
                                <div class="dark-yellow py-2 text-center text-white">
                                    保育園からの連絡事項
                                </div>
                                <div class="light-yellow mt-1 p-3 contact-area">
                                    <textarea class="form-control" v-model="formData.contact0School" @change="dataChanged = true;" />
                                </div>
                            </div>
                        </div>
                        <div class="align-items-center d-flex fixed-btn-group float-right mt-3" :class="{'is-invalid': inputError}">
                            <button class="btn btn-primary float-right mr-md-2" @click="saveContact(1)">一時保存</button>
                            <button class="btn btn-primary float-right mr-md-2" @click="saveContact(2)">完了</button>
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
import LocalStorage from '../../helpers/localStorage';
import { validateHhMm } from '../../helpers/datetime';
import { isMobile, scrollToTop } from '../../helpers/mobile';
import MemoEditForm from './MemoEditForm.vue';
import ScrollButton from '../../components/ScrollButton.vue';

const initialFormData = {
    date: new Date(),
    weather: '',
    mood: null,
    guardian: '',
    nurseName: '',
    pickUpPerson: null,
    pickUpTime: null,
    temperatureStd: '',
    temperatureTimeStd: null,
    sleep0100School: false,
    sleep0130School: false,
    sleep0200School: false,
    sleep0230School: false,
    sleep0300School: false,
    sleep0330School: false,
    sleep0400School: false,
    sleep0430School: false,
    sleep0500School: false,
    sleep0530School: false,
    sleep0600School: false,
    sleep0630School: false,
    sleep0700School: false,
    sleep0730School: false,
    sleep0800School: false,
    sleep0830School: false,
    sleep0900School: false,
    sleep0930School: false,
    sleep1000School: false,
    sleep1030School: false,
    sleep1100School: false,
    sleep1130School: false,
    sleep1200School: false,
    sleep1230School: false,
    sleep1300School: false,
    sleep1330School: false,
    sleep1400School: false,
    sleep1430School: false,
    sleep1500School: false,
    sleep1530School: false,
    sleep1600School: false,
    sleep1630School: false,
    sleep1700School: false,
    sleep1730School: false,
    sleep1800School: false,
    sleep1830School: false,
    sleep1900School: false,
    sleep1930School: false,
    sleep2000School: false,
    sleep2030School: false,
    sleep2100School: false,
    sleep2130School: false,
    sleep2200School: false,
    sleep2230School: false,
    sleep2300School: false,
    sleep2330School: false,
    sleep2400School: false,
    sleep2430School: false,
    temperature01School: null,
    temperature02School: null,
    temperature03School: null,
    temperature04School: null,
    temperature05School: null,
    temperature06School: null,
    temperature07School: null,
    temperature08School: null,
    temperature09School: null,
    temperature10School: null,
    temperature11School: null,
    temperature12School: null,
    temperature13School: null,
    temperature14School: null,
    temperature15School: null,
    temperature16School: null,
    temperature17School: null,
    temperature18School: null,
    temperature19School: null,
    temperature20School: null,
    temperature21School: null,
    temperature22School: null,
    temperature23School: null,
    temperature24School: null,
    defecation1School: 0,
    defecation2School: 0,
    defecation3School: 0,
    defecation4School: 0,
    defecation5School: 0,
    defecation6School: 0,
    defecation7School: 0,
    defecation8School: 0,
    defecation9School: 0,
    defecation10School: 0,
    defecation11School: 0,
    defecation12School: 0,
    defecation13School: 0,
    defecation14School: 0,
    defecation15School: 0,
    defecation16School: 0,
    defecation17School: 0,
    defecation18School: 0,
    defecation19School: 0,
    defecation20School: 0,
    defecation21School: 0,
    defecation22School: 0,
    defecation23School: 0,
    defecation24School: 0,
    meal1School: '',
    meal2School: '',
    meal3School: '',
    meal4School: '',
    meal5School: '',
    meal6School: '',
    meal7School: '',
    meal8School: '',
    meal9School: '',
    meal10School: '',
    meal11School: '',
    meal12School: '',
    meal13School: '',
    meal14School: '',
    meal15School: '',
    meal16School: '',
    meal17School: '',
    meal18School: '',
    meal19School: '',
    meal20School: '',
    meal21School: '',
    meal22School: '',
    meal23School: '',
    meal24School: '',
    state0School: '',
    contact0School: '',
}

const PARENT = 1;
const SCHOOL = 2;
const BASE = 3;

export default {
    components: {
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
                if(this.formData[`sleep${('0' + hourIndex).slice(-2) + '00'}Home`] != 1) {
                    this.formData[`sleep${('0' + hourIndex).slice(-2) + '00'}School`] = 1 - this.formData[`sleep${('0' + hourIndex).slice(-2) + '00'}School`];
                }
            }
            else if(number == 2) {
                if(this.formData[`sleep${('0' + hourIndex).slice(-2) + '30'}Home`] != 1) {
                    this.formData[`sleep${('0' + hourIndex).slice(-2) + '30'}School`] = 1 - this.formData[`sleep${('0' + hourIndex).slice(-2) + '30'}School`];
                }
            }
        },
        saveContact(status) {
            if(this.actionLoading) return;
            if (!this.validate()) {
                scrollToTop();
                return;
            }
            const requestData = this.formData;
            requestData['date'] = moment(this.selectedDate).format('YYYY-MM-DD');
            requestData['status'] = status;
            // if(this.formData.pickUpTime)
            //     requestData['pick_up_time'] = moment(this.formData.pickUpTime, 'h:mm:ss').format('HH:mm');
            // if(this.formData.temperatureTimeStd)
            //     requestData['temperature_time_std'] = moment(this.formData.temperatureTimeStd, 'h:mm:ss').format('HH:mm');
            this.setActionLoading();
            api.post('contact-book/child/' + this.child.id + '/school/0', null, requestData)
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
            if(this.formData.temperatureStd && this.formData.temperatureStd < 0) {
                this.errors.temperatureStd = this.$t('Please input positive number');
                valid = false;
            }
            // if(!this.formData.guardian) {
            //     this.errors.guardian = this.$t('Please input name');
            //     valid = false;
            // }
            // if(this.formData.guardian && this.formData.guardian.length > 20) {
            //     this.errors.guardian = this.$t('Please enter 20 characters or less');
            //     valid = false;
            // }
            if(!this.formData.nurseName) {
                this.errors.nurseName = this.$t('Please input name');
                valid = false;
            }
            if(this.formData.nurseName && this.formData.nurseName.length > 20) {
                this.errors.nurseName = this.$t('Please enter 20 characters or less');
                valid = false;
            }
            // if(!this.formData.pickUpPerson) {
            //     this.errors.pickUpPerson = this.$t('Please input name');
            //     valid = false;
            // }
            // if(this.formData.pickUpPerson && this.formData.pickUpPerson.length > 20) {
            //     this.errors.pickUpPerson = this.$t('Please enter 20 characters or less');
            //     valid = false;
            // }
            // if(this.formData.temperatureTimeStd && !validateHhMm(this.formData.temperatureTimeStd)) {
            //     this.errors.temperatureTimeStd = this.$t('Invalid time format');
            //     valid = false;
            // }
            // if(this.formData.pickUpTime && !validateHhMm(this.formData.pickUpTime)) {
            //     this.errors.pickUpTime = this.$t('Invalid time format');
            //     valid = false;
            // }
            this.hours.forEach(element => {
                if(this.formData[`temperature${('0' + element.time).slice(-2)}School`] && this.formData[`temperature${('0' + element.time).slice(-2)}School`] < 0) {
                    this.errors[`temperature${('0' + element.time).slice(-2)}School`] = this.$t('Please input positive number');
                    valid = false;
                }
                if(this.formData[`temperature${('0' + element.time).slice(-2)}School`] && (this.formData[`temperature${('0' + element.time).slice(-2)}School`] < 34 || this.formData[`temperature${('0' + element.time).slice(-2)}School`] > 42)) {
                    this.errors[`temperature${('0' + element.time).slice(-2)}School`] = this.$t('Incorrect temperature value');
                    valid = false;
                }
            });
            this.hours.forEach(element => {
                if(this.formData[`meal${element.time}School`] && this.formData[`meal${element.time}School`].length > 200) {
                    this.errors[`meal${element.time}School`] = this.$t('Please enter 50 characters or less');
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
        exportExcel() {
            const date = moment(this.selectedDate).format('YYYY-MM-DD');
            location.href = process.env.MIX_APP_BASE_URL + 'childcare-contact-book/excel/' + this.child.id + '/?date=' + date + '&token=' + LocalStorage.getToken();
        },
        formatMoodStatus(status) {
            if(status == 1) {
                return '普通';
            } else if(status == 2) {
                return '良い';
            } else if(status == 3) {
                return '悪い';
            } else {
                return '';
            }
        },
        formatTimeStd(timeStd) {
            if(timeStd) {
                return moment(timeStd, 'hh:mm:ss').format('HH:mm');
            }
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
    mounted() {
        if (this.$route.query.date) {
            this.date = moment(this.$route.query.date, 'YYYY-MM-DD');
            this.todayDate = moment(this.$route.query.date, 'YYYY-MM-DD').format('YYYY年 M月 D日 (ddd)');
            this.selectedDate = this.$route.query.date;
        }
        this.todayDate = this.getCurrentDate().toString();
        this.selectedDate = this.date;
        this.convertToFormData();
        this.initFormError();
        if(this.child.admissionDate) {
            this.disabledDates = {to: moment(this.child.admissionDate).toDate()};
        }
    }
}
</script>
<style scoped>
    .calendar-center {
        align-items: center;
        display: flex;
        justify-content: center;
    }

    .calendar-left {
        align-items: center;
        display: flex;
        justify-content: left;
    }

    .calendar-title {
        align-items: center;
        display: flex;
    }

    @media (max-width: 500px) {
       h5.card-title {
           font-size: 13px!important;
       }
    }
</style>
