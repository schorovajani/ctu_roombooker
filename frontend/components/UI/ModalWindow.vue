<template>
  <div class="modal" @click="$emit('hideModal')">
    <div class="modal-info">
      <button class="cross-icon" @click="$emit('hideModal')">
        <img alt="cross icon" :src="require(`@/assets/UI/cross_icon.png`)" />
      </button>
      <div class="modal-content">
        <slot> </slot>
      </div>
    </div>
  </div>
</template>

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

.modal-info {
  padding: 2rem 3rem 4rem 4rem;
  background-color: #dcf9f4;
  display: flex;
  flex-direction: column;
  align-items: flex-start;
}

.cross-icon {
  align-self: flex-end;
  margin-bottom: 0.5rem;
}

.cross-icon img {
  height: 1.5rem;
}
</style>
