<template>
  <div class="aside-bar">
    <!--<img class="aside-background" src="~/assets/UI/aside.png" />-->
    <p class="aside-motto">Chceš se učit s přáteli?</p>
    <p id="aside-motto-2" class="aside-motto">Potřebuješ si zavolat?</p>
    <p id="aside-motto-3" class="aside-motto">Chceš jen chvíli klid?</p>
    <p class="aside-motto">Zarezervuj si místnost!</p>
    <div class="aside-text">
      <p>Chceš vidět všechny volné místnosti?</p>
      <nuxt-link class="aside-link" to="/login"> Přihlaš se! </nuxt-link>
    </div>
  </div>
</template>

<script>
export default {}
</script>

<style scoped>
.aside-bar {
  height: 903px;
  width: 515px;
  background-image: url('~/assets/UI/aside.png');
  margin: 3rem 0 2rem 0;
  padding: 5rem 10rem 7rem 2rem;
  position: relative;
  display: flex;
  flex-direction: column;
  justify-content: space-around;
  align-items: center;
}
.aside-motto {
  font-family: 'Sorts Mill Goudy', serif;
  font-size: 1.4rem;
}

#aside-motto-2 {
  align-self: flex-start;
}

#aside-motto-3 {
  align-self: flex-end;
}

.aside-text {
  display: flex;
  flex-direction: column;
  align-items: center;
}

.aside-text p {
  margin-bottom: 0.5rem;
}

.aside-text a {
  text-decoration: underline;
}
</style>
