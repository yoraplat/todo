import axios from "axios";
import store from "@/store";
import { checkRoles } from "@/helpers/rolesHelper";

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
  async getTodos(search) {
    
    let uri = '/api/todos';
    if (checkRoles(store.getters["auth/authUser"], ['admin'])) {
      
      uri = '/api/admin/todos';
      if (search) {
        uri = `/api/admin/todos?search=${search}`;
      }
    } else {
      if (search) {
        uri = `/api/todos?search=${search}`;
      }
    }

    return todoClient.get(uri);
  },
  async finishTodo(todo) {
    let uri = `/api/todos/${todo.id}`;

    if (checkRoles(store.getters["auth/authUser"], ['admin'])) {
      uri = `/api/admin/todos/${todo.id}`
    }

    const res = await todoClient.patch(uri, todo);
    return res.data;
  },
  async createTodo(todo) {
    const res = await todoClient.post(`/api/todos`, todo);
    return res.data;
  },
  async deleteTodo(todo) {
    let uri = `/api/todos/${todo.id}`;

    if (checkRoles(store.getters["auth/authUser"], ['admin'])) {
      uri = `/api/admin/todos/${todo.id}`
    }

    const res = await todoClient.delete(uri, todo);
    return res.data;
  }
};