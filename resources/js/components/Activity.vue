<template>
  <div class="bellContainer">
    <div 
      :class="[{
        'bellContainer__circleClicked' : status,
      }, 'bellContainer__circle']"
      v-on:click="handleStatusChange()">
      <img src="img/bell-notification.png" alt="bell-notification-icon">
      <div class="bellContainer__circleCount" v-show="count > 0">
        <span v-if="count < 1000">{{count}}</span>
        <span v-else>999+</span>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'Activity',

  props: {
    count: {
      type: Number,
      default: 0,
    },

    status: {
      type: Boolean,
      default: false,
    },

    handleStatusChange: Function,
  },
}
</script>

<style lang="scss" scoped>
.bellContainer__circle {
  width: 40px;
  height: 40px;
  border-radius: 40px;
  background: transparent;
  cursor: pointer;

  img {
    width: calc(100% - 20px);
    margin: 10px;
  }

  &:hover {
    background-color: #EDF0F7;
  }
}

.bellContainer__circleClicked {
  background-color: #EDF0F7;
}

.bellContainer__circleCount {
  position: relative;
  top: -40px;
  right: -15px;
  width: fit-content;
  height: 20px;
  padding: 2px 4px;
  border-radius: 20px;
  background-color: #3D79F5;
  font-size: 10px;
  text-align: center;
  color: #fff;
}
</style>