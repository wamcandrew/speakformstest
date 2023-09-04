<template>
  <div class="row" style="height: 100%; align-items: center;">
    <div style="margin: 0px auto; padding: 0 5%; text-align: center;">
        <router-link to='/'>
          <img class="spkfrm__loginLogo" :src="logo" />
        </router-link>
        <h1 class="spkfrm_LoginText">{{ $t('login') }}</h1>
        <form class="spkfrm__ContainerForm" @submit.prevent="login" @keydown="form.onKeydown($event)">
          <!-- Email -->
          <div class="form-group row ">
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

          <!-- Remember Me -->
          <div class="form-group row">
            <div class="col-md-12 d-flex p-0">
              <!-- Submit Button -->
              <Button class="mr-4" type="primary_WhiteBG" size="nl" :loading="form.busy" :handleClick="login">
                {{ $t('login') }}
              </Button>

              <!-- Remember Link -->
              <checkbox v-model="remember" name="remember">
                {{ $t('remember_me') }}
              </checkbox>

            </div>
          </div>

          <div class="spkfrm__horizontalRowLine spkfrm__horizontalRowLine-top"></div>

          <div class="form-group row">
            <div class="col-md-12 px-0 d-flex spkfrm__socialLoginBtnGroup">
                  <!-- Facebook Login Button -->
                  <login-f-b>Login with Facebook</login-f-b>
            </div>
            <div class="col-md-12 px-0 d-flex spkfrm__socialLoginBtnGroup">
                  <!-- Google Login Button -->
                  <login-google>Login with Google</login-google>
            </div>
          </div>

          <div class="spkfrm__horizontalRowLine"></div>

          <div class="form-group row">
            <div class="col-md-10 offset-md-1 d-flex">
              <router-link to="/register" class="small mt-auto my-auto spkfrm__Links">
                {{ $t('sign_up') }}
              </router-link>
              <router-link :to="{ name: 'password.request' }" class="small ml-auto my-auto spkfrm__Links">
                {{ $t('forgot_password') }}
              </router-link>
            </div>
          </div>
        </form>
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
    return { title: this.$t('login') }
  },

  data: () => ({
    form: new Form({
      email: '',
      password: ''
    }),
    logo: '/img/logo.png',
    remember: false
  }),

  methods: {
    async login () {
      // Submit the form.
      const { data } = await this.form.post('/api/login')

      // Save the token.
      this.$store.dispatch('auth/saveToken', {
        token: data.token,
        remember: this.remember
      })

      // Fetch the user.
      await this.$store.dispatch('auth/fetchUser')

      // Redirect home.
      this.$router.push({ name: 'home' })
    },
  }
}
</script>

<style lang="scss" scope>
.loginBtn {
  margin: 0 20px 0 0;
}

.spkfrm__loginLogo {
  max-width: 200px;
  margin-top: 100px;
}

.spkfrm_LoginText {
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