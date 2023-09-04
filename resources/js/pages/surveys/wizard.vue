<template>
    <div class="spkfrm--surveyWizardContainer">
        <header class="surveyWizardContainer__surveyTabbedHeader">
            <span class="surveyTabbedHeader__goBackIcon">
                <a
                    @click="goBackPage()">
                    <v-icon class="goBackIcon--icon">fa-arrow-left</v-icon>
                </a>
            </span>
            <div class="surveyWizardContainer--headerTitle">
              <h1>{{ survey_details.survey_name }}</h1>
            </div>
        </header>
        
        <div class="surveyWizardContainer__wizardStepper">
            <div
              v-if="slideSteps === 'intro'"
              class="wizardStepper--introContainer">
                <h4 class="introContainer--title">{{ survey_details.survey_name }}</h4>
                <p class="introContainer--para">We appreciate you taking the time to fill out this survey, it will take a few minutes to complete.</p>
                <Button size="lg" :handleClick="startSurvey">Start Survey</Button>
            </div>
            <div
              v-else-if="slideSteps === 'questions'"
              class="wizardStepper__questionsContainer">
              <div v-if="startQuestions === false" class="questionsContainer--emailInput">
                  <h4 class="questionsContainer--enterMail">Enter your email</h4>
                  <input
                    type="text"
                    class="questionsContainer--txtBox surveyTextInput"
                    v-model="eMail"
                    />
                  <Button size="nl" :handleClick="startWizard" :disabled="!isEmailValid()">Proceed</Button>
              </div>
              <div
                v-else
                class="questionsContainer__surveyWizard">
                  <div v-for="(question, index) in questions" :key="index">
                      <div v-show="selectedQuestion === index+1">
                        <h5>{{ question.question_index }}. {{ question.question_content }}</h5>
                        <p>You can fill up the text or/and record voice</p>
                        <textarea
                            class="surveyWizard--txtArea surveyTextInput"
                            :background-color="startRec ? 'grey lighten-2': ''"
                            :disabled="startRec"
                            v-model="questions[index].answer_content">
                        </textarea>                        
                        <div
                          v-if="question.answer_url != null">
                          <audio controls :src="question.answer_url" controlsList="nodownload" style="width: 100%; margin: 10px 0; outline: none;" />
                        </div>
                        <Button
                          type="primary_PrimaryBG"
                          class="surveyWizard--audioBtnWrapper"
                          size="nl"
                          v-show="!startRec"
                          @click.native="recording('start', index)">
                          <div class="surveyWizard--audioBtn" v-if='question.audio_base64 != null && question.audio_base64 != ""'>
                            <v-icon>fa-redo</v-icon>
                            <p>Re-Do</p>
                          </div>
                          <div class="surveyWizard--audioBtn" v-else>
                            <v-icon>fa-microphone</v-icon>
                            <p>Start Recording Audio</p>
                          </div>
                        </Button>
                        <p v-show="error">{{ error }}</p>
                        <div
                          class="surveyWizard--deleteAudio"
                          v-show='question.audio_base64 != null && question.audio_base64 != ""'>
                          <Button
                            type="primary_PrimaryBG"
                            size="nl"
                            v-show="!startRec"
                            @click.native="deleteRecording(index)">
                            <div class="surveyWizard--audioBtn">
                                <v-icon>fa-trash</v-icon>
                                <p>Delete recording</p>
                            </div>
                          </Button>
                        </div>
                        <div
                          class="surveyWizard__questionAudioRecContainer"
                          v-show="startRec">
                            <Button
                              class="questionAudioRecContainer--rec"
                              type="primary_PrimaryBG"
                              size="nl">
                              <v-icon>far fa-dot-circle</v-icon>
                              Recording...
                            </Button>
                            <Button
                              class="questionAudioRecContainer--finish"
                              type="primary_PrimaryBG"
                              size="nl"
                              @click.native="recording('stop', index)">
                              <v-icon>far fa-stop-circle</v-icon>
                              Finish
                            </Button>
                            <Button
                              class="questionAudioRecContainer--pause"
                              type="primary_PrimaryBG"
                              v-show="showPause"
                              @click.native="recording('pause', index)"
                              size="nl">
                              <v-icon>far fa-pause-circle</v-icon>
                              Pause
                            </Button>
                            <Button
                              class="questionAudioRecContainer--resume"
                              type="primary_PrimaryBG"
                              v-show="showResume"
                              @click.native="recording('pause', index)"
                              size="nl">
                              <v-icon>far fa-play-circle</v-icon>
                              Resume
                            </Button>
                        </div>
                        <div class="surveyWizard--processBtn">
                            <Button
                              @click.native="questionNavigation('higher', selectedQuestion)">
                            Proceed
                            </Button>
                        </div>
                      </div>
                  </div>
              </div>
            </div>
            <div
              v-else
              class="wizardStepper--reviewContainer">
              <h4 class="reviewContainer--generalTitle">General</h4>
              <table class="reviewContainer_-statsTable">
                  <tr>
                      <td class="statsTable--leftCol">
                          <h5 class="reviewContainer--keyName">Email</h5>
                      </td>
                      <td>
                          <p class="reviewContainer--keyValue">{{ eMail }}</p>
                      </td>
                  </tr>
                  <tr>
                      <td class="statsTable--leftCol">
                          <h5 class="reviewContainer--keyName">Survey completed</h5>
                      </td>
                      <td>
                          <p class="reviewContainer--keyValue">100%</p>
                      </td>
                  </tr>
                  <tr>
                      <td class="statsTable--leftCol">
                          <h5 class="reviewContainer--keyName">Survey time</h5>
                      </td>
                      <td>
                          <p class="reviewContainer--keyValue">1:47</p>
                      </td>
                  </tr>
                  <tr>
                      <td class="statsTable--leftCol">
                          <h5 class="reviewContainer--keyName">Survey date</h5>
                      </td>
                      <td>
                          <p class="reviewContainer--keyValue">02-05-2020</p>
                      </td>
                  </tr>
              </table>
              <span class="surveyWizardContainer--horizontalLine"></span>
              <h4 class="reviewContainer--questionsTitle">Questions</h4>
              <ul>
                  <li v-for="(question, index) in questions" :key="index">
                      <p class="reviewContainer--questionContent">{{ question.question_index }}. {{ question.question_content }}</p>
                      <div class="reviewContainer__questionAnswer">
                          <div class="questionAnswer__providedAnswer">
                              <div class="providedAnswer--audio" v-show="question.answer_url">
                                  <audio controls :src="question.answer_url" controlsList="nodownload" style="width: 100%; margin: 10px 0; outline: none;" />
                              </div>
                              <div class="providedAnswer--txt" v-show="question.answer_content">
                                  <p>{{ question.answer_content }}</p>
                              </div>
                          </div>
                          <div class="questionAnswer--editAnswer">
                              <Button
                                @click.native="editQuestion(index)">
                              Edit
                              </Button>
                          </div>
                      </div>
                  </li>
              </ul>              
              <span class="surveyWizardContainer--horizontalLine"></span>
              <Button 
                @click.native="submitForm()">
                Submit the Survey
              </Button>
            </div>
        </div>
        <!-- <tabbed-stepper :steps=steps :currentTabIndex=currentTabIndex>
            <template v-slot:body>
                <div v-if="currentTabIndex === 1">
                    <h1>Basic Information</h1>
                    <b-form>
                        <b-form-group
                            id="email-input-group"
                            label="Email Address"
                            label-for="email-address"
                            description="Input your email address.">
                            <b-form-input
                                id="email-address"
                                class="tabbedStepper_input"
                                :class=[isEmailValid()]
                                v-model="eMail"
                                type="text"
                                required></b-form-input>                                            
                        </b-form-group>
                    </b-form>
                </div>
                <div v-if="currentTabIndex === 2">
                    <h1>Provide Answers</h1>
                    <div v-for="(question, index) in questions" :key="index">
                        <div v-show="selectedQuestion === index+1">
                            <b-container>
                            <b-row>
                                <b-col class="question_no" cols="12">
                                    <p>0{{ index+1 }}. {{ question.question_content }}</p>
                                </b-col>
                            </b-row>
                            <b-row>
                                <b-col class="tab-wrapper" cols="11" offset="1">
                                    <div class="tab-header">
                                        <template v-if="answerType">
                                            <ul class="md-tabs row" style="margin: 0;">
                                                <li v-bind:class="{'tabActiveElem':(answerType[index].type==0)}" @click="changeTab(question.question_index, 0)" class="col-md-4 col-sm-12"><a><v-icon>fa-pencil-alt</v-icon>Text</a></li>
                                                <li v-bind:class="{'tabActiveElem':(answerType[index].type==1)}" @click="changeTab(question.question_index, 1)" class="col-md-4 col-sm-12"><a><v-icon>fa-microphone</v-icon>Audio</a></li>
                                            </ul>
                                        </template>
                                    </div>
                                    <div class="tab-container">
                                        <template v-if="answerType">
                                            <div class="tab-containerText" :class="{'showDiv' : answerType[index].type==0, 'hideDiv' : answerType[index].type==1}">
                                                <div class="answerListItem__textArea">
                                                    <textarea
                                                        :id="'textArea-'+index"
                                                        class="autoExpand answerListItem__textAreaInput"
                                                        rows="1"
                                                        data-min-rows="1"
                                                        :name="'textField__text'+index"
                                                        placeholder="Input your answer here"
                                                        v-model="current_text[index].text"
                                                        @change="changeAnswerText(index)"></textarea>
                                                </div>
                                            </div>
                                            <div class="tab-containerAudio" :class="{'showDiv' : answerType[index].type==1, 'hideDiv' : answerType[index].type==0}">                                                
                                                <b-container>
                                                    <b-row>
                                                        <b-col md="12" sm="12" cols="12">
                                                            <div class="answerListItem__audioRecorder">
                                                                <div class="recording-controls row">
                                                                    <v-btn
                                                                        class="recording-controlsbtn col-xs-12"
                                                                        :class="{
                                                                            'recording-controlsbtn__grey' : question.audio_base64 == null,
                                                                            'recording-controlsbtn__blue' : question.audio_base64 != null
                                                                        }"
                                                                        v-show="showStart"
                                                                        @click="recording('start', index)">
                                                                        <div v-if='question.audio_base64 != null && question.audio_base64 != ""'>
                                                                            <v-icon>fa-redo</v-icon>
                                                                            <p>Re-Do</p>
                                                                        </div>
                                                                        <div v-else>
                                                                            <v-icon>fa-play</v-icon>
                                                                            <p>Start Recording</p>
                                                                        </div>
                                                                    </v-btn>
                                                                    <v-btn
                                                                        class="recording-controlsbtn recording-controlsbtn__black col-xs-12"
                                                                        v-show="showPause"
                                                                        @click="recording('pause', index)">
                                                                        <v-icon>fa-pause</v-icon>
                                                                        <p>Pause Recording</p></v-btn>
                                                                    <v-btn
                                                                        class="recording-controlsbtn recording-controlsbtn__grey col-xs-12"
                                                                        v-show="showResume"
                                                                        @click="recording('pause', index)">
                                                                        <v-icon>fa-play</v-icon>
                                                                        <p>Resume Recording</p></v-btn>                                                        
                                                                    <v-btn
                                                                        class="recording-controlsbtn recording-controlsbtn__red col-xs-12"
                                                                        v-show="showStop"
                                                                        @click="recording('stop', index)">
                                                                        <v-icon>fa-stop</v-icon>
                                                                        <p>Stop Recording</p></v-btn>
                                                                </div>
                                                            </div>
                                                        </b-col>
                                                        <b-col md="12" sm="12" cols="12">
                                                            <div class="answerListItem__audioPlayer">   
                                                                <div v-if="question.is_audio == true">
                                                                    <audio controls :src="question.answer_content" controlsList="nodownload" style="width: 100%; margin: 10px 0;" />
                                                                </div>
                                                            </div>
                                                        </b-col>
                                                    </b-row>
                                                </b-container>
                                            </div>
                                        </template>
                                    </div>
                                </b-col>
                            </b-row>
                            </b-container>
                        </div>
                    </div>
                    <div class="survey_questionNavigation">
                        <a @click="questionNavigation('lower', selectedQuestion)">
                            <v-icon>fa-chevron-left</v-icon>
                        </a>
                        <p class="survey_questionIndex">
                            <span class="survey_questionIndex--selected">
                                <span v-if="selectedQuestion <= questions.length">{{ selectedQuestion }}</span>
                            </span> 
                            <span class="survey_questionIndex--length">/ {{ questions.length }}</span>
                        </p>
                        <a @click="questionNavigation('higher', selectedQuestion)">
                            <v-icon>fa-chevron-right</v-icon>
                        </a>
                    </div>
                </div>
                <div v-if="currentTabIndex === 3">
                    <h1>Review</h1>
                    <div class="surveyWizard__review">
                        <b-container>
                            <b-row v-for="question in questions" :key="question.question_index" style="margin-bottom: 20px;">
                                <b-col cols="12">
                                    <div class="surveyWizard__review--question">
                                        <p>{{ question.question_index }} - {{ question.question_content }}</p>
                                    </div>
                                </b-col>
                                <b-col cols="12">
                                    <div class="surveyWizard__review--answer">
                                        <b-card class="surveyWizard__review--component surveyWizard__review--answerAudio" v-if="question.is_audio">
                                            <audio controls="controls" controlsList="nodownload" style="width: 100%; margin: 10px 0;">
                                                <source :src="question.answer_content" type="audio/wav">
                                            </audio>
                                        </b-card>
                                        <b-card class="surveyWizard__review--component surveyWizard__review--answerText" v-else>
                                            <p>{{ question.answer_content }}</p>
                                        </b-card>
                                    </div>
                                </b-col>
                            </b-row>
                        </b-container>
                    </div>
                </div>
            </template>
            <template v-slot:footer_buttons>
                <div class="tabbedStepper__btnContainer">
                    <a href="#" class="spkFormBtn float_left" v-on:click="currentTabIndex -= 1" v-if="currentTabIndex <= steps.length && currentTabIndex > 1">Prev</a>
                    <a href="#" class="spkFormBtn float_right" v-on:click="currentTabIndex += 1" v-if="currentTabIndex < steps.length" :class="{'disabled-link' : steps[currentTabIndex-1].incomplete==true}">Next</a>
                    <a href="#" class="spkFormBtn float_right" v-on:click="submitForm()" v-if="currentTabIndex === steps.length" :clas="{'disabled-link' : steps[currentTabIndex-1].incomplete==true}">Submit</a>
                </div>
            </template>
        </tabbed-stepper> -->
        <!-- Survey Created Modal -->
        <v-dialog
            v-model="modalShow"
            class="surveyWizard__modal surveyModal"
            >
            <div class="surveyWizard__modalWrapper text-xs-center">
                <div 
                    class="surveyWizard__modalClose"
                    @click="modalShow=false">
                    <v-icon>fa-times</v-icon>
                </div>
                <h3 class="headline">Survey Completed</h3>

                <div class="modal__modalText"
                    v-html="modalText"
                    >
                </div>
                <div class="submitProgress_container">                    
                    <p class="submitProgress__text" v-if="submitProgress < 100">{{ submitProgress }}%</p>
                    <div class="checkmark"></div>
                    <div
                        class="circle-loader"                        
                        :class="{'load-complete' : submitProgress == 100}"
                        v-show="true">
                        <div
                            class="checkmark draw"
                            :class="{'checkmark-show' : submitProgress == 100}">
                        </div>
                    </div>
                </div>

            </div>
        </v-dialog>
    </div>
