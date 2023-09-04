<template>
    <div class="spkfrm--surveyCreateContainer">
        <header class="surveyCreateContainer__surveyTabbedHeader">
            <span class="surveyTabbedHeader__goBackIcon">
                <a
                    @click="goBackPage()">
                    <v-icon class="goBackIcon--icon">fa-arrow-left</v-icon>
                </a>
            </span>
            <h2>Create a Survey</h2>
        </header>
        <div
          class="surveyCreateContainer__surveyFormContainer">
          <h4 class="surveyFormContainer--title">Survey Name</h4>
          <v-text-field
            class="surveyFormContainer--txtfld"
            v-model="wizard.survey_name"
            solo></v-text-field>
          <span class="surveyCreateContainer__horizontalLine"></span>
          <h6 class="surveyFormContainer--titleQuestions">Questions</h6>
          <ul class="surveyFormContainer__surveyFormQuestions">
            <li
              class="surveyFormContainer__surveyFormQuestion--item"
              v-for="(question, index) in questions" :key="index">
              <p>{{index+1}}.</p>
              <v-text-field
                v-model="question.content"
                @change="checkIncomplete()"
                solo></v-text-field>
              <v-icon class="surveyFormContainer--removeQuestion" @click="removeQuestion(index)">far fa-times-circle</v-icon>
            </li>
          </ul>
          <div class="surveyFormContainer__surveyFormAddQuestion">
            <Button
              type="tertiary_WhiteBG"
              :handleClick="addQuestion">
              <v-icon>fas fa-plus-circle</v-icon>
              <span>Add a Question</span>
            </Button>
          </div>
          <span class="surveyCreateContainer__horizontalLine"></span>
          <Button size="nl" :handleClick="submit">
            Publish Survey
          </Button>
        </div>
    </div>
</template>

<script>
import Vue from 'vue'
import axios from 'axios'
import { mapGetters } from 'vuex'
import Button from '../../components/Button'
import TabbedStepper from '../../components/TabbedStepper/main.vue'
import {FormPlugin, FormGroupPlugin, FormInputPlugin, TablePlugin } from 'bootstrap-vue'

Vue.use(FormPlugin);
Vue.use(FormGroupPlugin);
Vue.use(FormInputPlugin);
Vue.use(TablePlugin);

