export default {
  setRooms(state, rooms) {
    state.rooms = rooms
  },
  setFilteredRooms(state, rooms) {
    let filteredRooms = []
    rooms.forEach((room) => {
      const findObject = filteredRooms.find(
        (ro) => ro['buildName'] && ro['buildName'] === room.building.name
      )

      if (!findObject) {
        filteredRooms.push({
          id: room.building.id,
          buildName: room.building.name,
          rooms: [room],
        })
      } else if (findObject) {
        let index = filteredRooms.indexOf(findObject)
        filteredRooms[index].rooms.push(room)
      }
    })
    // console.log('mutations')
    // console.log(filteredRooms)
    state.filteredRooms = filteredRooms
  },
  setRoomManager(state, roomUsers) {
    state.roomManager = roomUsers.find((user) => {
      return user.roomRoles.some((role) => role.roleType.name === 'Manager')
    })
  },
  setRoomMembers(state, roomUsers) {
    state.roomMembers = roomUsers.filter((user) => {
      return user.roomRoles.some((role) => role.roleType.name === 'Member')
    })
  },
}
