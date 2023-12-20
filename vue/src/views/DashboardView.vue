<template>
  <div class="mb-5 ml-[15px]">
    <button class="bg-black text-white py-[8px] px-[15px] border-2 border-black duration-300 hover:bg-white hover:text-black rounded" @click="toggleCompleted">Show {{ this.showAll ? 'open' : 'all' }} tasks</button>
    <button class="bg-black ml-2 text-white py-[8px] px-[15px] border-2 border-black duration-300 hover:bg-white hover:text-black rounded" @click="openOverlay">New</button>
  </div>

  <div class="fixed bg-black/50 w-full h-full top-0 flex justify-center items-center" :class="{ 'hidden' : !showOverlay}">
    <form class="w-1/3 bg-white rounded-md py-10 px-5">
      <div class="flex flex-col">
        <input v-model="form.title" class="outline-none pb-2 mb-4 border-b-2 border-black placeholder:text-black" type="text" name="title" id="title" placeholder="Title">
      </div>
      <div class="mt-3">
        <textarea v-model="form.description" class="outline-none h-[200px] border-b-2 border-black placeholder:text-black w-full" name="description" id="description" placeholder="Description"></textarea>
      </div>
      <button @click.prevent="createTodo" class="bg-black text-white py-[8px] px-[15px] rounded mt-3">Save</button>
      <button @click.prevent="closeOverlay" class="ml-2 bg-black text-white py-[8px] px-[15px] rounded mt-3">Cancel</button>
    </form>
  </div>
  <div class="todos grid grid-cols-4 gap-3 p-[15px]">
    <div
      class="flex flex-col text-start p-5 rounded-lg bg-zinc-100"
      :class="{ 'completed': todo.is_completed }"
      v-for="todo in visibleTodos"
      :key="todo.id"
    >
      <div class="text-lg font-bold mb-1">{{ todo.title }}</div>
      <div class="text-base grow">{{ todo.description }}</div>
      <div class="flex items-center justify-between mt-3">
        <button class="todo-state" @click="toggleTask(todo)">
          <span v-if="todo.is_completed">
            <font-awesome-icon icon="fa fa-square-xmark" />
            Re-open
          </span>
          <span v-else>
            <font-awesome-icon icon="fa fa-square-check" />
            Finish
          </span>
        </button>
        <button @click="removeTodo(todo)" class=""><font-awesome-icon icon="fa fa-trash" /></button>
      </div>
    </div>
  </div>
</template>
  
  <script>
  import TodoService from "@/services/TodoService";
  
  export default {
    name: "DashboardView",
    data() {
      return {
        todos: [],
        incompleteTodos: [],
        showOverlay: false,
        showAll: false,
        form: {
          title: '',
          description: '',
        },
        user: this.$store.getters["auth/authUser"],
      };
    },
    mounted() {
      this.fetchTodos();
    },
    methods: {
      async toggleTask(todo) {
        try {
          if (todo.is_completed) {
            todo.is_completed = false;
          } else {
            todo.is_completed = true;
          }

          await TodoService.finishTodo(todo);
        } catch (error) {
          console.error("Error fetching todos:", error);
        }
      },
      async fetchTodos() {
        try {
          const todos = await TodoService.getTodos(this.user.id);

          this.todos = todos.data.data;
          this.incompleteTodos = this.todos.filter(todo => !todo.is_completed);
        } catch (error) {
          console.error("Error fetching todos:", error);
        }
      },
      toggleCompleted() {
        this.showAll = !this.showAll;
      },
      openOverlay() {
        this.showOverlay = true;
        this.form.title = '';
        this.form.description = '';
      },
      closeOverlay() {
        this.showOverlay = false;
      },
      async createTodo() {
        let formData = this.form;
        formData.is_completed = false;
        formData.user_id = this.$store.state.auth.user.id;
        const todo = await TodoService.createTodo(this.form);
        this.todos.push(todo.data)
        this.form.title = '';
        this.form.description = '';

        this.closeOverlay()
      },
      async removeTodo(removedTodo) {
        await TodoService.deleteTodo(removedTodo);
        this.todos = this.todos.filter(todo => todo.id !== removedTodo.id);
      }
    },
    computed: {
      visibleTodos() {
        return this.showAll ? this.todos : this.todos.filter(todo => !todo.is_completed);
      }
    },
  };
  </script>

<style scoped>
.completed {
  background-color: black;
  color: white;
}

.todo-state {
  background-color: black;
  width: fit-content;
  padding: 8px 15px;
  color: white;
  border-radius: 5px;
}

.completed .todo-state {
  background-color: white;
  color: black;
}
</style>