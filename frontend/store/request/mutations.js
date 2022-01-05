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
    // console.log('hhehehehehe')
    // console.log(filteredRequests)
    // console.log(requests)
    state.filteredRequests = filteredRequests
  },

  deleteMyRequest(state, id) {
    state.myRequests = state.myRequests.filter((req) => req.id !== id)
  },

  deleteRequest(state, id) {
    state.requests = state.requests.filter((req) => req.id !== id)
    state.filteredRequests.forEach((room) => {
      room.requests = room.requests.filter((req) => req.id !== id)
    })
  },

  setRequest(state, request) {
    state.request = request
  },

  editRequest(state, payload) {
    //console.log(payload.data)
    let index1 = state.requests.indexOf(payload.data)
    state.requests[index1] = payload.data

    state.filteredRequests.every((room) => {
      let findObject = room.requests.find((req) => req.id === payload.id)
      if (findObject) {
        let index2 = room.requests.indexOf(findObject)
        room[index2] = payload.data
        return false
      }
      return true
    })
  },
}
