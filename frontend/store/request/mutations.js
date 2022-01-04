export default {
  setMyRequests(state, requests) {
    state.myRequests = requests
  },
  setRequests(state, requests) {
    state.requests = requests
  },
  setFilteredRequests(state, requests) {
    let filteredRequests = []
    requests.forEach((req) => {
      const roomName = `${req.room.building.name}:${req.room.name}`
      const isEmpty = !filteredRequests.length
      const findObject = filteredRequests.find(
        (req) => req['roomName'] && req['roomName'] === roomName
      )
      if (isEmpty || !findObject) {
        filteredRequests.push({
          id: req.room.id,
          roomName: roomName,
          requests: [req],
        })
      } else if (findObject) {
        let index = filteredRequests.indexOf(findObject)
        //console.log(filteredRequests[index].requests)
        filteredRequests[index].requests.push(req)
      }
    })
    console.log('hhehehehehe')
    console.log(filteredRequests)
    state.filteredRequests = filteredRequests
  },

  deleteMyRequest(state, id) {
    state.myRequests = state.myRequests.filter((req) => req.id !== id)
  },

  deleteRequest(state, id) {
    state.requests = state.requests.filter((req) => req.id !== id)

    state.filteredRequests = state.filteredRequests.forEach((room) => {
      room.request = room.requests.filter((req) => req.id !== id)
    })
  },
}
