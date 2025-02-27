# 360 Frame

360 Frame is a full-stack web application for image management that uses a Laravel-based CMS for the backend and a Vue 3 (PWA) for the frontend. It enables users to upload images with captions and visibility settings, view them in a personal library or on a public gallery, and even enjoy an immersive 360° viewer built with Three.js. The app also supports offline viewing by caching image metadata with IndexedDB.Three.js.

![360 Frame Logo](./frontend/src/assets/360logo_color.png)

## Table of Contents

- [Overview](#overview)
- [Features](#features)
- [Tech Stack](#tech-stack)
- [Installation and Setup](#installation-and-setup)
  - [Backend (Laravel)](#backend-laravel)
  - [Frontend (Vue 3)](#frontend-vue-3)
- [Usage](#usage)
  - [Image Upload & Management](#image-upload--management)
  - [360° Viewer](#360-viewer)
  - [Offline Support](#offline-support)


## Overview

360 Frame is designed to transform how you manage and interact with your images. Whether you're uploading images to share with the world or viewing them privately in your personal library, 360 Frame offers an engaging experience by turning your images into immersive panoramas that you can explore in 360°.

## Features

- **User Authentication:** Secure registration and login powered by Laravel Sanctum.
- **Image Upload:** Users can choose a file or capture a photo via their camera. Add captions and set the image's visibility as public or private.
- **Image Management:** View, delete, and toggle the visibility of your uploaded images.
- **Public Gallery:**  Displays only public images for all visitors.
- **360° Viewer:**  Uses Three.js to display a panoramic environment based on the uploaded image.
- **Offline Support:**  Uses IndexedDB (with the idb library) to store image metadata for offline access.


## Tech Stack

- **Backend:** Laravel, PHP, Laravel Sanctum, MySQL/PostgreSQL/SQLite
- **Frontend:** Vue 3 (PWA) with Vite, Vue Router, Axios
- **3D Viewer:** Three.js with OrbitControls
- **Offline Storage:** IndexedDB (using the idb library)


## Installation and Setup
<!-- Clone the Repository -->

### Backend (Laravel)

 **Backend Directory**

cp .env.example .env
Update the .env file

composer install

php artisan migrate
php artisan storage:link


php artisan serve

### Frontend (Vue 3)
 **Frontend Directory**
npm install
npm run dev
