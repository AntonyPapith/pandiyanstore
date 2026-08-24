<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Manage Stories | Seyon Advertising</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,700;1,700&family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/seyon.css') }}">
</head>
<body>
<div class="bg-glow" aria-hidden="true"></div>
<main class="add-page">
  <div class="add-shell table-shell">
    <a class="back-link" href="{{ route('home') }}">← Back to home</a>
    <header class="add-brand">
      <h1>Manage Stories</h1>
      <p>Edit or delete the content added to the home page.</p>
    </header>
    <div class="table-actions"><a class="add-content-link" href="{{ route('admin.stories.create') }}">+ Add new story</a></div>
    <div class="manage-table-wrap">
      <table class="manage-table">
        <thead><tr><th>S.No</th><th>Name</th><th>Action</th></tr></thead>
        <tbody id="storiesTableBody"></tbody>
      </table>
    </div>
  </div>
</main>
<script src="{{ asset('js/table.js') }}"></script>
</body>
</html>
