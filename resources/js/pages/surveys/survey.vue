<template>
  <div class="spkfrm--surveyIndexContainer">
    <div v-if="Object.keys(survey_details).length != 0">
      <header class="surveyIndexContainer__surveyTabbedHeader">
        <span class="surveyTabbedHeader__goBackIcon">
          <a
            @click="goBackPage()">
            <v-icon class="goBackIcon--icon">fa-arrow-left</v-icon>
          </a>
        </span>
        <ul>
          <li
            :class="{'font-weight-bold':(survey_selected_tab == 'general')}">
            <a @click="tabChange('general')">General</a>
          </li>
          <li
            :class="{'font-weight-bold':(survey_selected_tab == 'share')}">
            <a @click="tabChange('share')">Share</a>
          </li>
          <li
            :class="{'font-weight-bold':(survey_selected_tab == 'responses')}">
            <a @click="tabChange('responses')">Responses</a>
          </li>
        </ul>
      </header>
      <div
        v-show="survey_selected_tab == 'general'"
        class="surveyIndexContainer__surveyInfoContainer">
        <div v-show="alertShow" class="alert alert-danger">{{ alert_message }}</div>
        <div class="surveyInfoContainer--header">
          <h4 class="surveyInfoContainer--title">{{ survey_details.survey_name }}</h4>
          <h6 class="surveyInfoContainer--date">Created {{ survey_details.created_at | moment("Do MMMM YYYY") }}</h6>
        </div>
        <span class="surveyIndexContainer--horizontalLine"></span>
        <div class="surveyInfoContainer--body">
          <h4 class="surveyInfoContainer--questionTitle">Questions</h4>
          <div v-for="(question,index) in questions" :key="index" class="surveyInfoContainer--questionsContainer">
            <p>{{ question.question_index }}. {{ question.question_content }}</p>
          </div>
        </div>
        <span class="surveyIndexContainer--horizontalLine"></span>
        <div class="surveyInfoContainer--footer">          
          <Button
            type="primary_WhiteBG"
            size="sm"
            :handleClick="deleteSurvey"
          >
            Remove Survey
          </Button>
        </div>
      </div>
      <div
        v-show="survey_selected_tab == 'share'"
        class="surveyIndexContainer__surveyShareContainer">
        <h4 class="surveyShareContainer--title">Share survey</h4>
        <h6 class="surveyShareContainer--para">You can send the link to your respondent to complete the survey</h6>
        <v-text-field
          class="surveyShareContainer--urlTxtBox"
          v-model="survey_link"
          solo></v-text-field>
        <div class="surveyShareContainer--btnGroup">
          <Button
            type="primary_WhiteBG"
            :handleClick="copyLinkToClipboard"
            size="sm">Copy Link</Button>
          <a
            :href="survey_link"
            target="_blank">
            <Button
              type="primary_WhiteBG"
              size="sm">Open in New Tab</Button>
          </a>
        </div>
        <span class="surveyIndexContainer--boxSpace"></span>
        <span class="surveyIndexContainer--horizontalLine"></span>
        <div class="surveyShareContainer__socialShareGrid">
          <a
            :href='`https://www.facebook.com/sharer/sharer.php?u=${survey_link}&quote=I created a survey on SpeakForms, Check it out!`'
            target="_blank">
            <v-icon class="socialShareGrid--icon">fab fa-facebook-square</v-icon>
          </a>
          <a
            :href='`https://twitter.com/intent/tweet?url=${survey_link}
            &text="I created a survey on SpeakForms, Check it out! "
            &hashtags=SpeakForms
            &related=twitterapi,twitter`'
            target="_blank">
            <v-icon class="socialShareGrid--icon">fab fa-twitter-square</v-icon>
          </a>
          <a
            :href='`whatsapp://send?text="I created a survey on SpeakForms, Check it out! ${survey_link}"`'
            target="_blank">
            <v-icon class="socialShareGrid--icon">fab fa-whatsapp-square</v-icon>
          </a>
          <a
            :href='`mailto:respondent@speakforms.com?subject="Please fill in this form on SpeakForms"&body=I created a survey on SpeakForms, Check it out! ${survey_link}"`'
            target="_blank">
            <v-icon class="socialShareGrid--icon">fa-envelope-square</v-icon>
          </a>
        </div>
      </div>
      <div
        v-show="survey_selected_tab == 'responses'"
        class="surveyIndexContainer__surveyResContainer">
        <div class="surveyResContainer--header">
          <h4 class="surveyResContainer--title">Responses</h4>
        </div>
        <div class="surveyResContainer--resStatsGrid">
          <div class="resStatsGrid__resStatsCol">
            <h5 class="resStatsCol--value">23</h5>
            <h6 class="resStatsCol--key">Responses</h6>
          </div>
          <div class="resStatsGrid__resStatsCol">
            <h5 class="resStatsCol--value">1:42</h5>
            <h6 class="resStatsCol--key">Avg. Time</h6>
          </div>
        </div>
        <span class="surveyIndexContainer--horizontalLine"></span>
        <v-simple-table>
          <template
            class="surveyResContainer__statsDataTable"
            v-slot:default>
            <thead>
              <tr>
                <th scope="col">Email</th>
                <th scope="col">Date</th>
                <th scope="col">Time</th>
                <th scope="col">&nbsp;</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>jvk@mail.com</td>
                <td>02-05-20</td>
                <td>11:22</td>
                <td>
                  <div class="statsDataTable--actions">
                    <a href="#">Review</a>
                    <v-icon>far fa-times-circle</v-icon>
                  </div>
                </td>
              </tr>
            </tbody>
          </template>
        </v-simple-table>
        <span class="surveyIndexContainer--horizontalLine"></span>
        <div class="surveyResContainer--dataGridFooter">
          <div class="dataGridFooter--paginationStatus">
            <p>Showing 10 of 20</p>
          </div>
          <div class="dataGridFooter--paginationPerPage">
            <v-select
              class="dataGridFooter--paginationRangeSelect"
              value="10 per Page"
              :items="resultsPerPage"
              solo></v-select>
            <v-pagination
              class="dataGridFooter--paginationNav"
              v-model="selectedResPage"
              prev-icon="fas fa-chevron-left"
              next-icon="fas fa-chevron-right"
              :length="2"></v-pagination>
          </div>
        </div>
      </div>
    </div>
    <div v-else-if="loading == true">
      <span>&nbsp;</span>
    </div>
    <div v-else>
      <header class="surveyIndexContainer__surveyTabbedHeader">
        <span class="surveyTabbedHeader__goBackIcon">
          <v-icon class="goBackIcon--icon">fa-arrow-left</v-icon>
        </span>
        <span>&nbsp;</span>
      </header>
      <div class="surveyIndexContainer__surveyNotAvailable">        
        <Label type="danger" size="normal">404 - Survey Not Available</Label>
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
import axios from "axios";
import _ from "lodash";
import Button from '../../components/Button';
import Label from '../../components/Label';

