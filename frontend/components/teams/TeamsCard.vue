<template>
  <article :id="team.id">
    <div class="info">
      <h3>{{ team.name }}</h3>
      <h4>Místnosti:</h4>
      <div class="rooms">
        <span v-for="room in team.rooms" :key="room.id">
          {{ room.building.name }}:{{ room.name }}
        </span>
      </div>
      <h4>Podskupiny:</h4>
      <div class="subteams">
        <a
          v-for="subteam in team.children"
          :key="subteam.id"
          :href="`#${subteam.id}`"
        >
          {{ subteam.name }}
        </a>
      </div>
    </div>
    <div class="buttons">
      <button @click="showUsers">Členové</button>
      <button>Upravit</button>
      <button @click="askDelete">Smazat</button>
    </div>
    <AlertWindow
      v-if="alert"
      @accepted="deleteTeam"
      @cancel="alert = false"
      :message="`Opravdu chcete skupinu ${team.name} smazat?`"
      btn1="Smazat"
      btn1Color="btn-red"
      btn2="Zrušit"
    />
  </article>
</template>

<script>
import AlertWindow from '../UI/AlertWindow.vue'
export default {
  components: { AlertWindow },
  props: {
    team: Object,
  },
  data() {
    return {
      alert: false,
    }
  },
  methods: {
    showUsers() {
      this.$emit('usersClick', this.team)
    },
    askDelete() {
      this.alert = true
    },
    deleteTeam() {
      this.$store.dispatch('team/deleteTeam', this.team.id)
      this.$emit('usersClick', this.team)
      this.alert = false
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
}

h3 {
  font-weight: 600;
  font-size: 1.3rem;
}

h4 {
  font-weight: 600;
  margin: 1.2rem 0 0.6rem 0;
}

.rooms {
  display: flex;
  justify-content: flex-start;
  flex-wrap: wrap;
}

.rooms span {
  padding: 0.3rem 0.6rem 0.3rem 0.6rem;
  margin: 0 0.5rem 0 0.5rem;
  background-color: #dcf9f4;
}

.subteams {
  display: flex;
  flex-direction: column;
  align-items: flex-start;
}

.subteams a {
  margin: 0.3rem;
  padding: 0.3rem 0.6rem 0.3rem 0.6rem;
  background-color: #fff0e6;
  border: solid 2px #dfbcb1;
}

.subteams a:hover {
  background-color: #dfbcb1;
}

.buttons {
  display: flex;
  flex-direction: column;
  align-items: flex-end;
}

.buttons button {
  margin: 0.3rem;
  padding: 0.3rem 0.6rem 0.3rem 0.6rem;
  background-color: #e6f5ff;
  border: solid 2px #b1d4df;
}
</style>
