<template>
  <div class="row" style="height: 100%; align-items: center;">
    <div style="margin: 20px auto; padding: 0 5%; text-align: center;">
      <router-link to='/'>
        <img class="spkfrm__loginLogo" :src="logo" />
      </router-link>
      <card v-if="mustVerifyEmail" :title="$t('sign_up')">
        <div class="alert alert-success" role="alert">
          {{ $t('verify_email_address') }}
        </div>
      </card>
      <div v-else>
        <h1 class="spkfrm_SignupText">{{ $t('sign_up') }}</h1>
        <form @submit.prevent="register" @keydown="form.onKeydown($event)">
          <!-- Name -->
          <div class="form-group row">
            <label class="col-md-12 col-form-label text-left spkfrm__loginLabel">{{ $t('name') }}</label>
            <div class="col-md-12 spkfrm__loginTxtInput">
              <input v-model="form.name" :class="{ 'is-invalid': form.errors.has('name') }" class="form-control" type="text" name="name">
              <has-error :form="form" field="name" />
            </div>
          </div>

          <!-- Email -->
          <div class="form-group row">
            <label class="col-md-12 col-form-label text-left spkfrm__loginLabel">{{ $t('email') }}</label>
            <div class="col-md-12 spkfrm__loginTxtInput">
              <input v-model="form.email" :class="{ 'is-invalid': form.errors.has('email') }" class="form-control" type="email" name="email">
              <has-error :form="form" field="email" />
            </div>
          </div>

          <!-- Password -->
          <div class="form-group row">
            <label class="col-md-12 col-form-label text-left spkfrm__loginLabel">{{ $t('password') }}</label>
            <div class="col-md-12 spkfrm__loginTxtInput">
              <input v-model="form.password" :class="{ 'is-invalid': form.errors.has('password') }" class="form-control" type="password" name="password">
              <has-error :form="form" field="password" />
            </div>
          </div>

          <!-- Password Confirmation -->
          <div class="form-group row">
            <label class="col-md-12 col-form-label text-left spkfrm__loginLabel">{{ $t('confirm_password') }}</label>
            <div class="col-md-12 spkfrm__loginTxtInput">
              <input v-model="form.password_confirmation" :class="{ 'is-invalid': form.errors.has('password_confirmation') }" class="form-control" type="password" name="password_confirmation">
              <has-error :form="form" field="password_confirmation" />
            </div>
          </div>

            <div class="form-group row">
              <div class="col-md-12 text-left p-0">
                <!-- Submit Button -->
                <Button type="primary_WhiteBG" size="nl" :loading="form.busy" :handleClick="register" style="margin-right: 5px;">
                  {{ $t('sign_up') }}
                </Button>
              </div>
            </div>
            
            <div class="spkfrm__horizontalRowLine spkfrm__horizontalRowLine-top"></div>

            <div class="form-group row">
              <div class="col-md-12 px-0 d-flex spkfrm__socialLoginBtnGroup">
                    <!-- Facebook Login Button -->
                    <login-f-b>{{ $t('sign_up_facebook') }}</login-f-b>
              </div>
              <div class="col-md-12 px-0 d-flex spkfrm__socialLoginBtnGroup">
                    <!-- Google Login Button -->
                    <login-google>{{ $t('sign_up_google') }}</login-google>
              </div>
            </div>

            <div class="spkfrm__horizontalRowLine spkfrm__horizontalRowLine-top"></div>

            <div class="form-group row">
              <div class="col-md-12">
                <router-link to="/login" class="small mt-auto my-auto spkfrm__Links">
                  {{ $t('to_login') }} >
                </router-link>
              </div>
            </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
import Vue from 'vue'
import Form from 'vform'
import Button from '../../components/Button'
import LoginFB from '../../components/loginFB'
import LoginGoogle from '../../components/loginGoogle'

export default {
  middleware: 'guest',

  layout: 'basic',

  created() {
    Vue.set(this.$store.state.setting, 'bgColor', '#E6E6E6');
  },

  components: {
    Button,
    LoginFB,
    LoginGoogle
  },

  metaInfo () {
    return { title: this.$t('register') }
  },

  data: () => ({
    form: new Form({
      name: '',
      email: '',
      password: '',
      password_confirmation: ''
    }),
    logo: '/img/logo.png',
    mustVerifyEmail: false
  }),

  methods: {
    async register () {
      // Register the user.
      const { data } = await this.form.post('/api/register')

      // Must verify email fist.
      if (data.status) {
        this.mustVerifyEmail = true
      } else {
        // Log in the user.
        const { data: { token } } = await this.form.post('/api/login')

        // Save the token.
        this.$store.dispatch('auth/saveToken', { token })

        // Update the user.
        await this.$store.dispatch('auth/updateUser', { user: data })

        // Redirect home.
        this.$router.push({ name: 'home' })
      }
    }
  }
}
</script>

<style lang="scss" scope>
.loginBtn {
  margin: 0 20px 0 0;
}

.spkfrm__loginLogo {
  max-width: 200px;
}

.spkfrm_SignupText {
  font-family: 'Nunito', sans-serif;
  font-size: 24px;
  font-weight: 600;
  margin-top: 40px;
  margin-bottom: 10px;
  color: #000;
}

.spkfrm__loginLabel {
  font-weight: 400;
  padding: 0;
  margin: 0;
}

.spkfrm__horizontalRowLine {
  width: 100%;
  height: 1px;
  background-color: #ccc;
  margin: 20px 0;
}

.spkfrm__horizontalRowLine-top {
  margin: 50px 0 20px;
}

.spkfrm__socialLoginBtnGroup {
  button {
    width: 100% !important;
    font-size: 16px !important;
  }
}

.spkfrm__loginTxtInput {
  padding: 5px 0;
}

.spkfrm__Links {
  font-size: 16px;

  &:hover {
    text-decoration: none;
    font-weight: 700;
  }
}

.spkfrm__ContainerForm {
  width: 320px;
  margin: 0 auto;
}

.invalid-feedback {
  text-align: left;
}
</style>