</template>

<script>
import Vue from 'vue'
import axios from 'axios'
import _ from 'lodash'
import { CardPlugin, LayoutPlugin, FormPlugin, FormInputPlugin, FormGroupPlugin } from 'bootstrap-vue'
import TabbedStepper from '../../components/TabbedStepper/main.vue'
import Button from '../../components/Button'

Vue.use(CardPlugin);
Vue.use(LayoutPlugin);
Vue.use(FormPlugin);
Vue.use(FormInputPlugin);
Vue.use(FormGroupPlugin);

export default {
    name: 'SurveyWizard',
    components: {
        TabbedStepper,
        Button
    },
    data () {
        return {
            error: null,
            valid: false,
            survey_id: null,
            currentTabIndex: 1,
            slideSteps: 'intro',
            selectedQuestion: 1,
            startQuestions: false,
            eMail: '',
            emailError: '',
            emailReg: /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,24}))$/,
            questions: [],
            answers: [],
            current_text: [],
            survey_details: { survey_name: '...' },
            answerType: [],
            errored: false,
            isLoading: false,
            modalShow: false,
            modalText: null,
            submitProgress: 0,
            completedList: [],
            generatedURL: null,
            response: null,
            gumStream: null,
            startRec: false,
            rec: null,
            input: null,
            isRecording: false,
            showStart: true,
            showResume: false,
            showPause: false,
            showStop: false,
        }
    },
    metaInfo () {
        return { title: `${this.survey_details.survey_name} - Survey Wizard` }
    },
    created () {
        this.survey_id = this.$route.params.surveyid;
        // Retrieve survey information and store
        axios
            .get('/api/survey/'+this.survey_id)
            .then(res => {
                this.survey_details = res.data
            })
            .catch(error => {
                this.errored = true
            });
        // Retrieve questions for survey
        axios
            .get('/api/question/'+this.survey_id)
            .then(res => {
                var index, len;
                for(index = 0, len = res.data.length; index < len; index++){
                    this.questions.push({
                        question_index: res.data[index].question_no,
                        question_id: res.data[index].question_id,
                        question_content: res.data[index].question_content,
                        answer_url: null,
                        answer_content: "",
                        audio_base64: null
                    });
                    this.completedList.push({
                        index: res.data[index].question_no,
                        submitted: false
                    });
                    this.answerType.push({
                        index: res.data[index].question_no,
                        type: 0,
                    });
                }
                this.questions = _.orderBy(this.questions, 'question_index');
                this.completedList = _.orderBy(this.completedList, 'index');
                this.answerType = _.orderBy(this.answerType, 'index');
                const list = []
                this.questions.forEach(function(question){
                    list.push({
                        text: ''
                    });
                });
                this.current_text = list;
            });            
    },
    methods: {
        goBackPage: function() {
          (window.history) ? window.history.go(-1) : null;
        },
        // Move screen to email input
        startSurvey: function() {
            this.slideSteps = 'questions';
        },
        startWizard: function() {
            if(this.isEmailValid()) {
                this.startQuestions = true;
            }
        },
        // Validate Email Address
        isEmailValid: function() {
            return (this.eMail == "")? "" : (this.emailReg.test(this.eMail)) ? true : false;            
        },
        // Script to check tab change TEXT=>AUDIO and vice-versa
        changeTab: function(typeIndex, type){
            var that = this;
            var typeIndex = typeIndex - 1;

            function stop() {
                that.rec.stop();
                that.showStart = true;
                that.showPause = false;
                that.showResume = false;
                that.showStop = false;
                that.isRecording = false;
                that.gumStream.getAudioTracks()[0].stop();
            }        
            
            function checkIfRecording(){
                if(that.rec != null){                    
                    if(that.rec.recording || that.isRecording == 'pause'){
                        if(confirm('Audio is recording, click OK to stop and lose recording, click Cancel to continue recording.')){                        
                            that.answerType[typeIndex].type = 0;
                            stop();
                        }
                    } else {
                        (that.questions[typeIndex].answer_content == null || that.questions[typeIndex].answer_content == "") ? that.answerType[typeIndex].type = type : null;
                    }
                } else {
                    (that.questions[typeIndex].answer_content == null || that.questions[typeIndex].answer_content == "") ? that.answerType[typeIndex].type = type : null;
                }
            }
            
            if(that.questions[typeIndex].answer_content == null || that.questions[typeIndex].answer_content == "") {
                checkIfRecording();
            } else if(that.questions[typeIndex].answer_content.length > 0) {
                if(confirm('Your current answer will be lost, are you sure?')){
                    that.questions[typeIndex].answer_content = '';
                    that.current_text[typeIndex].text = '';
                    that.questions[typeIndex].audio_base64 = '';
                    that.answerType[typeIndex].type = type;
                } else {                                    
                    checkIfRecording();
                }
            }
        },
        // Handle recording options      
        recording: function(status, index){
            URL = window.URL || window.webkitURL;
            const that = this;
            var AudioContext = window.AudioContext || window.webkitAudioContext;
            var audioContext = new AudioContext;

            var constraints = {
                audio: true,
                video: false
            }

            function start(){
                function startRecording(){
                    navigator.mediaDevices.getUserMedia(constraints).then(function(stream) {
                        /* assign to gumStream for later use */
                        that.gumStream = stream;
                        /* use the stream */
                        that.input = audioContext.createMediaStreamSource(stream);
                        /* Create the Recorder object and configure to record mono sound (1 channel) Recording 2 channels will double the file size */
                        that.rec = new Recorder(that.input, {
                            numChannels: 1
                        }) 
                        //start the recording process 
                        that.rec.record()
                        that.startRec = true;
                        that.isRecording = true;
                        that.showStart = false;
                        that.showPause = true;
                        that.showStop = true;
                    }).catch(function(err) {
                        //enable the record button if getUserMedia() fails 
                    });
                }
                if(that.questions[index].audio_base64 == null || that.questions[index].audio_base64 == ""){
                    startRecording();
                } else {
                    if(confirm('Your existing record will be removed, are you sure?')){
                        that.questions[index].answer_url = null; // To hide the audio controller
                        startRecording();
                    }
                }
            }    

            /**
             * Function to conv. audio to base64
             */
            function createDownloadLink(blob) {
                var url = URL.createObjectURL(blob);
                var base64data;
                var reader = new FileReader();
                reader.readAsDataURL(blob);
                reader.onloadend = function() {
                    var base64data = reader.result;
                    that.questions[index].audio_base64 = base64data;
                };
                that.questions[index].answer_url = url;
                if(that.questions.length == that.selectedQuestion){
                    that.steps[1].incomplete = false;
                }
            }

            /**
             * Function to pause recording
             */
            function pause() {
                if(that.rec.recording) {
                    that.showResume = true;
                    that.showPause = false;
                    that.isRecording = 'pause';
                    that.rec.stop();
                } else {
                    that.showResume = false;
                    that.showPause = true;
                    that.rec.record();
                    that.isRecording = true;
                }
            }
            
            /**
             * Function to stop recording
             */
            function stop() {
                that.rec.stop();
                that.startRec = false;
                that.showStart = true;
                that.showPause = false;
                that.showResume = false;
                that.showStop = false;
                that.isRecording = false;
                that.gumStream.getAudioTracks()[0].stop();
                that.rec.exportWAV(createDownloadLink);                
            }
            
            // Conditional statement to call rec. functions
            if(status == 'start') {  // Start rec.
                start();
            } else if(status == 'pause') {  // Pause rec.
                pause();
            } else {  // Stop rec.
                stop();
            }
        },
        // Delete recording for question
        deleteRecording: function(index){
            this.questions[index].answer_url = null;
            this.questions[index].audio_base64 = null;
        },
        // Handle question navigation
        questionNavigation: function(value, index){
            const that = this;
            index = index - 1;

            if(that.rec != null){   // CheckKKKKK if recording
                if(that.rec.recording){
                    alert('Audio is currently recording, please save before moving to next question');
                } else if(that.isRecording == 'pause') {
                    alert('Finish recording your answer before moving to the next question.');
                } else {    // Run script if not recording
                    if(value == 'higher'){  // Parameter = higher
                        if (that.questions[index].answer_content === ""
                            && that.questions[index].answer_url === null) { // Check for empty answer
                            alert('Please submit your answer, before moving to the next question.');
                        } else if (that.questions[index].answer_url != null) {
                            console.log(`textg ${that.selectedQuestion} : ${that.questions.length}`);
                            if (that.selectedQuestion <= that.questions.length){ // Current Question is less than or equal to questions length
                                if(that.selectedQuestion == that.questions.length) { that.slideSteps = 'review'; } else { that.selectedQuestion++; }
                            }              
                        } else {
                            console.log(`audiop ${that.selectedQuestion} : ${that.questions.length}`);
                            if (that.selectedQuestion <= that.questions.length){ // Current Question is less than or equal to questions length
                                if(that.selectedQuestion == that.questions.length) { that.slideSteps = 'review'; } else { that.selectedQuestion++; }
                            } 
                        }
                    } else if (value == 'lower'){   // Parameter = lower
                        if(that.selectedQuestion != 1) { // Ignore if selected question less than 1
                            that.selectedQuestion--;
                        }
                    }
                }
            } else if (that.questions[index].answer_content == "" && that.questions[index].answer_url == null) {
                alert('Please submit your answer, before moving to the next question.')
            } else {
                // If answer is provided in text at least
                if(that.selectedQuestion <= that.questions.length){ // Current Question is less than or equal to questions length
                    if(that.selectedQuestion == that.questions.length) { that.slideSteps = 'review'; } else { that.selectedQuestion++; }
                }
            }   
        },
        // Change answer value to input
        changeAnswerText: function(index){
            this.questions[index].answer_content = this.current_text[index].text;
            this.questions[index].audio_base64 = null;
            if(this.questions.length == this.selectedQuestion){
                (this.questions[index].answer_content == '') ? this.steps[1].incomplete = true : this.steps[1].incomplete = false;
            }
        },
        // Edit Question
        editQuestion: function(index) {
            this.slideSteps = 'questions';
            this.selectedQuestion = index+1;
        },
        // Submit form to the server
        submitForm: function(){
            this.isLoading = true;
            this.modalShow = true;
            this.modalText = '<h4 class="modal__isLoading">Hold on while your answers are being submitted, please do not close your browser window.</h4>';
            function randomString(length, chars) {
                var result = '';
                for (var i = length; i > 0; --i) result += chars[Math.floor(Math.random() * chars.length)];
                return result;
            }
            var respondent_id = randomString(24, '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ');
            axios
                .post('/api/respondent', {
                    respondent_id: respondent_id,
                    survey_id: this.survey_details.survey_id,
                    respondent_email: this.eMail,
                    notification_sent: 0,
                })
                .then(res => {
                    var survey_completion_id = randomString(24, '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ');
                    var date = new Date();
                    // Pad Numbers to Two Digits
                    function twoDigits(d) {
                        if(0 <= d && d < 10) return "0" + d.toString();
                        if(-10 < d && d < 0) return "-0" + (-1*d).toString();
                        return d.toString();
                    }
                    // Convert date to MySQL compatible date type
                    Date.prototype.toMysqlFormat = function() {
                        return this.getUTCFullYear() + "-" + twoDigits(1 + this.getUTCMonth()) + "-" + twoDigits(this.getUTCDate()) + " " + twoDigits(this.getUTCHours()) + ":" + twoDigits(this.getUTCMinutes()) + ":" + twoDigits(this.getUTCSeconds());
                    };
                    date = date.toMysqlFormat();
                    
                    axios
                        .post('/api/survey/completed', {
                            survey_completion_id: survey_completion_id,
                            completed_on: date,
                            notification_sent: 0,
                            notification_read: 0,
                            survey_id: this.survey_details.survey_id,
                            respondent_id: respondent_id,
                            created_user_id: this.survey_details.user_id
                        })
                        .then(res => {
                            var that = this;
                            this.generatedURL = process.env.MIX_APP_URL+ '/surveys/completed/' +survey_completion_id;
                            async function submitAnswers() {
                                return new Promise((resolve, reject) => {
                                    // Loop over answers and submit post request
                                    that.questions.forEach(function(question, index) {
                                        // Submit individual answers to post api endpoint
                                        function submitAnswer(){
                                            var answer_id = randomString(24, '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ');

                                            axios
                                                .post('/api/answer', {
                                                    answer_id: answer_id,
                                                    survey_completion_id: survey_completion_id,
                                                    question_id: question.question_id,
                                                    respondent_id: respondent_id,
                                                    survey_id: that.survey_details.survey_id,
                                                    answer_content: question.answer_content,
                                                    answer_url: question.answer_url,
                                                })
                                                .then(function(response){
                                                    // Set current index item to submitted
                                                    that.completedList[index].submitted = true;
                                                    // Identify how much questions have been submitted out of the total
                                                    var percentage_index = that.completedList.filter(function(s) { return s.submitted; }).length;
                                                    // Calculate percentage for progress indication
                                                    that.submitProgress = Math.trunc((percentage_index / that.questions.length)*100);

                                                    function isSubmitted(currVal){
                                                        return currVal.submitted == true;
                                                    }
                                                    
                                                    // If list is fully completed, show link to completed page
                                                    if(that.completedList.every(isSubmitted)) {
                                                        that.isLoading = false;
                                                        that.modalText = '<h4 class="modal__isLoading"><span class="modal_isLoading--text">Here is the completion link:</span> <a href="' +that.generatedURL+ '" class="surveyModal_completedLink">' +that.generatedURL+ '</a></h4>';
                                                    }
                                                    // Return JS Promise
                                                    resolve();
                                                })
                                                .catch(function(error){
                                                    console.log(error);
                                                    reject();
                                                });
                                        }
                                        if(question.answer_url != null){
                                            function dataURItoBlob(dataURI) {
                                                // convert base64/URLEncoded data component to raw binary data held in a string
                                                var byteString;
                                                if (dataURI.split(',')[0].indexOf('base64') >= 0)
                                                    byteString = atob(dataURI.split(',')[1]);
                                                else
                                                    byteString = unescape(dataURI.split(',')[1]);
                                                // separate out the mime component
                                                var mimeString = dataURI.split(',')[0].split(':')[1].split(';')[0];
                                                // write the bytes of the string to a typed array
                                                var ia = new Uint8Array(byteString.length);
                                                for (var i = 0; i < byteString.length; i++) {
                                                    ia[i] = byteString.charCodeAt(i);
                                                }
                                                return new Blob([ia], {type:mimeString});
                                            }

                                            var fd = new FormData();
                                            var dataURI = that.questions[index].audio_base64;

                                            var blob = dataURItoBlob(dataURI);

                                            fd.set('id', survey_completion_id);
                                            fd.append('audio', blob, 'submit.wav ');
                                            axios({
                                                method: 'post',
                                                url: '/api/upload/audio',
                                                data: fd,
                                                config: { headers: {'Content-Type': 'multipart/form-data'}}
                                            })
                                            .then(function(response){
                                                that.questions[index].answer_url = response.data;
                                                // Call function to submit answer, once uploaded
                                                submitAnswer();
                                                resolve();
                                            })
                                            .catch(function(error){
                                                console.log(error);
                                                reject();
                                            });
                                        } else {
                                            submitAnswer();
                                        }
                                    });
                                });
                            }

                            function surveyComplete() {
                                axios
                                    .get('/api/settings/profile/'+that.survey_details.user_id)
                                    .then(res => {
                                        if(res.data.allow_notification == 1){
                                            // Send email to survey creator
                                            axios
                                                .post('/api/survey-completed', {
                                                    to_email: res.data.email,
                                                    res_email: that.eMail,
                                                    survey_name: that.survey_details.survey_name,
                                                    url: that.generatedURL 
                                                })
                                                .then(function(response){
                                                    axios
                                                        .put('/api/survey/notification/'+survey_completion_id);
                                                })
                                                .catch(function(error){
                                                    console.log(error);
                                                });
                                        }
                                    })
                                    .catch(function(error){
                                        console.log(error);
                                    })
                                
                            }

                            async function submit_survey() {
                                await submitAnswers();  // Await until answers are submitted
                                surveyComplete();
                            }

                            submit_survey();

                        })
                        .catch(error => {
                            this.error = error;
                        })
                })
                .catch(error => {
                    this.error = error;
                })
        },
    }
}
</script>

