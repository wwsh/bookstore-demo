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
            <v-toolbar-title>Books</v-toolbar-title>
  
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
                    Create New Book
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
                      <span class="headline">New Book</span>
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
                          <v-col
                            cols="12"
                          >
                            <v-combobox
                              v-model="categories"
                              :items="form.categories"
                              chips
                              clearable
                              label="Categories"
                              multiple
                              solo
                            >
                              <template v-slot:selection="{ categories, item, select, selected }">
                                <v-chip
                                  v-bind="categories"
                                  :input-value="selected"
                                  close
                                  @click="select"
                                  @click:close="remove(item)"
                                >
                                  <strong>{{ item }}</strong>
                                </v-chip>
                              </template>
                            </v-combobox>
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
                        @click="createBook()"
                      >
                        Create
                      </v-btn>
                    </v-card-actions>
                  </v-card>
                </v-form>
              </v-dialog>
              <v-dialog
                v-model="editDialog"
                persistent
              >
                <v-form
                  ref="form"
                  v-model="formValid"
                >
                  <v-card>
                    <v-alert
                      :value="editDialogAlert"
                      prominent
                      dark
                      type="error"
                      color="red"
                      transition="scale-transition"
                    >
                      {{ alertMessage }}
                    </v-alert>
                    <v-card-title>
                      <span class="headline">Editing Book</span>
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
                          <v-col
                            cols="12"
                          >
                            <v-combobox
                              v-model="categories"
                              :items="form.categories"
                              chips
                              clearable
                              label="Categories"
                              multiple
                              solo
                            >
                              <template v-slot:selection="{ categories, item, select, selected }">
                                <v-chip
                                  v-bind="categories"
                                  :input-value="selected"
                                  close
                                  @click="select"
                                  @click:close="remove(item)"
                                >
                                  <strong>{{ item }}</strong>
                                </v-chip>
                              </template>
                            </v-combobox>
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
                        @click="editDialog = false"
                      >
                        Close
                      </v-btn>
                      <v-btn
                        color="green darken-1"
                        text
                        @click="editBook()"
                      >
                        Edit
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
                  color="orange"
                  @click="name = item.name; categories = item.categories; bookId = item.id; editDialog = true"
                >
                  Edit
                </v-btn>
                <v-btn
                  small
                  color="red"
                  @click="bookId = item.id; deleteDialog = true"
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
                  You want to delete this book?
                </v-card-text>

                <v-divider />

                <v-card-actions>
                  <v-spacer />
                  <v-btn
                    color="primary"
                    text
                    @click="deleteBook();"
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

import API from "../api/api";

export default {
  data() {
    return {
      name: '',
      categories: [],
      form: {},
      formValid: false,
      dialog: false,
      editDialog: false,
      deleteDialog: false,
      dialogAlert: false,
      editDialogAlert: false,
      deleteDialogAlert: false,
      alertMessage: '',
      totalItems: 0,
      loading: true,
      page: 1,
      pageCount: 0,
      itemsPerPage: 1000, // pagination does not work
      items: [],
      options: {},
      bookId: null,
      headers: [
        {text: 'Name', value: 'name'},
        {text: 'Categories', value: 'categories'},
        {text: 'Actions', value: 'actions', sortable: false }
      ]
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
    API.getCategories()
      .then(response => {
        this.form.categories = response.data.map(i => i.name)
      });
  },

  methods: {
    getData() {
      var localThis = this
      this.loading = true
      return new Promise((resolve) => {
        //const {sortBy, descending, page, rowsPerPage} = this.pagination
        let items = this.getFromApi().then(
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
    getFromApi() {
      return API.getBooks()
        .then(function (response) {
          var result = response.data;
          return result;
        }).catch(function (error) {
          // handle error
          alert(error);
        })
    },
    getColor() {
      return 'grey';
    },
    createBook() {
      this.$refs.form.validate();
      var that = this;
      this.dialogAlert = false;
      this.items = [];
      this.totalItems = 0;
      this.loading = true
      API.createBook(this.name, this.categories)
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
    editBook() {
      this.$refs.form.validate();
      var that = this;
      this.editDialogAlert = false;
      this.items = [];
      this.totalItems = 0;
      this.loading = true
      API.updateBook(this.bookId, this.name, this.categories)
        .then(function(response) {
          if (response.status !== 202) {
            that.alertMessage = response.data.error;
            that.editDialogAlert = true;
            return;
          }
          that.editDialog = false;
          that.getData()
            .then(data => {
              that.items = data.items;
              that.totalItems = data.total
            });
          return;
        }).catch(function (error) {
        // handle error
          that.alertMessage = typeof error.response === 'object' ? error.response.data.error : error.response;
          that.editDialogAlert = true;
          return;
        });
      return false;
    },
    deleteBook() {
      var that = this;
      this.deleteDialogAlert = false;
      this.items = [];
      this.totalItems = 0;
      this.loading = true
      API.deleteBook(this.bookId)
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
