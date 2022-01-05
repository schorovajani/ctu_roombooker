<template>
  <section>
    <v-app>
      <div class="request-form">
        <v-form ref="form" v-model="valid" @submit.prevent="submit">
          <v-autocomplete
            v-if="isManager"
            v-model="manager"
            :items="users"
            item-text="lastName"
            item-value="id"
            label="Správce"
            clearable
          >
          </v-autocomplete>
          <v-radio-group v-model="choosenRoom">
            <template v-slot:label>
              <div>Místnost</div>
            </template>
            <v-radio
              v-for="room in rooms"
              :key="room.id"
              :label="`${room.building.name}:${room.name}`"
              :value="room.id"
            ></v-radio>
          </v-radio-group>
          <v-menu
            v-model="dateMenu"
            :close-on-content-click="false"
            :nudge-right="40"
            transition="scale-transition"
            offset-y
            min-width="auto"
          >
            <template v-slot:activator="{ on, attrs }">
              <v-text-field
                v-model="date"
                label="Datum"
                prepend-icon="mdi-calendar"
                readonly
                v-bind="attrs"
                v-on="on"
              ></v-text-field>
            </template>
            <v-date-picker
              v-model="date"
              @input="dateMenu = false"
              locale="cs-CZ"
            ></v-date-picker>
          </v-menu>
          <div class="time-pickers">
            <span>Počáteční čas</span>
            <v-time-picker v-model="startTime" format="24hr"></v-time-picker>
            <span>Konečný čas</span>
            <v-time-picker v-model="endTime" format="24hr"></v-time-picker>
          </div>
          <v-text-field label="Popis" v-model="description"></v-text-field>
          <v-radio-group v-if="edit" v-model="choosenStatus">
            <template v-slot:label>
              <div>Status</div>
            </template>
            <v-radio
              v-for="s in status"
              :key="s.id"
              :label="s.name"
              :value="s.id"
            ></v-radio>
          </v-radio-group>
          <v-autocomplete
            v-model="attendees"
            :items="users"
            item-text="lastName"
            item-value="id"
            label="Účastníci"
            chips
            deletable-chips
            multiple
          ></v-autocomplete>
          <v-btn type="submit">{{
            edit ? 'Upravit rezervaci' : 'Zarezervovat'
          }}</v-btn>
        </v-form>
        <v-calendar
          v-model="date"
          ref="calendar"
          v-if="showCalendar"
          :now="date"
          type="day"
          :events="events"
        ></v-calendar>
      </div>
    </v-app>
  </section>
</template>

<script>
import isManager from '~/middleware/isManager'
export default {
  created() {
    this.$store.dispatch('user/getUsers')
    this.$store.dispatch('room/getAllRooms')
    if (this.request) {
      this.$store.dispatch('room/getRoomRequests', this.request.room.id)
    }
  },
  props: {
    isManager: Boolean,
    request: Object,
  },
  data() {
    return {
      edit: this.request ? true : false,
      manager: this.request?.user.id || null,
      choosenRoom: this.request?.room.id || null,
      date: this.request
        ? new Date(this.request.eventStart).toISOString().slice(0, 10)
        : new Date().toISOString().slice(0, 10),
      startTime: this.request
        ? new Date(this.request.eventStart).toISOString().slice(11, 16)
        : '',
      endTime: this.request
        ? new Date(this.request.eventEnd).toISOString().slice(11, 16)
        : '',
      attendees: this.request?.attendees || [], // jen ids [1,2,3]
      description: this.request?.description || '',
      choosenStatus: this.request?.status.id || 1,
      showCalendar: this.request ? true : false,
      dateMenu: false,
      valid: false,
      status: [
        {
          id: 1,
          name: 'nevyřízeno',
        },
        {
          id: 2,
          name: 'potvrdit',
        },
        {
          id: 3,
          name: 'zamítnout',
        },
      ],
    }
  },
  mounted() {
    // this.$refs.calendar.checkChange()
  },
  computed: {
    users() {
      return this.$store.getters['user/users']
    },
    rooms() {
      return this.$store.getters['room/rooms']
    },
    events() {
      return this.$store.getters['room/roomRequests']
    },
  },
  watch: {
    choosenRoom() {
      this.$store.dispatch('room/getRoomRequests', this.choosenRoom)
      this.showCalendar = true
    },
  },
  methods: {
    submit() {
      console.log(this.startTime)
      console.log(this.attendees)
      if (!this.isManager) {
        this.manager = this.$auth.user.id
      }
      let att = []
      this.attendees.forEach((aid) => {
        if (this.edit) {
          att.push({ id: aid.id })
        } else {
          att.push({ id: aid })
        }
      })
      console.log(att)
      if (this.edit) {
        this.$store.dispatch('request/editRequest', {
          id: this.request.id,
          data: {
            description: this.description,
            eventStart: new Date(
              `${this.date} ${this.startTime}`
            ).toISOString(),
            eventEnd: new Date(`${this.date} ${this.endTime}`).toISOString(),
            room: { id: this.choosenRoom },
            user: { id: this.manager },
            attendees: this.attendees,
            status: { id: this.choosenStatus },
          },
        })
      } else {
        this.$store.dispatch('request/postRequest', {
          description: this.description,
          eventStart: new Date(`${this.date} ${this.startTime}`).toISOString(),
          eventEnd: new Date(`${this.date} ${this.endTime}`).toISOString(),
          room: { id: this.choosenRoom },
          user: { id: this.manager },
          attendees: att,
        })
      }
      if (this.isManager || this.edit) {
        this.$router.replace('/requests')
      } else {
        this.$router.replace('/my-requests')
      }
    },
  },
}
</script>

<style scoped>
.request-form {
  display: flex;
  flex-direction: row;
}

.v-form,
.v-calendar {
  margin: 1rem;
  width: 50%;
}

.time-pickers {
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
}

.time-pickers span {
  margin: 4rem 0 2rem 0;
  font-weight: 600;
}
</style>
