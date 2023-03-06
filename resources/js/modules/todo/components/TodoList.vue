<template>
    <section
        id="todos"
        class="mt-20"
    >
        <el-button
            type="primary"
            icon="plus"
            class="float-left"
            @click="handleAdd"
        >
            Add
        </el-button>
        <base-filter
            v-model="filters"
            class="float-right"
        />
        <!-- table -->
        <el-table
            v-loading="loading"
            :data="items"
            highlight-current-row
            class="w-100"
            @sort-change="handleSortChange"
            @filter-change="fetchAll(params)"
        >
            <el-table-column
                prop="name"
                label="Name"
                min-width="200"
                sortable
            >
                <template #default="scope">
                    <s v-if="scope.row.done">
                        {{ scope.row.name }}
                    </s>
                    <template v-else>
                        {{ scope.row.name }}
                    </template>
                </template>
            </el-table-column>
            <el-table-column
                sortable
                prop="updated_at"
                label="Updated"
                width="170"
            >
                <template #default="scope">
                    {{ $filters.time(scope.row.updated_at) }}
                </template>
            </el-table-column>
            <el-table-column
                label="Actions"
                width="170"
                align="right"
            >
                <template #default="scope">
                    <el-tooltip
                        :show-after="300"
                        :content="$t('global.done')"
                        placement="top"
                    >
                        <span>
                            <el-button
                                size="small"
                                class="ml-10"
                                @click="handleDone(scope.row)"
                            >
                                <el-icon><Check /></el-icon>
                            </el-button>
                        </span>
                    </el-tooltip>
                    <el-tooltip
                        :show-after="300"
                        :content="$t('global.edit')"
                        placement="top"
                    >
                        <span>
                            <el-button
                                size="small"
                                class="ml-10"
                                @click="handleEdit(scope.row)"
                            >
                                <i class="fas fa-pencil-alt" />
                            </el-button>
                        </span>
                    </el-tooltip>
                    <el-tooltip
                        :show-after="300"
                        :content="$t('global.delete')"
                        placement="top"
                    >
                        <span>
                            <el-button
                                type="danger"
                                size="small"
                                plain
                                class="ml-10"
                                @click="handleDelete(scope.row)"
                            >
                                <i class="fa fa-trash" />
                            </el-button>
                        </span>
                    </el-tooltip>
                </template>
            </el-table-column>
        </el-table>

        <!-- pagination -->
        <el-pagination
            v-model:current-todo="todo"
            v-model:todo-size="todoSize"
            :total="meta.total || 0"
            small
            layout="total, sizes, prev, todor, next"
            class="float-right mt-20 mb-20"
            @size-change="fetchAll(params)"
            @current-change="fetchAll(params)"
        />

        <!-- form dialog -->
        <el-dialog
            v-model="formVisible"
            :title="formTitle"
            destroy-on-close
        >
            <TodoForm
                :initial-form="formData"
                @saved="saved"
                @cancel="formVisible = false"
            />
        </el-dialog>
    </section>
</template>

<script setup>
import {computed, ref, watch} from 'vue'
import todoApi from '../todoApi'
import TodoForm from './TodoForm.vue'
import {storeToRefs} from 'pinia'
import {useTodoStore} from '@/modules/todo/todoStore'
import {ElMessage, ElMessageBox} from 'element-plus'
import BaseFilter from '@/base/components/filters/BaseFilter.vue'

const sortBy = ref('id,asc')
const filters = ref({search: ''})
const todo = ref(1)
const todoSize = ref(10)
const formVisible = ref(false)
const formTitle = ref('New Todo')
const formData = ref(null)

const { items, meta, loading } = storeToRefs(useTodoStore())
const { fetchAll } = useTodoStore()

const params = computed(() => ({
    todo: todo.value,
    sortBy: sortBy.value,
    todoSize: todoSize.value,
    ...filters.value,
}))

fetchAll(params.value)

function handleSortChange(val) {
    if (val.prop != null && val.order != null) {
        let sort = val.order.startsWith('a') ? 'asc' : 'desc'
        sortBy.value = val.prop + ',' + sort
        fetchAll(params.value)
    }
}

function handleAdd() {
    formTitle.value = 'New Todo'
    formData.value = {}
    formVisible.value = true
}

function handleEdit(row) {
    formTitle.value = 'Edit Todo'
    formData.value = Object.assign({}, row)
    formVisible.value = true
}

function handleDone(row) {
    row.done = !row.done
    const message = row.done ? 'Done' : 'UnDone'
    todoApi.done(row.id).then(() => {
        ElMessage.success(message)
    })
}

function handleDelete(row) {
    ElMessageBox.confirm('This will permanently delete the todo. Continue?', 'Warning', {
        type: 'warning'
    }).then(() => {
        todoApi.delete(row.id).then((response) => {
            ElMessage({
                message: response.data.message,
                type: response.data.type
            })
            fetchAll(params.value)
        })
    })
}

watch(
    () => filters.value,
    val => applySearch(),
    { deep: true }
)

const applySearch = _.debounce(() => fetchAll(params.value), 300)


function saved() {
    formVisible.value = false
    fetchAll(params.value)
}
</script>
