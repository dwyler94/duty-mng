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
            <li class="nav-item kintai-system system-selected">
                <a
                    class="nav-link px-md-3 px-sm-1"
                    :href="isAdmin ? '/child/application-table' : '/child/'"
                    role="button"
                >
                    勤怠
                </a>
            </li>
            <li class="nav-item childcare-system system-unselected">
                <a
                    class="nav-link px-md-3 px-sm-1"
                    :href="isAdmin ? '/child/application-table' : '/child/'"
                    role="button"
                >
                    保育
                </a>
            </li>
        </ul> -->
        <ul class="navbar-nav ml-auto">
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
        isAdmin() {
            return this.session.roleId === Guards.ADMIN
        }
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
