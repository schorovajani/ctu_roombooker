<template>
  <main>
    <div class="list-header">
      <nuxt-link class="new-request" to="/requests/new">
        <button class="btn-blue">
          Nová rezervace<br />pro jiného uživatele
        </button>
      </nuxt-link>
      <h2>Rezervace</h2>
    </div>
    <section>
      <div v-for="room in filteredRequests" :key="room.id">
        <h3>{{ room.roomName }}</h3>
        <RequestsRow
          v-for="request in room.requests"
          :key="request.id"
          :request="request"
        />
      </div>
    </section>
  </main>
</template>

<script>
export default {
  middleware: 'isManager',
  created() {
    this.loadRequests()
  },
  computed: {
    filteredRequests() {
      return this.$store.getters['request/filteredRequests']
    },
  },
  methods: {
    loadRequests() {
      this.$store.dispatch('request/getAllRequests')
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

h3 {
  font-weight: 600;
  font-size: 1.2rem;
  margin: 3rem 0 0 0;
}

.list-header {
  display: flex;
  justify-content: space-between;
  flex-direction: row-reverse;
  align-items: flex-end;
}

.new-request button {
  padding: 1rem;
}
</style>
