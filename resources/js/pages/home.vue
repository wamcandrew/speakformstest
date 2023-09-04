<template>
  <div class="spkfrm__homePageWrapper">
      <div class="spkfrm--surveysList">
        <Button class="surveysList--createBtn" type="primary_WhiteBG" size="lg" :handleClick="createSurvey">
          <div class="spkfrm--surveysList__Btn">
            <span class="spkfrm--surveyList__Btn-txt">
              {{ $t('create_survey') }}
            </span>
            <span>&nbsp;</span>
            <span class="spkfrm--surveyList__Btn-plus">
              +
            </span>
          </div>
        </Button>
        <div
          class="w-full spkfrm--surveysList__Container">
          <Card
            :title="survey.survey_name"
            :link='`/surveys/info/${survey.survey_id}`'
            v-for="survey in surveys"
            :key="survey.survey_id">
            <div class="spkfrm--surveysLists__resContainer">
              <div class="spkfrm--resContainer--info">
                <span class="spkfrm--resContainer--info_txt">
                  Responses 98
                </span>
                <Label type="primary" size="small">36 New</Label>
              </div>
              <div
                class="spkfrm--resContainer--moreActions">
                <a @click="openAction(survey.survey_id)">
                  ...
                </a>                
                <div
                  class="spkfrm--moreActions__popUp"
                  tabindex="1"
                  v-show="showMoreActionId == survey.survey_id">
                  <div
                    class="spkfrm--moreActions__popUpBlocker"
                    @click="hideMoreAction()"></div>
                  <ul>
                    <li>
                      <router-link :to='`/surveys/info/${survey.survey_id}`'> Overview</router-link></li>
                    <li>
                      <a
                        @click="copyLinkToClipboard(survey.survey_id)">
                        Copy Public Link
                      </a>
                    </li>
                    <li>
                      <router-link :to='`/surveys/wizard/${survey.survey_id}`'>Visit Survey</router-link>
                    </li>
                  </ul>
                </div>
              </div>
            </div>
          </Card>
          <!-- <Card title="Customer Satisfaction Survey Apr 2020">
            <div class="spkfrm--surveysLists__resContainer">
              <div class="spkfrm--resContainer--info">
                <span class="spkfrm--resContainer--info_txt">
                  Responses 98
                </span>
                <Label type="primary" size="small">36 New</Label>
              </div>
              <div
                class="spkfrm--resContainer--moreActions">
                <a @click="openAction(2)">
                  ...
                </a>
                <div
                  class="spkfrm--moreActions__popUp"
                  tabindex="1"
                  v-show="showMoreActionId == 2">
                  <div
                    class="spkfrm--moreActions__popUpBlocker"
                    @click="hideMoreAction()"></div>
                  <ul>
                    <li>Survey Overview</li>
                    <li>Copy Public Link</li>
                    <li>Visit Survey</li>
                  </ul>
                </div>
              </div>
            </div>
          </Card> -->
        </div>
      </div>
      <div class="spkfrm--activitiesContiner" v-show="showActivities">
          <div class="spkfrm--notificationsContainer">
              <h4 class="notificationsContainer__title">Activities</h4>
              <div v-if="notifications.length != 0">
                <div v-for="(notification, index) in notifications" :key="index" class="notificationsContainer__listItem custom_scrollbar">
                  <Label class="notificationsContainer__dateLabel" type="gray" size="normal">Yesterday</Label>
                  <router-link :to="{name: 'survey.complete', params: {completeid: notification.survey_completion_id}}" class="notificationsContainer__link">
                    <Notification>
                      <Label class="notificationsContainer__labelNo" type="info" size="small">1 New</Label>
                      <span class="notificationsContainer__responseText">response in</span>
                      <span class="notificationsContainer__surveyName">{{ notification.survey_name }}</span>
                      <span class="notificationsContainer__surveyTime">10:47AM</span>
                    </Notification>
                  </router-link>
                </div>
                <div class="notificationsContainer__loadMore">
                  <Button type="tertiary_GrayXtraLightBG" size="nl" :handleClick="prevPage">
                    Load Older Activities
                  </Button>
                </div>
              </div>
              <div style="padding-top: 30px; margin-bottom: 40px;" v-else>
                <h4 class="loadingContext">There are no notifications to show</h4>
              </div>
          </div>
      </div>
      <v-snackbar
        color="blue darken-4"
        v-model="copiedShowSnackbar">
        Link was copied to your clipboard!
      </v-snackbar>
  </div>
