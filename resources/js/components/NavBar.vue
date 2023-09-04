<template>
    <nav class="navbar navbar-expand-lg navbar-light bg-white spkfrm__navBar">
        <div class="container-fluid">
            <router-link :to="{ name: user ? 'home' : 'welcome' }" class="navbar-brand speakForms__headerLogo">
                <img class="spkfrm__headerLogoImg" :src="logo" />
                <img class="spkfrm__headerLogoImg--mobile" :src="logoSm" />
            </router-link>
            <!-- <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarToggler" aria-controls="navbarToggler" aria-expanded="false">
                <span class="navbar-toggler-icon" />
            </button> -->

            <div id="navbarToggler" class="spkfrm__navBarActions">                

                <ul class="navbar-nav ml-auto">
                <!-- Authenticated -->
                <div v-if="user">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-dark"
                    href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                    >
                    <img :src="user.photo_url" class="rounded-circle profile-photo mr-1">
                    {{ user.name }}
                    </a>
                    <div class="dropdown-menu">
                    <router-link v-if="user.roles == 'admin'" :to="{ name: 'admin.user' }" class="dropdown-item pl-3">
                        <v-icon style="font-size: 1em;">fa-cog</v-icon>
                        {{ $t('admin') }}
                    </router-link>
                    <router-link :to="{ name: 'settings.profile' }" class="dropdown-item pl-3">
                        <v-icon style="font-size: 1em;">fa-cog</v-icon>
                        {{ $t('settings') }}
                    </router-link>

                    <div class="dropdown-divider" />
                    <a href="#" class="dropdown-item pl-3" @click.prevent="logout">
                        <v-icon style="font-size: 1em;">fa-sign-out-alt</v-icon>
                        {{ $t('logout') }}
                    </a>
                    </div>
                </li>
                </div>
                <!-- Guest -->
                <template v-else>
                    <li class="nav-item">
                    <router-link :to="{ name: 'login' }" class="nav-link" active-class="active">
                        {{ $t('login') }}
                    </router-link>
                    </li>
                    <li class="nav-item">
                    <router-link :to="{ name: 'register' }" class="nav-link" active-class="active">
                        {{ $t('register') }}
                    </router-link>
                    </li>
                </template>
                </ul>

                <Activity :count="11" :status="showActivities" :handleStatusChange="changeActivitiesShowStatus" />

            </div>
        </div>
    </nav>
</template>

<script>
import { mapGetters } from 'vuex'
import Activity from '../components/Activity'

export default {
    data: () => ({
        status_one: true,
        appName: window.config.appName,        
        logo: '/img/logo.png',
        logoSm: '/img/logo-small.png',
    }),

    components: {
        Activity,
    },

    computed: mapGetters({
        user: 'auth/user',
        showActivities: 'setting/showActivities',
    }),

    methods: {
        changeActivitiesShowStatus: function() {
            // Change the show activity bar status
            this.$store.dispatch('setting/setShowActivity', { value: !this.showActivities });
        },
        async logout () {
            // Log out the user.
            await this.$store.dispatch('auth/logout')

            // Redirect to login.
            this.$router.push({ name: 'login' })
        }
    }
}
</script>

<style scope>
.profile-photo {
    width: 30px;
}
.spkfrm__headerLogoImg--mobile {
    display: none;
    width: 30px;
    margin: 0 0 0 25px;
}
.dropdown-menu {
    min-width: 13rem;
    border: none !important;
    box-shadow: 0px 2px 2px 0px #00000017;
}
.nav-link {
    font-size: 1.3em;
    font-weight: 400;
}
.nav-link.active {
    color: #0054a6 !important;
}
.spkfrm__navBarActions {
    display: flex;
    flex-direction: row;
}
.navbar-nav .dropdown-menu {
    position: absolute !important;
}
.navLinks__container {
    width: 170px;
    line-height: 40px;
    margin-left: -120px;
}
.navLinks__container a {
    font-size: 1.3em;
    color: #000;
    float: left;
    padding: 0 15px;
}
.navLinks__container a:last-child {
    padding-right: 20px !important;
}
.navLinks__container a.router-link-active {
    color: #0054a6 !important; 
}
.navbar-collapse {
    flex-basis: 100% !important;
}
@media screen and (max-width: 425px) {
    
}
@media screen and (max-width: 600px) {
    .spkfrm__headerLogoImg {
        display: none;
    }
    .spkfrm__headerLogoImg--mobile {
        display: block;
        margin-left: 5px;
    }
    .nav-item a {
        font-size: 12px;
        margin-right: 5px;
    }
}
@media screen and (max-width: 968px) {
    .navLinks__container {
        width: auto !important;
        margin-left: 0;
    }

    .navLinks__container a{
        float: none;
        padding: 0;
    }
}
</style>