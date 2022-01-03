export default {
  async getMyRequests(context) {
    let response
    try {
      response = await this.$axios.$get(
        `${this.$axios.defaults.baseURL}/users/${this.$auth.user.id}/requests`
      )
    } catch (err) {
      console.log(err)
      return
    }
    //console.log(response)
    context.commit('setMyRequests', response)
  },
  async getAllRequests(context) {
    let response
    try {
      response = await this.$axios.$get(
        `${this.$axios.defaults.baseURL}/requests`
      )
    } catch (err) {
      console.log(err)
      return
    }

    console.log(response)
    context.commit('setRequests', response)
    context.commit('setFilteredRequests', response)
  },
}
