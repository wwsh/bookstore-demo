import axios from "axios";

export default {
  createBook(name, categories) {
    return axios.post("http://api.localhost/api/books", {
      name: name, categories: categories
    });
  },
  updateBook(id, name, categories) {
    return axios.patch("http://api.localhost/api/books/" + id, {
      name: name, categories: categories
    });
  },
  deleteBook(id) {
    return axios.delete("http://api.localhost/api/books/" + id);
  },
  getBook(id) {
    return axios.get("http://api.localhost/api/books/" + id);
  },
  getBooks() {
    return axios.get("http://api.localhost/api/books");
  },
  createCategory(name) {
    return axios.post("http://api.localhost/api/categories", {
      name: name
    });
  },
  getCategories() {
    return axios.get("http://api.localhost/api/categories");
  },
  deleteCategory(id) {
    return axios.delete("http://api.localhost/api/categories/" + id);
  }
};
