export default {
  setAlert(state, alert) {
    state.alert = alert.show
    state.alertMessage = alert.message
  },
}
