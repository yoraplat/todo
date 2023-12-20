import axios from "axios";
import store from "@/store";

export const todoClient = axios.create({
  baseURL: process.env.VUE_APP_API_URL,
  withCredentials: true,
  withXSRFToken: true,
});

/*
 * Add a response interceptor
 */
todoClient.interceptors.response.use(
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
  async getTodos() {
    return todoClient.get(`/api/todos`);
  },
  async finishTodo(todo) {
    const res = await todoClient.patch(`/api/todos/${todo.id}/update`, todo);
    return res.data;
  },
  async createTodo(todo) {
    const res = await todoClient.post(`/api/todos`, todo);
    return res.data;
  },
  async deleteTodo(todo) {
    const res = await todoClient.delete(`/api/todos/${todo.id}`, todo);
    return res.data;
  }
};