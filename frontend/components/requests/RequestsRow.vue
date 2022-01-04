<template>
  <article>
    <div class="small-info">
      <span>{{ date }}</span>
      <span>{{ time }}</span>
      <span>{{ userName }}</span>
      <!-- <span>{{ request.description }}</span> -->
      <span>{{ status }}</span>
      <div class="buttons">
        <button v-if="isPending" class="btn-green">Schválit</button>
        <button v-if="isPending" class="btn-red">Zamítnout</button>
        <button v-if="less" class="btn-more" @click="less = false">
          <img
            alt="Ukázat detail"
            :src="require(`@/assets/UI/left_icon.png`)"
          />
        </button>
        <button v-else class="btn-more" @click="less = true">
          <img alt="Skrýt detail" :src="require(`@/assets/UI/down_icon.png`)" />
        </button>
      </div>
    </div>
    <hr v-if="!less" />
    <div v-if="!less" class="more-info">
      <div>
        <h4>{{ request.description }}</h4>
        <div class="info">
          <div class="info-left">
            <span>Datum:</span>
            <span>Čas:</span>
            <span>Status:</span>
            <span>Zadal:</span>
            <span>Účastníci: </span>
          </div>
          <div class="info-right">
            <span>{{ date }}</span>
            <span>{{ time }}</span>
            <span>{{ status }}</span>
            <span>{{ userName }}</span>
            <div class="attendees">
              <span v-for="attendee in request.attendees" :key="attendee.id"
                >{{ attendee.firstName }} {{ attendee.lastName }}</span
              >
            </div>
          </div>
        </div>
      </div>
      <div class="icons">
        <button>Upravit</button>
        <button>Smazat</button>
      </div>
    </div>
  </article>
</template>

<script>
export default {
  props: {
    request: Object,
  },
  data() {
    return {
      less: true,
    }
  },
  computed: {
    date() {
      const date = new Date(this.request.eventStart)
      return date.toLocaleDateString('cs-CZ')
    },
    time() {
      const start = new Date(this.request.eventStart)
      const end = new Date(this.request.eventEnd)
      return `${start.toLocaleTimeString('cs-CZ').slice(0, 5)} - ${end
        .toLocaleTimeString('cs-CZ')
        .slice(0, 5)}`
    },
    userName() {
      return `${this.request.user.firstName} ${this.request.user.lastName}`
    },
    status() {
      if (this.request.status.name === 'Rejected') {
        return 'Zamítnuta'
      } else if (this.request.status.name === 'Approved') {
        return 'Potvrzena'
      } else if (this.request.status.name === 'Pending') {
        return 'Nevyřízena'
      }
    },
    isPending() {
      return this.request.status.name === 'Pending'
    },
  },
}
</script>

<style scoped>
article {
  border: solid 1px #505050;
  padding: 2rem;
  margin: 1rem;
}

.small-info {
  display: flex;
  justify-content: space-between;
  align-items: center;
}
hr {
  margin: 2rem 0 2rem 0;
  opacity: 0.5;
}
.buttons {
  display: flex;
  justify-content: space-between;
}

.btn-more img {
  height: 2.5rem;
}

.more-info {
  display: flex;
  justify-content: space-between;
}

.btn-green,
.btn-red {
  margin: 0 0.5rem 0 0.5rem;
  padding: 0 1rem 0 1rem;
}

h4 {
  font-weight: 600;
}

.icons {
  display: flex;
  flex-direction: column;
}

.icons button {
  margin: 0.5rem;
  padding: 0.6rem 1rem 0.6rem 1rem;
  background-color: #e6f5ff;
  border: solid 2px #b1d4df;
}

.info {
  margin: 1rem;
  display: flex;
}

.info-left,
.info-right {
  display: flex;
  flex-direction: column;
}

.info-left span,
.info-right span {
  margin: 1rem 0.5rem 1rem 0.5rem;
}

.attendees {
  margin-top: 1rem;
}

.attendees span {
  background-color: #e6f5ff;
  padding: 0.5rem;
}
</style>
