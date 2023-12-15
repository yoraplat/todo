<template>
    <div>
      <form @submit.prevent="login">
        <div class="flex flex-col">
          <input class="text-center border-b-2 py-2 border-b-black text-black outline-0 placeholder:text-black" type="text" placeholder="Email" v-model="email" required />
          <input autocomplete="on" class="mt-5 text-center border-b-2 py-2 border-b-black text-black outline-0 placeholder:text-black" type="password" placeholder="Password" v-model="password" required />
          <span class="text-red-600 text-center mt-3" :class="{ 'hidden' : !this.failedLogin }">User does not exist</span>
        </div>

        <div class="text-center mt-[50px]">
          <button
            class="py-[8px] w-full bg-black text-white border-2 border-black hover:bg-white hover:text-black duration-300"
            type="submit"
          >
          {{ isLoading ? 'Loading...' : 'Login' }}
        </button>
        </div>
      </form>
    </div>
</template>
<script>
import AuthService from "@/services/AuthService";

export default {
  data() {
    return {
      isLoading: false,
      failedLogin: false,
      email: '',
      password: '',
    };
  },
  methods: {
    async login() {
      const payload = {
        email: this.email,
        password: this.password,
      };
      this.error = null;
      try {
        this.failedLogin = false
        this.isLoading = true
        const authUser = await AuthService.login(payload);
        await this.$store.dispatch("auth/getAuthUser")
        this.isLoading = false
        if (authUser) {
          this.$router.push("/");
        } else {
          const error = Error(
            "Unable to fetch user after login, check your API settings."
          );
          error.name = "Fetch User";
          throw error;
        }
      } catch (error) {
        this.isLoading = false
        if (error.response.status == 422) {
          this.failedLogin = true
        }
      }
    },
  },
};
</script>
<style scoped>

</style>