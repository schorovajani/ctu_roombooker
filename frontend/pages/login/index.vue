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
      try {
        const response = await this.$auth.loginWith('cookie', {
          data: {
            username: this.username,
            password: this.password,
          },
        })
        console.log(response.data) // login data, role resit zde na preroutovani
      } catch (err) {
        if (!err.response) {
          //todo
          console.log('Server neběží')
        } else {
          if (err.response.status === 401) {
            console.log('Špatné přihlašovací údaje')
          } else {
            // Co muze byt vse za chyby
            console.log('Jiná chyba')
          }
        }
        //console.log(err.response.status)
      }

      // console.log(this.$auth.loggedIn)
      // console.log(this.$auth.user.firstName)
      // const response = await this.$axios.$get(
      // `${this.$axios.defaults.baseURL}/me`
      // )
      // this.data = response
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
