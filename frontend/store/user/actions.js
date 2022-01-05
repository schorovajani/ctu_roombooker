export default {
  async getUsers(context) {
    let response
    try {
      response = await this.$axios.$get(`${this.$axios.defaults.baseURL}/users`)
    } catch (error) {
      console.log(error)
      return
    }
    console.log(response)
    context.commit('setUsers', response)
  },
}
