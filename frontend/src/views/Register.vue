<template>
  <div class="auth-container">
    <h1>Register</h1>
    <form @submit.prevent="register">
      <label for="name">Name</label>
      <input type="text" v-model="name" id="name" required />

      <label for="email">Email</label>
      <input type="email" v-model="email" id="email" required />

      <label for="password">Password</label>
      <input type="password" v-model="password" id="password" required />

      <button type="submit">Register</button>
    </form>
    <p v-if="error" class="error">{{ error }}</p>
  </div>
</template>

<script>
import axios from 'axios'

export default {
  name: 'Register',
  data() {
    return {
      name: '',
      email: '',
      password: '',
      error: null,
    }
  },
  methods: {
    async register() {
      try {
        // Optionally, if you need CSRF protection for SPA auth:
        // await axios.get('http://localhost:8000/sanctum/csrf-cookie')
        const response = await axios.post('http://localhost:8000/api/register', {
          name: this.name,
          email: this.email,
          password: this.password,
        }, {
          headers: { 'Accept': 'application/json' }
        })
        // Save token to localStorage
        localStorage.setItem('access_token', response.data.access_token)
        axios.defaults.headers.common['Authorization'] = `Bearer ${response.data.access_token}`
        this.error = null
        // Redirect to Home or desired route
        this.$router.push({ name: 'Home' })
      } catch (err) {
        this.error = err.response?.data?.message || 'Registration failed'
        console.error(err)
      }
    }
  }
}
</script>

<style scoped>

</style>
