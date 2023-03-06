import { ref } from 'vue'
import { defineStore } from 'pinia'
import todoApi from './todoApi'

export const useTodoStore = defineStore('todo', () => {

    const items = ref([])
    const meta = ref([])
    const loading = ref(true)

    async function fetchAll(params) {
        loading.value = true
        const {data} = await todoApi.all(params)
        items.value = data.data
        meta.value = data.meta
        loading.value = false
    }

    return {
        items,
        meta,
        loading,
        fetchAll
    }
})
