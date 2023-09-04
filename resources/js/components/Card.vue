<template>
  <div class="spkfrm__cardContainer">
    <div v-if="title" class="spkfrm__cardContainer-header">
      <router-link :to="link">
        {{ title }}
      </router-link>
    </div>

    <div class="spkfrm__cardHorizontalLine"></div>

    <div class="spkfrm__cardContainer-body">
      <slot />
    </div>
  </div>
</template>

<script>
export default {
  name: 'Card',

  props: {
    title: { type: String, default: null },
    link: { type: String, default: null }
  }
}
</script>

<style lang="scss" scoped>
.spkfrm__cardContainer {  
  min-width: 300px;
  background: #fff;
  box-shadow: 0px 2px 8px 0px rgba(61, 88, 143, 0.06), 0px 6px 16px -3px rgba(71, 91, 133, 0.2);
  border-radius: 6px;
  border: 1px solid #DEE3ED;
}

.spkfrm__cardHorizontalLine {
  width: 100%;
  height: 1px;
  margin: 1.5rem auto;
  background-color: #DEE3ED;
}

.spkfrm__cardContainer-header {
  font-family: 'Nunito', sans-serif;
  font-weight: 600;
  font-size: 18px;
  line-height: 24px;
  color: #525F7A;
  padding: 1rem 1rem 0 1rem;

  a {
    color: #525F7A;
    text-decoration: none;
  }
}

.spkfrm__cardContainer-body {
  padding: 0 1rem 1rem 1rem;
}
</style>
