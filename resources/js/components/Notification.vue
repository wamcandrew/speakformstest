<template>
  <div class="spkfrm__notificationContainer">
    <slot />
  </div>
</template>

<script>
export default {
  name: `Notification`,
};
</script>

<style lang="scss">
.spkfrm__notificationContainer {
  background: #fff;
  box-shadow: 0px 2px 8px 0px rgba(61, 88, 143, 0.06), 0px 6px 16px -3px rgba(71, 91, 133, 0.2);
  border-radius: 6px;
  border: 1px solid #DEE3ED;
  padding: 8px 10px;
  margin: 5px 0;
}
</style>