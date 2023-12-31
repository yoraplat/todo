import axios from "axios";
import store from "@/store";

export const authClient = axios.create({
  baseURL: process.env.VUE_APP_API_URL,
  withCredentials: true,
  withXSRFToken: true,
});

/*
 * Add a response interceptor
 */
authClient.interceptors.response.use(
  (response) => {
    return response;
  },
  function (error) {
    if (
      error.response &&
      [401, 419].includes(error.response.status) &&
      store.getters["auth/authUser"]
    ) {
      store.dispatch("auth/logout");
    }
    return Promise.reject(error);
  }
);

export default {
  async login(payload) {
    await authClient.get("/sanctum/csrf-cookie");
    return authClient.post("/login", payload);
  },
  logout() {
    return authClient.post("/logout");
  },
  async getAuthUser() {
    return await authClient.get("/api/users/auth")
  },
  async registerUser(payload) {
    await authClient.get("/sanctum/csrf-cookie");
    return authClient.post("/register", payload);
  },
};