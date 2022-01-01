<template>
  <article>
    <h2>{{ name }}</h2>
    <div class="build-card">
      <ul v-if="showLess" class="build-list">
        <li v-for="room in rooms.slice(0, 6)" :key="room.id">
          <nuxt-link :to="'/rooms/' + room.id">{{ room.name }}</nuxt-link>
        </li>
      </ul>
      <ul v-else class="build-list">
        <li v-for="room in rooms" :key="room.id">
          <nuxt-link :to="'/rooms/' + room.id">{{ room.name }}</nuxt-link>
        </li>
      </ul>
      <button @click="showLess = !showLess">
        {{ showLess ? 'více' : 'méně' }} místností
      </button>
    </div>
  </article>
</template>

<script>
export default {
  props: {
    name: String,
    rooms: Array,
  },
  data() {
    return {
      showLess: true,
    }
  },
}
</script>

<style scoped>
article {
  margin: 3rem;
}
.build-card {
  border: solid 1px #505050;
  padding: 1rem;
  text-align: right;
}

.build-list {
  padding: 2rem;
  display: grid;
  grid-template-columns: 1fr 1fr;
  justify-items: center;
}

h2 {
  font-weight: 600;
  font-size: 1.3rem;
}

a {
  text-decoration: underline;
}

a:hover {
  color: #608394;
}

li {
  margin: 1rem;
}

button {
  text-decoration: underline;
  font-size: 0.9rem;
}

button:hover {
  color: #608394;
}
</style>
