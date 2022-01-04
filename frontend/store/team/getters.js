export default {
  teams(state) {
    return state.teams
  },
  manager(state) {
    console.log('manager - team')
    console.log(state.manager)
    return state.manager
  },
  members(state) {
    return state.members
  },
}
