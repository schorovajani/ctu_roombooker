<template>
  <main>
    <h2>Nová rezervace</h2>
    <RequestsForm :isManager="true" />
  </main>
</template>

<script>
import RequestsForm from '~/components/requests/RequestsForm.vue'
export default {
  components: { RequestsForm },
}
</script>

<style scoped>
main {
  width: 70%;
  margin: 4rem auto 4rem auto;
  border: solid 1px #505050;
  padding: 2rem;
  background-color: #ffffff;
}

h2 {
  font-weight: 600;
  font-size: 1.5rem;
}
</style>
