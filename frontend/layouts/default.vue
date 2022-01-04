<template>
  <div class="page">
    <header>
      <img class="header-polygon" src="~/assets/UI/light_polygon.png" />
      <nuxt-link to="/"><h1>RoomBooker</h1></nuxt-link>
      <nav>
        <nuxt-link class="nav-item" to="/"> Domů </nuxt-link>
        <!-- přihlášený uživatel -->
        <nuxt-link v-if="isAuthenticated" class="nav-item" to="/my-requests">
          Mé rezervace
        </nuxt-link>
        <!-- správce místnosti, skupiny, administrátor -->
        <nuxt-link v-if="isManagerOrAdmin" class="nav-item" to="/requests">
          Rezervace
        </nuxt-link>
        <!-- správce skupiny, administrátor -->
        <nuxt-link v-if="isGroupManagerOrAdmin" class="nav-item" to="/rooms">
          Místnosti
        </nuxt-link>
        <!-- administrátor -->
        <nuxt-link v-if="isAdmin" class="nav-item" to="/teams">
          Skupiny
        </nuxt-link>

        <nuxt-link v-if="!isAuthenticated" class="nav-item" to="/login">
          Přihlásit se
        </nuxt-link>
        <button v-if="isAuthenticated" class="nav-item" @click="logout">
          Odhlásit se
        </button>
      </nav>
    </header>
    <Nuxt />
    <AlertBar />
    <footer>
      <span>Semestrální práce BI-TWA</span>
      <span>Tým Nováčci</span>
    </footer>
  </div>
</template>

<script>
import AlertBar from '~/components/UI/AlertBar.vue'
export default {
  components: { AlertBar },
  computed: {
    isAuthenticated() {
      return this.$auth.loggedIn
    },
    isManagerOrAdmin() {
      return (
        this.$auth.hasScope('admin') ||
        this.$auth.hasScope('roomManager') ||
        this.$auth.hasScope('groupManager')
      )
    },
    isGroupManagerOrAdmin() {
      return this.$auth.hasScope('admin') || this.$auth.hasScope('groupManager')
    },
    isAdmin() {
      return this.$auth.hasScope('admin')
    },
  },
  methods: {
    async logout() {
      this.$store.dispatch('building/getAllBuildings') //reload rooms
      await this.$auth.logout()
      console.log('odhlaseno')
    },
  },
}
//     <img class="header-polygon" src="~/assets/UI/light_polygon.png" />
</script>

<style>
@import url('https://fonts.googleapis.com/css2?family=Sorts+Mill+Goudy&display=swap');
@import '~/assets/reset.css';

/* 
blue-nav: #e6f5ff //very light blue
blue-polygon: #b7dcef
blue-dark: #768e9a // dark blue polygon
green-aside: #dcf9f4
green-polygon: #bee5e2
green-dark: #77908e //dark green polygon
grey font, border: #505050
*/

html,
#__nuxt,
#__layout {
  height: 100%;
}

body {
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  color: #505050;
  background-color: #fcfdff;
  height: 100%;
}

a {
  text-decoration: none;
  color: #505050;
}

.page {
  height: 100%;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
}

header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  background-color: #e6f5ff;
  padding: 0rem 2rem 0rem 2rem;
}

.header-polygon {
  position: absolute;
  top: 0;
  left: 1.5rem;
  width: 20%;
}

h1 {
  position: absolute;
  top: 1.5rem;
  font-family: 'Sorts Mill Goudy', serif;
  font-size: 2.2vw;
  margin-left: 3rem;
}
nav {
  height: 4rem;
  display: flex;
  justify-content: space-between;
  align-items: center;
}
.nav-item {
  height: 100%;
  padding: 1.5rem;
}

.nav-item:hover {
  background-color: #b7dcef;
}

.nav-item.nuxt-link-exact-active {
  background-color: #b7dcef;
}

footer {
  flex-shrink: 0;
  background-color: #505050;
  color: #ffffff;
  text-align: right;
  padding: 1rem;
  display: flex;
  flex-direction: column;
}

.btn-red {
  background-color: #f9dce1;
  border: #d8a7bf 2px solid;
}

.btn-red:hover {
  background-color: #d8a7bf;
}

.btn-green {
  background-color: #dcf9f4;
  border: #a7d8c0 2px solid;
}

.btn-green:hover {
  background-color: #a7d8c0;
}

.btn-blue {
  background-color: #e6f5ff;
  border: solid 2px #b1d4df;
}

.btn-blue:hover {
  background-color: #b1d4df;
}

.btn-orange {
  background-color: #fff0e6;
  border: solid 2px #dfbcb1;
}

.btn-orange:hover {
  background-color: #dfbcb1;
}

.btn-white {
  background-color: #f9f9f9;
  border: solid 2px #c6c8cb;
}

.btn-white:hover {
  background-color: #c6c8cb;
}
</style>
