<template>
  <div class="about">
    <div class="content">
      <div>
      <img src="@/assets/360logo_l.png" alt="360_Logo" style="max-height: 300px; width: 100%;  object-fit: contain;" />
      <!-- <h5>All Images</h5> -->
      </div>
      <h1>About </h1>
      <p>
        <strong>360 Frame</strong> is an innovative full-stack web application designed for seamless image management and immersive viewing experiences.
        Built with a Laravel backend and a Vue 3 Progressive Web App (PWA) frontend, it allows users to upload images, manage visibility settings, and explore images in a unique 360° environment.
      </p>

      <h2>Key Features</h2>
      <ul>
        <li>🔒 Secure user authentication with Laravel Sanctum</li>
        <li>📸 Image upload with captions and visibility controls</li>
        <li>🖼️ Personal image library and public gallery</li>
        <li>🌐 Immersive 360° image viewer powered by Three.js</li>
        <li>📶 Offline support using IndexedDB for cached metadata</li>
      </ul>

      <h2>The 360° Viewer</h2>
      <p>
        One of the standout features of 360 Frame<strong>360° Viewer</strong> is the, which transforms standard images into interactive panoramas.
        Using <strong>Three.js</strong> and <strong>OrbitControls </strong>, the app allows users to freely explore their uploaded images in a fully navigable spherical space.
      </p>
      <p>
        Whether you're viewing a panoramic landscape, a 3D-rendered scene, or a detailed architectural space,
        the <strong>360° Viewer </strong>creates a dynamic and immersive experience that enhances how images are explored.
      </p>


    </div>
  </div>
</template>

<style>
@media (min-width: 1024px) {
  .about {
    min-height: 100vh;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 20px;
    text-align: left;
  }

  .content {
    max-width: 800px;
  }

  h1 {
    font-size: 2rem;
    margin-bottom: 50px;
    text-align: center;
    border-bottom: 2px solid rgba(128, 128, 128, 0.331);
  }

  h2 {
    font-size: 1.5rem;
    margin-top: 20px;
  }

  ul {
    list-style: none;
    padding: 0;
  }

  ul li {
    font-size: 1.1rem;
    margin: 5px 0;
  }
}
</style>
