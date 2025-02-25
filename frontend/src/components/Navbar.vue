<template>
  <nav class="navbar">
    <div class="navbar-brand">
      <router-link to="/" class="navbar-item">360Vault </router-link>
      <!-- Hamburger menu for mobile  -->
      <a role="button" class="navbar-burger" :class="{ 'is-active': burgerActive }" @click="toggleBurger">
        <span aria-hidden="true"></span>
        <span aria-hidden="true"></span>
        <span aria-hidden="true"></span>
      </a>
    </div>

    <div class="navbar-menu" :class="{ 'is-active': burgerActive }">
      <div class="navbar-start">
        <router-link to="/" class="navbar-item">Home</router-link>
        <router-link to="/images" class="navbar-item">Images</router-link>
        <router-link to="/upload" class="navbar-item">Upload</router-link>

      </div>

      <div class="navbar-end">
        <!-- If NOT logged in -->
        <div class="navbar-item" v-if="!isLoggedIn">
          <div class="buttons">
            <router-link to="/register" class="button is-primary">
              <strong>Register</strong>
            </router-link>
            <router-link to="/login" class="button is-light">Log in</router-link>
          </div>
        </div>

        <!-- If logged in -->
        <div class="navbar-item" v-else>

          <div class="button is-light" @click="logout">Logout</div>
        </div>
      </div>


    </div>
  </nav>
</template>

<script>
import axios from 'axios'

export default {
  name: "Navbar",
  data() {
    return {
      burgerActive: false,
      token: localStorage.getItem("access_token") || null,
      userEmail: localStorage.getItem("user_email") || ''
    }
  },
  computed: {
    isLoggedIn() {
      return !!this.token;
    }
  },
  methods: {
    toggleBurger() {
      this.burgerActive = !this.burgerActive;
    },
    logout() {
      // Remove token and any user data from localStorage
      localStorage.removeItem("access_token");
      localStorage.removeItem("user_email");
      delete axios.defaults.headers.common["Authorization"];
      this.token = null;
      this.userEmail = '';
      // Redirect to the Login page
      this.$router.push({ name: "Login" });
    }
  },
  created() {
    // If a token exists, set it as the default header for Axios
    if (this.token) {
      axios.defaults.headers.common["Authorization"] = `Bearer ${this.token}`;
    }
  }
}
</script>

<style scoped>
.navbar {
  background-color: #424242;
  padding: 0.75rem 1rem;
  border-bottom: 1px solid #e4e4e4;
  color: aliceblue;
  display: flex;
  justify-content: space-between;
  align-items: center;
}
.navbar-brand .navbar-item {
  font-size: 1.25rem;

  font-weight: bold;
}
.navbar-burger {
  cursor: pointer;
}
.navbar-menu {
  align-items: center;
}
.navbar-item {
  margin-right: 1rem;

}

</style>
