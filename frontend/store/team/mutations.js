export default {
  setTeams(state, teams) {
    state.teams = teams
  },
  setManager(state, teamUsers) {
    const test = teamUsers.find((user) => {
      return user.teamRoles.some((role) => role.roleType.name === 'Manager')
    })
    console.log('manager - mutations')
    console.log(test)
    state.manager = test
  },
  setMembers(state, teamUsers) {
    const test = teamUsers.filter((user) => {
      return user.teamRoles.some((role) => role.roleType.name === 'Member')
    })
    console.log('members - mutations')
    console.log(test)
    state.members = test
  },

  deleteTeam(state, id) {
    state.teams = state.teams.filter((team) => team.id !== id)
  },
}
