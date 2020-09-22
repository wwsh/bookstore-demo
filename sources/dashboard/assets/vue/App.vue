<template>
  <v-app id="inspire">
    <v-navigation-drawer
      v-model="drawer"
      app
      clipped
    >
      <v-list dense>
        <v-list-item
          v-for="(link, i) in links"
          :key="i"
          link
          :to="link.link"
        >
          <v-list-item-action>
            <v-icon>{{ link.icon }}</v-icon>
          </v-list-item-action>
          <v-list-item-content>
            <v-list-item-title>{{ link.title }}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
      </v-list>
    </v-navigation-drawer>

    <v-app-bar
      app
      clipped-left
    >
      <v-app-bar-nav-icon @click.stop="drawer = !drawer" />
      <v-toolbar-title>Dashboard</v-toolbar-title>
    </v-app-bar>

    <router-view />

    <v-footer app>
      <span>&copy; 2020 WWSH</span>
    </v-footer>
  </v-app>
</template>

<script>
export default {
  data() {
    return {
      drawer: true,
      links: [
        {title: 'Books', icon: 'mdi-view-dashboard', link: '/books'},
        {title: 'Categories', icon: 'mdi-view-dashboard', link: '/categories'},
      ]
    };
  }
}
</script>


