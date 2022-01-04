<template>
  <article>
    <h3>{{ request.description }}</h3>
    <div class="request-info">
      <div class="request-info-detail">
        <span>Místnost:</span>
        <span>Datum:</span>
        <span>Čas:</span>
        <span>Status:</span>
      </div>
      <div class="request-info-detail">
        <span>{{ request.room.building.name + ':' + request.room.name }}</span>
        <span>{{ requestDate }}</span>
        <span>{{ timeString }}</span>
        <span>{{ statusValue }}</span>
      </div>
    </div>
    <div class="buttons">
      <button
        v-if="isCreator"
        class="delete-btn btn-red"
        @click="askDeleteRequest"
      >
        <img alt="Vymazat" :src="require(`@/assets/UI/delete_icon.png`)" />
      </button>
      <button class="more-info" @click="showDetail = true">
        Více informací
      </button>
    </div>
    <ModalWindow v-if="showDetail" @hideModal="showDetail = false">
      <h3>{{ request.description }}</h3>
      <div class="request-info">
        <div class="request-info-detail">
          <span>Místnost:</span>
          <span>Datum:</span>
          <span>Čas:</span>
          <span>Status:</span>
          <span>Účastníci:</span>
          <span>Rezervaci vytvořil:</span>
        </div>
        <div class="request-info-detail">
          <span>{{
            request.room.building.name + ':' + request.room.name
          }}</span>
          <span>{{ requestDate }}</span>
          <span>{{ timeString }}</span>
          <span>{{ statusValue }}</span>
          <div class="attendees">
            <span v-for="attendee in request.attendees" :key="attendee.id">
              {{ attendee.firstName }} {{ attendee.lastName }}
            </span>
            <span>
              {{ request.user.firstName }} {{ request.user.lastName }}
            </span>
          </div>
          <span>
            {{ request.user.firstName }} {{ request.user.lastName }}
          </span>
        </div>
      </div>
    </ModalWindow>
    <AlertWindow
      v-if="alert"
      @accepted="deleteRequest"
      @cancel="alert = false"
      :message="`Opravdu chcete rezervaci ${request.description} smazat?`"
      btn1="Smazat"
      btn2="Zrušit"
    />
  </article>
</template>

<script>
import AlertWindow from '~/components/UI/AlertWindow.vue'
import ModalWindow from '~/components/UI/ModalWindow.vue'

export default {
  components: { AlertWindow, ModalWindow },
  props: {
    request: Object,
  },
  data() {
    return {
      showDetail: false,
      alert: false,
    }
  },
  computed: {
    requestDate() {
      const date = new Date(this.request.eventStart)
      return date.toLocaleDateString('cs-CZ')
    },
    timeString() {
      const start = new Date(this.request.eventStart)
      const end = new Date(this.request.eventEnd)
      return `${start.toLocaleTimeString('cs-CZ')} - ${end.toLocaleTimeString(
        'cs-CZ'
      )}`
    },
    statusValue() {
      if (this.request.status.name === 'Rejected') {
        return 'Zamítnuta'
      } else if (this.request.status.name === 'Approved') {
        return 'Potvrzena'
      } else if (this.request.status.name === 'Pending') {
        return 'Nevyřízena'
      }
    },
    isCreator() {
      return this.request.user.id === this.$auth.user.id
    },
  },
  methods: {
    askDeleteRequest() {
      this.alert = true
    },
    deleteRequest() {
      this.$store.dispatch('request/deleteMyRequest', this.request.id)
      this.alert = false
    },
  },
}
</script>

<style scoped>
article {
  width: 30rem;
  margin: 2rem;
  border: solid 1px #505050;
  padding: 3rem 3rem 2rem 3rem;
  display: flex;
  flex-direction: column;
}

h3 {
  font-weight: 600;
  font-size: 1.1rem;
}

.request-info {
  margin: 1rem 0 0 0;
  display: flex;
}

.request-info-detail {
  display: flex;
  flex-direction: column;
  margin: 0.5rem;
}
.request-info-detail span {
  margin: 0.5rem;
}

.buttons {
  margin-top: 1rem;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.delete-btn img {
  height: 2rem;
  opacity: 0.8;
}

.more-info {
  margin: 0.5rem 0 0 0;
  /* justify-self: flex-end; */
  justify-self: end;
  text-decoration: underline;
  color: #77908e;
}

button:hover {
  color: #bee5e2;
}
.attendees {
  margin: 0.5rem 0 0.5rem 0;
}

.attendees span {
  padding: 0.4rem;
  background-color: #b7dcef;
}
</style>
