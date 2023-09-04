<template>
  <div class="basic-layout d-flex align-items-center justify-content-center m-0">
    <v-app class="w-100" :style="background_color">
      <v-container class="p-0 h-100" fluid>
        <child />
      </v-container>
    </v-app>
  </div>
</template>

<script>
export default {
  name: 'BasicLayout',

  computed: {
    background_color() {
      return `background-color: ${this.$store.state.setting.bgColor}`;
    }
  },

}
</script>

<style lang="scss">
.basic-layout {
  color: #636b6f;
  height: 100vh;
  font-weight: 100;
  position: relative;

  .links > a {
    color: #636b6f;
    padding: 0 25px;
    font-size: 12px;
    font-weight: 600;
    letter-spacing: .1rem;
    text-decoration: none;
    text-transform: uppercase;
  }
}
</style>
