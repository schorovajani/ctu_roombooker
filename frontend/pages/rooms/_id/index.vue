<template>
  <section>
    <div class="info">
      <h2>{{ room.name }}</h2>
      <div class="buttons">
        <button @click="prev">
          <img alt="prev" :src="require(`@/assets/UI/left_icon.png`)" />
        </button>
        <span>{{ month }}</span>
        <button @click="next">
          <img alt="next" :src="require(`@/assets/UI/right_icon.png`)" />
        </button>
      </div>
    </div>
    <v-app>
      <v-calendar
        ref="calendar"
        v-model="focus"
        :events="events"
        event-color="#4c6978"
        type="week"
        :weekdays="weekdays"
        locale="cs-CZ"
        :light="true"
      ></v-calendar>
    </v-app>
  </section>
</template>

<script>
export default {
  created() {
    this.loadData()
  },
  mounted() {
    this.$refs.calendar.checkChange()
  },
  data() {
    return {
      focus: '2022-01-05',
      weekdays: [1, 2, 3, 4, 5, 6, 0],
      // events: [
      //   {
      //     name: 'title2',
      //     start: '2022-01-03 14:00',
      //     end: '2022-01-03 15:00',
      //   },
      // ],
    }
  },
  computed: {
    month() {
      const test = new Date(this.focus)
      const i = test.getMonth()
      switch (i) {
        case 0:
          return 'Leden'
        case 1:
          return 'Únor'
        case 2:
          return 'Březen'
        case 3:
          return 'Duben'
        case 4:
          return 'Květen'
        case 5:
          return 'Červen'
        case 6:
          return 'Červenec'
        case 7:
          return 'Srpen'
        case 8:
          return 'Září'
        case 9:
          return 'Říjen'
        case 10:
          return 'Listopad'
        case 11:
          return 'Prosinec'
        default:
          return '...'
      }
    },
    room() {
      return this.$store.getters['room/room']
    },
    events() {
      return this.$store.getters['room/roomRequests']
    },
  },
  methods: {
    loadData() {
      this.$store.dispatch('room/getRoom', this.$route.params.id)
      this.$store.dispatch('room/getRoomRequests', this.$route.params.id)
    },
    prev() {
      this.$refs.calendar.prev()
    },
    next() {
      this.$refs.calendar.next()
    },
  },
}
</script>

<style scoped>
section {
  margin: 4rem auto;
  width: 70%;
}

h2 {
  font-weight: 600;
  font-size: 1.5rem;
}

.buttons img {
  height: 1.5rem;
}
.info {
  display: flex;
  justify-content: space-between;
  margin-bottom: 1rem;
}
</style>
