<template>
  <main>
    <h2>Upravit rezervaci</h2>
    <RequestsForm :isManager="true" :request="request" />
  </main>
</template>

<script>
export default {
  async asyncData({ $axios, params }) {
    let request = {}
    try {
      request = await $axios.$get(
        `${$axios.defaults.baseURL}/requests/${params.id}`
      )
    } catch (error) {
      console.log(error)
      return
    }
    console.log(request)

    return {
      request,
    }
  },
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