</template>

<script>
import Vue from 'vue'
import axios from 'axios'
import { mapGetters } from 'vuex'
import clickOutside from '../directives'
import Notification from '../components/Notification'
import Button from '../components/Button'
import Card from '../components/Card'
import Label from '../components/Label'

export default {
  middleware: 'auth',
  components: {
    Notification,
    Button,
    Card,
    Label,
  },
  data (){
    return {
      error: false,
      notifications: [
        {
          survey_completion_id: 1,
          survey_name: 'Test1',
          respondent_email: 'jvk1@mail.com'
        },
        {
          survey_completion_id: 2,
          survey_name: 'Test 2',
          respondent_email: 'jvk2@mail.com'
        }
      ],
      id: 2,
      showMoreActionId: null,
      copiedShowSnackbar: false,
      surveys: [],
      offset: 0,
      maxPerPage: 10,
      showPrev: false,
    }
  },
  computed: mapGetters({
      user: 'auth/user',
      showActivities: 'setting/showActivities'
  }),
  created() {
    var that = this;
    axios
        .get('/api/notifications/'+this.user.id+'/'+this.offset+'/'+this.maxPerPage)
        .then(res => {
          // this.notifications = res.data;
          this.showPrev = true;
        })
        .catch(err => {
          this.error = err;
        });
    axios
        .get('/api/survey_user/'+this.user.id+'/0/10')
        .then(res => {
          this.surveys = res.data;
        })
        .catch(err => {
          this.error = err;
        });
    Vue.set(this.$store.state.setting, 'bgColor', '#FFF');
  },
  methods: {
    openAction: function(id) {
      this.showMoreActionId = id;
    },
    hideMoreAction: function() {
      this.showMoreActionId = null;
    },
    copyLinkToClipboard: function(survey_link) {
      const _that = this;

      navigator.clipboard.writeText(process.env.MIX_APP_URL + '/surveys/wizard/' + survey_link).then(function() {
        _that.copiedShowSnackbar = true;    // Show snackbar
        _that.showMoreActionId = null;    // Hide More Actions
      }, function(err) { return; });    // Failed to copy to clipboard
    },
    createSurvey: function() {
      this.$router.push({name: 'survey.create'});
    },
    prevPage: function() {
      var that = this;
      if(this.offset>1){
        this.offset = this.offset-this.maxPerPage;
        axios
          .get('/api/notifications/'+this.user.id+'/'+this.offset+'/'+this.maxPerPage)
          .then(res => {
            this.notifications = res.data;            
          })
          .catch(err => {
            this.error = err;
          });
      }
    },
    nextPage: function() {
      var that = this;
      this.offset = this.offset+this.maxPerPage+1;
      axios
        .get('/api/notifications/'+this.user.id+'/'+this.offset+'/'+this.maxPerPage)
        .then(res => {
          if(res.data != null){
            this.notifications = res.data;
          }
        })
        .catch(err => {
          this.error = err;
        });
    }
  },
  metaInfo () {
    return { title: this.$t('home') }
  }
}
</script>

