<template>
    <div id="surveyCompleted" class="gutted_container" style="padding-top: 20px;">
        <div class="card">
            <h1 class="surveyCompleted__title">Review: {{completion_details.survey_name}}</h1>
            <p>Completed by : <span class="surveyCompleted__email">{{completion_details.completed_by}}</span></p>
            <p>Completed on : <span class="surveyCompleted__date">{{completion_details.completed_on | moment("dddd, MMMM Do YYYY, h:mm:ss a")}}</span></p>
            <div class="surveyCompleted__answersList">
                <div v-for="(question, index) in questions" :key="index">
                    <h4>{{index+1}} - {{ question.question_content }}</h4>
                    <div class="answersList__answer">
                        <b-card class="surveyReview__review--component surveyReview__review--answerAudio">
                            <p>{{ questions[index].answer_content }}</p>
                            <audio v-if="questions[index].answer_url" controls :src="questions[index].answer_url" controlsList="nodownload" style="width: 100%; margin: 10px 0;" />
                        </b-card>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Vue from 'vue'
import axios from 'axios'
import _ from 'lodash'
import { CardPlugin } from 'bootstrap-vue'

Vue.use(CardPlugin);

export default {
    name: 'SurveyCompleted',
    data () {
        return {
            id: '',
            completion_details: {
                survey_id: '',
                survey_name: '',
                completed_on: '',
                completed_by: ''
            },
            questions: []
        }
    },
    created() {
        this.id = this.$route.params.completeid;
        axios.get('/api/survey-complete/'+this.id)
            .then(res => {         
                this.completion_details.survey_id = res.data.survey_id;  
                this.completion_details.survey_name = res.data.survey_name;
                this.completion_details.completed_on = res.data.survey_completed_on;
                this.completion_details.completed_by = res.data.survey_respondent;

                // Retrieve questions for survey
                axios
                    .get('/api/question/'+this.completion_details.survey_id)
                    .then(res => {
                        var index, len;
                        for(index = 0, len = res.data.length; index < len; index++){
                            this.questions.push({
                                question_id: res.data[index].question_id,
                                question_index: res.data[index].question_no,
                                question_content: res.data[index].question_content,
                                is_audio: null,
                                answer_content: null
                            });
                        }
                        this.questions = _.orderBy(this.questions, 'question_index');

                        // Retrieve answers for questions
                        axios
                            .get('/api/answer/'+this.id)
                            .then(res => {
                                var index, len;
                                for(index = 0, len = res.data.length; index < len; index++){
                                    this.questions.some(function(el) {
                                        if (el.question_id == res.data[index].question_id) {
                                            el.is_audio = res.data[index].is_audio;
                                            el.answer_content = res.data[index].answer_content;
                                            el.answer_url = res.data[index].answer_url;
                                        }
                                    });
                                }
                            })
                    });
            });
        
    }
}
</script>

<style scope>
audio
{
    background: #f1f3f4;
    border: 1px solid #b8b8b8;
    -webkit-transition:all 0.5s linear;
    -moz-transition:all 0.5s linear;
    -o-transition:all 0.5s linear;
    transition:all 0.5s linear;
    -moz-box-shadow: 2px 2px 4px 0px #0054a680;
    -webkit-box-shadow:  2px 2px 4px 0px #0054a680;
    box-shadow: 2px 2px 4px 0px #0054a680;
    -moz-border-radius: 5px ;
    -webkit-border-radius: 5px ;
    border-radius: 5px ;
}
.surveyCompleted {
    margin-top: 20px;
}
.answersList__answer {
    margin-bottom: 20px;
}
.surveyReview__review--component {
    padding: 0 !important;
}
.surveyReview__review--component .card-body {
    padding: 0.75rem;
}
</style>