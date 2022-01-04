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

  async getManagerRooms(context) {
    let teams
    try {
      teams = await this.$axios.$get(
        `${this.$axios.defaults.baseURL}/users/${this.$auth.user.id}/teams`
      )
    } catch (error) {
      console.log(error)
      return
    }
    let managerRooms = []
    teams.forEach(async (team) => {
      let rooms
      try {
        rooms = await this.$axios.$get(
          `${this.$axios.defaults.baseURL}/teams/${team.id}/rooms`
        )
      } catch (error) {
        console.log(error)
        return
      }
      managerRooms.push({ id: team.id, name: team.name, rooms: rooms })
    })

    console.log(managerRooms)
    //context.commit('setManagerRooms', managerRooms)
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

  async deleteRoom(context, payload) {
    try {
      await this.$axios.$delete(
        `${this.$axios.defaults.baseURL}/rooms/${payload}`
      )
    } catch (error) {
      console.log(error)
      return
    }

    context.commit('deleteRoom', payload)
  },
}
