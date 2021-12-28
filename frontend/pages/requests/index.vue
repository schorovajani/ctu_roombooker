<template>
  <div>
    <h2>testing</h2>
    <p>{{ response }}</p>
  </div>
</template>

<script>
export default {
  async asyncData({ $axios }) {
    const response = await $axios.$get(
      `${$axios.defaults.baseURL}/api/canBeAccessed`
    )
    console.log(response)
    return {
      response,
    }
  },
}
</script>
