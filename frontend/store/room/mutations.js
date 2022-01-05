export default {
  setRooms(state, rooms) {
    state.rooms = rooms
  },
  setFilteredRooms(state, rooms) {
    let filteredRooms = []
    rooms.forEach((room) => {
      const findObject = filteredRooms.find(
        (ro) => ro['name'] && ro['name'] === room.building.name
      )

      if (!findObject) {
        filteredRooms.push({
          id: room.building.id,
          name: room.building.name,
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

  setManagerRooms(state, rooms) {
    state.managerRooms = rooms
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
  setRoom(state, room) {
    state.room = room
  },
  setRoomRequests(state, requests) {
    console.log('nenjedn')
    console.log(requests)
    let events = []
    requests.forEach((req) => {
      const start = new Date(req.eventStart).toISOString()
      const startString = `${start.substring(0, 10)} ${start.substring(11, 16)}`

      const end = new Date(req.eventEnd).toISOString()
      const endString = `${end.substring(0, 10)} ${end.substring(11, 16)}`

      events.push({
        name: req.description,
        start: startString,
        end: endString,
        user: req.user,
        status: req.status.name,
      })
    })

    console.log('events')
    console.log(events)
    state.roomRequests = events
  },

  deleteRoom(state, id) {
    state.rooms = state.rooms.filter((room) => room.id !== id)
    state.filteredRooms.forEach((build) => {
      build.rooms = build.rooms.filter((room) => room.id !== id)
    })
  },
}
