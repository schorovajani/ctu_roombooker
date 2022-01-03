<template>
  <article>
    <div class="left">
      <h4>Místnost {{ room.name }}</h4>
      <div class="info">
        <span><strong>Skupina:</strong> {{ room.team.name }}</span>
        <span><strong>Veřejná:</strong> {{ isPublic }}</span>
      </div>
      <div class="buttons">
        <button @click="showUsers">Členové</button>
        <nuxt-link :to="roomPath"><button>Rezervace</button></nuxt-link>
      </div>
    </div>
    <div class="right">
      <button class="edit">
        <img alt="Upravit" :src="require(`@/assets/UI/edit_icon.png`)" />
      </button>
    </div>

    <div v-if="showDetail" class="modal" @click="showDetail = false">
      <div class="modal-info">
        <button class="cross-icon" @click="showDetail = false">
          <img alt="cross icon" :src="require(`@/assets/UI/cross_icon.png`)" />
        </button>
        <h4>Místnost {{ room.name }}</h4>
        <div class="users">
          <span class="manager"><strong>Správce:</strong> {{ manager }} </span>
          <h5>Členové:</h5>
          <span class="members" v-if="members.length === 0">žádní</span>
          <div v-else class="members">
            <span v-for="member in members" :key="member.id">
              {{ member.firstName }} {{ member.lastName }}
            </span>
          </div>
        </div>
      </div>
    </div>
  </article>
</template>

<script>
export default {
  props: {
    room: Object,
  },
  data() {
    return {
      showDetail: false,
    }
  },
  computed: {
    isPublic() {
      return this.room.isPublic ? 'Ano' : 'Ne'
    },
    manager() {
      const manager = this.$store.getters['room/manager']
      return manager ? `${manager.firstName} ${manager.lastName}` : 'nezadán'
    },
    members() {
      return this.$store.getters['room/members']
    },
    roomPath() {
      return `/rooms/${this.room.id}`
    },
  },
  methods: {
    showUsers() {
      this.$store.dispatch('room/getRoomUsers', this.room.id)
      this.showDetail = true
      //console.log(this.manager)
      //console.log(this.members)
    },
  },
}
</script>

<style scoped>
article {
  border: solid 1px #505050;
  padding: 2rem;
  margin: 1rem;
  width: 33rem;
  display: flex;
  justify-content: space-between;
  background-color: #ffffff;
}

.info {
  display: flex;
  flex-direction: column;
  margin: 1rem;
}

.info span {
  margin-bottom: 0.8rem;
}

h4 {
  font-size: 1.1rem;
  font-weight: 600;
}

strong {
  font-weight: 600;
}

.buttons {
  display: flex;
}

.buttons button {
  margin: 0 1rem 0 1rem;
  padding: 0.5rem 1rem 0.5rem 1rem;
  background-color: #e6f5ff;
  border: solid 2px #b1d4df;
}

.edit {
  border: solid 2px #505050;
}

.edit img {
  height: 2rem;
  opacity: 0.9;
}

.modal {
  z-index: 1;
  position: fixed;
  top: 0;
  left: 0;
  margin: 0;
  padding: 0;
  height: 100%;
  width: 100%;
  display: flex;
  justify-content: center;
  align-items: center;
  background-color: #50505050;
}

.modal-info {
  padding: 2rem 3rem 4rem 4rem;
  background-color: #dcf9f4;
  display: flex;
  flex-direction: column;
}

.cross-icon {
  align-self: flex-end;
  margin-bottom: 1rem;
}

.cross-icon img {
  height: 1.5rem;
}

h5 {
  font-weight: 600;
  margin: 0.6rem 0 0.3rem 0;
}

.users {
  margin: 1rem;
}

.members {
  display: flex;
  flex-direction: column;
  align-items: center;
}

.members span {
  margin: 0.3rem;
  padding: 0.4rem;
  background-color: #b7dcef;
}
</style>
