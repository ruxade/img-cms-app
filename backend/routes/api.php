<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::get('/test', function () {
  return response()->json(['message' => 'API working!']);
});


// fetch('/api/test')
//   .then(res => res.json())
//   .then(data => console.log(data))
//   .catch(err => console.error('Error:', err));
