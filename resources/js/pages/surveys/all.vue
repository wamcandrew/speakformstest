<template>
    <div class="surveyList card gutted_container">
        <h4>All Surveys</h4>
        <div class="surveyList__container">
            <div v-if="surveys.length > 0" class="surveyList__linkList">
                <div v-for="(survey, index) in surveys" :key="index" class="surveyList__linkItem custom_scrollbar">
                    <router-link :to="{name: 'survey.info', params: {surveyid: survey.survey_id}}" class="surveyList__link">
                        {{ survey.survey_name }}
                    </router-link>
                </div>
                <div style="padding-top: 30px; margin-bottom: 20px;">
                    <a class="spkFormBtn" v-on:click="prevPage()">Previous</a>
                    <a class="spkFormBtn" v-on:click="nextPage()">Next</a>
                </div>
            </div>
            <div style="padding-top: 30px; margin-bottom: 40px;" v-else>
                <h4 class="loadingContext">There are no surveys to show.</h4>
                <a v-show="showPrev" class="spkFormBtn" style="position: relative; top: 30px;" v-on:click="prevPage()">Prev</a>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios'
import { mapGetters } from 'vuex'

export default {
    middleware: 'auth',
    name: 'SurveyAll',
    metaInfo () {
        return { title: this.$t('survey') }
    },
    computed: mapGetters({
        user: 'auth/user'
    }),
    data () {
        return {
            error: false,
            surveys: [],
            offset: 0,
            maxPerPage: 20,
            showPrev: false,
        }
    },
    created() {
        axios
            .get('/api/survey_user/'+this.user.id+'/0/10')
            .then(res => {
                this.showPrev = true;
                this.surveys = res.data;
            })
            .catch(err => {
                this.error = err;
            });
    },
    methods: {
        prevPage() {
            if(this.offset>1){
                this.offset = this.offset-this.maxPerPage-1;
                axios
                    .get('/api/survey_user/'+this.user.id+'/'+this.offset+'/'+this.maxPerPage)
                    .then(res => {
                        this.surveys = res.data;
                    })
                    .catch(err => {
                        this.error = err;
                    });
            }
        },
        nextPage() {
            this.offset = this.offset+this.maxPerPage+1;
            axios
                .get('/api/survey_user/'+this.user.id+'/'+this.offset+'/'+this.maxPerPage)
                .then(res => {
                    this.surveys = res.data;
                })
                .catch(err => {
                    this.error = err;
                });
        }
    }
}
</script>

<style scope>
.surveyList {
    margin-top: 10px;
    padding-top: 20px;
    padding-bottom: 30px;
}
.surveyList__container {
  max-height: 1000px;
  overflow-y: scroll;
  padding: 0 10px;
}
.surveyList__linkItem {
  padding: 10px 5px;
  border-bottom: 1px solid #ccc;
}
.surveyList__link {
  width: 100%;
  display: block;
  color: #000;
  font-size: 1.1em;
  font-weight: 400;
  text-decoration: none !important;
}
</style>
