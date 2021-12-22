<template>
  <article>
    <h2>{{ building }}</h2>
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
        {{ showLess ? 'více' : 'méně' }}
      </button>
    </div>
  </article>
</template>

<script>
export default {
  data() {
    return {
      showLess: true,
      building: 'T9',
      rooms: [
        {
          id: 1,
          name: '343',
        },
        {
          id: 2,
          name: '344',
        },
        {
          id: 3,
          name: '345',
        },
        {
          id: 4,
          name: '346',
        },
        {
          id: 5,
          name: '347',
        },
        {
          id: 6,
          name: '348',
        },
        {
          id: 7,
          name: '349',
        },
      ],
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
  padding: 0.5rem;
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

li {
  margin: 1rem;
}
</style>