export default {
    middleware: 'auth',
    name: 'CreateSurvey',
    components: {
        TabbedStepper,
        Button,
    },
    data () {
        return {
            el: 0,
            valid1: false,
            valid2: false,
            currentTabIndex: 1,
            formFields: [
                {key: 'index', label: 'Question No'},
                {key: 'question', label: 'Question Content'}
            ],
            modalInfo: {
                title: 'Survey is generating',
                show: false
            },
            steps: [
                {index: 1, title: 'Basic Information', incomplete: true},
                {index: 2, title: 'Survey Questions', incomplete: true},
                {index: 3, title: 'Review', incomplete: false}
            ],
            wizard: {
                survey_name: '',
                survey_questions: [],
                custom_brand: '',
                custom_brand_url: ''
            },
            formEl: {
                eMail: '',
                surveyName: '',                
            },
            questions: [
                {
                    'index': 1,
                    'content': 'Example Question?',
                },
                {
                    'index': 2,
                    'content': 'Another example question.',
                },
            ],
            emailReg: /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,24}))$/,
            nameRules: [
                v => !!v || "Name is required"
            ],
            emailRules: [
                v => !!v || "Email is required",
                v => /.+@.+/.test(v) || "Email is invalid"
            ],
            response: null,
            errors: null,
            errored: false,
            complete: false,
            generatedUrl: null
        }
    },
    metaInfo () {
        return { title: this.$t('create-survey') }
    },
    computed: mapGetters({
        user: 'auth/user'
    }),
    methods: {
        goBackPage: function() {
         window.history.go(-1);
        },
        isNameExists: function() {
            if(this.wizard.survey_name != ''){
                this.steps[0].incomplete = false;
            } else {
                this.steps[0].incomplete = true;
            }
            return (this.wizard.survey_name == "") ? '' : 'has-success';            
        },
        checkIncomplete() {
            console.log('Hello');
            if(this.questions.length > 0) {
                (this.questions[0].content == "") ? this.steps[1].incomplete = true : this.steps[1].incomplete = false;
            }
        },
        addQuestion() {
            var index_num = this.questions.length + 2;
            this.questions.push({
                index: index_num,
                content: '',
            });
            // Refresh indexing
            this.questions.forEach(function(question, index){
                question.index = index+1;
            });
        },
        removeQuestion(index) {
            this.questions.splice(index,1);
            this.questions.forEach(function(question, index){
                question.index = index+1;
            });

            if(this.questions.length > 0) {
                (this.questions[0].content == '') ? this.steps[1].incomplete = false : this.steps[1].incomplete = true;
            }
        },
        submit() {
            if (this.questions.length == 0){
                this.complete = false;
            } else {
                var that = this;
                that.modalInfo.show = true;
                // Generate Random Alpha-Numeric String
                function randomString(length, chars) {
                    var result = '';
                    for (var i = length; i > 0; --i) result += chars[Math.floor(Math.random() * chars.length)];
                    return result;
                }

                var survey_id = randomString(12, '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'); // Survey ID (alpha-numeric, length=12)

                // Submitting to survey api
                axios
                    .post('/api/survey', {
                        survey_id: survey_id,
                        survey_name: this.wizard.survey_name,
                        survey_creator: this.user.email,
                        user_id: this.user.id
                    })
                    .then(res => {
                        // For each question in questions submit to question api
                        this.questions.forEach(function(question){
                            var question_id = randomString(24, '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ');
                            axios
                                .post('/api/question', {
                                    question_id: question_id,
                                    survey_id: survey_id,
                                    question_no: question.index,
                                    question_content: question.content
                                })
                                .catch(error => {
                                    that.errors.push({
                                        error: error
                                    })
                                });
                        });

                        // Generate URL and submit
                        this.complete = true;
                        this.modalInfo.title = 'Survey Created';
                        this.generatedUrl = process.env.MIX_APP_URL+ '/surveys/wizard/' +survey_id;

                        axios
                            .post('/api/survey-created', {
                                email: this.user.email,
                                survey_name: this.wizard.survey_name,
                                url: this.generatedUrl
                            });
                    })
                    .catch(error => {
                        this.errors.push({
                            error: error
                        });
                    });
            }
        }
    }
}
</script>

<style lang="scss" scoped>
.spkfrm--surveyCreateContainer {
    width: 100%;
    height: 100%;
}
.goBackIcon--icon {
  flex: 0 1 auto;
  cursor: pointer;
  margin: 12px 0;
}
.surveyCreateContainer__surveyTabbedHeader {
    display: flex;
    justify-content: flex-start;
    position: relative;
    box-shadow: inset 0px -1px 0px 0px #CCC;
    padding: 10px 20px;

    h2 {
        position: absolute;
        left: 50%;
        font-size: 24px;
        font-weight: 400;
        line-height: 26px;        
        color: #000;
        transform: translateX(-50%);
        margin-top: 10px;        
    }
}
.surveyCreateContainer__surveyFormContainer {
    width: 640px;
    margin: 0 auto;
    padding-top: 40px;

    .surveyFormContainer--title,
    .surveyFormContainer--titleQuestions {
        font-size: 22px;
        font-weight: 600;
        line-height: 24px;
        color: #000;
        margin-bottom: 10px;  
    }

    .surveyFormContainer--txtfld {
        margin-top: 10px;
    }

    .surveyFormContainer__surveyFormQuestions {
        padding-left: 0;
        margin-top: 20px;

        li {
            display: flex;
            flex-direction: row;
            list-style-type: none;

            p {
                font-size: 14px;
                color: #000;
                margin-top: 10px;
                margin-right: 10px;
            }

            .surveyFormContainer--removeQuestion {
                margin-left: 10px;
                margin-bottom: 25px;
            }
        }
    }

    .surveyFormContainer__surveyFormAddQuestion {
        margin-left: 20px;
        margin-bottom: 20px;
    }
}

/** Utilities */
.surveyCreateContainer__horizontalLine {
    display: inline-block;
    width: 100%;
    height: 1px;
    background-color: #CCC;
    margin: 15px 0 25px;
}
</style>