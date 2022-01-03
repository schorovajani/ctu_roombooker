<template>
  <main>
    <h2>Místnosti</h2>
    <section>
      <div class="building" v-for="build in buildings" :key="build.id">
        <h3>{{ build.buildName }}</h3>
        <div class="rooms">
          <RoomsDetail
            v-for="room in build.rooms"
            :key="room.id"
            :room="room"
          />
        </div>
      </div>
    </section>
  </main>
</template>

<script>
import RoomsDetail from '~/components/rooms/RoomsDetails.vue'
export default {
  components: { RoomsDetail },
  middleware: ['isGroupManager'],
  created() {
    this.loadRooms()
  },
  computed: {
    buildings() {
      return this.$store.getters['room/filteredRooms']
    },
    members() {
      return [{ user: 'JAJA' }]
    },
  },
  methods: {
    loadRooms() {
      this.$store.dispatch('room/getAllRooms')
    },
  },
}
</script>

<style scoped>
main {
  width: 60%;
  margin: 4rem auto 4rem auto;
}

h2 {
  font-weight: 600;
  font-size: 1.5rem;
}

.building h3 {
  font-size: 1.3rem;
  margin: 2rem 0 0 0.5rem;
}

.rooms {
  display: flex;
  flex-wrap: wrap;
}
</style>
