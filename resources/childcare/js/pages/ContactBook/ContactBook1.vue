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
                            <div class="col-md-2 col-4 contact-book-cell dark-pink text-white">お迎え予定</div>
                            <div class="col-md-4 col-8 contact-book-cell light-pink">
                                {{ formatTimeStd(formData.pickUpTime) }}
                            </div>
                            <div class="col-md-2 col-4 contact-book-cell dark-pink text-white">お迎えの方</div>
                            <div class="col-md-4 col-8 contact-book-cell light-pink">
                                {{ formData.pickUpPerson }}
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
                                    <div class="col-md-11 col-12">
                                        <div class="row row-mobile">
                                            <template v-if="!isMobile()">
                                                <div class="col-md-7 col-12 contact-book-cell light-brown">
                                                    {{ formatTimeStd(formData.mealTime1Home) }}
                                                </div>
                                                <div class="col-md-5 col-12 contact-book-cell light-brown">
                                                    {{ formatMealStatus(formData.mealAmount1Home) }}
                                                </div>
                                                <div class="col-md-12 col-12 contact-book-cell justify-content-start light-brown">
                                                    {{ formData.mealMemo1Home }}
                                                </div>
                                                <div class="col-md-7 col-12 contact-book-cell light-brown">
                                                    {{ formatTimeStd(formData.mealTime2Home) }}
                                                </div>
                                                <div class="col-md-5 col-12 contact-book-cell light-brown">
                                                    {{ formatMealStatus(formData.mealAmount2Home) }}
                                                </div>
                                                <div class="col-md-12 col-12 contact-book-cell justify-content-start light-brown">
                                                    {{ formData.mealMemo2Home }}
                                                </div>
                                                <div class="col-md-7 col-12 contact-book-cell light-brown">
                                                    {{ formatTimeStd(formData.mealTime3Home) }}
                                                </div>
                                                <div class="col-md-5 col-12 contact-book-cell light-brown">
                                                    {{ formatMealStatus(formData.mealAmount3Home) }}
                                                </div>
                                                <div class="col-md-12 col-12 contact-book-cell justify-content-start light-brown">
                                                    {{ formData.mealMemo3Home }}
                                                </div>
                                            </template>
                                            <div class="w-100" v-else v-for="i in 3" :key="i">
                                                <template v-if="i == 1">
                                                    <div class="col-md-3 col-12 contact-book-cell time-zone-label">
                                                        {{ i }} 食目
                                                    </div>
                                                    <div class="col-md-7 col-12 contact-book-cell light-brown">
                                                        {{ formatTimeStd(formData.mealTime1Home) }}
                                                    </div>
                                                    <div class="col-md-5 col-12 contact-book-cell light-brown">
                                                        {{ formatMealStatus(formData.mealAmount1Home) }}
                                                    </div>
                                                    <div class="col-md-12 col-12 contact-book-cell justify-content-start light-brown">
                                                        {{ formData.mealMemo1Home }}
                                                    </div>
                                                </template>
                                                <template v-else-if="formData[`mealTime${i}Home`] || formData[`mealAmount${i}Home`] || formData[`mealMemo${i}Home`]">
                                                    <div class="col-md-3 col-12 contact-book-cell time-zone-label">
                                                        {{ i }} 食目
                                                    </div>
                                                    <div class="col-md-7 col-12 contact-book-cell light-brown">
                                                        {{ formatTimeStd(formData[`mealTime${i}Home`]) }}
                                                    </div>
                                                    <div class="col-md-5 col-12 contact-book-cell light-brown">
                                                        {{ formatMealStatus(formData[`mealAmount${i}Home`]) }}
                                                    </div>
                                                    <div class="col-md-12 col-12 contact-book-cell justify-content-start light-brown">
                                                        {{ formData[`mealMemo${i}Home`] }}
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
                                            <div class="col-md-3 col-12 contact-book-cell time-zone-label">
                                                前夜
                                            </div>
                                            <div class="col-md-9 col-12 contact-book-cell light-brown">
                                                {{ formatMoodStatus(formData.mood1Home) }}
                                            </div>
                                            <div class="col-md-3 col-12 contact-book-cell time-zone-label">
                                                今朝
                                            </div>
                                            <div class="col-md-9 col-12 contact-book-cell light-brown">
                                                {{ formatMoodStatus(formData.mood2Home) }}
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
                                                    {{ formatTimeStd(formData.defecationTime1Home) }}
                                                </div>
                                                <div class="col-md-5 col-12 contact-book-cell light-brown">
                                                    {{ formatDefecationStatus(formData.defecation1Home) }}
                                                </div>
                                                <div class="col-md-12 col-12 contact-book-cell justify-content-start light-brown">
                                                    {{ formData.defecationMemo1Home }}
                                                </div>
                                                <div class="col-md-7 col-12 contact-book-cell light-brown">
                                                    {{ formatTimeStd(formData.defecationTime2Home) }}
                                                </div>
                                                <div class="col-md-5 col-12 contact-book-cell light-brown">
                                                    {{ formatDefecationStatus(formData.defecation2Home) }}
                                                </div>
                                                <div class="col-md-12 col-12 contact-book-cell justify-content-start light-brown">
                                                    {{ formData.defecationMemo2Home }}
                                                </div>
                                                <div class="col-md-7 col-12 contact-book-cell light-brown">
                                                    {{ formatTimeStd(formData.defecationTime3Home) }}
                                                </div>
                                                <div class="col-md-5 col-12 contact-book-cell light-brown">
                                                    {{ formatDefecationStatus(formData.defecation3Home) }}
                                                </div>
                                                <div class="col-md-12 col-12 contact-book-cell justify-content-start light-brown">
                                                    {{ formData.defecationMemo3Home }}
                                                </div>
                                            </template>
                                            <div v-else class="w-100" v-for="i in 3" :key="i">
                                                <template v-if="i == 1">
                                                    <div class="col-md-3 col-12 contact-book-cell time-zone-label">
                                                        {{ i }} 回目
                                                    </div>
                                                    <div class="col-md-7 col-12 contact-book-cell light-brown">
                                                        {{ formatTimeStd(formData.defecationTime1Home) }}
                                                    </div>
                                                    <div class="col-md-5 col-12 contact-book-cell light-brown">
                                                        {{ formatDefecationStatus(formData.defecation1Home) }}
                                                    </div>
                                                    <div class="col-md-12 col-12 contact-book-cell justify-content-start light-brown">
                                                        {{ formData.defecationMemo1Home }}
                                                    </div>
                                                </template>
                                                <template v-else-if="formData[`defecationTime${i}Home`] || formData[`defecation${i}Home`] || formData[`defecationMemo${i}Home`]">
                                                    <div class="col-md-3 col-12 contact-book-cell time-zone-label">
                                                        {{ i }} 回目
                                                    </div>
                                                    <div class="col-md-7 col-12 contact-book-cell light-brown">
                                                        {{ formatTimeStd(formData[`defecationTime${i}Home`]) }}
                                                    </div>
                                                    <div class="col-md-5 col-12 contact-book-cell light-brown">
                                                        {{ formatDefecationStatus(formData[`defecation${i}Home`]) }}
                                                    </div>
                                                    <div class="col-md-12 col-12 contact-book-cell justify-content-start light-brown">
                                                        {{ formData[`defecationMemo${i}Home`] }}
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
                                                {{ formatSleepTime(formData.sleepStart1Home, formData.sleepEnd1Home) }}
                                            </div>
                                            <div class="col-md-12 col-12 contact-book-cell light-brown">
                                                {{ formatSleepTime(formData.sleepStart2Home, formData.sleepEnd2Home) }}
                                            </div>
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
                                                {{ formatBathStatus(formData.bathingHome) }}
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
                                            <div class="col-md-4 contact-book-cell light-brown">
                                                {{ formatTimeStd(formData.temperatureTime1Home) }}
                                            </div>
                                            <div class="col-md-4 contact-book-cell light-brown">
                                                {{ formatTimeStd(formData.temperatureTime2Home) }}
                                            </div>
                                            <div class="col-md-4 contact-book-cell light-brown">
                                                {{ formatTimeStd(formData.temperatureTime3Home) }}
                                            </div>
                                            <div class="col-md-4 contact-book-cell light-brown">
                                                {{ formatTemperature(formData.temperature1Home) }}
                                            </div>
                                            <div class="col-md-4 contact-book-cell light-brown">
                                                {{ formatTemperature(formData.temperature2Home) }}
                                            </div>
                                            <div class="col-md-4 contact-book-cell light-brown">
                                                {{formatTemperature(formData.temperature3Home)}}
                                            </div>
                                        </div>
                                    </div>
                                    <div v-else class="col-12">
                                        <div class="row row-mobile">
                                            <div class="col-6 contact-book-cell light-brown">
                                                {{ formatTimeStd(formData.temperatureTime1Home) }}
                                            </div>
                                            <div class="col-6 contact-book-cell light-brown">
                                                {{ formatTemperature(formData.temperature1Home) }}
                                            </div>
                                            <template v-if="formData.temperatureTime2Home || formData.temperature2Home">
                                                <div class="col-6 contact-book-cell light-brown">
                                                    {{ formatTimeStd(formData.temperatureTime2Home) }}
                                                </div>
                                                <div class="col-6 contact-book-cell light-brown">
                                                    {{ formatTemperature(formData.temperature2Home) }}
                                                </div>
                                            </template>
                                            <template v-if="formData.temperatureTime3Home || formData.temperature3Home">
                                                <div class="col-6 contact-book-cell light-brown">
                                                    {{ formatTimeStd(formData.temperatureTime3Home) }}
                                                </div>
                                                <div class="col-6 contact-book-cell light-brown">
                                                    {{ formatTemperature(formData.temperature3Home) }}
                                                </div>
                                            </template>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <div class="dark-blue py-2 text-center text-white">
                                            家庭での様子・連絡事項
                                        </div>
                                        <div class="light-blue mt-1 p-3 contact-area"
                                            v-text="formData.state0Home">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- 保育園ここから -->
                            <div class="col-md-6 col-12 d-none d-md-block mt-md-0" :class="{'d-block mt-1': tab == SCHOOL}">
                                <div class="border-left border-right border-white dark-yellow d-none d-md-block fixed-height-40 mb-1 py-2 text-center text-white">保育園より</div>
                                <div class="form-group row">
                                    <div class="col-md-1 col-12 pr-md-0">
                                        <div class="align-items-center border border-white dark-brown d-flex h-100 justify-content-center text-center">
                                            <label class="mb-0">食事</label>
                                        </div>
                                    </div>
                                    <div v-if="!isMobile()" class="col-md-11 col-10">
                                        <div class="row row-mobile">
                                            <div class="col-md-5 col-12 contact-book-cell light-brown">
                                                <hour-minute-input
                                                    v-model="formData.mealTime1School"
                                                    :error="errors.mealTime1School"
                                                    @input="dataChanged = true; errors.mealTime1School = null; inputError = false;"
                                                />
                                            </div>
                                            <div class="col-md-7 col-12 contact-book-cell light-brown">
                                                <custom-radio
                                                    name="radioMeal1"
                                                    v-model="formData.mealAmount1School"
                                                    :options="{'完食': 1, '残食': 2, 'おかわり': 3}"
                                                    @input="dataChanged = true;"
                                                ></custom-radio>
                                            </div>
                                            <div class="col-md-12 col-12 contact-book-cell light-brown">
                                                <input
                                                    class="form-control"
                                                    placeholder="メモ"
                                                    type="text"
                                                    v-model="formData.mealMemo1School"
                                                    :class="{'is-invalid' : errors.mealMemo1School}"
                                                    @change="dataChanged = true; errors.mealMemo1School = null; inputError = false;"
                                                />
                                                <span class="error invalid-feedback" v-if="errors.mealMemo1School">
                                                    {{ errors.mealMemo1School }}
                                                </span>
                                            </div>
                                            <div class="col-md-5 col-12 contact-book-cell light-brown">
                                                <hour-minute-input
                                                    v-model="formData.mealTime2School"
                                                    :error="errors.mealTime2School"
                                                    @input="dataChanged = true; errors.mealTime2School = null; inputError = false;"
                                                />
                                            </div>
                                            <div class="col-md-7 col-12 contact-book-cell light-brown">
                                                <custom-radio
                                                    name="radioMeal2"
                                                    v-model="formData.mealAmount2School"
                                                    :options="{'完食': 1, '残食': 2, 'おかわり': 3}"
                                                    @input="dataChanged = true;"
                                                ></custom-radio>
                                            </div>
                                            <div class="col-md-12 col-12 contact-book-cell light-brown">
                                                <input
                                                    class="form-control"
                                                    placeholder="メモ"
                                                    type="text"
                                                    v-model="formData.mealMemo2School"
                                                    :class="{'is-invalid' : errors.mealMemo2School}"
                                                    @change="dataChanged = true; errors.mealMemo2School = null; inputError = false;"
                                                />
                                                <span class="error invalid-feedback" v-if="errors.mealMemo2School">
                                                    {{ errors.mealMemo2School }}
                                                </span>
                                            </div>
                                            <div class="col-md-5 col-12 contact-book-cell light-brown">
                                                <hour-minute-input
                                                    v-model="formData.mealTime3School"
                                                    :error="errors.mealTime3School"
                                                    @input="dataChanged = true; errors.mealTime3School = null;"
                                                />
                                            </div>
                                            <div class="col-md-7 col-12 contact-book-cell light-brown">
                                                <custom-radio
                                                    name="radioMeal3"
                                                    v-model="formData.mealAmount3School"
                                                    :options="{'完食': 1, '残食': 2, 'おかわり': 3}"
                                                    @input="dataChanged = true;"
                                                ></custom-radio>
                                            </div>
                                            <div class="col-md-12 col-12 contact-book-cell light-brown">
                                                <input
                                                    class="form-control"
                                                    placeholder="メモ"
                                                    type="text"
                                                    v-model="formData.mealMemo3School"
                                                    :class="{'is-invalid' : errors.mealMemo3School}"
                                                    @change="dataChanged = true; errors.mealMemo3School = null; inputError = false;"
                                                />
                                                <span class="error invalid-feedback" v-if="errors.mealMemo3School">
                                                    {{ errors.mealMemo3School }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 px-0" v-else>
                                        <div class="row row-mobile">
                                            <div class="col-12 mb-1" v-for="i in formCount.meal" :key="i">
                                                <div class="contact-book-cell light-brown">
                                                    <hour-minute-input
                                                        v-model="formData[`mealTime${i}School`]"
                                                        :error="errors[`mealTime${i}School`]"
                                                        @input="dataChanged = true; errors[`mealTime${i}School`] = null; inputError = false;"
                                                    />
                                                </div>
                                                <div class="contact-book-cell light-brown">
                                                    <custom-radio
                                                        v-model="formData[`mealAmount${i}School`]"
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
                                                        v-model="formData[`mealMemo${i}School`]"
                                                        :class="{'is-invalid': errors[`mealMemo${i}School`]}"
                                                        @click="showMemoEditForm(`mealMemo${i}School`)"
                                                        @change="dataChanged = true; errors[`mealMemo${i}School`] = null; inputError = false;"
                                                    />
                                                    <span v-if="errors[`mealMemo${i}School`]" class="error invalid-feedback">
                                                        {{ errors[`mealMemo${i}School`] }}
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
                                                午前
                                            </div>
                                            <div class="col-md-9 col-12 contact-book-cell light-brown">
                                                <custom-radio
                                                    name="radioMood1"
                                                    v-model="formData.mood1School"
                                                    :options="{'普通': 1, '良い': 2, '悪い': 3}"
                                                    @input="dataChanged = true;"
                                                ></custom-radio>
                                            </div>
                                            <div class="col-md-3 col-12 contact-book-cell time-zone-label">
                                                午後
                                            </div>
                                            <div class="col-md-9 col-12 contact-book-cell light-brown">
                                                <custom-radio
                                                    name="radioMood2"
                                                    v-model="formData.mood2School"
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
                                    <div class="col-md-11 col-12" v-if="!isMobile()">
                                        <div class="row row-mobile">
                                            <div class="col-md-5 contact-book-cell light-brown">
                                                <hour-minute-input
                                                    v-model="formData.defecationTime1School"
                                                    :error="errors.defecationTime1School"
                                                    @input="dataChanged = true; errors.defecationTime1School = null; inputError = false;"
                                                />
                                            </div>
                                            <div class="col-md-7 contact-book-cell light-brown">
                                                <custom-radio
                                                    name="radioDefecation1"
                                                    v-model="formData.defecation1School"
                                                    :options="{'普': 1, '軟': 2, '固': 3}"
                                                    @input="dataChanged = true;"
                                                ></custom-radio>
                                            </div>
                                            <div class="col-md-12 col-12 contact-book-cell light-brown">
                                                <input
                                                    class="form-control"
                                                    placeholder="メモ"
                                                    type="text"
                                                    v-model="formData.defecationMemo1School"
                                                    :class="{'is-invalid': errors.defecationMemo1School}"
                                                    @change="dataChanged = true; errors.defecationMemo1School = null; inputError = false;"
                                                />
                                                <span class="error invalid-feedback" v-if="errors.defecationMemo1School">
                                                    {{ errors.defecationMemo1School }}
                                                </span>
                                            </div>
                                            <div class="col-md-5 col-12 contact-book-cell light-brown">
                                                <hour-minute-input
                                                    v-model="formData.defecationTime2School"
                                                    :error="errors.defecationTime2School"
                                                    @input="dataChanged = true; errors.defecationTime2School = null; inputError = false;"
                                                />
                                            </div>
                                            <div class="col-md-7 col-12 contact-book-cell light-brown">
                                                <custom-radio
                                                    name="radioDefecation2"
                                                    v-model="formData.defecation2School"
                                                    :options="{'普': 1, '軟': 2, '固': 3}"
                                                    @input="dataChanged = true;"
                                                ></custom-radio>
                                            </div>
                                            <div class="col-md-12 col-12 contact-book-cell light-brown">
                                                <input
                                                    class="form-control"
                                                    placeholder="メモ"
                                                    type="text"
                                                    v-model="formData.defecationMemo2School"
                                                    :class="{'is-invalid': errors.defecationMemo2School}"
                                                    @change="dataChanged = true; errors.defecationMemo2School = null; inputError = false;"
                                                />
                                                <span class="error invalid-feedback" v-if="errors.defecationMemo2School">
                                                    {{ errors.defecationMemo2School }}
                                                </span>
                                            </div>
                                            <div class="col-md-5 col-12 contact-book-cell light-brown">
                                                <hour-minute-input
                                                    v-model="formData.defecationTime3School"
                                                    :error="errors.defecationTime3School"
                                                    @input="dataChanged = true; errors.defecationTime3School = null;"
                                                />
                                            </div>
                                            <div class="col-md-7 col-12 contact-book-cell light-brown">
                                                <custom-radio
                                                    name="radioDefecation3"
                                                    v-model="formData.defecation3School"
                                                    :options="{'普': 1, '軟': 2, '固': 3}"
                                                    @input="dataChanged = true;"
                                                ></custom-radio>
                                            </div>
                                            <div class="col-md-12 col-12 contact-book-cell light-brown">
                                                <input
                                                    class="form-control"
                                                    placeholder="メモ"
                                                    type="text"
                                                    v-model="formData.defecationMemo3School"
                                                    :class="{'is-invalid': errors.defecationMemo3School}"
                                                    @change="dataChanged = true; errors.defecationMemo3School = null; inputError = false;"
                                                />
                                                <span v-if="errors.defecationMemo3School" class="error invalid-feedback">
                                                    {{ errors.defecationMemo3School }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 px-0" v-else>
                                        <div class="row row-mobile">
                                            <div class="col-12 mb-1" v-for="i in formCount.defecation" :key="i">
                                                <div class="contact-book-cell light-brown">
                                                    <hour-minute-input
                                                        v-model="formData[`defecationTime${i}School`]"
                                                        :error="errors[`defecationTime${i}School`]"
                                                        @input="dataChanged = true; errors[`defecationTime${i}School`] = null; inputError = false;"
                                                    />
                                                </div>
                                                <div class="contact-book-cell light-brown">
                                                    <custom-radio
                                                        v-model="formData[`defecation${i}School`]"
                                                        :name="`radioDefecation${i}School`"
                                                        :options="{'普': 1, '軟': 2, '固': 3}"
                                                        @input="dataChanged = true;"
                                                    ></custom-radio>
                                                </div>
                                                <div class="contact-book-cell light-brown">
                                                    <input
                                                        class="form-control text-truncate"
                                                        placeholder="メモ"
                                                        type="text"
                                                        v-model="formData[`defecationMemo${i}School`]"
                                                        :class="{'is-invalid' : errors[`defecationMemo${i}School`]}"
                                                        @click="showMemoEditForm(`defecationMemo${i}School`)"
                                                        @change="dataChanged = true; errors[`defecationMemo${i}School`] = null; inputError = false;"
                                                    />
                                                    <span class="error invalid-feedback" v-if="errors[`defecationMemo${i}School`]">
                                                        {{ errors[`defecationMemo${i}School`] }}
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
                                                    v-model="formData.sleepStart1School"
                                                    :error="errors.sleepStart1School"
                                                    @input="dataChanged = true; errors.sleepStart1School = null; inputError = false;"
                                                />
                                                ~
                                                <hour-minute-input
                                                    v-model="formData.sleepEnd1School"
                                                    :error="errors.sleepEnd1School"
                                                    @input="dataChanged = true; errors.sleepEnd1School = null; inputError = false;"
                                                />
                                            </div>
                                            <div class="col-md-12 contact-book-cell light-brown">
                                                <hour-minute-input
                                                    v-model="formData.sleepStart2School"
                                                    :error="errors.sleepStart2School"
                                                    @input="dataChanged = true; errors.sleepStart2School = null; inputError = false;"
                                                />
                                                ~
                                                <hour-minute-input
                                                    v-model="formData.sleepEnd2School"
                                                    :error="errors.sleepEnd2School"
                                                    @input="dataChanged = true; errors.sleepEnd2School = null; inputError = false;"
                                                />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 px-0" v-else>
                                        <div class="row row-mobile">
                                            <div class="col-12 mb-1" v-for="i in formCount.sleep" :key="i">
                                                <div class="contact-book-cell light-brown">
                                                    <hour-minute-input
                                                        v-model="formData[`sleepStart${i}School`]"
                                                        :error="errors[`sleepStart${i}School`]"
                                                        @input="dataChanged = true; errors.sleepStart1School = null; inputError = false;"
                                                    />
                                                    ~
                                                    <hour-minute-input
                                                        v-model="formData[`sleepEnd${i}School`]"
                                                        :error="errors[`sleepEnd${i}School`]"
                                                        @input="dataChanged = true; errors.sleepEnd1School = null; inputError = false;"
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
                                            <label class="mb-0">沐浴</label>
                                        </div>
                                    </div>
                                    <div class="col-md-11 col-12">
                                        <div class="row row-mobile">
                                            <div class="col-md-12 col-12 contact-book-cell light-brown">
                                                <custom-radio
                                                    name="radioBathingSchool"
                                                    v-model="formData.bathingSchool"
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
                                                    v-model="formData.temperatureTime1School"
                                                    :error="errors.temperatureTime1School"
                                                    @input="dataChanged = true; errors.temperatureTime1School = null; inputError = false;"
                                                />
                                            </div>
                                            <div class="col-md-4 contact-book-cell light-brown">
                                                <hour-minute-input
                                                    v-model="formData.temperatureTime2School"
                                                    :error="errors.temperatureTime2School"
                                                    @input="dataChanged = true; errors.temperatureTime2School = null; inputError = false;"
                                                />
                                            </div>
                                            <div class="col-md-4 contact-book-cell light-brown">
                                                <hour-minute-input
                                                    v-model="formData.temperatureTime3School"
                                                    :error="errors.temperatureTime3School"
                                                    @input="dataChanged = true; errors.temperatureTime3School = null; inputError = false;"
                                                />
                                            </div>
                                           <div class="col-md-4 contact-book-cell light-brown">
                                                <input
                                                    class="form-control form-control-temp"
                                                    max="42"
                                                    min="32"
                                                    type="number"
                                                    v-model="formData.temperature1School"
                                                    :class="{'is-invalid': errors.temperature1School}"
                                                    @change="dataChanged = true; errors.temperature1School = null; inputError = false;"
                                                />
                                                <span class="error invalid-feedback" v-if="errors.temperature1School">
                                                    {{ errors.temperature1School }}
                                                </span>
                                                <label class="mb-0">℃</label>
                                            </div>
                                           <div class="col-md-4 contact-book-cell light-brown">
                                                <input
                                                    class="form-control form-control-temp"
                                                    max="42"
                                                    min="32"
                                                    type="number"
                                                    v-model="formData.temperature2School"
                                                    :class="{'is-invalid': errors.temperature2School}"
                                                    @change="dataChanged = true; errors.temperature2School = null; inputError = false;"
                                                />
                                                <span class="error invalid-feedback" v-if="errors.temperature2School">
                                                    {{ errors.temperature2School }}
                                                </span>
                                                <label class="mb-0">℃</label>
                                            </div>
                                           <div class="col-md-4 contact-book-cell light-brown">
                                                <input
                                                    class="form-control form-control-temp"
                                                    max="42"
                                                    min="32"
                                                    type="number"
                                                    v-model="formData.temperature3School"
                                                    :class="{'is-invalid': errors.temperature3School}"
                                                    @change="dataChanged = true; errors.temperature3School = null; inputError = false;"
                                                />
                                                <span class="error invalid-feedback" v-if="errors.temperature3School">
                                                    {{ errors.temperature3School }}
                                                </span>
                                                <label class="mb-0">℃</label>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- ここからモバイル -->
                                    <div v-else class="col-12">
                                        <div class="row row-mobile mb-1" v-for="i in formCount.temperature" :key="i">
                                            <div class="col-6 contact-book-cell light-brown">
                                                <hour-minute-input
                                                    v-model="formData[`temperatureTime${i}School`]"
                                                    :error="errors[`temperatureTime${i}School`]"
                                                    @input="dataChanged = true; errors[`temperatureTime${i}School`] = null; inputError = false;"
                                                />
                                            </div>
                                            <div class="col-6 contact-book-cell flex-wrap light-brown ">
                                                <input
                                                    class="form-control form-control-temp"
                                                    max="42"
                                                    min="32"
                                                    type="number"
                                                    v-model="formData[`temperature${i}School`]"
                                                    :class="{'is-invalid' : errors[`temperature${i}School`]}"
                                                    @change="dataChanged = true; errors[`temperature${i}School`] = null; inputError = false;"
                                                />
                                                <label class="mb-0">℃</label>
                                                <div class="w-100 error invalid-feedback text-center" v-if="errors[`temperature${i}School`]">
                                                    {{ errors[`temperature${i}School`] }}
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
                                    <div class="col-12">
                                        <div class="dark-yellow py-2 text-center text-white">
                                            保育園での様子・連絡事項
                                        </div>
                                        <div class="light-yellow mt-1 p-3 contact-area">
                                            <textarea class="form-control" v-model="formData.state0School" @change="dataChanged = true;" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="align-items-center d-flex fixed-btn-group float-right mt-3" :class="{'is-invalid': inputError}">
                            <button class="btn btn-primary float-right mr-md-2" @click="saveContact(1)">一時保存</button>
                            <button class="btn btn-primary float-right mr-md-2" @click="saveContact(2)">完了</button>
                            <button class="btn btn-primary float-right" @click="exportExcel">Excel出力</button>
                        </div>
                        <div class="error invalid-feedback text-right" style="margin-top: 75px;" v-if="inputError">
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
import { changeToHhMm, validateHhMm } from '../../helpers/datetime';
import LocalStorage from '../../helpers/localStorage';
import CustomRadio from '../../components/CustomRadio.vue';
import { isMobile, scrollToTop } from '../../helpers/mobile';
import MemoEditForm from './MemoEditForm.vue';
import ScrollButton from '../../components/ScrollButton.vue';
import ConfirmModal from '../../components/ConfirmModal.vue';


const initialFormData = {
    date: new Date(),
    weather: '',
    mood: null,
    guardian: '',
    pickUpPerson: null,
    pickUpTime: null,
    mealTime1School: null,
    mealTime2School: null,
    mealTime3School: null,
    mealAmount1School: null,
    mealAmount2School: null,
    mealAmount3School: null,
    mealMemo1School: '',
    mealMemo2School: '',
    mealMemo3School: '',
    mood1School: null,
    mood2School: null,
    defecationTime1School: null,
    defecationTime2School: null,
    defecationTime3School: null,
    defecation1School: null,
    defecation2School: null,
    defecation3School: null,
    defecationMemo1School: null,
    defecationMemo2School: null,
    defecationMemo3School: null,
    sleepStart1School: null,
    sleepEnd1School: null,
    sleepStart2School: null,
    sleepEnd2School: null,
    bathingSchool: null,
    temperatureTime1School: null,
    temperature1School: '',
    temperatureTime2School: null,
    temperature2School: '',
    temperatureTime3School: null,
    temperature3School: '',
    state0School: '',
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
        child: {},
        contact: {},
        date: null,
    },
    data () {
        return {
            currentDate: new Date(),
            dataChanged: false,
            disabledDates: {
                to: null,
            },
            errors: {},
            formCount: {
                temperature: 1,
                meal: 1,
                sleep: 1,
                defecation: 1,
            },
            formData: {...initialFormData},
            inputError: false,
            ja: ja,
            selectedDate: new Date(),
            tab: SCHOOL,
            todayDate: "",
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
        saveContact(status) {
            if(this.actionLoading) return;
            if (!this.validate()) {
                scrollToTop();
                return;
            }

            this.carryingFormData();
            const requestData = Vue.util.extend({}, this.formData);
            requestData['date'] = moment(this.selectedDate).format('YYYY-MM-DD');
            requestData['status'] = status;

            // if(this.formData.pickUpTime)
            //     requestData['pick_up_time'] = moment(this.formData.pickUpTime, 'h:mm:ss').format('HH:mm');

            if(this.formData.mealTime1School)
                requestData['meal_time_1_school'] = moment(this.formData.mealTime1School, 'h:mm:ss').format('HH:mm');

            if(this.formData.mealTime2School)
                requestData['meal_time_2_school'] = moment(this.formData.mealTime2School, 'h:mm:ss').format('HH:mm');

            if(this.formData.mealTime3School)
                requestData['meal_time_3_school'] = moment(this.formData.mealTime3School, 'h:mm:ss').format('HH:mm');

            if(this.formData.defecationTime1School)
                requestData['defecation_time_1_school'] = moment(this.formData.defecationTime1School, 'h:mm:ss').format('HH:mm');

            if(this.formData.defecationTime2School)
                requestData['defecation_time_2_school'] = moment(this.formData.defecationTime2School, 'h:mm:ss').format('HH:mm');

            if(this.formData.defecationTime3School)
                requestData['defecation_time_3_school'] = moment(this.formData.defecationTime3School, 'h:mm:ss').format('HH:mm');

            if(this.formData.sleepStart1School)
                requestData['sleep_start_1_school'] = moment(this.formData.sleepStart1School, 'h:mm:ss').format('HH:mm');

            if(this.formData.sleepEnd1School)
                requestData['sleep_end_1_school'] = moment(this.formData.sleepEnd1School, 'h:mm:ss').format('HH:mm');

            if(this.formData.sleepStart2School) {
                requestData['sleep_start_2_school'] = moment(this.formData.sleepStart2School, 'h:mm:ss').format('HH:mm');
            }

            if(this.formData.sleepEnd2School) {
                requestData['sleep_end_2_school'] = moment(this.formData.sleepEnd2School, 'h:mm:ss').format('HH:mm');
            }

            if(this.formData.temperatureTime1School)
                requestData['temperature_time_1_school'] = moment(this.formData.temperatureTime1School, 'h:mm:ss').format('HH:mm');

            if(this.formData.temperatureTime2School)
                requestData['temperature_time_2_school'] = moment(this.formData.temperatureTime2School, 'h:mm:ss').format('HH:mm');

            if(this.formData.temperatureTime3School)
                requestData['temperature_time_3_school'] = moment(this.formData.temperatureTime3School, 'h:mm:ss').format('HH:mm');

            this.setActionLoading();
            api.post('contact-book/child/' + this.child.id + '/school/1', null, requestData)
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
            // if(!this.formData.guardian) {
            //     this.errors.guardian = this.$t('Please input name');
            //     valid = false;
            // }
            // if(this.formData.guardian && this.formData.guardian.length > 20) {
            //     this.errors.guardian = this.$t('Please enter 20 characters or less');
            //     valid = false;
            // }
            if(this.formData.weather && this.formData.weather.length > 10) {
                this.errors.weather = this.$t('Please enter 10 characters or less');
                valid = false;
            }
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

            // this.formData.pickUpTime = changeToHhMm(this.formData.pickUpTime);
            // if(!this.formData.pickUpTime) {
            //     this.errors.pickUpTime = this.$t('Please input time');
            //     valid = false;
            // }

            // if(this.formData.pickUpTime && !validateHhMm(this.formData.pickUpTime)) {
            //     this.errors.pickUpTime = this.$t('Invalid time format');
            //     valid = false;
            // }

            //meal
            this.formData.mealTime1School = changeToHhMm(this.formData.mealTime1School);
            if(this.formData.mealTime1School && !validateHhMm(this.formData.mealTime1School)) {
                this.errors.mealTime1School = this.$t('Invalid time format');
                valid = false;
            }
            this.formData.mealTime2School = changeToHhMm(this.formData.mealTime2School);
            if(this.formData.mealTime2School && !validateHhMm(this.formData.mealTime2School)) {
                this.errors.mealTime2School = this.$t('Invalid time format');
                valid = false;
            }
            this.formData.mealTime3School = changeToHhMm(this.formData.mealTime3School);
            if(this.formData.mealTime3School && !validateHhMm(this.formData.mealTime3School)) {
                this.errors.mealTime3School = this.$t('Invalid time format');
                valid = false;
            }

            if(this.formData.mealMemo1School && this.formData.mealMemo1School.length > 200) {
                this.errors.mealMemo1School = this.$t('Please enter 200 characters or less');
                valid = false;
            }
            if(this.formData.mealMemo2School && this.formData.mealMemo2School.length > 200) {
                this.errors.mealMemo2School = this.$t('Please enter 200 characters or less');
                valid = false;
            }
            if(this.formData.mealMemo3School && this.formData.mealMemo3School.length > 200) {
                this.errors.mealMemo3School = this.$t('Please enter 200 characters or less');
                valid = false;
            }

            // defecation
            this.formData.defecationTime1School = changeToHhMm(this.formData.defecationTime1School);
            if(this.formData.defecationTime1School && !validateHhMm(this.formData.defecationTime1School)) {
                this.errors.defecationTime1School = this.$t('Invalid time format');
                valid = false;
            }
            this.formData.defecationTime2School = changeToHhMm(this.formData.defecationTime2School);
            if(this.formData.defecationTime2School && !validateHhMm(this.formData.defecationTime2School)) {
                this.errors.defecationTime2School = this.$t('Invalid time format');
                valid = false;
            }
            this.formData.defecationTime3School = changeToHhMm(this.formData.defecationTime3School);
            if(this.formData.defecationTime3School && !validateHhMm(this.formData.defecationTime3School)) {
                this.errors.defecationTime3School = this.$t('Invalid time format');
                valid = false;
            }
            if(this.formData.defecationMemo1School && this.formData.defecationMemo1School.length > 200) {
                this.errors.defecationMemo1School = this.$t('Please enter 200 characters or less');
                valid = false;
            }
            if(this.formData.defecationMemo2School && this.formData.defecationMemo2School.length > 200) {
                this.errors.defecationMemo2School = this.$t('Please enter 200 characters or less');
                valid = false;
            }
            if(this.formData.defecationMemo3School && this.formData.defecationMemo3School.length > 200) {
                this.errors.defecationMemo3School = this.$t('Please enter 200 characters or less');
                valid = false;
            }


            // sleep
            this.formData.sleepStart1School = changeToHhMm(this.formData.sleepStart1School);
            if(this.formData.sleepStart1School && !validateHhMm(this.formData.sleepStart1School)) {
                this.errors.sleepStart1School = this.$t('Invalid time format');
                valid = false;
            }
            // if(this.formData.sleepStart1School && this.formData.sleepEnd1School && this.formData.sleepStart1School > this.formData.sleepEnd1School) {
            //     this.errors.sleepStart1School = this.$t('start time must be earlier than end time');
            //     valid = false;
            // }
            // if(this.formData.sleepStart2School && this.formData.sleepEnd2School && this.formData.sleepStart2School > this.formData.sleepEnd2School) {
            //     this.errors.sleepStart2School = this.$t('start time must be earlier than end time');
            //     valid = false;
            // }

            this.formData.sleepEnd1School = changeToHhMm(this.formData.sleepEnd1School);
            if(this.formData.sleepEnd1School && !validateHhMm(this.formData.sleepEnd1School)) {
                this.errors.sleepEnd1School = this.$t('Invalid time format');
                valid = false;
            }
            this.formData.sleepStart2School = changeToHhMm(this.formData.sleepStart2School);
            if(this.formData.sleepStart2School && !validateHhMm(this.formData.sleepStart2School)) {
                this.errors.sleepStart2School = this.$t('Invalid time format');
                valid = false;
            }
            this.formData.sleepEnd2School = changeToHhMm(this.formData.sleepEnd2School);
            if(this.formData.sleepEnd2School && !validateHhMm(this.formData.sleepEnd2School)) {
                this.errors.sleepEnd2School = this.$t('Invalid time format');
                valid = false;
            }

            // temperature
            this.formData.temperatureTime1School = changeToHhMm(this.formData.temperatureTime1School);
            if(this.formData.temperatureTime1School && !validateHhMm(this.formData.temperatureTime1School)) {
                this.errors.temperatureTime1School = this.$t('Invalid time format');
                valid = false;
            }
            this.formData.temperatureTime2School = changeToHhMm(this.formData.temperatureTime2School);
            if(this.formData.temperatureTime2School && !validateHhMm(this.formData.temperatureTime2School)) {
                this.errors.temperatureTime2School = this.$t('Invalid time format');
                valid = false;
            }
            this.formData.temperatureTime3School = changeToHhMm(this.formData.temperatureTime3School);
            if(this.formData.temperatureTime3School && !validateHhMm(this.formData.temperatureTime3School)) {
                this.errors.temperatureTime3School = this.$t('Invalid time format');
                valid = false;
            }

            if(this.formData.temperature1School && this.formData.temperature1School < 0) {
                this.errors.temperature1School = this.$t('Please input positive number');
                valid = false;
            }
            if(this.formData.temperature1School && (this.formData.temperature1School < 34 || this.formData.temperature1School > 42)) {
                this.errors.temperature1School = this.$t('Incorrect temperature value');
                valid = false;
            }
            if(this.formData.temperature2School && this.formData.temperature2School < 0) {
                this.errors.temperature2School = this.$t('Please input positive number');
                valid = false;
            }
            if(this.formData.temperature2School && (this.formData.temperature2School < 34 || this.formData.temperature2School > 42)) {
                this.errors.temperature2School = this.$t('Incorrect temperature value');
                valid = false;
            }
            if(this.formData.temperature3School && this.formData.temperature3School < 0) {
                this.errors.temperature3School = this.$t('Please input positive number');
                valid = false;
            }
            if(this.formData.temperature3School && (this.formData.temperature3School < 34 || this.formData.temperature3School > 42)) {
                this.errors.temperature3School = this.$t('Incorrect temperature value');
                valid = false;
            }

            // temperature & temperatureTime
            for (let i = 1; i <= 3; i++) {
                if(!this.formData[`temperature${i}School`] && this.formData[`temperatureTime${i}School`]) {
                    this.errors[`temperature${i}School`] = this.$t('Please input both temperature and time');
                    valid = false;
                }

                if(this.formData[`temperature${i}School`] && !this.formData[`temperatureTime${i}School`]) {
                    this.errors[`temperatureTime${i}School`] = this.$t('Please input both temperature and time');
                    valid = false;
                }
            }

            this.inputError = !valid;
            return valid;
        },
        carryingFormData() {
            const keyGroups = [
                ['mealTime*School', "mealAmount*School", "mealMemo*School"],
                ['defecationTime*School', 'defecation*School', 'defecationMemo*School'],
                ['temperatureTime*School', 'temperature*School'],
                ['sleepStart*School', 'sleepEnd*School'],
            ];

            keyGroups.forEach(keys => {
                let validFormValues = [];
                let validCount = 0;
                // NOTE:sleepのみ最大2つまで
                let max = keys[0] == 'sleepStart*School' ? 2 : 3;

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
                pickUpTime: null,
                pickUpPerson: null,
                guardian: null,
                nurseName: null,
                mealTime1School: null,
                mealTime2School: null,
                mealTime3School: null,
                mealMemo1School: null,
                mealMemo2School: null,
                mealMemo3School: null,
                sleepStart1School: null,
                sleepEnd1School: null,
                sleepStart2School: null,
                sleepEnd2School: null,
                temperature1School: null,
                temperature2School: null,
                temperature3School: null,
                temperatureTime1School: null,
                temperatureTime2School: null,
                temperatureTime3School: null,
                weather: null,
                defecationCount1School: null,
                defecationCount2School: null
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

                    this.inputError = false;
                    this.initFormError();
                    if (response.contactBook) {
                        this.formData = response.contactBook;
                        this.resetFormCounts();
                        this.updateFormCounts(response.contactBook);
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
                return moment(sleepTimeStart, 'hh:mm:ss').format("HH:mm") + "~" + moment(sleepTimeEnd, 'hh:mm:ss').format("HH:mm");
            } else {
                return '';
            }
        },
        formatTemperature(temperature){
            if(temperature) {
                return temperature + "℃";
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
        formatStatus(status) {
            if(status == 1) {
                return '普通';
            } else if(status == 2) {
                return '少ない';
            } else if(status == 3) {
                return '多い';
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
        formatTimeStd(timeStd) {
            if(timeStd) {
                return moment(timeStd, 'hh:mm:ss').format('HH:mm');
            }
            return null;
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
        },
        onPrev() {
            var date = moment(this.selectedDate).add(-1, 'days').format('YYYY-MM-DD');
            this.getContact(date);
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
                this.formData[`mealTime${count}School`] = null;
                this.formData[`mealAmount${count}School`] = null;
                this.formData[`mealMemo${count}School`] = null;
                this.errors[`mealTime${count}School`] = null;
                this.errors[`mealMemo${count}School`] = null;
                this.formCount.meal -= 1
            }
        },
        async onDelDefecationForm() {
            if (await this.$refs.ConfirmModal.confirm('削除確認', '１つ削除してよろしいですか？')) {
                let count = this.formCount.defecation;
                this.formData[`defecationTime${count}School`] = null;
                this.formData[`defecation${count}School`] = null;
                this.formData[`defecationMemo${count}School`] = null;
                this.errors[`defecationTime${count}School`] = null;
                this.errors[`defecationMemo${count}School`] = null;
                this.formCount.defecation -= 1;
            }
        },
        async onDelTempForm() {
           if (await this.$refs.ConfirmModal.confirm('削除確認', '１つ削除してよろしいですか？')) {
                let count = this.formCount.temperature;
                this.formData[`temperatureTime${count}School`] = null;
                this.formData[`temperature${count}School`] = null;
                this.errors[`temperatureTime${count}School`] = null;
                this.errors[`temperature${count}School`] = null;
                this.formCount.temperature -= 1;
            }
        },
        async onDelSleepForm() {
            if (await this.$refs.ConfirmModal.confirm('削除確認', '１つ削除してよろしいですか？')) {
                let count = this.formCount.sleep;
                this.formData[`sleepStart${count}School`] = null;
                this.formData[`sleepEnd${count}School`] = null;
                this.errors[`sleepStart${count}School`] = null;
                this.errors[`sleepEnd${count}School`] = null;
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
            // TODO:リファクタ
            // meal
            if (contact.mealTime3School || contact.mealMemo3School || contact.mealAmount3School) {
                this.formCount.meal = 3;
            } else if (contact.mealTime2School || contact.mealMemo2School|| contact.mealAmount2School) {
                this.formCount.meal = 2;
            }

            // defecation
            if (contact.defecationTime3School || contact.defecation3School || contact.defecationMemo3School) {
                this.formCount.defecation = 3;
            } else if (contact.defecationTime2School || contact.defecation2School|| contact.defecationMemo2School) {
                this.formCount.defecation = 2;
            }
            // sleep
            if (contact.sleepStart2School || contact.sleepEnd2School) {
                this.formCount.sleep = 2;
            }

            // temperature
            if (contact.temperatureTime3School || contact.temperature3School ) {
                this.formCount.temperature = 3;
            } else if (contact.temperatureTime2School || contact.temperature2School) {
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
        display: flex;
        justify-content: center;
        align-items: center;
    }
    .calendar-left {
        display: flex;
        justify-content: left;
        align-items: center;
    }
    .calendar-title {
        display: flex;
        align-items: center;
    }
    .hour-minute-input > input {
        padding: 2px!important;
    }
    div.meal-time-input div.home-minute-input > input {
        padding: 2px!important;
    }
    @media (max-width: 500px) {
        h5.card-title {
            font-size: 13px!important;
        }
    }
</style>
