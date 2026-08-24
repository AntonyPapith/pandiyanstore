const queue = document.getElementById("videoQueue");
const backButton = document.querySelector(".video-back");
const videoData = window.SEYON_VIDEO_DATA || { story: "Story", videos: [] };
const controlTimers = new WeakMap();
let navigatingHome = false;

const icons = {
  play: `<svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M8 5v14l11-7Z"/></svg>`,
  pause: `<svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M7 5h4v14H7zM14 5h4v14h-4z"/></svg>`,
  muted: `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M5 9v6h4l5 4V5L9 9H5Z"/><path d="m18 9 4 4m0-4-4 4" stroke-linecap="round"/></svg>`,
  sound: `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M5 9v6h4l5 4V5L9 9H5Z"/><path d="M17 9.5a4 4 0 0 1 0 5M19.5 7a7 7 0 0 1 0 10" stroke-linecap="round"/></svg>`,
};

const socialIcons = `
  <div class="video-socials" aria-label="Social media">
    <a href="#" aria-label="WhatsApp"><img src="/logo/whatsapp.png" alt=""></a>
    <a href="#" aria-label="Instagram"><img src="/logo/instagram.png" alt=""></a>
    <a href="#" aria-label="Facebook"><img src="/logo/facebook.png" alt=""></a>
    <a href="#" aria-label="YouTube"><img src="/logo/youtube.png" alt=""></a>
  </div>`;

function updatePlayButton(video, button) {
  const isPlaying = !video.paused && !video.ended;
  button.innerHTML = isPlaying ? icons.pause : icons.play;
  button.setAttribute("aria-label", isPlaying ? "Pause video" : "Play video");
  button.title = isPlaying ? "Pause" : "Play";
}

function updateSoundButton(video, button) {
  const isMuted = video.muted || video.volume === 0;
  button.innerHTML = isMuted ? icons.muted : icons.sound;
  button.setAttribute("aria-label", isMuted ? "Turn sound on" : "Mute video");
  button.title = isMuted ? "Sound on" : "Mute";
}

function showControls(slide, autoHide = true) {
  slide.classList.add("controls-visible");
  clearTimeout(controlTimers.get(slide));
  if (autoHide) {
    controlTimers.set(slide, setTimeout(() => slide.classList.remove("controls-visible"), 2600));
  }
}

function rewindVideo(video) {
  try {
    video.currentTime = 0;
  } catch (_) {
    video.addEventListener("loadedmetadata", () => { video.currentTime = 0; }, { once: true });
  }
}

function resetAllVideos() {
  queue.querySelectorAll("video").forEach(video => {
    video.pause();
    rewindVideo(video);
  });
}

function goToHome() {
  if (navigatingHome) return;
  navigatingHome = true;
  resetAllVideos();
  window.location.replace("/");
}

function setUpSlide(slide) {
  const video = slide.querySelector("video");
  const playButton = slide.querySelector(".video-play-center");
  const soundButton = slide.querySelector(".video-sound");
  const syncPlayState = () => updatePlayButton(video, playButton);
  const syncSoundState = () => updateSoundButton(video, soundButton);

  playButton.addEventListener("click", event => {
    event.stopPropagation();
    if (video.paused || video.ended) video.play().catch(() => showControls(slide));
    else video.pause();
    showControls(slide);
  });

  soundButton.addEventListener("click", event => {
    event.stopPropagation();
    video.muted = !video.muted;
    syncSoundState();
    showControls(slide);
  });

  slide.addEventListener("click", event => {
    if (!event.target.closest("a, button")) showControls(slide);
  });

  slide.querySelectorAll(".video-socials a").forEach(link => {
    link.addEventListener("click", event => {
      event.preventDefault();
      event.stopPropagation();
      showControls(slide);
    });
  });

  video.addEventListener("play", syncPlayState);
  video.addEventListener("pause", syncPlayState);
  video.addEventListener("ended", () => {
    syncPlayState();
    showControls(slide, false);
  });
  video.addEventListener("volumechange", syncSoundState);

  syncPlayState();
  syncSoundState();
  showControls(slide);
}

function loadVideos() {
  const videos = videoData.videos;
  if (!videos.length) {
    queue.innerHTML = '<div class="video-empty"><p>No videos added for this category.</p></div>';
    return;
  }

  videos.forEach((item, index) => {
    const slide = document.createElement("section");
    slide.className = "video-slide";
    slide.tabIndex = 0;
    slide.innerHTML = `
      <video src="${item.url}" playsinline muted preload="auto" ${index === 0 ? "autoplay" : ""}></video>
      <div class="video-shade"></div>
      <div class="video-caption"><h2></h2><p></p></div>
      <button class="video-play-center video-control" type="button" aria-label="Play video"></button>
      ${socialIcons}
      <button class="video-sound video-control" type="button" aria-label="Turn sound on"></button>`;
    slide.querySelector("h2").textContent = item.title;
    slide.querySelector("p").textContent = videoData.story;
    queue.appendChild(slide);
    setUpSlide(slide);
  });

  const observer = new IntersectionObserver(entries => entries.forEach(entry => {
    const slide = entry.target;
    const video = slide.querySelector("video");
    if (entry.isIntersecting && entry.intersectionRatio > .7) {
      if (slide.dataset.active !== "true") rewindVideo(video);
      slide.dataset.active = "true";
      video.play().catch(() => showControls(slide, false));
      showControls(slide);
    } else {
      slide.dataset.active = "false";
      video.pause();
      rewindVideo(video);
      slide.classList.remove("controls-visible");
      clearTimeout(controlTimers.get(slide));
    }
  }), { root: queue, threshold: [.7] });

  queue.querySelectorAll(".video-slide").forEach(slide => observer.observe(slide));
}

history.pushState({ seyonVideoGuard: true }, "", window.location.href);
backButton?.addEventListener("click", event => {
  event.preventDefault();
  history.back();
});
window.addEventListener("popstate", goToHome);
window.addEventListener("pagehide", resetAllVideos);

loadVideos();
