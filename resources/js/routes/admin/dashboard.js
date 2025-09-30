
import AdminLayout from '../../components/layouts/AdminLayout.vue';
import DashboardIndex from '../../pages/Dashboard/Index.vue';
import ServicesIndex from '../../pages/Services/Index.vue';
import ServiceForm from '../../pages/Services/ServiceForm.vue';

export default [
    {
        path: '/admin',
        name: 'admin',
        component: AdminLayout,
        redirect: {name: 'admin.dashboard'},
        meta: {
            auth: true,
            breadcrumb: 'dashboard'
        },
        children: [
            {
                path: 'dashboard',
                name: 'admin.dashboard',
                component: DashboardIndex,
                meta: {
                    auth: true,
                    breadcrumb: ''
                },
            },
            {
                path: 'categories',
                name: 'admin.categories',
                redirect: {name: 'admin.categories.list'},
                meta: {
                    auth: true,
                    breadcrumb: 'categories',
                },
                children: [
                    {
                        path: '',
                        name: 'admin.categories.list',
                        component: () => import('../../pages/Categories/Index.vue'),
                        meta: {
                            auth: true,
                            breadcrumb: '',
                        },
                    },
                    {
                        path: 'create',
                        name: 'admin.categories.create',
                        component: () => import('../../pages/Categories/Form.vue'),
                        meta: {
                            auth: true,
                            breadcrumb: 'category_create',
                        },
                    },
                    {
                        path: ':id/edit',
                        name: 'admin.categories.edit',
                        component: () => import('../../pages/Categories/Form.vue'),
                        meta: {
                            auth: true,
                            breadcrumb: 'category_edit',
                        },
                    },
                ]
            },
            {
                path: 'services',
                name: 'admin.services',
                redirect: {name: 'admin.services.list'},
                meta: {
                    auth: true,
                    breadcrumb: 'services',
                },
                children: [
                    {
                        path: '',
                        name: 'admin.services.list',
                        component: ServicesIndex,
                        meta: {
                            auth: true,
                            breadcrumb: '',
                        },
                    },
                    {

                        path: 'create',
                        name: 'admin.services.create',
                        component: ServiceForm,
                        meta: {
                            auth: true,
                            breadcrumb: 'service_create',
                        },
                    },
                    {
                        path: ':id/edit',
                        name: 'admin.services.edit',
                        component: ServiceForm,
                        meta: {
                            auth: true,
                            breadcrumb: 'service_edit',
                        },
                        props: true,
                    },
                    {
                        path: ':id/masters',
                        name: 'admin.services.masters',
                        component: () => import('../../pages/Services/ServiceMasters.vue'),
                        meta: {
                            auth: true,
                            breadcrumb: 'service_masters',
                        },
                        props: true,
                    }
                ]
            },
            {
                path: 'calendar',
                name: 'admin.calendar',
                redirect: {name: 'admin.calendar.index'},
                meta: {
                    auth: true,
                    breadcrumb: 'calendar',
                },
                children: [
                    {

                        path: '',
                        name: 'admin.calendar.index',
                        component: () => import('../../pages/Calendar/Index.vue'),
                        meta: {
                            auth: true,
                            breadcrumb: '',
                        },
                    },
                ]
            },
            {
                path: 'blog',
                name: 'admin.blog',
                redirect: {name: 'admin.blog.list'},  
                meta: {
                    auth: true,
                    breadcrumb: 'blog',
                },
                children: [
                    {
                        path: '',
                        name: 'admin.blog.stats',
                        component: () => import('../../pages/Blog/Stats.vue'),
                        meta: {
                            auth: true,
                            breadcrumb: 'blog_stats',
                        },  
                    },
                    {
                        path: 'list',
                        name: 'admin.blog.list',
                        component: () => import('../../pages/Blog/List.vue'),
                        meta: {
                            auth: true,
                            breadcrumb: 'blog_list',
                        },
                    },
                    {
                        path: ':id',
                        name: 'admin.blog.edit',
                        component: () => import('../../pages/Blog/Edit.vue'),
                        meta: {
                            auth: true,
                            breadcrumb: 'blog_edit',
                        },
                    },
                    {
                        path: 'create',
                        name: 'admin.blog.create',
                        component: () => import('../../pages/Blog/Edit.vue'),
                        meta: {
                            auth: true,
                            breadcrumb: 'blog_create',
                        },
                    },
                ]
            },
            {
                path: 'events',
                name: 'admin.events',
                redirect: {name: 'admin.events.list'},
                meta: {
                    auth: true,
                    breadcrumb: 'events',
                },
                children: [
                    {
                        path: '',
                        name: 'admin.events.list',
                        component: () => import('../../pages/Events/Index.vue'),
                        meta: {
                            auth: true,
                            breadcrumb: '',
                        },
                    },
                    {
                        path: 'stats',
                        name: 'admin.events.stats',
                        component: () => import('../../pages/Events/Stats.vue'),
                        meta: {
                            auth: true,
                            breadcrumb: 'blog_stats',
                        },
                    },
                    {
                        path: 'create',
                        name: 'admin.events.create',
                        component: () => import('../../pages/Events/Form.vue'),
                        meta: {
                            auth: true,
                            breadcrumb: 'event_create',
                        },
                    },
                    {
                        path: ':id',
                        name: 'admin.events.edit',
                        component: () => import('../../pages/Events/Form.vue'),
                        meta: {
                            auth: true,
                            breadcrumb: 'event_edit',
                        },
                        props: true,
                    }
                ]
            }
        ]
    },
]