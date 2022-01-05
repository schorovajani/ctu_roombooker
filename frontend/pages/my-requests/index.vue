<template>
  <main>
    <div class="list-header">
      <nuxt-link class="new-request" to="/my-requests/new">
        <button class="btn-blue">Nová rezervace</button>
      </nuxt-link>
      <h2>Moje rezervace</h2>
    </div>
    <section>
      <RequestsCard
        v-for="request in requests"
        :key="request.id"
        :request="request"
      />
    </section>
  </main>
</template>

<script>
import RequestCard from '~/components/requests/RequestsCard.vue'
export default {
  components: { RequestCard },
  middleware: ['auth'],
  created() {
    this.loadRequests()
  },
  computed: {
    requests() {
      console.log(this.$store.getters['request/myRequests'])
      return this.$store.getters['request/myRequests']
    },
  },
  methods: {
    loadRequests() {
      this.$store.dispatch('request/getMyRequests')
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

section {
  display: flex;
  flex-wrap: wrap;
  justify-content: space-between;
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
