import TodoList from './components/TodoList.vue'
import TodoView from './components/TodoView.vue'

export const routes = [
    {
        path: '/',
        name: 'Todos',
        component: TodoList,
    },
    {
        path: '/todos/:id',
        name: 'Show Todo',
        component: TodoView,
        hidden: true
    },
]
