<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Add Video | Seyon Advertising</title>
<link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,700;1,700&family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/seyon.css') }}">
</head>
<body><div class="bg-glow" aria-hidden="true"></div>
<main class="add-page"><div class="add-shell">
  <a class="back-link" href="{{ route('home') }}">← Back to home</a>
  <header class="add-brand"><h1>Add Category Video</h1><p>Select a created card category, upload a video, and save it to that category's queue.</p></header>
  <form class="content-form" id="videoForm">
    <div class="form-field"><label for="videoCategory">Category</label><select id="videoCategory" required></select></div>
    <div class="form-field"><label for="videoTitle">Video title</label><input id="videoTitle" maxlength="80" placeholder="Enter video title" required></div>
    <div class="form-field"><label for="videoFile">Upload video</label><input id="videoFile" type="file" accept="video/*" required><p class="form-note">The video is stored locally in this browser.</p></div>
    <button class="form-submit" type="submit">Save video</button>
    <p class="form-message" id="videoMessage" role="status"></p>
  </form>
</div></main><script src="{{ asset('js/video-db.js') }}"></script><script src="{{ asset('js/createvideo.js') }}"></script></body>
</html>