<style lang="scss" scope>
.spkfrm--surveyWizardContainer {
  width: 100%;
  height: 100%;
}
.goBackIcon--icon {
  cursor: pointer;
}
.surveyTextInput {
    width: 100%;
    padding: 10px 5px;
    margin-bottom: 20px;
    border: 1px solid #CCC;
    background-color: #FFF;
    border-radius: 6px;

    &:focus {
        border: 1px solid #757575;
        outline: none;
    }
}
.surveyTextInput {
    outline: none;
}
.surveyWizard--processBtn {
    width: 100%;
    clear: both;
}
.surveyWizardContainer__surveyTabbedHeader {
  display: grid;
  grid-template-columns: repeat(5, 1fr);
  box-shadow: inset 0px -1px 0px 0px #CCC;
  padding: 10px 20px;
  align-items: center;

  .surveyWizardContainer--headerTitle {
    grid-column: 3/4;
    text-align: center;

    h1 {
      font-size: 22px;
      font-weight: 600;
    }
  }
}
.surveyWizardContainer__wizardStepper {    
    display: flex;
    align-items: center;
    justify-content: center;
    width: 100%;
    height: calc(100% - 55px);
    background-color: #E6E6E6;
    padding-bottom: 40px;

    .wizardStepper--introContainer {
        text-align: center;

        h4 {
            font-size: 24px;
            font-weight: 600;
            margin: 20px 0;
        }

        p {
            font-size: 18px;
        }
    }


    .wizardStepper__questionsContainer {
        width: 80%;

        .questionsContainer--emailInput {
            max-width: 400px;
            margin: 0 auto;
        }
    }

    .surveyWizard--audioBtnWrapper {
        margin-bottom: 20px;
        float: left;
    }

    .surveyWizard__questionAudioRecContainer {
        margin-bottom: 20px;

        .questionAudioRecContainer--rec,
        .questionAudioRecContainer--rec i {
            color: #f70000;
        }
    }

    .surveyWizard--audioBtn {
        display: flex;
        flex-direction: row;

        p {
            margin-top: 18px;
            margin-left: 5px;
        }
    }

    .surveyWizard--deleteAudio {
        width: 200px;
        float: left;
        margin-left: 10px;
    }
}

