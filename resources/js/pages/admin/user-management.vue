<template>
  <card>
    <h3>{{ $t('admin-user') }}</h3>
      <div v-show="alertShow" class="alert alert-primary">
          {{ alert_message }}
      </div>

      <!-- Email -->
      <div class="form-group row">
        <label class="col-md-3 col-form-label text-md-right">Search by email</label>
        <div class="col-md-7">
          <input v-model="user_email" class="form-control" type="email" name="email" />
        </div>
        <div class="col-md-2">
            <a href="#" class="spkFormBtn" v-on:click="fetch_user()">Search</a>
        </div>
      </div>

      <div v-if="user_details == null">
        <p>User details will be loaded here.</p>
      </div>
      <div v-else-if="user_details == 'unknown'">
        <p>User was not found.</p>
      </div>
      <div v-else>
        <div class="form-group row">
            <label class="col-md-3 col-form-label text-md-right">Name</label>
            <div class="col-md-7">
            <p>{{ user_details.name }}</p>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-md-3 col-form-label text-md-right">Clean User</label>
            <div class="col-md-7">
                <a href="#" class="spkFormBtn" style="background-color: green;" v-on:click="clean_user()">
                    <v-icon style="font-size: 1em;color: #fff; margin-right: 5px;">fa-broom</v-icon>
                    Clean</a>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-md-3 col-form-label text-md-right">Delete User</label>
            <div class="col-md-7">
                <a href="#" class="spkFormBtn" style="background-color: red;" v-on:click="clean_user()">
                    <v-icon style="font-size: 1em;color: #fff; margin-right: 5px;">fa-trash</v-icon>
                    Delete</a>
            </div>
        </div>
      </div>
  </card>
</template>

<script>
import Form from 'vform'
import axios from 'axios'
import { mapGetters } from 'vuex'

export default {
  scrollToTop: false,

  metaInfo () {
    return { title: this.$t('settings') }
  },

  data: () => ({
    alertShow: false,
    alert_message: '',
    user_email: '',
    user_details: null,
  }),

  computed: mapGetters({
    user: 'auth/user'
  }),

  methods: {
    fetch_user () {
      if(this.user_email != ''){
        axios
            .get('/api/user/details/'+this.user_email)
            .then(res => {
              if(res.data.name == null){
                this.user_details = 'unknown';
              } else {
                this.user_details = res.data;
              }
            })
            .catch(error => {
                this.alert_message = 'No such user exists.';
                this.alertShow = true;
            });
      } else {
          this.alert_message = 'Please input an email address.';
          this.alertShow = true;
      }
    },
    clean_user() {
        axios
           .post('/api/user/clean/'+this.user_details.id)
           .then(res => {
                this.alert_message = 'User data was cleared.';
                this.alertShow = true;
           })
           .catch(error => {
                this.alert_message = 'No such user exists.';
                this.alertShow = true;
            });
    },
    delete_user() {
        axios
           .post('/api/user/delete/'+this.user_details.id)
           .then(res => {
                this.alert_message = 'User was removed.';
                this.alertShow = true;
           })
           .catch(error => {
                this.alert_message = 'No such user exists.';
                this.alertShow = true;
            });
    }
  }
}
</script>