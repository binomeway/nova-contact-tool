Nova.booting((Vue, router, store) => {
  router.addRoutes([
    {
      name: 'nova-contact-tool',
      path: '/nova-contact-tool',
      component: require('./components/Tool'),
    },
  ])
})