export default {
  name: `SurveyIndex`,
  components: {
    Button,
    Label,
  },
  data() {
    return {
      loading: true,
      error: false,
      copiedShowSnackbar: false,
      survey_selected_tab: 'general',
      survey_id: null,
      survey_details: [],
      survey_link: null,
      questions: [],
      notifications: [],
      resultsPerPage: [
        "10 per Page",
        "20 per Page",
        "50 per Page",
      ],
      selectedResPage: 1,
      showTab: 0,
      alertShow: false,
      alert_message: ""
    };
  },
  metaInfo() {
    return { title: `${this.survey_details.survey_name} - Overview` }
  },
  created() {
    this.survey_id = this.$route.params.surveyid;
    axios
      .get("/api/survey/" + this.survey_id)
      .then(res => {
        this.loading = false;
        this.survey_details = res.data;
        this.survey_link = process.env.MIX_APP_URL + '/surveys/wizard/' + res.data.survey_id;
        axios
          .get("/api/notifications/" + this.survey_details.survey_id)
          .then(res => {
            this.notifications = res.data;
          })
          .catch(error => {
            this.error = true;
          });
      })
      .catch(error => {
        this.error = true;
      });
    axios
      .get("/api/question/" + this.survey_id)
      .then(res => {
        var index, len;
        for (index = 0, len = res.data.length; index < len; index++) {
          this.questions.push({
            question_index: res.data[index].question_no,
            question_content: res.data[index].question_content
          });
        }
        this.questions = _.orderBy(this.questions, "question_index");
      })
      .catch(error => {
        this.error = true;
      });
  },
  methods: {
    goBackPage: function() {
      window.history.go(-1);
    },
    tabChange: function(selectedTab) {
      this.survey_selected_tab = selectedTab;
    },
    copyLinkToClipboard: function() {
      const _that = this;

      navigator.clipboard.writeText(this.survey_link).then(function() {
        _that.copiedShowSnackbar = true;  // Show snackbar
      }, function(err) { return; }); // Failed to copy to clipboard
    },
    deleteSurvey: function() {
      axios
        .delete("/api/survey/" + this.survey_id)
        .then(res => {
          this.alertShow = true;
          this.alert_message = "Survey was deleted";
        })
        .catch(error => {
          this.alertShow = true;
          this.alert_message = "Failed to delete the survey";
        });
    }
  }
};
</script>

