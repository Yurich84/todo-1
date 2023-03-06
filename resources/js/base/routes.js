import Index        from './components/Index.vue'
import NotFound     from './components/NotFound.vue'
import Base         from './components/Base.vue'
import Child        from './components/Child.vue'

const autoImportModules = import.meta.glob('../modules/*/routes.js', { import: 'routes' })

let moduleRoutes = []

for (const path in autoImportModules) {
    const routes = await autoImportModules[path]()
    moduleRoutes = moduleRoutes.concat(routes)
}

export const routes = [
    {
        path: '/',
        component: Base,
        children: [
            {
                path: '/',
                name: 'Home',
                component: Child,
                children: [
                    ...moduleRoutes,
                ]
            },
            {
                path: ':pathMatch(.*)*',
                component: NotFound,
                name: '404',
                meta: {
                    layout: 'Welcome',
                    auth: undefined
                },
            }
        ]
    }
]

