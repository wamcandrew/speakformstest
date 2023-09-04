<template>
  <div class="componentGrid">
    <h3>Style Guide - Components Implementation</h3>
    <div class="componentGrid__Container_row3">
        <Button type="primary_WhiteBG" size="sm">Small Button</Button>
        <Button type="primary_WhiteBG" size="nl">Normal Button</Button>
        <Button type="primary_WhiteBG" size="lg">Large Button</Button>
    </div>
    <div class="componentGrid__Container_row4">
        <Button type="primary_WhiteBG" size="lg">Primary in White BG</Button>
        <Button type="primary_GrayXtraLightBG" size="lg">Primary in Gray Extra Light</Button>
        <Button type="primary_GrayDarkBG" size="lg">Primary in Gray Dark</Button>
        <Button type="primary_PrimaryBG" size="lg">Primary in Primary</Button>
        <Button type="secondary_WhiteBG" size="lg">Secondary in White BG</Button>
        <Button type="secondary_GrayXtraLightBG" size="lg">Secondary in Gray Extra Light</Button>
        <Button type="secondary_GrayDarkBG" size="lg">Secondary in Gray Dark</Button>
        <Button type="secondary_PrimaryBG" size="lg">Secondary in Primary</Button>
    </div>
    <div class="componentGrid__Container_row3">
        <Button type="tertiary_WhiteBG" size="lg">Tertiary in White BG</Button>
        <Button type="tertiary_GrayXtraLightBG" size="lg">Tertiary in Gray Extra Light</Button>
        <Button type="tertiary_GrayDarkBG" size="lg">Tertiary in Gray Dark</Button>
    </div>
    <div class="componentGrid__Container_row4">
        <Activity :count="11" :status="status_one" :handleStatusChange="changeStatusOne" />
        <Activity :count="24839" :status="status_two" :handleStatusChange="changeStatusTwo" />
        <Label type="primary" size="small">Primary</Label>
        <Label type="info" size="small">Info</Label>
        <Label type="danger" size="small">Danger</Label>
        <Label type="warning" size="small">Warning</Label>
        <Label type="success" size="small">Success</Label>
        <Label type="gray" size="small">Gray</Label>
        <Label type="inverted" size="small">Inverted</Label>
        <Label type="primary" size="normal">Primary</Label>        
        <Label type="info" size="normal">Info</Label>
        <Label type="danger" size="normal">Danger</Label>
        <Label type="warning" size="normal">Warning</Label>
        <Label type="success" size="normal">Success</Label>
        <Label type="gray" size="normal">Gray</Label>
        <Label type="inverted" size="normal">Inverted</Label>
    </div>
  </div>
</template>

<script>
import Button from '../components/Button';
import Activity from '../components/Activity';
import Label from '../components/Label';

export default {
  components: {
    Button,
    Activity,
    Label,
  },

  data: () => ({
    status_one: false,
    status_two: false,
  }),

  methods: {
    changeStatusOne: function() {
      this.status_one = !this.status_one;
    },

    changeStatusTwo: function() {
      this.status_two = !this.status_two;
    }
  },

  metaInfo () {
    return { title: this.$t('components') }
  }
}
</script>

<style lang="scss">
.componentGrid__Container_row3,
.componentGrid__Container_row4 {
  display: grid;
  grid-gap: 10px;
  margin: 10px 0;

  button,
  div {
    margin: 0 auto;
  }
}

.componentGrid__Container_row3 {  
  grid-template-columns: repeat(3, 1fr);
}

.componentGrid__Container_row4 {  
  grid-template-columns: repeat(4, 1fr);
}
</style>