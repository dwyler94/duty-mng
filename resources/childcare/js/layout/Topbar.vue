<template>
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a
                    class="nav-link"
                    data-widget="pushmenu"
                    href="#"
                    role="button"
                >
                    <i class="fas fa-bars"></i>
                </a>
            </li>
        </ul>
        <!-- <ul class="navbar-nav ml-3" v-if="showChangeBtn()">
            <li class="nav-item kintai-system system-unselected">
                <a
                    class="nav-link px-md-3 px-sm-1"
                    href="/"
                    role="button"
                >
                    勤怠
                </a>
            </li>
            <li class="nav-item childcare-system system-selected">
                <a
                    class="nav-link px-md-3 px-sm-1"
                    href="#"
                    role="button"
                >
                    保育
                </a>
            </li>
        </ul> -->
        <ul class="navbar-nav ml-auto">
            <li class="nav-item dropdown">
                <a class="nav-link" data-toggle="dropdown" href="#" v-if="session.noticeCount">
                    <i class="far fa-bell"></i>
                    <span class="badge badge-warning navbar-badge">{{ session.noticeCount }}</span>
                </a>
                <div class="dropdown-menu">
                    <div v-for="(notification, index) in session.notifications" :key="index">
                        <a v-bind:href="`/notice/${notification.id}`" class="dropdown-item">
                            {{ notification.message }}<small><i class="far fa-clock mr-1"></i>{{ notification.ago }}</small>
                        </a>
                        <div class="dropdown-divider"></div>
                    </div>
                    <span class="dropdown-item dropdown-footer text-gray text-center"><small>最新の10件を表示しています</small></span>
                </div>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="#">
                    <div class="top-profile-link">
                        <div class="top-avatar">
                            <img src="/images/dummy-avatar.png" />
                        </div>
                        <div class="top-profile-name">
                            {{ session.name }}
                        </div>
                    </div>
                </a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link" style="font-size: 20px;" @click="signOut">
                    <i class="fas fa-sign-out-alt"></i>
                </a>
            </li>
        </ul>
    </nav>
</template>
<script>
import { handleSignOut } from '../helpers/error';
import { mapState } from 'vuex';
import { Guards } from '../global/consts';

export default {
    computed: {
        ... mapState({
            session: state => state.session.info
        }),
    },
    methods: {
        signOut() {
            handleSignOut();
        },
        showChangeBtn() {
            if(this.session.office) {
                if(this.session.office.isNursery || this.session.roleId === Guards.ADMIN)
                    return true;
                else
                    return false;
            }
            return false;
        }
    }
};
</script>
<style scoped>
.dropdown-menu{
    top: 30px;
    left: -130px;
    width:300px;
    max-height:600px;
    overflow-x: hidden;
    overflow-y: auto;
}

.dropdown-item{
    white-space: normal;
    word-break: break-all;
}
</style>
