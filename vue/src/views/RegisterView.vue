<template>
    <div class="max-w-sm p-5 m-auto">
      <AuthRegister />
    </div>
  </template>
  
  <script>
  import AuthRegister from "@/components/AuthRegister";
  
  export default {
    name: "RegisterView",
    components: {
      AuthRegister,
    },
  };
  </script>