<template>
  <div>
    <div class="top-right links">
      <template v-if="authenticated">
        <router-link :to="{ name: 'home' }">
          {{ $t('home') }}
        </router-link>
      </template>
      <template v-else>
        <router-link :to="{ name: 'login' }">
          {{ $t('login') }}
        </router-link>
        <router-link :to="{ name: 'register' }">
          {{ $t('register') }}
        </router-link>
      </template>
    </div>

    <div class="text-center">
      <div class="title mb-4">
        <img class="speakForms__headerLogoImg img-fluid" :src="logo" />
        <nav style="margin-top: 20px;">
            <router-link :to="{name: 'tos'}" style="color: #000; margin-top: 20px; margin-right: 5px;">Terms of Services</router-link>
            <router-link :to="{name: 'privacy-policy'}" style="color: #000; margin-top: 20px; margin-left: 5px;">Privacy Policy</router-link>
        </nav>
      </div>
    </div>
  </div>
</template>

<script>
import Vue from 'vue'
import { mapGetters } from 'vuex'

export default {
  layout: 'basic',

  created() {
    Vue.set(this.$store.state.setting, 'bgColor', '#FFF');
  },

  metaInfo () {
    return { title: this.$t('home') }
  },

  data: () => ({
    title: window.config.appName,
    logo: '/img/logo.png',
  }),

  computed: mapGetters({
    authenticated: 'auth/check'
  })
}
</script>

<style scoped>
.top-right {
  position: absolute;
  right: 10px;
  top: 18px;
}

.title {
  font-size: 85px;
}
</style>
