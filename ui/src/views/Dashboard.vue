<template>
  <div>
    <h1>{{$t('dashboard.title')}}</h1>

    <!-- error message -->
    <div v-if="errorMessage" class="alert alert-danger alert-dismissable">
      <span class="pficon pficon-error-circle-o"></span>
      {{ errorMessage }}.
    </div>

    <div v-if="!uiLoaded" class="spinner spinner-lg"></div>
    <div v-if="uiLoaded">
      <form class="form-horizontal" v-on:submit.prevent="btSaveClick">
        <div class="form-group">
          <label
            class="col-sm-2 control-label margin-top-2"
          >{{$t('dashboard.flashstart_enabled')}}</label>
          <div class="col-sm-2 margin-top-6">
            <span v-if="flashstartEnabled">
              <span class="pficon pficon-ok login-icon"></span>
            </span>
            <span v-else>
              <span class="pficon pficon-error-circle-o login-icon"></span>
            </span>
          </div>
        </div>
        <div class="form-group" v-if="flashstartEnabled">
          <label
            class="col-sm-2 control-label margin-top-2"
          >{{$t('dashboard.authentication_status')}}</label>
          <div class="col-sm-2 margin-top-6">
            <span v-if="loginOk">
              <span class="pficon pficon-ok login-icon"></span>
            </span>
            <span v-else>
              <span class="pficon pficon-error-circle-o login-icon"></span>
            </span>
          </div>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
export default {
  name: "Dashboard",
  components: {
  },
  props: {
  },
  mounted() {
    this.getConfig()
  },
  data() {
    return {
      uiLoaded: true,
      errorMessage: null,
      loginOk: false,
      flashstartEnabled: false
    };
  },
  methods: {
    getConfig() {
      this.errorMessage = null
      this.uiLoaded = false;
      var ctx = this;
      nethserver.exec(
        ["nethserver-flashstart/read"],
        { "config": "dashboard" },
        null,
        function(success) {
          var output = JSON.parse(success)
          ctx.loginOk = output.configuration.loginOk
          ctx.flashstartEnabled = output.configuration.flashstartEnabled === 'enabled'
          ctx.uiLoaded = true
        },
        function(error) {
          ctx.showErrorMessage(ctx.$i18n.t("dashboard.error_retrieving_dashboard_data"), error)
        }
      );
    },
    showErrorMessage(errorMessage, error) {
      console.error(errorMessage, error) /* eslint-disable-line no-console */
      this.errorMessage = errorMessage
    },
  }
}
</script>

<style>
.margin-top-6 {
  margin-top: 6px;
}

.margin-top-2 {
  margin-top: 2px;
}

.login-icon {
  margin-left: 5px;
  font-size: 140%;
}
</style>