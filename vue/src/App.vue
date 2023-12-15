<template>
  <NavigationComponent />
  <router-view></router-view>
</template>

<script>
import NavigationComponent from "@/components/NavigationComponent";
export default {
  name: 'App',
  components: {
    NavigationComponent,
  },
}
</script>

<style lang="scss">
@import './assets/styles/main.css';
</style>
