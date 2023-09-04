<template>
  <div class="labelContainer">
    <div
      :class="{
        [`labelContainer__elem_size-${size}`]: size,
        [`labelContainer__elem_type-${type}`]: type,
      }">
      <slot></slot>
    </div>
  </div>
</template>

<script>
export default {
  name: 'Label',

  props: {
    size: {
      type: String,
      default: 'normal',
    },

    type: {
      type: String,
      default: 'gray',
    }
  },
}
</script>

<style lang="scss" scoped>
.labelContainer__elem_size-small {
  width: fit-content;
  font-size: 12px;
  font-weight: 600;
  padding: 2px 10px;
  border-radius: 15px;
  cursor: pointer;
}

.labelContainer__elem_size-normal {
  width: fit-content;
  font-size: 16px;
  font-weight: 600;
  padding: 2px 18px;
  border-radius: 15px;
  cursor: pointer;
}

.labelContainer__elem_type-primary {
  color: #2659C2;
  background-color: #DDE8FD;
}

.labelContainer__elem_type-info {
  color: #2681C2;
  background-color: #DDF0FD;
}

.labelContainer__elem_type-danger {
  color: #C22626;
  background-color: #FDDDDD;
}

.labelContainer__elem_type-warning {
  color: #C28E26;
  background-color: #FDF2DD;
}

.labelContainer__elem_type-success {
  color: #40C226;
  background-color: #E2FDDD;
}

.labelContainer__elem_type-gray {
  color: #525F7A;
  background-color: #DEE3ED;
}

.labelContainer__elem_type-inverted {
  color: #FFFFFF;
  background-color: #525F7A;
}
</style>