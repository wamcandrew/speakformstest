<template>
    <card>
        <h3>{{ $t('admin-pages') }}</h3>
        <div v-show="alertShow" class="alert alert-primary">
            {{ alertMessage }}
        </div>
        <div class="dropdown">
            <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Select your page
            </button>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                <div v-for="(pagesItem, index) in pagesList" :key="index">
                    <a class="dropdown-item" href="#" v-on:click="loadPage(pagesItem.id)">{{ pagesItem.name }}</a>
                </div>
            </div>
        </div>

        <div v-if="pageLoaded">
            <div style="margin-top: 10px;">
                <textarea :value="pageData" @input="update" style="width: 50%; float:left;"></textarea>
                <div v-html="compiledMarkdown" style="width: 50%; padding: 0 10px; float: right;"></div>
            </div>
            <div style="clear: both; padding-top: 30px;">
                <a href="#" class="spkFormBtn" v-on:click="updatePage()">Update Page</a>
            </div>
        </div>
    </card>
</template>

<script>
import axios from 'axios'
import _ from 'lodash'
import marked from 'marked'

export default {
    data () {
        return {
            alertShow: false,
            alertMessage: null,
            pageLoaded: false,
            pageData: '# Load Data',
            selectedId: null,
            pagesList: [
                { id: 'tos', name: 'Terms of Services' },
                { id: 'privacy', name: 'Privacy Policy' },
            ],
        }
    },
    computed: {
        compiledMarkdown: function() {
            return marked(this.pageData, { sanitize: true })
        }
    },
    methods: {
        loadPage(page_id) {
            this.selectedId = page_id;
            this.pageLoaded = false;
            this.alertShow = false;
            axios
                .get('/api/pages/'+page_id)
                .then(res => {
                    this.pageData = res.data.body;
                    this.pageLoaded = true;
                });
        },
        updatePage() {
            axios
                .post('/api/pages/'+this.selectedId, {
                    body: this.pageData
                })
                .then(res => {
                    this.alertMessage = 'Page has been updated';
                    this.alertShow = true;
                })
        },
        update: _.debounce(function (e) {
            this.pageData = e.target.value
        }, 300)
    }
}
</script>

<style scope>
textarea, #editor div {
  display: inline-block;
  width: 49%;
  height: 100%;
  vertical-align: top;
  box-sizing: border-box;
  padding: 0 20px;
}

textarea {
  border: none;
  border-right: 1px solid #ccc;
  resize: none;
  outline: none;
  background-color: #f6f6f6;
  font-size: 14px;
  font-family: 'Monaco', courier, monospace;
  padding: 20px;
}

code {
  color: #f66;
}
</style>