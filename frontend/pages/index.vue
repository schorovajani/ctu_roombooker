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
}
</script>

<style scoped>
main {
  display: flex;
}
</style>