<style lang="scss" scope>
.loadingContext {
  font-size: 1.2em;
  font-weight: 300;
}
.notificationsContainer__title {
  font-family: 'Nunito', sans-serif;
  font-size: 22px;
  font-weight: 600;
  line-height: 24px;
  color: #525F7A;
  margin-top: 30px;
}
.notificationsContainer__listItem {
  scrollbar-width: none;
}
.notificationsContainer__listItem,
.spkfrm--surveysContainer {
  max-height: 400px;
  overflow-y: scroll;
  padding: 0 10px;
}
.notificationsContainer__listItem,
.surveysContainer__listItem {
  padding: 10px 5px;
}
.notificationsContainer__link,
.surveysContainer__link {
  width: 100%;
  display: grid;
  color: #000;
  font-size: 1.1em;
  font-weight: 400;
  text-decoration: none !important;
  
  .spkfrm__notificationContainer {
    display: flex;
    flex-direction: row;
    align-items: baseline;
  }
}
.notificationsContainer__labelNo {
  width: fit-content;
}
.notificationsContainer__responseText,
.notificationsContainer__surveyName {
  font-family: 'Nunito', sans-serif;
  font-size: 16px;
  margin: 0 3px;
  color: #525F7A;
}
.notificationsContainer__surveyTime {
  font-family: 'Nunito', sans-serif;
  font-size: 12px;
  color: #9FACC6;
  margin-left: auto;
}
.cardTitle {
  float: left;
  margin: 2px 0;
}
.cardHeader__btn {
  float: right;
  border: 1px dashed #3490dc;
  padding: 5px 10px;
  border-radius: 8px;
  font-size: 1em;
  text-decoration: none !important;
  margin-bottom: 12px;
}
.cardHeader__btn i {
  font-size: 1.2em;
  color: #3490dc !important;
}
.spkfrm__homePageWrapper {
  display: flex;
  flex-direction: row;
  justify-content: space-between;
}
.spkfrm--surveysList {
  padding: 2rem 2.5rem;
}
.spkfrm--activitiesContiner {
  width: 30vw;
  background-color: #EDF0F7;
  min-height: 100vh;
  padding: 0 1rem;
}
.notificationsContainer__dateLabel {
  display: flex;
  justify-content: center;
  margin: 5px 0;
}
.notificationsContainer__loadMore {
  display: flex;
  justify-content: center;
  padding-top: 30px;
  margin-bottom: 20px;
}
.spkfrm--surveysList__Btn {
  width: 100%;
  display: flex;
  justify-content: space-between;
}
.spkfrm--surveysList__Btn-txt {
  font-family: 'Nunito', sans-serif;
  font-size: 18px
}
.spkfrm--surveysList__Btn-plus {
  font-size: 18px;
}
.spkfrm--surveysList__Container {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 15px;
  margin-top: 2rem;
}
.spkfrm--surveysLists__resContainer {
  display: flex;
  flex-direction: row;
  justify-content: space-between;
}
.spkfrm--resContainer--info {
  display: flex;
  flex-direction: row;
  justify-content: space-around;
}
.spkfrm--resContainer--info_txt {
  font-family: 'Nunito', sans-serif;
  font-size: 16px;
  line-height: 24px;
  color: #525F7A;
  padding-right: 5px;
}
.spkfrm--resContainer--moreActions {
  margin-left: 20px;

  a {
    font-size: 18px;
    display: inline-block;
    height: 10px;
    line-height: 10px;
    cursor: pointer;
  }
}
.spkfrm--moreActions__popUpBlocker {
  position: fixed;
  top: 0;
  left: 0;
  bottom: 0;
  right: 0;
  content: ' ';
}
.spkfrm--moreActions__popUp {
  position: absolute;

  ul {
    position: fixed;
    box-shadow: 0px 3px 6px -2px rgba(0, 0, 0, 0.1), 0 4px 24px -2px rgba(0, 0, 0, 0.2);
    padding: 10px 15px 0;
    background-color: #fff;
    border-radius: 5px;
    margin-top: -30px;
    margin-left: -20px;
    padding-bottom: 5px;
    list-style-type: none;

    li {
      margin: 2px 0;

      a {
        font-size: 14px;
        line-height: 16px;
        text-decoration: none;
        color: #000;

        &:hover {
          color: #3d79f5 !important;
        }
      }
    }
  }
}
@media screen and (max-width: 1024px) {
  .spkfrm--activitiesContiner {
    width: 40vw;
    position: absolute;
    top: 60px;
    right: 0;
  }

  .spkfrm--surveysList__Container {
    grid-template-columns: repeat(2, 1fr);
  }
}
@media screen and (max-width: 600px) {
  .spkfrm--surveysList {
    padding: 20px 15px 10px 10px;
  }

  .spkfrm--surveysList__Container {
    grid-template-columns: repeat(1, 1fr);
  }

  .spkfrm__cardContainer {
    width: 100%;
    min-width: inherit;
  }

  .surveysList--createBtn {
    width: 100% !important;
  }
  
  .spkfrm--activitiesContiner {
    width: 100%;
    right: inherit;
  }
}
</style>