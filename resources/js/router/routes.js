function page (path) {
    return () => import(/* webpackChunkName: '' */ `../pages/${path}`).then(m => m.default || m)
}

export default [    
    { path: '/', name: 'welcome' , component: page('welcome.vue') },
    { path: '/components', name: 'components', component: page('components.vue')},
    { path: '/login', name: 'login', component: page('auth/login.vue') },
    { path: '/register', name: 'register', component: page('auth/register.vue') },
    { path: '/password/reset', name: 'password.request', component: page('auth/password/email.vue') },
    { path: '/password/reset/:token', name: 'password.reset', component: page('auth/password/reset.vue') },
    { path: '/email/verify/:id', name: 'verification.verify', component: page('auth/verification/verify.vue') },
    { path: '/email/resend', name: 'verification.resend', component: page('auth/verification/resend.vue') },
    
    { path: '/home', name: 'home', component: page('home.vue') },
    { path: '/terms-of-services', name: 'tos', component: page('tos.vue') },
    { path: '/privacy-policy', name: 'privacy-policy', component: page('privacy.vue') },
    { path: '/settings',
        component: page('settings/index.vue'),
        children: [
        { path: '', redirect: { name: 'settings.profile' } },
        { path: 'profile', name: 'settings.profile', component: page('settings/profile.vue') },
        { path: 'password', name: 'settings.password', component: page('settings/password.vue') },
        ] },
    { path: '/surveys',
        component: page('surveys/index.vue'),
        children: [
        { path: 'list', name: 'survey.all', component: page('surveys/all.vue') },
        { path: 'info/:surveyid', name: 'survey.info', component: page('surveys/survey.vue') },
        { path: 'create', name: 'survey.create', component: page('surveys/create.vue') },
        { path: 'wizard/:surveyid', name: 'survey.wizard', component: page('surveys/wizard.vue') },
        { path: 'completed/:completeid', name: 'survey.complete', component: page('surveys/complete.vue') }
        ] },

    { path: '/administrator',
        component: page('admin/index.vue'),
        children: [
        { path: '', redirect: { name: 'admin.user' } },
        { path: 'user-management', name: 'admin.user', component: page('admin/user-management.vue') },
        { path: 'pages', name: 'admin.pages', component: page('admin/pages.vue') },
        ] },

    { path: '*', component: page('errors/404.vue') }
]