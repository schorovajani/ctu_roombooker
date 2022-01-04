<template>
  <div class="users-card">
    <h4>{{ title }}</h4>
    <div class="users">
      <span class="manager"><strong>Správce:</strong> {{ managerName }}</span>
      <h5>Členové:</h5>
      <div class="members">
        <span v-for="member in members" :key="member.id">
          {{ member.firstName }} {{ member.lastName }}
        </span>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    type: String,
    name: String,
    manager: Object,
    members: Array,
  },
  computed: {
    managerName() {
      return this.manager
        ? `${this.manager.firstName} ${this.manager.lastName}`
        : 'nezadán'
    },
    title() {
      if (this.type === 'room') {
        return `Místnost ${this.name}`
      } else if (this.type === 'team') {
        return `Skupina ${this.name}`
      }
    },
  },
}
</script>

<style scoped>
.users-card {
  border: solid 1px #505050;
  position: sticky;
  top: 1rem;
  padding: 1.5rem;
}

h4 {
  font-weight: 600;
  font-size: 1.1rem;
}

.users {
  margin: 1.3rem 0.6rem 0.6rem 0.6rem;
}

strong {
  font-weight: 600;
}

h5 {
  font-weight: 600;
  margin: 1rem 0 0.6rem 0;
}
.members {
  display: flex;
  flex-wrap: wrap;
  justify-content: space-around;
  align-items: center;
}

.members span {
  padding: 0.3rem 0.6rem 0.3rem 0.6rem;
  margin: 0.2rem;
  background-color: #dcf9f4;
}
</style>
