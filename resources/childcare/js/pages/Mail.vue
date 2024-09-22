<template>
    <div class="container">
        <br>
        <br>
        <div class="card">
            <div class="card-header calendar-title">
                <h3 class="card-title mb-0">{{ officeName }}</h3>
            </div>
            <div class="card-body">
                <div class="row justify-content-center mt-2">
                    <div class="col-md-2">
                    </div>
                    <div class="col-md-2">
                        <router-link :to="{name: 'send-mail', query: {childrenClassId: 0, type: 0}}">
                            <div type="button" class="btn btn-block btn-all-email">
                                <div class="text-white text-center mb-0 mt-2">
                                    メール送信
                                </div>
                            </div>
                        </router-link>
                    </div>
                    <div class="col-md-2">
                        <router-link :to="{name: 'mail-table'}">
                            <div type="button" class="btn btn-block btn-class-email">
                                <div class="text-white text-center mb-0 mt-2">
                                    送信履歴
                                </div>
                            </div>
                        </router-link>
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
