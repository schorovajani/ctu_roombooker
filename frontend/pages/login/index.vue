<template>
  <section>
    <h2>Přihlásit se</h2>
    <v-form ref="loginForm" v-model="valid">
      <v-text-field
        :rules="userRules"
        v-model="username"
        label="Uživatelské jméno"
        required
      ></v-text-field>
      <v-text-field
        :append-icon="show ? 'mdi-eye' : 'mdi-eye-off'"
        :rules="passRules"
        :type="show ? 'text' : 'password'"
        label="Heslo"
        v-model="password"
        @click:append="show = !show"
      ></v-text-field>
      <v-btn color="#ffffff" @click="submit"> Přihlásit se </v-btn>
    </v-form>
    <p>{{ data }}</p>
  </section>
</template>

<script>
export default {
  data() {
    return {
      valid: false,
      username: 'tomas',
      password: 'tomas',
      show: false,
      userRules: [(v) => !!v || 'Zadejte prosím uživatelské jméno'],
      passRules: [(v) => !!v || 'Zadejte heslo'],
      data: '',
    }
  },
  methods: {
    async submit() {
      //await this.$auth.loginWith('cookie', {
      //  data: {
      //    username: this.username,
      //    password: this.password,
      //  },
      //})

      console.log(this.$auth.loggedIn)

      // await this.$axios.$post(`${this.$axios.defaults.baseURL}/api/login`, {
      //   username: this.username,
      //   password: this.password,
      // })
      const response = await this.$axios.$get(
        `${this.$axios.defaults.baseURL}/api/cannotBeAccessed`
      )
      this.data = response
    },
  },
}
</script>

<style scoped>
section {
  width: 28rem;
  margin: auto;
  background-color: #e6f5ff;
  padding: 4rem;
}

h2 {
  margin-bottom: 2rem;
  font-weight: 600;
  font-size: 1.3rem;
}
</style>
