<template></template>

<script>
export default {
  async asyncData(context) {},
  computed: {
    roomId() {
      return this.$route.params.id
    },
  },
}
</script>
