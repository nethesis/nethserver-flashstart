<template>
  <div>
    <h1>{{$t('settings.title')}}</h1>

    <!-- error message -->
    <div v-if="errorMessage" class="alert alert-danger alert-dismissable">
      <button type="button" class="close" @click="closeErrorMessage()" aria-label="Close">
        <span class="pficon pficon-close"></span>
      </button>
      <span class="pficon pficon-error-circle-o"></span>
      {{ errorMessage }}.
    </div>

    <div v-if="!uiLoaded" class="spinner spinner-lg"></div>
    <div v-if="uiLoaded">
      <!-- banner -->
      <div class="alert alert-info alert-dismissable">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span class="pficon pficon-close"></span>
        </button>
        <span class="pficon pficon-info"></span>
        {{$t('settings.before_using_flashstart')}} <a href="http://flashstart.nethesis.it/" target="_blank">http://flashstart.nethesis.it/</a>.
      </div>

      <form class="form-horizontal" v-on:submit.prevent="btSaveClick">
        <!-- enable flashstart -->
        <div class="form-group">
          <label
            class="col-sm-2 control-label"
            for="textInput-modal-markup"
          >{{$t('settings.enable_flashstart_filter')}}</label>
          <div class="col-sm-5">
            <toggle-button
              class="min-toggle"
              :width="40"
              :height="20"
              :color="{checked: '#0088ce', unchecked: '#bbbbbb'}"
              :value="enableFlashStart"
              :sync="true"
              @change="toggleEnableFlashStart()"
            />
          </div>
        </div>
        <div v-if="enableFlashStart">
          <!-- username -->
          <div class="form-group" :class="{ 'has-error': showErrorUsername }">
            <label
              class="col-sm-2 control-label"
              for="textInput-modal-markup"
            >{{$t('settings.username_email')}}</label>
            <div class="col-sm-5">
              <input type="input" class="form-control" v-model="username">
              <span
                class="help-block"
                v-if="showErrorUsername"
              >{{$t('settings.username_validation')}}</span>
            </div>
          </div>
          <!-- password -->
          <div class="form-group" :class="{ 'has-error': showErrorPassword }">
            <label
              class="col-sm-2 control-label"
              for="textInput-modal-markup"
            >{{$t('settings.password')}}</label>
            <div class="col-sm-5">
              <input
                :type="passwordVisible ? 'text' : 'password'"
                class="form-control"
                v-model="viewConfig.password"
              >
              <span
                class="help-block"
                v-if="showErrorPassword"
              >{{$t('settings.password_validation')}}</span>
            </div>
            <!-- Toggle password visibility -->
            <div class="col-sm-2 adjust-index">
              <button
                tabindex="-1"
                type="button"
                class="btn btn-primary"
                @click="togglePasswordVisibility()"
              >
                <span :class="[!passwordVisible ? 'fa fa-eye' : 'fa fa-eye-slash']"></span>
              </button>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
  export default {
    name: "Settings",
    components: {
    },
    props: {
    },
    mounted() {
      // this.getConfig()
    },
    data() {
      return {
        uiLoaded: true,
        errorMessage: null,
        enableFlashStart: false,
        username: '',
        showErrorUsername: false,
        showErrorPassword: false,
        showErrorBypass: false
      };
    },
    methods: {
      // getConfig() {
      //   this.uiLoaded = false;
      //   var ctx = this;
      //   nethserver.exec(
      //     ["nethserver-freepbx/settings/read"],
      //     { "config": "asterisk" },
      //     null,
      //     function(success) {
      //       var asteriskConfigOutput = JSON.parse(success);
      //       ctx.getAsteriskConfigSuccess(asteriskConfigOutput)
      //     },
      //     function(error) {
      //       ctx.showErrorMessage(ctx.$i18n.t("settings.error_reading_asterisk_configuration"), error)
      //     }
      //   );
      // },
      closeErrorMessage() {
        this.errorMessage = null
      },
      showErrorMessage(errorMessage, error) {
        console.error(errorMessage, error) /* eslint-disable-line no-console */
        this.errorMessage = errorMessage
      },
      toggleEnableFlashStart() {
        this.enableFlashStart = !this.enableFlashStart;
      }
    }
  }
</script>

<style>
.divider {
  border-bottom: 1px solid #d1d1d1;
}
</style>