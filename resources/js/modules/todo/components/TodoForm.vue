<template>
    <section id="todo-form">
        <el-form
            ref="formRef"
            :model="form"
            label-width="120px"
            :rules="rules"
            @submit.prevent="saveSubmit"
        >
            <el-form-item
                label="Todo Name"
                prop="name"
                :error="errors.get('name')"
            >
                <el-input
                    v-model="form.name"
                    suffix-icon="el-icon-edit"
                    @change="errors.clear('name')"
                />
            </el-form-item>
        </el-form>
        <div class="dialog-footer">
            <el-button @click="cancel">
                {{ $t('global.cancel') }}
            </el-button>
            <el-button
                :loading="loading"
                type="success"
                class="float-right"
                @click="saveSubmit"
            >
                {{ $t('global.save') }}
            </el-button>
        </div>
    </section>
</template>

<script setup>
import {ref, onMounted} from 'vue'
import {useI18n} from 'vue-i18n'
import {ElMessage} from 'element-plus'
import {useErrors} from '@/includes/composable/errors'
import todoApi from '../todoApi'

const props = defineProps({
    initialForm: {
        type: Object,
        default: {}
    }
})

const {t} = useI18n()
const emit = defineEmits()
const formRef = ref()
const errors = useErrors()
const loading = ref(false)
const form = ref({})

const rules = ref({
    name: [
        { required:true, message: t('global.form.rules.required', { 'fieldName': 'name'}), trigger: 'blur' }
    ],
})

onMounted(() => form.value = Object.assign({}, props.initialForm))

function saveSubmit() {
    formRef.value.validate((valid) => {
        if (valid) {
            loading.value = true
            errors.clear()
            let action = form.value.id ? todoApi.update : todoApi.create

            action(form.value).then((response) => {
                ElMessage({
                    message: response.data.message,
                    type: response.data.type
                })
                if(response.data.type === 'success') {
                    emit('saved')
                }
            }).catch(error => {
                if (error.response.data.errors) errors.record(error.response.data.errors)
            }).finally(() => loading.value = false)
        }
    })
}

function cancel() {
    clearForm()
    emit('cancel')
}

function clearForm() {
    errors.clear()
    formRef.value.resetFields()
}
</script>
