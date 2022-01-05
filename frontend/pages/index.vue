<template>
  <main>
    <section class="aside">
      <AsideBar />
    </section>
    <RoomsList />
  </main>
</template>

<script>
import AsideBar from '~/components/UI/AsideBar.vue'
import RoomsList from '~/components/rooms/RoomsList.vue'

export default {
  components: { AsideBar, RoomsList },
  created() {
    this.loadBuildings()
  },
  methods: {
    loadBuildings() {
      this.$store.dispatch('building/getAllBuildings')
    },
  },
}
</script>

<style scoped>
main {
  display: flex;
}
</style>
