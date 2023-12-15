<template>
    <div class="mb-3">
      <form @submit.prevent="login">
        <div class="flex flex-col">
          <input class="text-center border-b-2 py-2 border-b-black text-black outline-0 placeholder:text-black" type="text" placeholder="Name" v-model="name" required />
          <input class="mt-5 text-center border-b-2 py-2 border-b-black text-black outline-0 placeholder:text-black" type="email" placeholder="Email" v-model="email" required />
          <input class="mt-5 text-center border-b-2 py-2 border-b-black text-black outline-0 placeholder:text-black" type="password" placeholder="Password" v-model="password" required />
          <input class="mt-5 text-center border-b-2 py-2 border-b-black text-black outline-0 placeholder:text-black" type="password" placeholder="Password Confirm" v-model="password_confirmation" required />
          <div :class="{ 'hidden' : !this.failedLoginErrors }" class="mt-3">
            <div v-for="(errors, index) in failedLoginErrors" :key="index">
              <div v-for="(error, index) in errors" :key="index">
                <span class="text-red-600 text-center mt-3">{{ error }}</span>
              </div>
            </div>
          </div>
        </div>

        <div class="text-center mt-[40px]">
          <button
            class="py-[8px] w-full bg-black text-white border-2 border-black hover:bg-white hover:text-black duration-300"
            type="submit"
          >
          {{ isLoading ? 'Loading...' : 'Register' }}
        </button>
        </div>
      </form>
    </div>
    <router-link to="/login">Login</router-link>
</template>
<script>
import AuthService from "@/services/AuthService";

export default {
  data() {
    return {
      isLoading: false,
      failedLoginErrors: null,
      email: '',
      password: '',
      password_confirmation: '',
      name: '',
    };
  },
  methods: {
    async login() {
      const payload = {
        email: this.email,
        name: this.name,
        password: this.password,
        password_confirmation: this.password_confirmation,
      };
      try {
        this.failedLoginErrors = null
        this.isLoading = true
        const authUser = await AuthService.registerUser(payload);
        await this.$store.dispatch("auth/getAuthUser")
        this.isLoading = false
        if (authUser) {
          this.$router.push("/");
        }
      } catch (error) {
        this.isLoading = false
        if (error.response.status == 422) {
          this.failedLoginErrors = error.response.data.errors;
        }
      }
    },
  },
};
</script>
<style scoped>

</style>