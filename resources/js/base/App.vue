<template>
    <div class="app">
        <router-view
            :key="$route.meta.layout || 'Default'"
            v-slot="{ Component }"
        >
            <component
                :is="Component"
            />
        </router-view>
    </div>
</template>
