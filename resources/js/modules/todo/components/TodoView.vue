<template>
    <section id="todo_show">
        <div class="text-center">
            {{ todo.name }}
        </div>
    </section>
</template>

<script setup>
import {ref} from 'vue'
import todoApi from '../todoApi'
import {useRoute} from 'vue-router'

const route = useRoute()

const todo = ref({})

fetchTodo()

async function fetchTodo() {
    const response = await todoApi.find(route.params.id)
    todo.value = response.data.data
}
</script>
