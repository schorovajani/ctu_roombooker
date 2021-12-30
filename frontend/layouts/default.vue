<template>
  <div class="page">
    <header>
      <img class="header-polygon" src="~/assets/UI/light_polygon.png" />
      <nuxt-link to="/"><h1>RoomBooker</h1></nuxt-link>
      <nav>
        <nuxt-link class="nav-item" to="/"> Domů </nuxt-link>
        <!-- přihlášený uživatel -->
        <nuxt-link class="nav-item" to="/requests/user">
          Mé rezervace
        </nuxt-link>
        <!-- správce místnosti, skupiny, administrátor -->
        <nuxt-link class="nav-item" to="/requests"> Rezervace </nuxt-link>
        <!-- správce skupiny, administrátor -->
        <nuxt-link class="nav-item" to="/rooms"> Místnosti </nuxt-link>
        <!-- administrátor -->
        <nuxt-link class="nav-item" to="/teams"> Skupiny </nuxt-link>

        <nuxt-link class="nav-item" to="/login"> Přihlásit se </nuxt-link>
        <button class="nav-item" @click="logout">Odhlásit se</button>
      </nav>
    </header>
    <Nuxt />
    <footer>
      <span>Semestrální práce BI-TWA</span>
      <span>Tým Nováčci</span>
    </footer>
  </div>
</template>

<script>
export default {
  methods: {
    async logout() {
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

.nav-item {
  padding: 1.4rem;
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
</style>
