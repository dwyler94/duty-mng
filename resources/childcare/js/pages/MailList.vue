<template>
    <div class="container">
        <br>
        <br>
        <div class="card">
            <div class="card-header calendar-title">
                <h3 class="card-title mb-0">{{ officeName }}</h3>
            </div>
            <div class="card-body">
                <div class="row justify-content-center">
                    <div class="col-md-2">
                        <router-link to="/broadcast-mail">
                            <router-link type="button" class="btn btn-block btn-all-email" :to="{name: 'send-mail', query: {childrenClassId: 0, type: 0}}">
                                <div class="text-white text-center mb-0 mt-2">
                                    一斉メール
                                </div>
                            </router-link>
                        </router-link>
                    </div>
                    <div class="col-md-2"></div>
                    <div class="col-md-2"></div>
                </div>
                <div class="row justify-content-center mt-5">
                    <div class="col-md-2">
                        <router-link :to="{name: 'send-mail', query: {childrenClassId: 1, type: 0}}">
                            <div type="button" class="btn btn-block btn-class-email">
                                <div class="text-white text-center mb-0 mt-2">
                                    0歳児クラス
                                </div>
                            </div>
                        </router-link>
                    </div>
                    <div class="col-md-2">
                        <router-link :to="{name: 'send-mail', query: {childrenClassId: 2, type: 0}}">
                            <div type="button" class="btn btn-block btn-class-email">
                                <div class="text-white text-center mb-0 mt-2">
                                    1歳児クラス
                                </div>
                            </div>
                        </router-link>
                    </div>
                    <div class="col-md-2">
                        <router-link :to="{name: 'send-mail', query: {childrenClassId: 3, type: 0}}">
                            <div type="button" class="btn btn-block btn-class-email">
                                <div class="text-white text-center mb-0 mt-2">
                                    2歳児クラス
                                </div>
                            </div>
                        </router-link>
                    </div>
                </div>
                <div class="row justify-content-center mt-5">
                    <div class="col-md-2">
                        <router-link :to="{name: 'send-mail', query: {childrenClassId: 4, type: 0}}">
                            <div type="button" class="btn btn-block btn-class-email">
                                <div class="text-white text-center mb-0 mt-2">
                                    3歳児クラス
                                </div>
                            </div>
                        </router-link>
                    </div>
                    <div class="col-md-2">
                        <router-link :to="{name: 'send-mail', query: {childrenClassId: 5, type: 0}}">
                            <div type="button" class="btn btn-block btn-class-email">
                                <div class="text-white text-center mb-0 mt-2">
                                    4歳児クラス
                                </div>
                            </div>
                        </router-link>
                    </div>
                    <div class="col-md-2">
                        <router-link :to="{name: 'send-mail', query: {childrenClassId: 6, type: 0}}">
                            <div type="button" class="btn btn-block btn-class-email">
                                <div class="text-white text-center mb-0 mt-2">
                                    5歳児クラス
                                </div>
                            </div>
                        </router-link>
                    </div>
                </div>
                <div class="row justify-content-center my-5">
                    <div class="col-md-2">
                        <router-link :to="{name: 'send-mail', query: {childrenClassId: 0, type: 1}}">
                            <div type="button" class="btn btn-block btn-emerge-email">
                                <div class="text-white text-center mb-0 mt-2">
                                    緊急メール
                                </div>
                            </div>
                        </router-link>
                    </div>
                    <div class="col-md-2">

                    </div>
                    <div class="col-md-2">
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import { mapState } from 'vuex';


export default {
    computed: {
        ...mapState({
            officeName: state =>  {
                if (state.session.info.office) return state.session.info.office.name;
                return '';
            }
        }),
    },
};
</script>
