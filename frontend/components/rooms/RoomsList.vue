<template>
  <section class="rooms-list">
    <RoomsCard />
    <RoomsCard />
    <RoomsCard />
    <RoomsCard />
    <RoomsCard />
    <RoomsCard />
  </section>
</template>

<script>
import RoomsCard from '~/components/rooms/RoomsCard.vue'
export default {
  components: { RoomsCard },
}
</script>

<style scoped>
.rooms-list {
  padding: 4rem;
  display: flex;
  flex-wrap: wrap;
}
</style>
