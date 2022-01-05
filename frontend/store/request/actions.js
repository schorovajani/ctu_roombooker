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

  async deleteMyRequest(context, payload) {
    try {
      await this.$axios.$delete(
        `${this.$axios.defaults.baseURL}/requests/${payload}`
      )
    } catch (error) {
      console.log(error)
      return
    }

    context.commit('deleteMyRequest', payload)
  },

  async deleteRequest(context, payload) {
    try {
      await this.$axios.$delete(
        `${this.$axios.defaults.baseURL}/requests/${payload}`
      )
    } catch (error) {
      console.log(error)
      return
    }

    context.commit('deleteRequest', payload)
  },

  async postRequest(_, payload) {
    let response
    try {
      response = await this.$axios.$post(
        `${this.$axios.defaults.baseURL}/requests`,
        payload
      )
    } catch (error) {
      console.log(error)
      return
    }
  },
}
