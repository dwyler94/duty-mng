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
                                <h5 class="card-title mb-0 pr-md-5">{{ currentOfficeName }}</h5>
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
                            <div class="align-items-center col-md-5 col-12 d-flex mb-2">
                                <label class="mb-0" for="mindername" style="min-width: 80px;">保育士名：</label>
                                {{ formData.nurseName }}
                            </div>
                        </div>
                        <div class="row" style="padding-left:15px; padding-right:15px;">
                            <div class="col-md-2 col-4 contact-book-cell dark-pink text-white">お迎え予定</div>
                            <div class="col-md-4 col-8 contact-book-cell light-pink">
                                <hour-minute-input
                                    v-model="formData.pickUpTime"
                                    :error="errors.pickUpTime"
                                    @input="dataChanged = true; errors.pickUpTime = null;"
                                />
                            </div>
                            <div class="col-md-2 col-4 contact-book-cell dark-pink text-white">お迎えの方</div>
                            <div class="col-md-4 col-8 contact-book-cell flex-wrap light-pink">
                                <input
                                    class="form-control"
                                    style="max-width: 55%;"
                                    type="text"
                                    v-model="formData.pickUpPerson"
                                    :class="{'is-invalid': errors.pickUpPerson}"
                                    @change="dataChanged = true; errors.pickUpPerson = null; inputError = false;"
                                />
                                <span class="error invalid-feedback text-center" v-if="errors.pickUpPerson">
                                    {{ errors.pickUpPerson }}
                                </span>
                            </div>
                        </div>
                        <br>
                        <div class="mobile-tab-group d-md-none">
                            <div class="mobile-tab parent" :class="{active: tab == PARENT}" @click="tab = PARENT">家庭より</div>
                            <div class="mobile-tab school" :class="{active: tab == SCHOOL}" @click="tab = SCHOOL">保育園より</div>
                        </div>
                        <div class="form-group row mb-0">
                            <div class="col-md-6 col-12 d-none d-md-block mt-md-0" :class="{'d-block mt-1': tab == PARENT}">
                                <div class="border-left border-right border-white dark-blue d-none d-md-block fixed-height-40 mb-1 py-2 text-center text-white">家庭より</div>
                                <div class="form-group row">
                                    <div class="col-md-1 col-12 pr-md-0">
                                        <div class="align-items-center border border-white dark-brown d-flex h-100 justify-content-center text-center">
                                            <label class="mb-0">食事</label>
                                        </div>
                                    </div>
                                    <div v-if="!isMobile()" class="col-md-11">
                                        <div class="row row-mobile">
                                            <div class="col-md-5 contact-book-cell light-brown">
                                                <hour-minute-input
                                                    v-model="formData.mealTime1Home"
                                                    :error="errors.mealTime1Home"
                                                    @input="dataChanged = true; errors.mealTime1Home = null; inputError = false;"
                                                />
                                            </div>
                                            <div class="col-md-7 contact-book-cell light-brown">
                                                <custom-radio
                                                    name="radioMeal1"
                                                    v-model="formData.mealAmount1Home"
                                                    :options="{'完食': 1, '残食': 2, 'おかわり': 3}"
                                                    @input="dataChanged = true;"
                                                ></custom-radio>
                                            </div>
                                            <div class="col-md-12 contact-book-cell light-brown">
                                                <input
                                                    class="form-control"
                                                    placeholder="メモ"
                                                    type="text"
                                                    v-model="formData.mealMemo1Home"
                                                    :class="{'is-invalid': errors.mealMemo1Home}"
                                                    @change="dataChanged = true; errors.mealMemo1Home = null; inputError = false;"
                                                />
                                                <span v-if="errors.mealMemo1Home" class="error invalid-feedback">
                                                    {{ errors.mealMemo1Home }}
                                                </span>
                                            </div>
                                            <div class="col-md-5 contact-book-cell light-brown">
                                                <hour-minute-input
                                                    v-model="formData.mealTime2Home"
                                                    :error="errors.mealTime2Home"
                                                    @input="dataChanged = true; errors.mealTime2Home = null; inputError = false;"
                                                />
                                            </div>
                                            <div class="col-md-7 contact-book-cell light-brown">
                                                <custom-radio
                                                    name="radioMeal2"
                                                    v-model="formData.mealAmount2Home"
                                                    :options="{'完食': 1, '残食': 2, 'おかわり': 3}"
                                                    @input="dataChanged = true;"
                                                ></custom-radio>
                                            </div>
                                            <div class="col-md-12 contact-book-cell light-brown">
                                                <input
                                                    class="form-control"
                                                    placeholder="メモ"
                                                    type="text"
                                                    v-model="formData.mealMemo2Home"
                                                    :class="{'is-invalid': errors.mealMemo2Home}"
                                                    @change="dataChanged = true; errors.mealMemo2Home = null; inputError = false;"
                                                />
                                                <span v-if="errors.mealMemo2Home" class="error invalid-feedback">
                                                    {{ errors.mealMemo2Home }}
                                                </span>
                                            </div>
                                            <div class="col-md-5 contact-book-cell light-brown">
                                                <hour-minute-input
                                                    v-model="formData.mealTime3Home"
                                                    :error="errors.mealTime3Home"
                                                    @input="dataChanged = true; errors.mealTime3Home = null; inputError = false;"
                                                />
                                            </div>
                                            <div class="col-md-7 contact-book-cell light-brown">
                                                <custom-radio
                                                    name="radioMeal3"
                                                    v-model="formData.mealAmount3Home"
                                                    :options="{'完食': 1, '残食': 2, 'おかわり': 3}"
                                                    @input="dataChanged = true;"
                                                ></custom-radio>
                                            </div>
                                           <div class="col-md-12 contact-book-cell light-brown">
                                                <input
                                                    class="form-control"
                                                    placeholder="メモ"
                                                    type="text"
                                                    v-model="formData.mealMemo3Home"
                                                    :class="{'is-invalid': errors.mealMemo3Home}"
                                                    @change="dataChanged = true; errors.mealMemo3Home = null; inputError = false;"
                                                />
                                                <span v-if="errors.mealMemo3Home" class="error invalid-feedback">
                                                    {{ errors.mealMemo3Home }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 px-0" v-else>
                                        <div class="row row-mobile">
                                            <div class="col-12 mb-1" v-for="i in formCount.meal" :key="i">
                                                <div class="contact-book-cell light-brown">
                                                    <hour-minute-input
                                                        v-model="formData[`mealTime${i}Home`]"
                                                        :error="errors[`mealTime${i}Home`]"
                                                        @input="dataChanged = true; errors[`mealTime${i}Home`] = null; inputError = false;"
                                                    />
                                                </div>
                                                <div class="contact-book-cell light-brown">
                                                    <custom-radio
                                                        v-model="formData[`mealAmount${i}Home`]"
                                                        :name="`radioMeal${i}`"
                                                        :options="{'完食': 1, '残食': 2, 'おかわり': 3}"
                                                        @input="dataChanged = true;"
                                                    ></custom-radio>
                                                </div>
                                                <div class="contact-book-cell light-brown">
                                                    <input
                                                        class="form-control text-truncate"
                                                        placeholder="メモ"
                                                        type="text"
                                                        v-model="formData[`mealMemo${i}Home`]"
                                                        :class="{'is-invalid': errors[`mealMemo${i}Home`]}"
                                                        @click="showMemoEditForm(`mealMemo${i}Home`)"
                                                        @change="dataChanged = true; errors[`mealMemo${i}Home`] = null; inputError = false;"
                                                    />
                                                    <span class="error invalid-feedback" v-if="errors[`mealMemo${i}Home`]">
                                                        {{ errors[`mealMemo${i}Home`] }}
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="col-12 text-right">
                                                <button class="btn btn-primary" @click="onDelMealForm()" :disabled="formCount.meal < 2 ">
                                                    <i class="fas fa-minus"></i>
                                                </button>
                                                <button class="btn btn-primary ml-2" @click="onAddForm('meal')" :disabled="formCount.meal > 2">
                                                    <i class="fas fa-plus"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-md-1 col-12 pr-md-0">
                                        <div class="align-items-center border border-white dark-brown d-flex h-100 justify-content-center text-center">
                                            <label class="mb-0">機嫌</label>
                                        </div>
                                    </div>
                                     <div class="col-md-11 col-12">
                                        <div class="row row-mobile">
                                            <div class="col-md-3 col-12 contact-book-cell time-zone-label">
                                                前夜
                                            </div>
                                            <div class="col-md-9 col-12 contact-book-cell light-brown">
                                                <custom-radio
                                                    name="radioMood1"
                                                    v-model="formData.mood1Home"
                                                    :options="{'普通': 1, '良い': 2, '悪い': 3}"
                                                    @input="dataChanged = true;"
                                                ></custom-radio>
                                            </div>
                                            <div class="col-md-3 col-12 contact-book-cell time-zone-label">
                                                今朝
                                            </div>
                                            <div class="col-md-9 col-12 contact-book-cell light-brown">
                                                <custom-radio
                                                    name="radioMood2"
                                                    v-model="formData.mood2Home"
                                                    :options="{'普通': 1, '良い': 2, '悪い': 3}"
                                                    @input="dataChanged = true;"
                                                ></custom-radio>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-md-1 col-12 pr-md-0">
                                        <div class="align-items-center border border-white dark-brown d-flex h-100 justify-content-center text-center">
                                            <label class="mb-0">排便</label>
                                        </div>
                                    </div>
                                    <div v-if="!isMobile()" class="col-md-11 col-10">
                                        <div class="row row-mobile">
                                            <div class="col-md-5 contact-book-cell light-brown">
                                                <hour-minute-input
                                                    v-model="formData.defecationTime1Home"
                                                    :error="errors.defecationTime1Home"
                                                    @input="dataChanged = true; errors.defecationTime1Home = null; inputError = false;"
                                                />
                                            </div>
                                            <div class="col-md-7 contact-book-cell light-brown">
                                                <custom-radio
                                                    name="radioDefecation1"
                                                    v-model="formData.defecation1Home"
                                                    :options="{'普': 1, '軟': 2, '固': 3}"
                                                    @input="dataChanged = true;"
                                                ></custom-radio>
                                            </div>
                                            <div class="col-md-12 col-12 contact-book-cell light-brown">
                                                <input
                                                    class="form-control"
                                                    placeholder="メモ"
                                                    type="text"
                                                    v-model="formData.defecationMemo1Home"
                                                    :class="{'is-invalid': errors.defecationMemo1Home}"
                                                    @change="dataChanged = true; errors.defecationMemo1Home = null; inputError = false;"
                                                />
                                                <span class="error invalid-feedback" v-if="errors.defecationMemo1Home">
                                                    {{ errors.defecationMemo1Home }}
                                                </span>
                                            </div>
                                            <div class="col-md-5 contact-book-cell light-brown">
                                                <hour-minute-input
                                                    v-model="formData.defecationTime2Home"
                                                    :error="errors.defecationTime2Home"
                                                    @input="dataChanged = true; errors.defecationTime2Home = null; inputError = false;"
                                                />
                                            </div>
                                            <div class="col-md-7 contact-book-cell light-brown">
                                                <custom-radio
                                                    name="radioDefecation2"
                                                    v-model="formData.defecation2Home"
                                                    :options="{'普': 1, '軟': 2, '固': 3}"
                                                    @input="dataChanged = true;"
                                                ></custom-radio>
                                            </div>
                                            <div class="col-md-12 col-12 contact-book-cell light-brown">
                                                <input
                                                    class="form-control"
                                                    placeholder="メモ"
                                                    type="text"
                                                    v-model="formData.defecationMemo2Home"
                                                    :class="{'is-invalid': errors.defecationMemo2Home}"
                                                    @change="dataChanged = true; errors.defecationMemo2Home = null; inputError = false;"
                                                />
                                                <span class="error invalid-feedback" v-if="errors.defecationMemo2Home">
                                                    {{ errors.defecationMemo2Home }}
                                                </span>
                                            </div>
                                            <div class="col-md-5 contact-book-cell light-brown">
                                                <hour-minute-input
                                                    v-model="formData.defecationTime3Home"
                                                    :error="errors.defecationTime3Home"
                                                    @input="dataChanged = true; errors.defecationTime3Home = null; inputError = false;"
                                                />
                                            </div>
                                            <div class="col-md-7 contact-book-cell light-brown">
                                                <custom-radio
                                                    name="radioDefecation3"
                                                    v-model="formData.defecation3Home"
                                                    :options="{'普': 1, '軟': 2, '固': 3}"
                                                    @input="dataChanged = true;"
                                                ></custom-radio>
                                            </div>
                                            <div class="col-md-12 col-12 contact-book-cell light-brown">
                                                <input
                                                    class="form-control"
                                                    placeholder="メモ"
                                                    type="text"
                                                    v-model="formData.defecationMemo3Home"
                                                    :class="{'is-invalid': errors.defecationMemo3Home}"
                                                    @change="dataChanged = true; errors.defecationMemo3Home = null; inputError = false;"
                                                />
                                                <span class="error invalid-feedback" v-if="errors.defecationMemo3Home">
                                                    {{ errors.defecationMemo3Home }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 px-0" v-else>
                                        <div class="row row-mobile">
                                            <div class="col-12 mb-1" v-for="i in formCount.defecation" :key="i">
                                                <div class="contact-book-cell light-brown">
                                                    <hour-minute-input
                                                        v-model="formData[`defecationTime${i}Home`]"
                                                        :error="errors[`defecationTime${i}Home`]"
                                                        @input="dataChanged = true;
                                                        errors[`defecationTime${i}Home`] = null;
                                                        inputError = false;"/>
                                                </div>
                                                <div class="contact-book-cell light-brown">
                                                    <custom-radio
                                                        v-model="formData[`defecation${i}Home`]"
                                                        :name="`radioDefecation${i}Home`"
                                                        :options="{'普': 1, '軟': 2, '固': 3}"
                                                        @input="dataChanged = true;"
                                                    ></custom-radio>
                                                </div>
                                                <div class="contact-book-cell light-brown">
                                                    <input
                                                        class="form-control text-truncate"
                                                        placeholder="メモ"
                                                        type="text"
                                                        v-model="formData[`defecationMemo${i}Home`]"
                                                        :class="{'is-invalid': errors[`defecationMemo${i}Home`]}"
                                                        @click="showMemoEditForm(`defecationMemo${i}Home`)"
                                                        @change="dataChanged = true; errors[`defecationMemo${i}Home`] = null; inputError = false;"
                                                    />
                                                    <span class="error invalid-feedback" v-if="errors[`defecationMemo${i}Home`]">
                                                        {{ errors[`defecationMemo${i}Home`] }}
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="col-12 text-right">
                                                <button class="btn btn-primary" @click="onDelDefecationForm()" :disabled="formCount.defecation < 2 ">
                                                    <i class="fas fa-minus"></i>
                                                </button>
                                                <button class="btn btn-primary ml-2" @click="onAddForm('defecation')" :disabled="formCount.defecation > 2">
                                                    <i class="fas fa-plus"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-md-1 col-12 pr-md-0">
                                        <div class="align-items-center border border-white dark-brown d-flex h-100 justify-content-center text-center">
                                            <label class="mb-0">睡眠</label>
                                        </div>
                                    </div>
                                    <div class="col-md-11" v-if="!isMobile()" >
                                        <div class="row row-mobile">
                                            <div class="col-md-12 contact-book-cell light-brown">
                                                <hour-minute-input
                                                    v-model="formData.sleepStart1Home"
                                                    :error="errors.sleepStart1Home"
                                                    @input="dataChanged = true; errors.sleepStart1Home = null; inputError = false;"
                                                />
                                                ~
                                                <hour-minute-input
                                                    v-model="formData.sleepEnd1Home"
                                                    :error="errors.sleepEnd1Home"
                                                    @input="dataChanged = true; errors.sleepEnd1Home = null; inputError = false;"
                                                />
                                            </div>
                                            <div class="col-md-12 contact-book-cell light-brown">
                                                <hour-minute-input
                                                    v-model="formData.sleepStart2Home"
                                                    :error="errors.sleepStart2Home"
                                                    @input="dataChanged = true; errors.sleepStart2Home = null; inputError = false;"
                                                />
                                                ~
                                                <hour-minute-input
                                                    v-model="formData.sleepEnd2Home"
                                                    :error="errors.sleepEnd2Home"
                                                    @input="dataChanged = true; errors.sleepEnd2Home = null; inputError = false;"
                                                />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 px-0" v-else>
                                        <div class="row row-mobile">
                                            <div class="col-12 mb-1" v-for="i in formCount.sleep" :key="i">
                                                <div class="contact-book-cell light-brown">
                                                    <hour-minute-input
                                                        v-model="formData[`sleepStart${i}Home`]"
                                                        :error="errors[`sleepStart${i}Home`]"
                                                        @input="dataChanged = true; errors[`sleepStart${i}Home`] = null; inputError = false;"
                                                    />
                                                    ~
                                                    <hour-minute-input
                                                        v-model="formData[`sleepEnd${i}Home`]"
                                                        :error="errors[`sleepEnd${i}Home`]"
                                                        @input="dataChanged = true; errors[`sleepEnd${i}Home`] = null; inputError = false;"
                                                    />
                                                </div>
                                             </div>
                                        </div>
                                        <div class="col-12 text-right">
                                            <button class="btn btn-primary" @click="onDelSleepForm()" :disabled="formCount.sleep == 1 ">
                                                <i class="fas fa-minus aaaaa"></i>
                                            </button>
                                            <button class="btn btn-primary ml-2" @click="onAddForm('sleep')" :disabled="formCount.sleep == 2">
                                                <i class="fas fa-plus"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-md-1 col-12 pr-md-0">
                                        <div class="align-items-center border border-white dark-brown d-flex h-100 justify-content-center text-center">
                                            <label class="mb-0">入浴</label>
                                        </div>
                                    </div>
                                    <div class="col-md-11 col-12">
                                        <div class="row row-mobile">
                                            <div class="col-md-12 col-12 contact-book-cell light-brown">
                                                <custom-radio
                                                    name="radioBathingHome"
                                                    v-model="formData.bathingHome"
                                                    :options="{'有り': 1, '無し': 2}"
                                                    @input="dataChanged = true;"
                                                ></custom-radio>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-md-1 col-12 pr-md-0">
                                        <div class="align-items-center border border-white dark-brown d-flex h-100 justify-content-center text-center">
                                            <label class="mb-0">検温</label>
                                        </div>
                                    </div>
                                    <div v-if="!isMobile()" class="col-md-11">
                                        <div class="row row-mobile">
                                            <div class="col-md-4 contact-book-cell light-brown">
                                                <hour-minute-input
                                                    v-model="formData.temperatureTime1Home"
                                                    :error="errors.temperatureTime1Home"
                                                    @input="dataChanged = true; errors.temperatureTime1Home = null; inputError = false;"
                                                />
                                            </div>
                                            <div class="col-md-4 contact-book-cell light-brown">
                                                <hour-minute-input
                                                    v-model="formData.temperatureTime2Home"
                                                    :error="errors.temperatureTime2Home"
                                                    @input="dataChanged = true; errors.temperatureTime2Home = null; inputError = false;"
                                                />
                                            </div>
                                            <div class="col-md-4 contact-book-cell light-brown">
                                                <hour-minute-input
                                                    v-model="formData.temperatureTime3Home"
                                                    :error="errors.temperatureTime3Home"
                                                    @input="dataChanged = true; errors.temperatureTime3Home = null; inputError = false;"
                                                />
                                            </div>
                                            <div class="col-md-4 contact-book-cell light-brown">
                                                <input
                                                    class="form-control form-control-temp"
                                                    max="42"
                                                    min="32"
                                                    type="number"
                                                    v-model="formData.temperature1Home"
                                                    :class="{'is-invalid': errors.temperature1Home}"
                                                    @change="dataChanged = true; errors.temperature1Home = null; inputError = false;"
                                                />
                                                <span v-if="errors.temperature1Home" class="error invalid-feedback">
                                                    {{ errors.temperature1Home }}
                                                </span>
                                                <label class="mb-0">℃</label>
                                            </div>
                                            <div class="col-md-4 contact-book-cell light-brown">
                                                <input
                                                    class="form-control form-control-temp"
                                                    max="42"
                                                    min="32"
                                                    type="number"
                                                    v-model="formData.temperature2Home"
                                                    :class="{'is-invalid': errors.temperature2Home}"
                                                    @change="dataChanged = true; errors.temperature2Home = null; inputError = false;"
                                                />
                                                <span v-if="errors.temperature2Home" class="error invalid-feedback">
                                                    {{ errors.temperature2Home }}
                                                </span>
                                                <label class="mb-0">℃</label>
                                            </div>
                                            <div class="col-md-4 contact-book-cell light-brown">
                                                <input
                                                    class="form-control form-control-temp"
                                                    max="42"
                                                    min="32"
                                                    type="number"
                                                    v-model="formData.temperature3Home"
                                                    :class="{'is-invalid': errors.temperature3Home}"
                                                    @change="dataChanged = true; errors.temperature3Home = null; inputError = false;"
                                                />
                                                <span v-if="errors.temperature3Home" class="error invalid-feedback">
                                                    {{ errors.temperature3Home }}
                                                </span>
                                                <label class="mb-0">℃</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div v-else class="col-12">
                                        <div class="row row-mobile mb-1" v-for="i in formCount.temperature" :key="i">
                                            <div class="col-6 contact-book-cell light-brown">
                                                <hour-minute-input
                                                    v-model="formData[`temperatureTime${i}Home`]"
                                                    :error="errors[`temperatureTime${i}Home`]"
                                                    @input="dataChanged = true; errors[`temperatureTime${i}Home`] = null; inputError = false;"
                                                />
                                            </div>
                                            <div class="col-6 contact-book-cell flex-wrap light-brown">
                                                <input
                                                    class="form-control form-control-temp"
                                                    max="42"
                                                    min="32"
                                                    type="number"
                                                    v-model="formData[`temperature${i}Home`]"
                                                    :class="{'is-invalid': errors[`temperature${i}Home`]}"
                                                    @change="dataChanged = true; errors[`temperature${i}Home`] = null; inputError = false;"
                                                />
                                                <label class="mb-0">℃</label>
                                                <div class="w-100 error invalid-feedback text-center" v-if="errors[`temperature${i}Home`]">
                                                    {{ errors[`temperature${i}Home`] }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 text-right pr-0">
                                            <button class="btn btn-primary" @click="onDelTempForm()" :disabled="formCount.temperature < 2 ">
                                                <i class="fas fa-minus"></i>
                                            </button>
                                            <button class="btn btn-primary ml-2" @click="onAddForm('temperature')" :disabled="formCount.temperature > 2">
                                                <i class="fas fa-plus"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 col-12">
                                        <div class="dark-blue text-center py-2 text-white">
                                            家庭での様子・連絡事項
                                        </div>
                                        <div class="light-blue mt-1 p-3 contact-area">
                                            <textarea class="form-control" v-model="formData.state0Home" @change="dataChanged = true;" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-12 d-none d-md-block mt-md-0" :class="{'d-block mt-1': tab == SCHOOL}">
                                <div class="border-left border-right border-white dark-yellow d-none d-md-block fixed-height-40 mb-1 py-2 text-center text-white">保育園より</div>
                                <div class="form-group row">
                                    <div class="col-md-1 col-12 pr-md-0">
                                        <div class="align-items-center border border-white dark-brown d-flex h-100 justify-content-center text-center">
                                            <label class="mb-0">食事</label>
                                        </div>
                                    </div>
                                    <div class="col-md-11 col-12">
                                        <div class="row row-mobile">
                                            <template v-if="!isMobile()">
                                                <div class="col-md-7 col-12 contact-book-cell light-brown">
                                                    {{ formatTimeStd(formData.mealTime1School) }}
                                                </div>
                                                <div class="col-md-5 col-12 contact-book-cell light-brown">
                                                    {{ formatMealStatus(formData.mealAmount1School) }}
                                                </div>
                                                <div class="col-md-12 col-12 contact-book-cell justify-content-start light-brown">
                                                    {{ formData.mealMemo1School }}
                                                </div>
                                                <div class="col-md-7 col-12 contact-book-cell light-brown">
                                                    {{ formatTimeStd(formData.mealTime2School) }}
                                                </div>
                                                <div class="col-md-5 col-12 contact-book-cell light-brown">
                                                    {{ formatMealStatus(formData.mealAmount2School) }}
                                                </div>
                                                <div class="col-md-12 col-12 contact-book-cell justify-content-start light-brown">
                                                    {{ formData.mealMemo2School }}
                                                </div>
                                                <div class="col-md-7 col-12 contact-book-cell light-brown">
                                                    {{ formatTimeStd(formData.mealTime3School) }}
                                                </div>
                                                <div class="col-md-5 col-12 contact-book-cell light-brown">
                                                    {{ formatMealStatus(formData.mealAmount3School) }}
                                                </div>
                                                <div class="col-md-12 col-12 contact-book-cell justify-content-start light-brown">
                                                    {{ formData.mealMemo3School }}
                                                </div>
                                            </template>
                                            <div v-else class="w-100" v-for="i in 3" :key="i">
                                                <template v-if="i == 1">
                                                    <div class="col-md-3 col-12 contact-book-cell time-zone-label">
                                                        {{ i }} 食目
                                                    </div>
                                                    <div class="col-md-7 col-12 contact-book-cell light-brown">
                                                        {{ formatTimeStd(formData.mealTime1School) }}
                                                    </div>
                                                    <div class="col-md-5 col-12 contact-book-cell light-brown">
                                                        {{ formatMealStatus(formData.mealAmount1School) }}
                                                    </div>
                                                    <div class="col-md-12 col-12 contact-book-cell justify-content-start light-brown">
                                                        {{ formData.mealMemo1School }}
                                                    </div>
                                                </template>
                                                <template v-else-if="formData[`mealTime${i}School`] || formData[`mealAmount${i}School`] || formData[`mealMemo${i}School`]">
                                                    <div class="col-md-3 col-12 contact-book-cell time-zone-label">
                                                        {{ i }} 食目
                                                    </div>
                                                    <div class="col-md-7 col-12 contact-book-cell light-brown">
                                                        {{ formatTimeStd(formData[`mealTime${i}School`]) }}
                                                    </div>
                                                    <div class="col-md-5 col-12 contact-book-cell light-brown">
                                                        {{ formatMealStatus(formData[`mealAmount${i}School`]) }}
                                                    </div>
                                                    <div class="col-md-12 col-12 contact-book-cell justify-content-start light-brown">
                                                        {{ formData[`mealMemo${i}School`] }}
                                                    </div>
                                                </template>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-md-1 col-12 pr-md-0">
                                        <div class="align-items-center border border-white dark-brown d-flex h-100 justify-content-center text-center">
                                            <label class="mb-0">機嫌</label>
                                        </div>
                                    </div>
                                    <div class="col-md-11 col-12">
                                        <div class="row row-mobile">
                                            <div class="col-md-3 col-12 contact-book-cell light-brown time-zone-label">
                                                午前
                                            </div>
                                            <div class="col-md-9 col-12 contact-book-cell light-brown">
                                                {{ formatStatus(formData.mood1School) }}
                                            </div>
                                            <div class="col-md-3 col-12 contact-book-cell light-brown time-zone-label">
                                                午後
                                            </div>
                                            <div class="col-md-9 col-12 contact-book-cell light-brown">
                                                {{ formatStatus(formData.mood2School) }}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-md-1 col-12 pr-md-0">
                                        <div class="align-items-center border border-white dark-brown d-flex h-100 justify-content-center text-center">
                                            <label class="mb-0">排便</label>
                                        </div>
                                    </div>
                                    <div class="col-md-11 col-12">
                                        <div class="row row-mobile">
                                            <template v-if="!isMobile()">
                                                <div class="col-md-7 col-12 contact-book-cell light-brown">
                                                    {{ formatTimeStd(formData.defecationTime1School) }}
                                                </div>
                                                <div class="col-md-5 col-12 contact-book-cell light-brown">
                                                    {{ formatDefecationStatus(formData.defecation1School) }}
                                                </div>
                                                <div class="col-md-12 col-12 contact-book-cell justify-content-start light-brown">
                                                    {{ formData.defecationMemo1School }}
                                                </div>
                                                <div class="col-md-7 col-12 contact-book-cell light-brown">
                                                    {{ formatTimeStd(formData.defecationTime2School) }}
                                                </div>
                                                <div class="col-md-5 col-12 contact-book-cell light-brown">
                                                    {{ formatDefecationStatus(formData.defecation2School) }}
                                                </div>
                                                <div class="col-md-12 col-12 contact-book-cell justify-content-start light-brown">
                                                    {{ formData.defecationMemo2School }}
                                                </div>
                                                <div class="col-md-7 col-12 contact-book-cell light-brown">
                                                    {{ formatTimeStd(formData.defecationTime3School) }}
                                                </div>
                                                <div class="col-md-5 col-12 contact-book-cell light-brown">
                                                    {{ formatDefecationStatus(formData.defecation3School) }}
                                                </div>
                                                <div class="col-md-12 col-12 contact-book-cell justify-content-start light-brown">
                                                    {{ formData.defecationMemo3School }}
                                                </div>
                                            </template>
                                            <div class="w-100" v-else v-for="i in 3" :key="i">
                                                <template v-if="i == 1">
                                                    <div class="col-md-3 col-12 contact-book-cell time-zone-label">
                                                        {{ i }} 回目
                                                    </div>
                                                    <div class="col-md-7 col-12 contact-book-cell light-brown">
                                                        {{ formatTimeStd(formData.defecationTime1School) }}
                                                    </div>
                                                    <div class="col-md-5 col-12 contact-book-cell light-brown">
                                                        {{ formatDefecationStatus(formData.defecation1School) }}
                                                    </div>
                                                    <div class="col-md-12 col-12 contact-book-cell justify-content-start light-brown">
                                                        {{ formData.defecationMemo1School }}
                                                    </div>
                                                </template>
                                                <template v-else-if="formData[`defecationTime${i}School`] || formData[`defecation${i}School`] || formData[`defecationMemo${i}School`]">
                                                    <div class="col-md-3 col-12 contact-book-cell time-zone-label">
                                                        {{ i }} 回目
                                                    </div>
                                                    <div class="col-md-7 col-12 contact-book-cell light-brown">
                                                        {{ formatTimeStd(formData[`defecationTime${i}School`]) }}
                                                    </div>
                                                    <div class="col-md-5 col-12 contact-book-cell light-brown">
                                                        {{ formatDefecationStatus(formData[`defecation${i}School`]) }}
                                                    </div>
                                                    <div class="col-md-12 col-12 contact-book-cell justify-content-start light-brown">
                                                        {{ formData[`defecationMemo${i}School`] }}
                                                    </div>
                                                </template>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-md-1 col-12 pr-md-0">
                                        <div class="align-items-center border border-white dark-brown d-flex h-100 justify-content-center text-center">
                                            <label class="mb-0">睡眠</label>
                                        </div>
                                    </div>
                                    <div class="col-md-11 col-12">
                                        <div class="row row-mobile">
                                            <div class="col-md-12 col-12 contact-book-cell light-brown">
                                                {{ formatSleepTime(formData.sleepStart1School, formData.sleepEnd1School) }}
                                            </div>
                                            <div class="col-md-12 col-12 contact-book-cell light-brown">
                                                {{ formatSleepTime(formData.sleepStart2School, formData.sleepEnd2School) }}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-md-1 col-12 pr-md-0">
                                        <div class="align-items-center border border-white dark-brown d-flex h-100 justify-content-center text-center">
                                            <label class="mb-0">沐浴</label>
                                        </div>
                                    </div>
                                    <div class="col-md-11 col-12">
                                        <div class="row row-mobile">
                                            <div class="col-md-12 col-12 contact-book-cell light-brown">
                                                {{ formatBathStatus(formData.bathingSchool) }}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-md-1 col-12 pr-md-0">
                                        <div class="align-items-center border border-white dark-brown d-flex h-100 justify-content-center text-center">
                                            <label class="mb-0">検温</label>
                                        </div>
                                    </div>
                                    <div v-if="!isMobile()" class="col-md-11 col-10">
                                        <div class="row row-mobile">
                                            <div class="col-md-4 col-12 contact-book-cell light-brown">
                                                {{ formatTimeStd(formData.temperatureTime1School) }}
                                            </div>
                                            <div class="col-md-4 col-12 contact-book-cell light-brown">
                                                {{ formatTimeStd(formData.temperatureTime2School) }}
                                            </div>
                                            <div class="col-md-4 col-12 contact-book-cell light-brown">
                                                {{ formatTimeStd(formData.temperatureTime3School) }}
                                            </div>
                                            <div class="col-md-4 col-12 contact-book-cell light-brown">
                                                {{ formatTemperature(formData.temperature1School) }}
                                            </div>
                                            <div class="col-md-4 col-12 contact-book-cell light-brown">
                                                {{ formatTemperature(formData.temperature2School) }}
                                            </div>
                                            <div class="col-md-4 col-12 contact-book-cell light-brown">
                                                {{ formatTemperature(formData.temperature3School) }}
                                            </div>
                                        </div>
                                    </div>
                                    <div v-else class="col-12">
                                        <div class="row row-mobile">
                                            <div class="col-md-6 col-6 contact-book-cell light-brown">
                                                {{ formatTimeStd(formData.temperatureTime1School) }}
                                            </div>
                                            <div class="col-md-6 col-6 contact-book-cell light-brown">
                                                {{ formatTemperature(formData.temperature1School) }}
                                            </div>
                                            <template v-if="formData.temperatureTime2School || formData.temperature2School">
                                                <div class="col-md-6 col-6 contact-book-cell light-brown">
                                                    {{ formatTimeStd(formData.temperatureTime2School) }}
                                                </div>
                                                <div class="col-md-6 col-6 contact-book-cell light-brown">
                                                    {{ formatTemperature(formData.temperature2School) }}
                                                </div>
                                            </template>
                                            <template v-if="formData.temperatureTime3School || formData.temperature3School">
                                                <div class="col-md-6 col-6 contact-book-cell light-brown">
                                                    {{ formatTimeStd(formData.temperatureTime3School) }}
                                                </div>
                                                <div class="col-md-6 col-6 contact-book-cell light-brown">
                                                    {{ formatTemperature(formData.temperature3School) }}
                                                </div>
                                            </template>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-12">
                                        <div class="dark-yellow py-2 text-center text-white">
                                            保育園での様子・連絡事項
                                        </div>
                                        <div class="light-yellow p-3 mt-1 contact-area"
                                            v-text="formData.state0School" >
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="align-items-center d-flex fixed-btn-group float-right mt-3" :class="{'is-invalid': inputError}">
                            <button class="btn btn-primary float-right mr-md-2" @click="saveContact">登録</button>
                            <button class="btn btn-primary float-right" @click="exportExcel">Excel出力</button>
                        </div>
                        <div v-if="inputError" class="error invalid-feedback text-right" style="margin-top: 75px;">
                            {{$t("Input error")}}
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
        <confirm-modal ref="ConfirmModal"></confirm-modal>
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
import { validateHhMm, changeToHhMm } from '../../helpers/datetime';
import LocalStorage from '../../helpers/localStorage';
import { isMobile, scrollToTop } from '../../helpers/mobile';
import CustomRadio from '../../components/CustomRadio.vue';
import MemoEditForm from '../ContactBook/MemoEditForm.vue';
import ScrollButton from '../../components/ScrollButton.vue';
import ConfirmModal from '../../components/ConfirmModal.vue';

const initialFormData = {
    date: new Date(),
    weather: '',
    mood: null,
    guardian: '',
    pickUpPerson: null,
    pickUpTime: null,
    mealTime1Home: null,
    mealTime2Home: null,
    mealTime3Home: null,
    mealAmount1Home: null,
    mealAmount2Home: null,
    mealAmount3Home: null,
    mealMemo1Home: '',
    mealMemo2Home: '',
    mealMemo3Home: '',
    mood1Home: null,
    mood2Home: null,
    defecationTime1Home: null,
    defecationTime2Home: null,
    defecationTime3Home: null,
    defecation1Home: null,
    defecation2Home: null,
    defecation3Home: null,
    defecationMemo1Home: null,
    defecationMemo2Home: null,
    defecationMemo3Home: null,
    sleepStart1Home: null,
    sleepEnd1Home: null,
    sleepStart2Home: null,
    sleepEnd2Home: null,
    bathingHome: null,
    temperatureTime1Home: null,
    temperature1Home: '',
    temperatureTime2Home: null,
    temperature2Home: '',
    temperatureTime3Home: null,
    temperature3Home: '',
    state0Home: '',
}

const PARENT = 1;
const SCHOOL = 2;

export default {
    components: {
        CustomRadio,
        Datepicker,
        HourMinuteInput,
        MemoEditForm,
        ScrollButton,
        ConfirmModal,
    },
    mixins: [actionLoading],
    computed: {
        ...mapState({
            currentOfficeName: state =>  {
                if (state.session.info.office) return state.session.info.office.name;
                return '';
            }
        })
    },
    props: {
        contact: {},
        child: {},
        date: null,
    },
    data () {
        return {
            dataChanged: false,
            formData: {...initialFormData},
            errors: {},
            formCount: {
                temperature: 1,
                meal: 1,
                sleep: 1,
                defecation: 1,
            },
            inputError: false,
            currentDate: new Date(),
            todayDate: "",
            disabledDates: {
                to: null,
            },
            selectedDate: new Date(),
            ja: ja,
            tab: PARENT,
            PARENT: PARENT,
            SCHOOL: SCHOOL,
        }
    },
    methods: {
        convertToFormData() {
            //this.initializeFormData();
            if (this.contact) {
                this.formData = {...this.contact};
                this.updateFormCounts(this.contact);
            }
        },
        initializeFormData() {
            this.formData = {

            };
        },
        saveContact() {
            if(this.actionLoading) return;
            if (!this.validate()) {
                scrollToTop();
                return;
            }
            this.carryingFormData();

            const requestData = Vue.util.extend({}, this.formData);
            requestData['date'] = moment(this.selectedDate).format('YYYY-MM-DD');
            if(this.formData.pickUpTime)
                requestData['pick_up_time'] = moment(this.formData.pickUpTime, 'h:mm:ss').format('HH:mm');

            if(this.formData.mealTime1Home)
                requestData['meal_time_1_home'] = moment(this.formData.mealTime1Home, 'h:mm:ss').format('HH:mm');

            if(this.formData.mealTime2Home)
                requestData['meal_time_2_home'] = moment(this.formData.mealTime2Home, 'h:mm:ss').format('HH:mm');

            if(this.formData.mealTime3Home)
                requestData['meal_time_3_home'] = moment(this.formData.mealTime3Home, 'h:mm:ss').format('HH:mm');

            if(this.formData.defecationTime1Home)
                requestData['defecation_time_1_home'] = moment(this.formData.defecationTime1Home, 'h:mm:ss').format('HH:mm');

            if(this.formData.defecationTime2Home)
                requestData['defecation_time_2_home'] = moment(this.formData.defecationTime2Home, 'h:mm:ss').format('HH:mm');

            if(this.formData.defecationTime3Home)
                requestData['defecation_time_3_home'] = moment(this.formData.defecationTime3Home, 'h:mm:ss').format('HH:mm');

            if(this.formData.sleepStart1Home)
                requestData['sleep_start_1_home'] = moment(this.formData.sleepStart1Home, 'h:mm:ss').format('HH:mm');

            if(this.formData.sleepEnd1Home)
                requestData['sleep_end_1_home'] = moment(this.formData.sleepEnd1Home, 'h:mm:ss').format('HH:mm');

            if(this.formData.sleepStart2Home)
                requestData['sleep_start_2_home'] = moment(this.formData.sleepStart2Home, 'h:mm:ss').format('HH:mm');

            if(this.formData.sleepEnd2Home)
                requestData['sleep_end_2_home'] = moment(this.formData.sleepEnd2Home, 'h:mm:ss').format('HH:mm');

            if(this.formData.temperatureTime1Home)
                requestData['temperature_time_1_home'] = moment(this.formData.temperatureTime1Home, 'h:mm:ss').format('HH:mm');

            if(this.formData.temperatureTime2Home)
                requestData['temperature_time_2_home'] = moment(this.formData.temperatureTime2Home, 'h:mm:ss').format('HH:mm');

            if(this.formData.temperatureTime3Home)
                requestData['temperature_time_3_home'] = moment(this.formData.temperatureTime3Home, 'h:mm:ss').format('HH:mm');

            this.setActionLoading();
            api.post('contact-book/child/' + this.child.id + '/home/1', null, requestData)
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
            if(!this.formData.pickUpPerson) {
                this.errors.pickUpPerson = this.$t('Please input');
                valid = false;
            }
            if(this.formData.pickUpPerson && this.formData.pickUpPerson.length > 20) {
                this.errors.pickUpPerson = this.$t('Please enter 20 characters or less');
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

            // meal
            this.formData.mealTime1Home = changeToHhMm(this.formData.mealTime1Home);
            if(this.formData.mealTime1Home && !validateHhMm(this.formData.mealTime1Home)) {
                this.errors.mealTime1Home = this.$t('Invalid time format');
                valid = false;
            }
            this.formData.mealTime2Home = changeToHhMm(this.formData.mealTime2Home);
            if(this.formData.mealTime2Home && !validateHhMm(this.formData.mealTime2Home)) {
                this.errors.mealTime2Home = this.$t('Invalid time format');
                valid = false;
            }
            this.formData.mealTime3Home = changeToHhMm(this.formData.mealTime3Home);
            if(this.formData.mealTime3Home && !validateHhMm(this.formData.mealTime3Home)) {
                this.errors.mealTime3Home = this.$t('Invalid time format');
                valid = false;
            }
            if(this.formData.mealMemo1Home && this.formData.mealMemo1Home.length > 200) {
                this.errors.mealMemo1Home = this.$t("Please enter 200 characters or less");
                valid = false;
            }
            if(this.formData.mealMemo2Home && this.formData.mealMemo2Home.length > 200) {
                this.errors.mealMemo2Home = this.$t("Please enter 200 characters or less");
                valid = false;
            }
            if(this.formData.mealMemo3Home && this.formData.mealMemo3Home.length > 200) {
                this.errors.mealMemo3Home = this.$t("Please enter 200 characters or less");
                valid = false;
            }

            this.formData.sleepStart1Home = changeToHhMm(this.formData.sleepStart1Home);
            if(this.formData.sleepStart1Home && !validateHhMm(this.formData.sleepStart1Home)) {
                this.errors.sleepStart1Home = this.$t('Invalid time format');
                valid = false;
            }
            this.formData.sleepEnd1Home = changeToHhMm(this.formData.sleepEnd1Home);
            if(this.formData.sleepEnd1Home && !validateHhMm(this.formData.sleepEnd1Home)) {
                this.errors.sleepEnd1Home = this.$t('Invalid time format');
                valid = false;
            }
            this.formData.sleepStart2Home = changeToHhMm(this.formData.sleepStart2Home);
            if(this.formData.sleepStart2Home && !validateHhMm(this.formData.sleepStart2Home)) {
                this.errors.sleepStart2Home = this.$t('Invalid time format');
                valid = false;
            }
            this.formData.sleepEnd2Home = changeToHhMm(this.formData.sleepEnd2Home);
            if(this.formData.sleepEnd2Home && !validateHhMm(this.formData.sleepEnd2Home)) {
                this.errors.sleepEnd2Home = this.$t('Invalid time format');
                valid = false;
            }
            // if(this.formData.sleepStart1Home && this.formData.sleepEnd1Home && this.formData.sleepStart1Home > this.formData.sleepEnd1Home) {
            //     this.errors.sleepStart1Home = this.$t('start time must be earlier than end time');
            //     valid = false;
            // }
            // if(this.formData.sleepStart2Home && this.formData.sleepEnd2Home && this.formData.sleepStart2Home > this.formData.sleepEnd2Home) {
            //     this.errors.sleepStart2Home = this.$t('start time must be earlier than end time');
            //     valid = false;
            // }

            // defecation
            this.formData.defecationTime1Home = changeToHhMm(this.formData.defecationTime1Home);
            if(this.formData.defecationTime1Home && !validateHhMm(this.formData.defecationTime1Home)) {
                this.errors.defecationTime1Home = this.$t('Invalid time format');
                valid = false;
            }
            this.formData.defecationTime2Home = changeToHhMm(this.formData.defecationTime2Home);
            if(this.formData.defecationTime2Home && !validateHhMm(this.formData.defecationTime2Home)) {
                this.errors.defecationTime2Home = this.$t('Invalid time format');
                valid = false;
            }
            this.formData.defecationTime3Home = changeToHhMm(this.formData.defecationTime3Home);
            if(this.formData.defecationTime3Home && !validateHhMm(this.formData.defecationTime3Home)) {
                this.errors.defecationTime3Home = this.$t('Invalid time format');
                valid = false;
            }
            if(this.formData.defecationMemo1Home && this.formData.defecationMemo1Home.length > 200) {
                this.errors.defecationMemo1Home = this.$t("Please enter 200 characters or less");
                valid = false;
            }
            if(this.formData.defecationMemo2Home && this.formData.defecationMemo2Home.length > 200) {
                this.errors.defecationMemo2Home = this.$t("Please enter 200 characters or less");
                valid = false;
            }
            if(this.formData.defecationMemo3Home && this.formData.defecationMemo3Home.length > 200) {
                this.errors.defecationMemo3Home = this.$t("Please enter 200 characters or less");
                valid = false;
            }

            // temperatureTime
            this.formData.temperatureTime1Home = changeToHhMm(this.formData.temperatureTime1Home);
            if(this.formData.temperatureTime1Home && !validateHhMm(this.formData.temperatureTime1Home)) {
                this.errors.temperatureTime1Home = this.$t('Invalid time format');
                valid = false;
            }
            this.formData.temperatureTime2Home = changeToHhMm(this.formData.temperatureTime2Home);
            if(this.formData.temperatureTime2Home && !validateHhMm(this.formData.temperatureTime2Home)) {
                this.errors.temperatureTime2Home = this.$t('Invalid time format');
                valid = false;
            }
            this.formData.temperatureTime3Home = changeToHhMm(this.formData.temperatureTime3Home);
            if(this.formData.temperatureTime3Home && !validateHhMm(this.formData.temperatureTime3Home)) {
                this.errors.temperatureTime3Home = this.$t('Invalid time format');
                valid = false;
            }
            // temperature
            if(this.formData.temperature1Home && this.formData.temperature1Home < 0) {
                this.errors.temperature1Home = this.$t('Please input positive number');
                valid = false;
            }
            if(this.formData.temperature1Home && (this.formData.temperature1Home < 34 || this.formData.temperature1Home > 42)) {
                this.errors.temperature1Home = this.$t('Incorrect temperature value');
                valid = false;
            }
            if(this.formData.temperature2Home && this.formData.temperature2Home < 0) {
                this.errors.temperature2Home = this.$t('Please input positive number');
                valid = false;
            }
            if(this.formData.temperature2Home && (this.formData.temperature2Home < 34 || this.formData.temperature2Home > 42)) {
                this.errors.temperature2Home = this.$t('Incorrect temperature value');
                valid = false;
            }
            if(this.formData.temperature3Home && this.formData.temperature3Home < 0) {
                this.errors.temperature3Home = this.$t('Please input positive number');
                valid = false;
            }
            if(this.formData.temperature3Home && (this.formData.temperature3Home < 34 || this.formData.temperature3Home > 42)) {
                this.errors.temperature3Home = this.$t('Incorrect temperature value');
                valid = false;
            }

            for (let i = 1; i <= 3; i++) {
                if(!this.formData[`temperature${i}Home`] && this.formData[`temperatureTime${i}Home`]) {
                    this.errors[`temperature${i}Home`] = this.$t('Please input both temperature and time');
                    valid = false;
                }

                if(this.formData[`temperature${i}Home`] && !this.formData[`temperatureTime${i}Home`]) {
                    this.errors[`temperatureTime${i}Home`] = this.$t('Please input both temperature and time');
                    valid = false;
                }
            }

            this.inputError = !valid;
            return valid;
        },
        carryingFormData() {
            const keyGroups = [
                ['mealTime*Home', "mealAmount*Home", "mealMemo*Home"],
                ['defecationTime*Home', 'defecation*Home', 'defecationMemo*Home'],
                ['temperatureTime*Home', 'temperature*Home'],
                ['sleepStart*Home', 'sleepEnd*Home'],
            ];

            keyGroups.forEach(keys => {
                let validFormValues = [];
                let validCount = 0;

                // NOTE:sleepのみ最大2つまで
                let max = keys[0] == 'sleepStart*Home' ? 2 : 3;

                for (let i = 1; i <= max; i++) {
                    let formValues = keys.map(key => {
                        return [key, this.formData[key.replace('*', i)]];
                    });

                    if (formValues.some(value => value[1])) {
                        validCount++;
                        validFormValues[validCount] = formValues;
                    }
                }

                for (let i = 1; i <= max; i++) {
                    if (validFormValues[i]) {
                        validFormValues[i].forEach(values => {
                            this.formData[values[0].replace('*', i)] = values[1];
                        })
                    } else {
                        keys.forEach(key => {
                            this.formData[key.replace('*', i)] = null;
                        })
                    }
                }
            });
        },
        initFormError() {
            this.errors = {
                weather:null,
                pickUpPerson: null,
                pickUpTime: null,
                mealTime1Home: null,
                mealTime2Home: null,
                mealTime3Home: null,
                mealMemo1Home: null,
                mealMemo2Home: null,
                mealMemo3Home: null,
                sleepStart1Home: null,
                sleepEnd1Home: null,
                sleepStart2Home: null,
                sleepEnd2Home: null,
                temperature1Home: null,
                temperature2Home: null,
                temperature3Home: null,
                temperatureTime1Home: null,
                temperatureTime2Home: null,
                temperatureTime3Home: null,
                defecationTime1Home: null,
                defecationTime2Home: null,
                defecationTime3Home: null,
                defecation1Home: null,
                defecation2Home: null,
                defecation3Home: null,
                defecationMemo1Home: null,
                defecationMemo2Home: null,
                defecationMemo3Home: null,
            }
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
                        this.resetFormCounts();
                        this.updateFormCounts(this.formData);
                    } else {
                        this.formData = {...initialFormData};
                        this.resetFormCounts();
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
        loadAttends(date){
            //TODO: axios.get
            this.attends = [
                {
                    date: new Date('2021/09/01'),
                },
            ];
        },
        loadRequests(date){
            //TODO: axios.get
            this.requests = {

            };
        },
        updateTable(date){
            this.currentDate = date;
            var firstDay = new Date(date.getFullYear(), date.getMonth(), 1).getDate();
            var lastDay = new Date(date.getFullYear(), date.getMonth() + 1, 0).getDate();
            for(let day = firstDay; day <= lastDay; day++) {
                this.days.push(new Date(date.getFullYear(), date.getMonth(), day));
            }
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
        formatSleepTime(sleepTimeStart, sleepTimeEnd) {
            if(sleepTimeStart && sleepTimeEnd) {
                return moment(sleepTimeStart, 'HH:mm:ss').format('HH:mm') + "~" + moment(sleepTimeEnd, 'HH:mm:ss').format('HH:mm');
            } else {
                return '';
            }
        },
        formatTimeStd(time) {
            if(time) return moment(time, 'HH:mm:ss').format('HH:mm');
            return null;
        },
        formatTemperature(temperature){
            if(temperature) {
                return temperature + "℃";
            } else {
                return '';
            }
        },
        formatStatus(status){
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
        formatMealStatus(status) {
            if(status == 1) {
                return '完食';
            } else if(status == 2) {
                return '残食';
            } else if(status == 3) {
                return 'おかわり';
            } else {
                return '';
            }
        },
        formatBathStatus(status) {
            if(status == 1) {
                return '有り';
            } else if(status == 2) {
                return '無し';
            } else {
                return '';
            }
        },
        formatDefecationStatus(status) {
            if(status == 1) {
                return '普';
            } else if(status == 2) {
                return '軟';
            } else if(status == 3) {
                return '固';
            } else {
                return '';
            }
        },
        isMobile() {
            if(window.innerWidth < 768) {
                return true;
            } else {
                return false;
            }
        },
        exportExcel() {
            const date = moment(this.selectedDate).format('YYYY-MM-DD');
            location.href = process.env.MIX_APP_BASE_URL + 'childcare-contact-book/excel/' + this.child.id + '/?date=' + date + '&token=' + LocalStorage.getToken();
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
        onAddForm(subject) {
            this.formCount[subject] += 1;
        },
       async onDelMealForm() {
            if (await this.$refs.ConfirmModal.confirm('削除確認', '１つ削除してよろしいですか？')) {
                let count = this.formCount.meal;
                this.formData[`mealTime${count}Home`] = null;
                this.formData[`mealAmount${count}Home`] = null;
                this.formData[`mealMemo${count}Home`] = null;
                this.errors[`mealTime${count}Home`] = null;
                this.errors[`mealMemo${count}Home`] = null;
                this.formCount.meal -= 1;
            }
        },
        async onDelDefecationForm() {
            if (await this.$refs.ConfirmModal.confirm('削除確認', '１つ削除してよろしいですか？')) {
                let count = this.formCount.defecation;
                this.formData[`defecation${count}Home`] = null;
                this.formData[`defecationTime${count}Home`] = null;
                this.formData[`defecationMemo${count}Home`] = null;
                this.errors[`defecationTime${count}Home`] = null;
                this.errors[`defecationMemo${count}Home`] = null;
                this.formCount.defecation -= 1;
            }
        },
        async onDelTempForm() {
            if (await this.$refs.ConfirmModal.confirm('削除確認', '１つ削除してよろしいですか？')) {
                let count = this.formCount.temperature;
                this.formData[`temperatureTime${count}Home`] = null;
                this.formData[`temperature${count}Home`] = null;
                this.errors[`temperatureTime${count}Home`] = null;
                this.errors[`temperature${count}Home`] = null;
                this.formCount.temperature -= 1;
            }
        },
        async onDelSleepForm() {
            if (await this.$refs.ConfirmModal.confirm('削除確認', '１つ削除してよろしいですか？')) {
                let count = this.formCount.sleep;
                this.formData[`sleepStart${count}Home`] = null;
                this.formData[`sleepEnd${count}Home`] = null;
                this.errors[`sleepStart${count}Home`] = null;
                this.errors[`sleepEnd${count}Home`] = null;
                this.formCount.sleep -= 1;
            }
        },
        showMemoEditForm(key, _isDisabled = false) {
            this.$refs.MemoEditForm.showModal('メモの入力/確認', this.formData[key], key, _isDisabled);
        },
        replaceFormData(key, value) {
            this.formData[key] = value;
            $('#contact-book-meal-edit-form').modal('hide');
        },
        updateFormCounts(contact) {
            // meal
            if (contact.mealTime3Home || contact.mealMemo3Home || contact.mealAmount3Home) {
                this.formCount.meal = 3;
            } else if (contact.mealTime2Home || contact.mealMemo2Home|| contact.mealAmount2Home) {
                this.formCount.meal = 2;
            }

            // defecation
            if (contact.defecationTime3Home || contact.defecation3Home || contact.defecationMemo3Home) {
                this.formCount.defecation = 3;
            } else if (contact.defecationTime2Home || contact.defecation2Home|| contact.defecationMemo2Home) {
                this.formCount.defecation = 2;
            }
            // sleep
            if (contact.sleepStart2Home || contact.sleepEnd2Home) {
                this.formCount.sleep = 2;
            }

            // temperature
            if (contact.temperatureTime3Home || contact.temperature3Home ) {
                this.formCount.temperature = 3;
            } else if (contact.temperatureTime2Home || contact.temperature2Home) {
                this.formCount.temperature = 2;
            }
        },
        resetFormCounts() {
            this.formCount.meal = 1;
            this.formCount.defecation = 1;
            this.formCount.sleep = 1;
            this.formCount.temperature = 1;
        }
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
    .hour-minute-input > input {
        padding: 2px!important;
    }

@media (max-width: 500px) {
       h5.card-title {
           font-size: 13px!important;
       }
    }
</style>
