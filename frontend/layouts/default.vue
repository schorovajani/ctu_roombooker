<template>
  <div>
    <header>
      <nuxt-link to="/"><h1>RoomBooker</h1></nuxt-link>
      <nav>
        <nuxt-link to="/">Domů</nuxt-link>
        <!-- přihlášený uživatel -->
        <nuxt-link to="/user/requests">Mé rezervace</nuxt-link>
        <!-- správce místnosti, skupiny, administrátor -->
        <nuxt-link to="/requests">Rezervace</nuxt-link>
        <!-- správce skupiny, administrátor -->
        <nuxt-link to="/rooms">Místnosti</nuxt-link>
        <!-- administrátor -->
        <nuxt-link to="/teams">Skupiny</nuxt-link>

        <nuxt-link to="/auth/login">Přihlásit se</nuxt-link>
        <nuxt-link to="/auth/logout">Odhlásit se</nuxt-link>
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
