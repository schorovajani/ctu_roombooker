<template>
  <v-snackbar v-model="alert">
    {{ alertMessage }}
    <template v-slot:action="{ attrs }">
      <v-btn color="pink" text v-bind="attrs" @click="closeAlert">
        Zavřít
      </v-btn>
    </template>
  </v-snackbar>
</template>

<script>
export default {
  computed: {
    alert: {
      get() {
        return this.$store.getters['alert/alertIsVisible']
      },
      set() {
        this.$store.dispatch('alert/hideAlert')
      },
    },
    alertMessage() {
      return this.$store.getters['alert/alertMessage']
    },
  },
  methods: {
    closeAlert() {
      this.$store.dispatch('alert/hideAlert')
    },
  },
}
</script>
