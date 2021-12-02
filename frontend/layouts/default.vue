<template>
  <div class="page">
    <header>
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

        <nuxt-link class="nav-item" to="/auth/login"> Přihlásit se </nuxt-link>
        <nuxt-link class="nav-item" to="/auth/logout"> Odhlásit se </nuxt-link>
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
export default {}
</script>

<style>
@import url('https://fonts.googleapis.com/css2?family=Sorts+Mill+Goudy&display=swap');

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
  padding: 0rem 2rem 0 2rem;
}

h1 {
  font-family: 'Sorts Mill Goudy', serif;
  font-size: 2.5rem;
  padding: 0.5rem;
  margin-left: 3rem;
}

.nav-item {
  padding: 1.7rem;
}

.nav-item:hover {
  background-color: #b2d9ea;
}

.nav-item.nuxt-link-exact-active {
  background-color: #b2d9ea;
}

footer {
  flex-shrink: 0;
  width: 100%;
  background-color: #505050;
  color: #ffffff;
  text-align: right;
  padding: 1rem;
  display: flex;
  flex-direction: column;
}
</style>