.recording-controls {
    display: flex;
    justify-content: center;
    padding-top: 20px;
}

.recording-controlsbtn__grey { background-color: #d6d6d6 !important; }
.recording-controlsbtn__black { background-color: #000 !important; }
.recording-controlsbtn__blue { background-color: #0054a6 !important; }

.recording-controlsbtn__black p,
.recording-controlsbtn__black i,
.recording-controlsbtn__blue p,
.recording-controlsbtn__blue i {
    color: #fff !important;
}

.recording-controlsbtn__red {
    background-color: #f70000 !important;
    float: left;
    padding: 6px 10px;
    border-radius: 15px;
    margin-top: 6px;
    margin-left: 5px;
}

.recording-controlsbtn__red i,
.recording-controlsbtn__red p {
    color: #fff !important;
    font-size: 12px;
}

.recording-controlsbtn {
    height: auto;
    float: left;
    padding: 3px 20px;
    border-radius: 20px;
}
.recording-controlsbtn i {
    font-size: 1.5em;
    line-height: 35px;
    float: left;
}
.recording-controlsbtn p {
    font-size: 1em !important;
    font-weight: 600 !important;
    line-height: 35px !important;
    margin-left: 10px;
    margin-bottom: 0;
    float: left;
}
.audioPlayer__controls {
    text-align: center;
    padding-top: 20px;
}
.survey_questionNavigation {
    width: 180px;
    display: flex;
    margin: 20px auto;
    justify-content: center;
}
.survey_questionNavigation a:first-child {
    float: left;
    padding-right: 5px;
    font-size: 2em;
    line-height: 1em;
}
.survey_questionIndex {
    float: left;
    font-size: 1.3em !important;
    margin-bottom: 0;
}
.survey_questionIndex--selected {
    font-size: 1.25em;
    color: #0054a6;
}
.survey_questionNavigation a:last-child {
    float: left;
    padding-left: 5px;
    font-size: 2em;
    line-height: 1em;
}
.surveyQuestions_complete {
    font-size: 1.2em;
    font-weight: 300;
    text-align: center;
}
.circle-loader {
  margin-bottom: 3.5em;
  border: 1px solid rgba(0, 0, 0, 0.2);
  border-left-color: #0054a6;
  animation: loader-spin 1.2s infinite linear;
  position: relative;
  display: inline-block;
  vertical-align: top;
  border-radius: 50%;
  width: 10em;
  height: 10em;
}

.load-complete {
  -webkit-animation: none;
  animation: none;
  border-color: #0054a6;
  transition: border 500ms ease-out;
}

.answerListItem__textAreaInput textarea {
    line-height: 15px;
}

.wizardStepper--reviewContainer {
    width: 80%;
    margin: 0 auto;

    .reviewContainer--generalTitle {
        margin-top: 20px;
        margin-bottom: 40px;
    }

    .reviewContainer_-statsTable {
        .statsTable--leftCol {
            width: 60%;
            max-width: 300px;
            
            h5 {
                font-size: 14px;
                font-weight: 600;
            }
        }
    }

    ul {
        padding-top: 10px;
        padding-left: 0;

        li {
            display: flex;
            flex-direction: column;
            list-style-type: none;
            margin-bottom: 10px;
        }
    }

    .reviewContainer--questionsTitle {
        margin-top: 20px;
    }

    .reviewContainer--questionContent {
        font-weight: 400;
        font-size: 16px;
    }

    .reviewContainer__questionAnswer {
        width: 100%;

        .questionAnswer__providedAnswer {
            display: flex;
            flex-direction: column;
            width: 80%;
            float: left;

            .providedAnswer--audio,
            .providedAnswer--txt {
                width: 100%;
            }
        }

        .questionAnswer--editAnswer {
            width: calc(20% - 10px);
            float: left;
            margin-left: 10px;
        }
    }
}

@media screen and (max-width: 768px) {
    .surveyWizard__title {
        font-size: 2.5em;
        text-align: center;
    }
}
@keyframes md-tab-ripple {
  0% {
    background: transparent;
  }
  50% {
    background: rgba(255, 255, 255, 0.05);
  }
  100% {
    background: transparent;
  }
}
</style>