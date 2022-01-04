<template>
  <section>
    <div>
      <h2>{{ room.name }}</h2>
      <div>
        <button @click="prev">left</button>
        <span>{{ month }}</span>
        <button @click="next">right</button>
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
      focus: '',
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
      const test = new Date()
      return test.getMonth()
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
  margin: 10rem auto;
  width: 90%;
}
</style>
