<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Add Content | Seyon Advertising</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,700;1,700&family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/seyon.css') }}">
</head>
<body>
<div class="bg-glow" aria-hidden="true"></div>
<main class="add-page">
  <div class="add-shell">
    <a class="back-link" href="{{ route('home') }}">← Back to home</a>
    <header class="add-brand">
      <h1 id="formHeading">Add New Story</h1>
      <p>Upload an image and add the title and description that should appear on the home page.</p>
    </header>
    <form class="content-form" id="contentForm">
      <div class="form-field">
        <label for="contentImage">Image</label>
        <input id="contentImage" name="image" type="file" accept="image/jpeg,image/png,image/webp" required>
        <p class="form-note">JPG, PNG or WebP. The image is optimized before saving.</p>
        <img class="image-preview" id="imagePreview" alt="Selected image preview">
      </div>
      <div class="form-field">
        <label for="contentTitle">Title</label>
        <input id="contentTitle" name="title" type="text" maxlength="80" placeholder="Enter a title" required>
      </div>
      <div class="form-field">
        <label for="contentDescription">Description</label>
        <textarea id="contentDescription" name="description" maxlength="500" placeholder="Enter a description" required></textarea>
      </div>
      <div class="form-field">
        <label for="contentColor">Card color</label>
        <div class="color-field">
          <input id="contentColor" name="color" type="color" value="#CD0000" aria-label="Choose card color">
          <input id="contentColorHex" type="text" value="#CD0000" maxlength="7" pattern="#[0-9A-Fa-f]{6}" placeholder="#CD0000" aria-label="Enter hex color">
        </div>
        <p class="form-note">Enter a six-digit hex color, for example #CD0000.</p>
      </div>
      <button class="form-submit" id="formSubmit" type="submit">Add to home page</button>
      <p class="form-message" id="formMessage" role="status" aria-live="polite"></p>
    </form>
  </div>
</main>
<script src="{{ asset('js/add.js') }}"></script>
</body>
</html>
