<template>
  <div class="flex items-center text-sm text-gray-600 ml-4">
    <template v-for="(crumb, index) in crumbs" :key="crumb.name || index">
      <router-link 
        v-if="crumb.route"
        :to="{ name: crumb.route }"
        class="text-indigo-600 hover:text-indigo-800 hover:underline"
      >
        {{ crumb.title }}
      </router-link>
      <span v-else class="text-gray-500">
        {{ crumb.title }}
      </span>
      <span v-if="index < crumbs.length - 1" class="mx-2">/</span>
    </template>
  </div>
</template>

<script>
export default {
  name: 'Breadcrumbs',
  computed: {
    crumbs() {
      const route = this.$route;

      const matched = route.matched.filter(r => r.meta.breadcrumb);

      return matched.map((routeRecord, index) => {
        const isLast = index === matched.length - 1;
        const title = routeRecord.meta.breadcrumb 
          ? this.$t(`msg.menu.${routeRecord.meta.breadcrumb}`) || routeRecord.meta.breadcrumb
          : '';

        return {
          title,
          route: isLast ? null : routeRecord.name,
          disabled: isLast
        };
      });
    }
  }
}
</script>

<style scoped>
.breadcrumb-item {
  transition: color 0.2s ease;
}
.breadcrumb-item:not(.disabled):hover {
  color: #4f46e5;
  text-decoration: underline;
}
.breadcrumb-separator {
  margin: 0 0.5rem;
  color: #9ca3af;
}
</style>
