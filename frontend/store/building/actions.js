export default {
  async getAllBuildings(context) {
    let response
    try {
      response = await this.$axios.$get(
        `${this.$axios.defaults.baseURL}/buildings`
      )
    } catch (err) {
      console.log(err)
      return
    }
    console.log(response)
    context.commit('setBuildings', response)
  },
}
