<template>
  <div class="modal">
    <div class="alert">
      <h3>Upozornění</h3>
      <p>{{ message }}</p>
      <div class="buttons">
        <button :class="btn1Color" @click="$emit('accepted')">
          {{ btn1 }}
        </button>
        <button class="btn-white" @click="$emit('cancel')">{{ btn2 }}</button>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    message: String,
    btn1: String,
    btn2: String,
    btn1Color: String,
  },
}
</script>

<style scoped>
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

.alert {
  padding: 3rem 4rem;
  background-color: #ffffff;
  display: flex;
  flex-direction: column;
}

h3 {
  font-weight: 600;
  font-size: 1.3rem;
  margin-bottom: 1rem;
  color: #6d3334;
}

.buttons {
  display: flex;
  justify-content: flex-end;
}

.buttons button {
  margin: 2rem 1rem 0 1rem;
  padding: 0.6rem;
}
</style>
