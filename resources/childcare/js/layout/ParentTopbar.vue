<template>
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a
                    class="nav-link"
                    href="#"
                    role="button"
                    data-widget="pushmenu"
                >
                    <i class="fas fa-bars"></i>
                </a>
            </li>
        </ul>
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

export default {
    computed: mapState({
        session: state => state.session.info
    }),
    methods: {
        signOut() {
            handleSignOut();
        }
    }
};
</script>
