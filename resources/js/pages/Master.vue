<template>
    <div class="container">
        <br>
        <br>
        <div class="row justify-content-center">
            <div class="col-md-4">
                <router-link to="/office-master">
                    <div type="button" class="border-radius-15 border-color-green border-style-solid border-width-thin background-grey py-1">
                        <h3 class="text-white text-center mb-0 mt-2">
                            事業所マスタ
                        </h3>
                        <div class="text-white text-center mb-2">事業所の登録｜編集｜削除</div>
                    </div>
                </router-link>
            </div>
            <div class="col-md-4">
                <router-link to="/region-master">
                    <div type="button" class="border-radius-15 border-color-green border-style-solid border-width-thin background-grey py-1">
                        <h3 class="text-white text-center mb-0 mt-2">
                            エリアマスタ
                        </h3>
                        <div class="text-white text-center mb-2">エリアの登録｜編集｜削除</div>
                    </div>
                </router-link>
            </div>
            <div class="col-md-4">
                <router-link to="/employee-master">
                    <div type="button" class="border-radius-15 border-color-green border-style-solid border-width-thin background-grey py-1">
                        <h3 class="text-white text-center mb-0 mt-2">
                            社員マスタ
                        </h3>
                        <div class="text-white text-center mb-2">社員情報の登録｜編集｜削除</div>
                    </div>
                </router-link>
            </div>
        </div>
        <br>
        <div class="row justify-content-center mt-5">
            <div class="col-md-4">
                <router-link to="/workinghours-master">
                    <div type="button" class="border-radius-15 border-color-green border-style-solid border-width-thin background-grey py-1">
                        <h3 class="text-white text-center mb-0 mt-2">
                            勤務時間マスタ
                        </h3>
                        <div class="text-white text-center mb-2">時間帯の登録｜編集｜削除</div>
                    </div>
                </router-link>
            </div>
            <div class="col-md-4">
                <router-link to="/hourly-master">
                    <div type="button" class="border-radius-15 border-color-green border-style-solid border-width-thin background-grey py-1">
                        <h3 class="text-white text-center mb-0 mt-2">
                            時給テーブルマスタ
                        </h3>
                        <div class="text-white text-center mb-2">時間帯の登録｜編集｜削除</div>
                    </div>
                </router-link>
            </div>
            <div class="col-md-4">
                <router-link to="/vacation-master">
                    <div type="button" class="border-radius-15 border-color-green border-style-solid border-width-thin background-grey py-1">
                        <h3 class="text-white text-center mb-0 mt-2">
                            休暇理由マスタ
                        </h3>
                        <div class="text-white text-center mb-2">休暇理由の登録｜編集｜削除</div>
                    </div>
                </router-link>
            </div>
        </div>
        <br>
        <div class="row justify-content-center my-5">
            <div class="col-md-4">
                <!-- <router-link to="/schedule-master">
                    <div type="button" class="border-radius-15 border-color-green border-style-solid border-width-thin background-grey py-1">
                        <h3 class="text-white text-center mb-0 mt-2">
                            所定労働時間マスタ
                        </h3>
                        <div class="text-white text-center mb-2">月毎の登録｜編集｜削除</div>
                    </div>
                </router-link> -->
            </div>
            <div class="col-md-4">

            </div>
            <div class="col-md-4">
            </div>
        </div>
    </div>
</template>
