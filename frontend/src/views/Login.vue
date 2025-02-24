<template>
  <div>
    <h1>Login</h1>
    <form @submit.prevent="login">
      <input type="email" v-model="email" placeholder="Email" required />
      <input type="password" v-model="password" placeholder="Password" required />
      <button type="submit">Login</button>
    </form>
    <p v-if="error" style="color: red">{{ error }}</p>
  </div>
</template>

<script>
import axios from 'axios'

export default {
  name: 'Login',
  data() {
    return {
      email: '',
      password: '',
      error: null,
    }
  },
  methods: {
    async login() {
      try {
        // Send the POST request to your Laravel endpoint
        const response = await axios.post('/api/login', {
          email: this.email,
          password: this.password,
        }, {
          headers: { 'Accept': 'application/json' }
        })
        // Store the token in localStorage after a successful login
        localStorage.setItem('access_token', response.data.access_token)
        this.error = null
        // Optionally, you can redirect the user to the home page here
        this.$router.push({ name: 'Home' })
      } catch (err) {
        this.error = err.response?.data?.message || 'Login failed'
      }
    },
  },
}
</script>
