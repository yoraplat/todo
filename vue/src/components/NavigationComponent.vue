<template>
  <div class="nav">
    <router-link class="nav-item" to="/">Dashboard</router-link>
    <router-link class="nav-item" v-if="!isAuthenticated" to="/login">Login</router-link>
    <button class="nav-item" v-if="isAuthenticated" @click="handleLogout">Logout</button>
  </div>
</template>
<script>

export default {
  data() {
  },
  computed: {
    isAuthenticated() {
      return this.$store.getters["auth/authUser"] !== null;
    }
  },
  methods: {
    handleLogout() {
      this.$store.dispatch("auth/logout");
    }
  }
};
</script>
<style scoped>
  .nav {
    display: flex;
    justify-content: flex-end;
    padding: 15px;
  }
  .nav .nav-item {
    font-size: 1.2rem;
    margin-left: 15px;
  }
</style>