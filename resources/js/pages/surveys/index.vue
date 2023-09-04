<template>
  <transition name="fade" mode="out-in">
    <router-view />
  </transition>
</template>

<script>
export default {
  name: `SurveyHome`,
  
  layout: 'basic',
}
</script>

<style scope>
</style>
