export default {
  filteredRooms(state) {
    return state.filteredRooms
  },
  manager(state) {
    console.log('getter')
    console.log(state.roomManager)
    return state.roomManager
  },
  members(state) {
    return state.roomMembers
  },
}