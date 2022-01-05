export default {
  async getAllTeams(context) {
    let response
    try {
      response = await this.$axios.$get(`${this.$axios.defaults.baseURL}/teams`)
    } catch (error) {
      console.log(error)
      return
    }

    console.log(response)
    context.commit('setTeams', response)
  },

  async getTeamUsers(context, payload) {
    let response
    try {
      response = await this.$axios.$get(
        `${this.$axios.defaults.baseURL}/teams/${payload}/users`
      )
    } catch (error) {
      console.log(error)
      return
    }

    console.log(response)
    context.commit('setManager', response)
    context.commit('setMembers', response)
  },

  async deleteTeam(context, payload) {
    try {
      await this.$axios.$delete(
        `${this.$axios.defaults.baseURL}/teams/${payload}`
      )
    } catch (error) {
      console.log(error)
      return
    }

    context.commit('deleteTeam', payload)
  },
}
