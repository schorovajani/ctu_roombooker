<template>
  <main>
    <h2>Skupiny</h2>
    <div class="teams">
      <section class="teams-info">
        <TeamsCard
          @usersClick="getTeamUsers"
          v-for="team in teams"
          :key="team.id"
          :team="team"
        />
      </section>
      <section v-if="showUsers" class="teams-users">
        <UserOf
          type="team"
          :name="title"
          :manager="manager"
          :members="members"
        />
      </section>
    </div>
  </main>
</template>

<script>
import TeamsCard from '~/components/teams/TeamsCard.vue'
import UserOf from '~/components/users/UsersOf.vue'

export default {
  middleware: ['isAdmin'],
  components: { TeamsCard, UserOf },
  data() {
    return {
      title: '',
      id: null,
      showUsers: false,
    }
  },
  created() {
    this.loadTeams()
  },
  computed: {
    teams() {
      return this.$store.getters['team/teams']
    },
    manager() {
      return this.$store.getters['team/manager']
    },
    members() {
      return this.$store.getters['team/members']
    },
  },
  methods: {
    loadTeams() {
      this.$store.dispatch('team/getAllTeams')
      //this.$store.dispatch('team/getTeamUsers', 3)
    },
    getTeamUsers(team) {
      if (this.id === team.id) {
        this.showUsers = false
        this.id = null
      } else {
        this.$store.dispatch('team/getTeamUsers', team.id)
        this.title = team.name
        this.id = team.id
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

.teams {
  display: flex;
}

.teams-info {
  width: 60%;
}

.teams-users {
  margin: 1rem 0 1rem 0;
  width: 40%;
}
</style>
