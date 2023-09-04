<template>
  <button :type="nativeType" :disabled="disabled" :class="{
    [`spkfrm__Btn-${type}`]: true,
    [`spkfrm__Btn_Size-${size}`]: size,
    [`spkfrm__Btn_disabled-${disabled}`]: true,
  }"
  @click="handleClick"
  >
    <slot />
  </button>
</template>

<script>
export default {
  name: 'VButton',

  props: {
    type: {
      type: String,
      default: 'primary_WhiteBG'
    },

    nativeType: {
      type: String,
      default: 'submit'
    },

    block: {
      type: Boolean,
      default: false
    },

    size: {
      type: String,
      default: 'sm'
    },

    disabled: {
      type: Boolean,
      default: false,
    },

    handleClick: {
      type: Function,
      default: () => {}
    },
  }
}
</script>

<style lang="scss" scoped>
// Primary Button Styling
.spkfrm__Btn-primary_WhiteBG,
.spkfrm__Btn-primary_GrayXtraLightBG,
.spkfrm__Btn-primary_GrayDarkBG {
  font-family: 'Nunito', Arial;
  background: #3D79F5;
  border-radius: 6px;
  box-shadow: 0px 2px 8px 0px rgba(61,88,143,0.05), 0 6px 16px -3px rgba(71, 91, 133, 0.20);
  outline: none;
  color: #fff;
}

.spkfrm__Btn-primary_PrimaryBG {
  font-family: 'Nunito', Arial;
  background: #fff;  
  border-radius: 6px;
  box-shadow: 0px 2px 8px 0px rgba(61,88,143,0.05);
  outline: none;
  color: #000;
}

// Secondary Button Styling
.spkfrm__Btn-secondary_WhiteBG,
.spkfrm__Btn-secondary_GrayXtraLightBG,
.spkfrm__Btn-secondary_GrayDarkBG {
  font-family: 'Nunito', Arial;
  background: #fff;  
  border-radius: 6px;
  box-shadow: 0px 2px 8px 0px rgba(61,88,143,0.05);
  outline: none;
  color: #525F7A;
}

.spkfrm__Btn-secondary_PrimaryBG {
  font-family: 'Nunito', Arial;
  background: transparent;  
  border-radius: 6px;
  box-shadow: 0px 2px 8px 0px rgba(61,88,143,0.05);
  border: 1px solid #fff;
  outline: none;
  color: #fff;
}

// Tertiary Button Styling
.spkfrm__Btn-tertiary_WhiteBG,
.spkfrm__Btn-tertiary_GrayXtraLightBG {
  font-family: 'Nunito', Arial;
  background: transparent;  
  border-radius: 6px;
  border: 2px solid #DEE3ED;
  outline: none;
  color: #525F7A;
}

.spkfrm__Btn-tertiary_GrayDarkBG {
  font-family: 'Nunito', Arial;
  background: transparent;  
  border-radius: 6px;
  border: 2px solid #DEE3ED;
  outline: none;
  color: #fff;
}

// Button Sizing
.spkfrm__Btn_Size-sm,
.spkfrm__Btn_Size-nl,
.spkfrm__Btn_Size-lg {
  width: fit-content;
}

.spkfrm__Btn_Size-sm {
  font-size: 14px;
  padding: 5px 15px;
}

.spkfrm__Btn_Size-nl {
  font-size: 16px;
  padding: 7px 16px;
}

.spkfrm__Btn_Size-lg {
  font-size: 18px;
  padding: 10px 20px;
}

.spkfrm__Btn_disabled-true {
  background: #CCC;
  color: #000;
}
</style>
