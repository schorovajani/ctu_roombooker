export default {
  showAlert(context, payload) {
    context.commit('setAlert', {
      show: true,
      message: payload,
    })
  },
  hideAlert(context) {
    context.commit('setAlert', {
      show: false,
      message: '',
    })
  },
}
