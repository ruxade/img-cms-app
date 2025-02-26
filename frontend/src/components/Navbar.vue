

<template>
  <nav class="navbar">

    <div class="navbar-brand">
      <router-link to="/" class="navbar-item">FRAME360</router-link>
    </div>

      <div class="navbar-middle">
        <!-- HOME -->
        <router-link to="/" class="navbar-item">
          <i class="fa-solid fa-house"></i>
        </router-link>

        <!-- Images -->
        <router-link to="/images" class="navbar-item">
          <i class="fas fa-images"></i>
        </router-link>
        <!-- Upload -->
        <router-link to="/upload" class="navbar-item" >
          <i class="fa-solid fa-folder-plus"></i>
         </router-link>
      </div>

      <div class="navbar-acc">
        <!-- Account Dropdown -->
        <div class="navbar-item has-dropdown" :class="{ 'is-active': dropdownActive }">
          <a class="navbar-link" @click="toggleDropdown">
            <i class="fas fa-user-circle"></i>
          </a>
          <div class="navbar-dropdown">
            <!-- If NOT logged in -->
            <div v-if="!isLoggedIn">
              <router-link to="/register" class="navbar-item" @click="closeDropdown">
                Register
              </router-link>
              <router-link to="/login" class="navbar-item" @click="closeDropdown">
                Log in
              </router-link>
            </div>
            <!-- If logged in -->
            <div v-else>
              <a class="navbar-item" @click="logout">
                Logout
              </a>
            </div>
          </div>
        </div>
      </div>

  </nav>
</template>

<script>
export default {
  name: "Navbar",
  data() {
    return {
      dropdownActive: false,
      // Check login state using localStorage
      isLoggedIn: !!localStorage.getItem("access_token"),
    }
  },
  methods: {

    toggleDropdown() {
      this.dropdownActive = !this.dropdownActive;
    },
    closeDropdown() {
      this.dropdownActive = false;
    },
    logout() {
      localStorage.removeItem("access_token");
      this.isLoggedIn = false;
      this.closeDropdown();
      this.$router.push({ name: "Login" });
    }
  }
}
</script>

<style scoped>

.navbar {
  position: relative;
  width: 100vw;
  /* background: linear-gradient(to bottom, #213555, #3E5879); */
  /* background-color: #35374B; */
  background: linear-gradient(to bottom, #497174, #609497);
  padding: 0.75rem 1rem;
  border-bottom: 2px solid #6196A6;
  color: aliceblue;
  display: flex;
  justify-content: space-between;
  align-items: center;
  box-shadow: 0px 5px 9px #89a8b279;
  max-height: 3.5rem;
}


.navbar-brand .navbar-item {
  color: #DFD8C8;
  font-size: large;
  font-family:Cambria, Cochin, Georgia, Times, 'Times New Roman', serif;
  font-weight: 600;
  font-stretch: -50%;
}


.has-dropdown {
  position: relative;
}

.navbar-middle {
  cursor: pointer;
  color: #fff;
  font-size: 1.5rem;
  padding: 1.5rem;
  gap: 20px;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.navbar-acc{
  color: #fff;
  font-size: 1.5rem;
  padding: 0.5rem;
}

.navbar-dropdown {
  display: none;
  position: absolute;
  right: 0;
  top: 100%;
  background-color: #fff;
  border: 1px solid #ccc;
  border-radius: 4px;
  min-width: 150px;
  z-index: 1000;
}

.has-dropdown.is-active .navbar-dropdown {
  display: block;
}

.navbar-dropdown .navbar-item {
  color: #333;
  padding: 0.5rem 1rem;
  text-decoration: none;
  display: block;
  transition: background-color 0.2s;
}

a:hover {
    color:#F2A154;
    font-size: larger;
  }


#navbar-btn{
  background-color: #89A8B2;
  color: #fff;
  padding: 5px;
  width: 30px;
  height: 30px;
  cursor: pointer;
  border-radius: 100%;
  font-size: 20px;
  font-weight: bold;
}


</style>
