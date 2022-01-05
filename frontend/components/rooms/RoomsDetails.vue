<template>
  <article>
    <div class="left">
      <h4>Místnost {{ room.name }}</h4>
      <div class="info">
        <span><strong>Skupina:</strong> {{ room.team.name }}</span>
        <span><strong>Veřejná:</strong> {{ isPublic }}</span>
      </div>
      <div class="buttons">
        <nuxt-link :to="roomPath"
          ><button class="btn-orange">Rezervace</button></nuxt-link
        >
      </div>
    </div>
    <div class="right">
      <button class="btn-blue" @click="showUsers">Členové</button>
      <button class="btn-blue">Upravit</button>
      <button v-if="isAdmin" class="btn-blue" @click="askDeleteRoom">
        Smazat
      </button>
    </div>
    <AlertWindow
      v-if="alert"
      @accepted="deleteRoom"
      @cancel="alert = false"
      :message="`Opravdu chcete místnost ${room.building.name}:${room.name} smazat?`"
      btn1="Smazat"
      btn2="Zrušit"
    />
  </article>
</template>

<script>
import AlertWindow from '../UI/AlertWindow.vue'
export default {
  components: { AlertWindow },
  props: {
    room: Object,
  },
  data() {
    return {
      alert: false,
    }
  },
  computed: {
    isPublic() {
      return this.room.isPublic ? 'Ano' : 'Ne'
    },
    roomPath() {
      return `/rooms/${this.room.id}`
    },
  },
  methods: {
    showUsers() {
      this.$emit('usersClick', this.room)
    },
    askDeleteRoom() {
      this.alert = true
    },
    deleteRoom() {
      this.$store.dispatch('room/deleteRoom', this.room.id)
      this.$emit('usersClick', this.room)
      this.alert = false
    },
    isAdmin() {
      return this.$auth.hasScope('admin')
    },
  },
}
</script>

<style scoped>
article {
  border: solid 1px #505050;
  padding: 2rem;
  margin: 1rem;
  display: flex;
  justify-content: space-between;
  background-color: #ffffff;
}

.info {
  display: flex;
  flex-direction: column;
  margin: 1rem;
}

.info span {
  margin-bottom: 0.8rem;
}

h4 {
  font-size: 1.1rem;
  font-weight: 600;
}

strong {
  font-weight: 600;
}

.buttons {
  display: flex;
}

.buttons button {
  margin: 0 1rem 0 1rem;
  padding: 0.5rem 1rem 0.5rem 1rem;
}

.edit {
  border: solid 2px #505050;
}

.edit img {
  height: 2rem;
  opacity: 0.9;
}

.right {
  display: flex;
  flex-direction: column;
  align-items: flex-end;
}

.right button {
  margin: 0.3rem;
  padding: 0.3rem 0.6rem 0.3rem 0.6rem;
}
</style>
