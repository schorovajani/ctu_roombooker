<template>
  <section class="rooms-list">
    <RoomsCard
      v-for="build in buildings"
      :key="build.id"
      :name="build.name"
      :rooms="build.rooms"
    />
  </section>
</template>

<script>
import RoomsCard from '~/components/rooms/RoomsCard.vue'
export default {
  components: { RoomsCard },
  computed: {
    buildings() {
      return this.$store.getters['building/buildings']
    },
  },
}
</script>

<style scoped>
.rooms-list {
  padding: 4rem;
  display: flex;
  flex-wrap: wrap;
}
</style>
