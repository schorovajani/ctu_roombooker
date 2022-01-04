export default {
  filteredRooms(state) {
    return state.filteredRooms
  },
  managerRooms(state) {
    return state.managerRooms
  },
  manager(state) {
    console.log('getter')
    console.log(state.roomManager)
    return state.roomManager
  },
  members(state) {
    return state.roomMembers
  },
  room(state) {
    return state.room
  },
  roomRequests(state) {
    return state.roomRequests
  },
}