<style lang="scss" scope>
.spkfrm--surveyIndexContainer {
  width: 100%;
  height: 100%;
}
.goBackIcon--icon {
  cursor: pointer;
}
.surveyIndexContainer__surveyTabbedHeader {
  display: grid;
  grid-template-columns: repeat(5, 1fr);
  box-shadow: inset 0px -1px 0px 0px #CCC;
  padding: 10px 20px;
  align-items: center;

  ul {
    display: flex;
    flex-direction: row;
    justify-content: center;
    grid-column: 3/4;
    align-items: center;
    margin-top: 10px;

    li {
      list-style-type: none;
      margin: 0 15px;
      cursor: pointer;
    }
  }
}
.surveyIndexContainer__surveyInfoContainer {
  width: 640px;
  margin: 0 auto;
  padding-top: 40px;

  .surveyInfoContainer--header {
    .surveyInfoContainer--title,
    .surveyInfoContainer--date {      
      text-align: left;
    }
    h4 {
      font-weight: 600;
      font-size: 22px;
      line-height: 24px;
      color: #000;
    }
    h6 {
      font-size: 16px;
      color: #999;
      margin-top: 3px;
    }
  }

  .surveyInfoContainer--body {
    font-family: 'Nunito', sans-serif;

    .surveyInfoContainer--questionTitle {
      font-weight: 600;
      font-size: 22px;
      line-height: 24px;
      color: #000;
    }

    .surveyInfoContainer--questionsContainer {
      p {
        font-size: 16px;
        line-height: 24px;
        color: #000;
      }
    }
  }  
}
.surveyIndexContainer__surveyNotAvailable {
  display: flex;
  justify-content: center;
  margin-top: 20px;
}
.surveyIndexContainer__surveyShareContainer {
  width: 640px;
  margin: 0 auto;
  padding-top: 40px;

  .surveyShareContainer--title,
  .surveyShareContainer--date {      
    text-align: left;
  }
  h4 {
    font-weight: 600;
    font-size: 22px;
    line-height: 24px;
    color: #000;
  }
  h6 {
    font-size: 16px;
    color: #999;
    margin-top: 3px;
  }

  .surveyShareContainer--urlTxtBox {
    margin-top: 20px;
  }

  .sureyShareContainer--btnGroup {
    display: flex;
    flex-direction: row;
  }

  .surveyShareContainer__socialShareGrid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    width: 60%;
    margin: 0 auto;

    .socialShareGrid--icon {
      font-size: 2.5em;
      margin: 0 auto;
      cursor: pointer;
    }
  }
}
.surveyIndexContainer__surveyResContainer {
  width: 640px;
  margin: 0 auto;
  padding-top: 40px;

  .surveyResContainer--header {
    .surveyResContainer--title {
      font-size: 22px;
      font-weight: 600;
      line-height: 24px;
      color: #000;
    }
  }

  .surveyResContainer--resStatsGrid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);

    .resStatsGrid__resStatsCol {
      grid-column: inherit;

      .resStatsCol--value {
        width: 100%;
        font-size: 28px;
        font-weight: 400;
        line-height: 32px;
        color: #000;
      }

      .resStatsCol--key {
        width: 100%;
        font-size: 16px;
        font-weight: 400;
        line-height: 24px;
        color: #000;
      }
    }
  }

  .surveyResContainer__statsDataTable {
    width: 100%;

    tbody {
      tr {
        td {
          margin: 0 2px;

          .statsDataTable--actions {
            a {
              font-size: 16px;
              line-height: 24px;
              text-decoration: none;
              margin-right: 2px;
              color: #0055FF;
            }
          }
        }
      }
    }
  }

  .surveyResContainer--dataGridFooter {
    display: flex;
    justify-content: space-between;

    .dataGridFooter--paginationStatus {
      p {
        font-size: 16px;
        margin-top: 15px;
      }
    }

    .dataGridFooter--paginationPerPage {
      display: flex;
      flex-direction: row;

      .dataGridFooter--paginationRangeSelect {
        width: 150px;
      }

      .dataGridFooter--paginationNav {
        margin-top: 2px;
        margin-left: 5px;

        i {
          font-size: 12px;
        }
      }
    }
  }
}

/** Utilities */
.surveyIndexContainer--boxSpace {
  display: inline-block;
  height: 5px;
}
.surveyIndexContainer--horizontalLine {
  display: inline-block;
  width: 100%;
  height: 1px;
  background-color: #CCCCCC;
  margin: 15px 0 25px;
}
</style>