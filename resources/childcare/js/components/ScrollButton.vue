<template>
    <transition name="fade">
        <div class="d-md-none scroll-button-group" v-if="visible">
            <button class="btn btn-secondary" @click="up"><i class="fas fa-chevron-up"></i></button>
            <button class="btn btn-secondary" @click="down"><i class="fas fa-chevron-down"></i></button>
        </div>
    </transition>
</template>

<script>
export default {
    data() {
        return {
            visible: false
        }
    },
    methods: {
        down() {
            window.scrollTo({
                behavior: 'smooth',
                top: document.body.scrollHeight
            })
        },
        scrollWindow() {
            this.visible = window.scrollY > 100;
        },
        up() {
            window.scrollTo({
                behavior: 'smooth',
                top: 0
            })
        }
    },
    mounted() {
        window.addEventListener('scroll', this.scrollWindow);
    }
}
</script>

<style lang="scss" scoped>
    .fade-enter-active,
    .fade-leave-active {
        transition: opacity .5s;
    }

    .fade-enter,
    .fade-leave-to {
        opacity: 0;
    }

    .scroll-button-group {
        bottom: 60px;
        position: fixed;
        right: 20px; 
        z-index: 12;

        .btn {
            border-radius: 50%;
            display: block;
            height: 40px;
            text-align: center;
            width: 40px;
        }
        
        .btn:first-child {
            margin-bottom: 10px;
        }

    }
</style>
