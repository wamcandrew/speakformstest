<template>
    <div class="tabbedStepper_wrapper">
        <b-container fluid id="tabbedStepper_container">
            <b-row>
                <b-col md="2" class="tabbedStepper__header">
                    <b-row>                        
                        <b-col md="12" sm="4" cols="4" class="tabbedStepper_headerCol" v-for="(step,index) in steps" v-bind:key="index">
                            <div class="tabbedStepper_headerItem" :class="{'tabbedStepper_headerItem--active' : index+1 == currentTabIndex}">
                                <h4>Step 0{{index+1}}:</h4>
                                <h2>{{ step['title'] }}</h2>                                
                            </div>
                            <div class="tabbedStepper_headerItemCircle" :class="{'tabbedStepper_headerItemCircle--active' : index+1 == currentTabIndex}">
                                <h4>0{{index+1}}</h4>
                            </div>
                        </b-col>                        
                    </b-row>
                </b-col>
                <b-col md="10" class="tabbedStepper__body">
                    <div class="tabbedStepper__bodyContainer">
                        <slot name="body"></slot>
                    </div>
                    <slot name="footer_buttons"></slot>
                </b-col>
            </b-row>
        </b-container>
    </div>
</template>

<script>
import Vue from 'vue'
import { LayoutPlugin } from 'bootstrap-vue'

Vue.use(LayoutPlugin);

export default {
    name: 'TabbedStepper',
    props: {
        steps: {
            type: Array,
            required: true
        },
        currentTabIndex: {
            type: Number,
            required: true
        }
    },
    data: function() {
        return {
            
        }
    },
    created() {
    }
}
</script>

<style scoped>
#tabbedStepper_container {
    margin-top: 1.5rem;
    margin-bottom: 4rem;
}
.tabbedStepper__header {
    padding-top: 10px;
}
.tabbedStepper_headerCol {
    padding-right: 0;
}
.tabbedStepper_headerItem--active {
    border-right: 5px solid #0054a6;
    opacity: 1.0 !important;
}
.tabbedStepper_headerItem {
    background: rgba(255, 255, 255, 1.0);
    padding: 1rem;
    margin: 2px 0;
    opacity: 0.5;
    cursor: pointer;
}
.tabbedStepper_headerItemCircle { display: none; }
.tabbedStepper_headerItem h4 {
    font-size: 12px;
    font-weight: 300;
    text-transform: uppercase;
}
.tabbedStepper_headerItem h2 {
    font-size: 16px;
    font-weight: 500;
    text-transform: uppercase;
}
.tabbedStepper__body {
    background-color: #fff;
    border: 1px solid #dadada;
    padding: 1rem 0.5em;
}
.tabbedStepper__bodyContainer {
    padding: 0 1rem;
}
.tabbedStepper__bodyContainer h1 {
    font-size: 1.8em;
    font-weight: 500;
    margin-bottom: 20px;
}
.tabbedStepper__bodyContainer h3 {
    font-size: 1.25em;
    font-weight: 500;
}
.tabbedStepper__bodyContainer p {
    font-size: 1em;
    font-weight: 400;
    line-height: 1.5em;
}
.tabbedStepper_input {
    border-radius: 0;
}
.tabbedStepper__btnContainer {
    margin-top: 30px;
    margin-bottom: 20px;
    margin-left: 20px;
    margin-right: 20px;
}
@media screen and (max-width: 768px) {
    .tabbedStepper_headerItem {
        display: none;
    }

    .tabbedStepper_headerItemCircle {
        display: block;
        margin-bottom: 30px;
    }

    .tabbedStepper_headerItemCircle h4 {
        width: 50px;
        height: 50px;
        font-size: 1.5em;
        line-height: 45px;
        border-radius: 25px;
        margin: 0 auto;
        border: 3px solid #aaaaaa;
        text-align: center;
        vertical-align: middle;
        color: #aaaaaa;
    }

    .tabbedStepper_headerItemCircle--active h4 {
        border: 3px solid #0054a6;
        color: #0054a6;
    }
}
</style>