<template>
    <div class="max-w-sm p-5 m-auto">
      <AuthLogin />
    </div>
  </template>
  
  <script>
  import AuthLogin from "@/components/AuthLogin";
  
  export default {
    name: "LoginView",
    components: {
      AuthLogin,
    },
  };
  </script>