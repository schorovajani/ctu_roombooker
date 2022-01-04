<template>
  <main>
    <h2>Místnosti</h2>
    <div class="rooms">
      <section class="rooms-info">
        <div class="title" v-for="title in titles" :key="title.id">
          <h3>{{ title.name }}</h3>
          <div class="rooms-detail">
            <RoomsDetail
              @usersClick="getRoomUsers"
              v-for="room in title.rooms"
              :key="room.id"
              :room="room"
            />
          </div>
        </div>
      </section>
      <section v-if="showUsers" class="rooms-users">
        <UserOf
          type="room"
          :name="title"
          :manager="manager"
          :members="members"
        />
      </section>
    </div>
  </main>
</template>

<script>
import RoomsDetail from '~/components/rooms/RoomsDetails.vue'
import UserOf from '~/components/users/UsersOf.vue'

export default {
  components: { RoomsDetail, UserOf },
  middleware: ['isGroupManager'],
  data() {
    return {
      title: '',
      id: null,
      showUsers: false,
    }
  },
  created() {
    this.loadRooms()
  },
  computed: {
    titles() {
      if (this.$auth.hasScope('admin')) {
        return this.$store.getters['room/filteredRooms']
      } else {
        return this.$store.getters['room/managerRooms']
      }
    },
    manager() {
      return this.$store.getters['room/manager']
    },
    members() {
      return this.$store.getters['room/members']
    },
  },
  methods: {
    loadRooms() {
      if (this.$auth.hasScope('admin')) {
        this.$store.dispatch('room/getAllRooms')
      } else {
        this.$store.dispatch('room/getManagerRooms')
      }
    },
    getRoomUsers(room) {
      if (this.id === room.id) {
        this.showUsers = false
        this.id = null
      } else {
        this.$store.dispatch('room/getRoomUsers', room.id)
        this.title = `${room.building.name}:${room.name}`
        this.id = room.id
        this.showUsers = true
      }
    },
  },
}
</script>

<style scoped>
main {
  width: 70%;
  margin: 4rem auto 4rem auto;
}

h2 {
  font-weight: 600;
  font-size: 1.5rem;
}

.title h3 {
  font-size: 1.3rem;
  margin: 2rem 0 0 0.5rem;
}

.rooms {
  display: flex;
}

.rooms-info {
  width: 60%;
}

.rooms-detail {
  display: flex;
  flex-direction: column;
}

.rooms-users {
  margin: 4rem 0 1rem 0;
  width: 40%;
}
</style>
