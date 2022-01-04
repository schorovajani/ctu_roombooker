export default {
  async getAllRooms(context) {
    let response
    try {
      response = await this.$axios.$get(`${this.$axios.defaults.baseURL}/rooms`)
    } catch (error) {
      console.log(error)
      return
    }

    //console.log(response)
    context.commit('setRooms', response)
    context.commit('setFilteredRooms', response)
  },

  async getRoomUsers(context, payload) {
    let response
    try {
      response = await this.$axios.$get(
        `${this.$axios.defaults.baseURL}/rooms/${payload}/users`
      )
    } catch (error) {
      console.log(error)
      return
    }

    console.log(response)
    context.commit('setRoomManager', response)
    context.commit('setRoomMembers', response)
  },

  async getRoom(context, payload) {
    let response
    try {
      response = await this.$axios.$get(
        `${this.$axios.defaults.baseURL}/rooms/${payload}`
      )
    } catch (error) {
      console.log(error)
      return
    }

    console.log(response)
    context.commit('setRoom', response)
  },

  async getRoomRequests(context, payload) {
    let response
    try {
      response = await this.$axios.$get(
        `${this.$axios.defaults.baseURL}/rooms/${payload}/requests`
      )
    } catch (error) {
      console.log(error)
      return
    }

    console.log(response)
    context.commit('setRoomRequests', response)
  },
}
