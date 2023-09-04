<template>
  <div class="row settingsCard__row gutted_container">
    <div class="col-md-3 col-sm-12 settingsCard__container">
      <card class="settings-card">
        <h3>{{ $t('settings') }}</h3>
        <ul class="nav flex-column nav-pills">
          <li v-for="tab in tabs" :key="tab.route" class="nav-item">
            <router-link :to="{ name: tab.route }" class="nav-link" active-class="active">
              <v-icon style="font-size:1em;">{{tab.icon}}</v-icon>
              {{ tab.name }}
            </router-link>
          </li>
        </ul>
      </card>
    </div>

    <div class="col-md-9 col-sm-12 settingsCard__container">
      <transition name="fade" mode="out-in">
        <router-view />
      </transition>
    </div>
  </div>
</template>

<script>
export default {
  middleware: 'admin',

  computed: {
    tabs () {
      return [
        {
          icon: 'fa-user',
          name: this.$t('admin-user'),
          route: 'admin.user'
        },
        {
          icon: 'fa-pencil-alt',
          name: this.$t('admin-pages'),
          route: 'admin.pages'
        },
      ]
    }
  }
}
</script>

<style>
.settingsCard__row {
  margin-top: 20px;
}
.settings-card .card-body {
  padding: 0;
}
@media screen and (max-width: 968px) {
  .settingsCard__container {
    margin: 0 10px;
  }
}
</style>