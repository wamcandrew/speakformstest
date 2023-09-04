<template>
  <div class="main-layout" style="min-height: 100vh;">
    <v-app class="w-100">
      <v-container class="p-0 h-100" fluid>
        <navbar />

        <div class="container-fluid" style="padding: 0;">
          <child />
        </div>
      </v-container>
    </v-app>
  </div>
</template>

<script>
import Navbar from '../components/NavBar'
import SiteFooter from '../components/SiteFooter'

export default {
  name: 'MainLayout',

  components: {
    Navbar,
    SiteFooter
  }
}
</script>
