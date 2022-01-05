export default {
  setUsers(state, users) {
    // let simple = []
    // users.forEach((user) => {
    //   simple.push(`${user.firstName} ${user.lastName}`)
    // })
    state.users = users
  },
}
