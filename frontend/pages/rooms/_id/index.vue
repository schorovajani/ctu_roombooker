<template>
  <section>
    <div>
      <h2>{{ building }}:{{ room }}</h2>
      <div>
        <button>left</button>
        <span>{{ month }}</span>
        <button>right</button>
      </div>
    </div>
    <v-app>
      <v-calendar
        ref="calendar"
        type="week"
        :now="today"
        color="color"
        :weekdays="weekdays"
        :events="events"
      ></v-calendar>
    </v-app>
  </section>
</template>

<script>
export default {
  data() {
    return {
      building: 'T9',
      room: '343',
      now: new Date(),
      weekdays: [1, 2, 3, 4, 5, 6, 0],
      color: 'red',
      events: [
        {
          name: 'title1',
          start: '2021-12-30 09:00',
          end: '2021-12-30 11:00',
        },
        {
          name: 'title2',
          start: '2021-12-31 14:00',
          end: '2021-12-31 15:00',
        },
      ],
    }
  },
  computed: {
    today() {
      return this.now.toISOString().substring(0, 10)
    },
    month() {
      return this.now.getMonth()
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
