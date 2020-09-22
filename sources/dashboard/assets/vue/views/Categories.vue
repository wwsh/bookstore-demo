<template>
  <v-content>
    <v-container
      class="pa-0 ma-0"
      fluid
    >
      <v-layout column>
        <v-flex>
          <v-toolbar
            dense
            flat
          >
            <v-toolbar-title>Categories</v-toolbar-title>
  
            <v-spacer />

            <template>
              <v-dialog
                v-model="dialog"
                persistent
              >
                <template v-slot:activator="{ on }">
                  <v-btn
                    color="secondary"
                    v-on="on"
                  >
                    Create New Category
                  </v-btn>
                </template>
                <v-form
                  ref="form"
                  v-model="formValid"
                >
                  <v-card>
                    <v-alert
                      :value="dialogAlert"
                      prominent
                      dark
                      type="error"
                      color="red"
                      transition="scale-transition"
                    >
                      {{ alertMessage }}
                    </v-alert>
                    <v-card-title>
                      <span class="headline">New Category</span>
                    </v-card-title>
                    <v-card-text>
                      <v-container>
                        <v-row>
                          <v-col
                            cols="12"
                            sm="6"
                          >
                            <v-text-field
                              v-model="name"
                              outlined
                              label="Name*"
                              required
                            />
                          </v-col>
                        </v-row>
                      </v-container>
                      <small>*indicates required field</small>
                    </v-card-text>
                    <v-card-actions>
                      <v-spacer />
                      <v-btn
                        color="blue darken-1"
                        text
                        @click="dialog = false"
                      >
                        Close
                      </v-btn>
                      <v-btn
                        color="green darken-1"
                        text
                        @click="createCategory()"
                      >
                        Create
                      </v-btn>
                    </v-card-actions>
                  </v-card>
                </v-form>
              </v-dialog>
            </template>
          </v-toolbar>
        </v-flex>
        <v-flex>
          <template>
            <v-data-table
              :headers="headers"
              :items="items"
              class="elevation-1"
              :page.sync="page"
              :items-per-page="itemsPerPage"
              :server-items-length="totalItems"
              :options.sync="options"
              :loading="loading"
            >
              <template
                v-slot:item.actions="{ item }"
              >
                <v-btn
                  small
                  color="red"
                  @click="categoryId = item.id; deleteDialog = true"
                >
                  Delete
                </v-btn>
              </template>
            </v-data-table>
            <v-dialog
              v-model="deleteDialog"
              width="500"
            >
              <v-card>
                <v-alert
                  :value="deleteDialogAlert"
                  prominent
                  dark
                  type="error"
                  color="red"
                  transition="scale-transition"
                >
                  {{ alertMessage }}
                </v-alert>

                <v-card-title class="headline">
                  Confirmation
                </v-card-title>

                <v-card-text>
                  You want to delete this category?
                </v-card-text>

                <v-divider />

                <v-card-actions>
                  <v-spacer />
                  <v-btn
                    color="primary"
                    text
                    @click="deleteCategory();"
                  >
                    Yes
                  </v-btn>
                  <v-btn
                    color="blue darken-1"
                    text
                    @click="deleteDialog = false"
                  >
                    Close
                  </v-btn>
                </v-card-actions>
              </v-card>
            </v-dialog>
          </template>
        </v-flex>
      </v-layout>
    </v-container>
  </v-content>
</template>

<script>

import dateformat from "dateformat";
import API from "../api/api";

export default {
  data() {
    return {
      name: '',
      form: {},
      formValid: false,
      dialog: false,
      dialogAlert: false,
      deleteDialog: false,
      deleteDialogAlert: false,
      alertMessage: '',
      totalItems: 0,
      loading: true,
      page: 1,
      pageCount: 0,
      itemsPerPage: 1000, // todo
      items: [],
      categoryId: null,
      options: {},
      headers: [
        {text: 'Name', value: 'name'},
        {text: 'Actions', value: 'actions', sortable: false }
      ],
      tags: []
    }
  },

  watch: {
    options: {
      handler() {
        this.getData()
          .then(data => {
            this.items = data.items;
            this.totalItems = data.total
          })
      },
      deep: true
    }
  },

  created() {
    this.$vuetify.theme.dark = true;
  },

  mounted() {
    API.getDomains()
      .then(response => {
        this.form.applications = response.data.map(i => i.label)
      });
    API.getTags()
      .then(response => {
        this.form.tags = response.data
      });
  },

  methods: {
    getData() {
      var localThis = this
      this.loading = true
      return new Promise((resolve) => {
        let items = this.getCategoriesFromApi().then(
          function (response) {
            items = response;
            const total = response.length;
            setTimeout(() => {
              localThis.loading = false;
              resolve({
                items,
                total
              })
            }, 500)
          })
      })
    },
    getCategoriesFromApi() {
      return API.getCategories()
        .then(function (response) {
          var result = response.data;
          return result;
        }).catch(function (error) {
          // handle error
          alert(error);
        })
    },
    getDate(utcDate) {
      return dateformat(utcDate, "fullDate")
    },
    createCategory() {
      this.$refs.form.validate();
      var that = this;
      this.dialogAlert = false;
      this.items = [];
      this.totalItems = 0;
      this.loading = true
      API.createCategory(this.name)
        .then(function(response) {
          if (response.status !== 201) {
            that.alertMessage = response.data.error;
            that.dialogAlert = true;
            return;
          }
          that.dialog = false;
          that.getData()
            .then(data => {
              that.items = data.items;
              that.totalItems = data.total
            });
          return;
        }).catch(function (error) {
        // handle error
          that.alertMessage = typeof error.response === 'object' ? error.response.data.error : error.response;
          that.dialogAlert = true;
          return;
        });
      return false;
    },
    deleteCategory() {
      var that = this;
      this.deleteDialogAlert = false;
      this.items = [];
      this.totalItems = 0;
      this.loading = true
      API.deleteCategory(this.categoryId)
        .then(function(response) {
          if (response.status !== 202) {
            that.alertMessage = response.data.error;
            that.deleteDialogAlert = true;
            return;
          }
          that.deleteDialog = false;
          that.getData()
            .then(data => {
              that.items = data.items;
              that.totalItems = data.total
            })
          return;
        }).catch(function (error) {
        // handle error
          that.alertMessage = typeof error.response === 'object' ? error.response.data.error : error.response;
          that.deleteDialogAlert = true;
          return;
        });
      return false;
    }
  }
}
</script>
