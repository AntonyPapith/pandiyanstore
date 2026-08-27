/* =========================================================
   SEYON ADVERTISING — carousel + reel logic
   Replace the placeholder gradients in CATEGORIES / reel items
   with real <video> sources or poster images for production.
   ========================================================= */

const ICONS = {
  home: `<path d="M4 11l8-6 8 6" stroke-linecap="round" stroke-linejoin="round"/><path d="M6 10v9h12v-9" stroke-linecap="round" stroke-linejoin="round"/>`,
  cross: `<path d="M12 4v16M4 12h16" stroke-linecap="round"/>`,
  sofa: `<path d="M5 12V8a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v4" stroke-linecap="round"/><rect x="3" y="12" width="18" height="6" rx="1.5"/><path d="M5 18v2M19 18v2" stroke-linecap="round"/>`,
  diamond: `<path d="M6 9l3-5h6l3 5-6 10-6-10Z" stroke-linejoin="round"/><path d="M6 9h12M9 4l1.5 5L9 9m6-5l-1.5 5L15 9" stroke-linejoin="round"/>`,
  burger: `<path d="M4 10h16M4 14h16" stroke-linecap="round"/><path d="M5 10a7 5 0 0 1 14 0M4 14a1.5 1.5 0 0 0 0 3h16a1.5 1.5 0 0 0 0-3" stroke-linejoin="round"/>`,
  dress: `<path d="M9 3l3 3 3-3 2 5-3 2 1 11H7l1-11-3-2 2-5Z" stroke-linejoin="round"/>`,
  cap: `<path d="M12 5 2 9l10 4 10-4-10-4Z" stroke-linejoin="round"/><path d="M6 11v4c0 1.7 2.7 3 6 3s6-1.3 6-3v-4" stroke-linejoin="round"/>`,
  car: `<path d="M4 15l1.5-5A2 2 0 0 1 7.4 9h9.2a2 2 0 0 1 1.9 1.4L20 15" stroke-linecap="round" stroke-linejoin="round"/><rect x="3" y="15" width="18" height="4" rx="1.5"/><circle cx="7.5" cy="19.5" r="1.4"/><circle cx="16.5" cy="19.5" r="1.4"/>`
};

const CATEGORIES = [
  { key:"real-estate", label:"Real Estate", icon:"home", wash:"wash-realestate",
    tagline:"Spaces that feel like a story before you move in.",
    reels:[
      "A golden-hour walkthrough of a modern hillside villa.",
      "Drone reveal of a lakeside residential project.",
      "Interior design tour — minimal luxury living room.",
      "Before / after: a full home renovation in 60 seconds."
    ]},
  { key:"hospital", label:"Hospital", icon:"cross", wash:"wash-hospital",
    tagline:"Trust, precision and care, told visually.",
    reels:[
      "Inside a state-of-the-art operation theatre.",
      "Patient care journey — from admission to recovery.",
      "Meet the specialists: a day in the ICU.",
      "New wing launch — diagnostic center walkthrough."
    ]},
  { key:"furniture", label:"Furniture", icon:"sofa", wash:"wash-furniture",
    tagline:"Craft, texture and comfort in every frame.",
    reels:[
      "Handcrafted teak collection — studio shoot.",
      "A living room styled three different ways.",
      "Behind the scenes: upholstery craftsmanship.",
      "New season catalogue — cinematic lookbook."
    ]},
  { key:"jewellery", label:"Jewellery", icon:"diamond", wash:"wash-jewellery",
    tagline:"Every stone, every setting, catching the light.",
    reels:[
      "Bridal collection — macro shots of a diamond necklace.",
      "Behind the craft: hand-setting a ruby pendant.",
      "Editorial shoot — gold jewellery on red velvet.",
      "Client story: an heirloom, reimagined.",
      "Festive collection launch — 30 second film."
    ]},
  { key:"food", label:"Food", icon:"burger", wash:"wash-food",
    tagline:"Flavour you can almost taste through the screen.",
    reels:[
      "Sizzling kitchen reel — the perfect cheese pull.",
      "Farm to table — a chef's tasting menu.",
      "Street food series: the city's best burger.",
      "New restaurant launch — ambience and plating."
    ]},
  { key:"fashion", label:"Fashion", icon:"dress", wash:"wash-fashion",
    tagline:"Movement, mood and the story of a silhouette.",
    reels:[
      "Runway highlight — evening wear collection.",
      "Editorial: red gown, golden hour, city rooftop.",
      "Behind the seams — atelier fitting session.",
      "Lookbook film — spring/summer drop."
    ]},
  { key:"education", label:"Education", icon:"cap", wash:"wash-education",
    tagline:"Milestones worth celebrating on screen.",
    reels:[
      "Campus tour — a day in the life of a student.",
      "Convocation highlights — class of 2026.",
      "Admissions film: what makes this campus different.",
      "Alumni stories — where they are now."
    ]},
  { key:"automobile", label:"Automobile", icon:"car", wash:"wash-automobile",
    tagline:"Horsepower and design, cut to the beat.",
    reels:[
      "Studio reveal — new sedan, cinematic lighting.",
      "Test drive diaries — coastal highway run.",
      "Showroom launch event — full film.",
      "Detailing series: paint correction close-ups."
    ]}
];

let carouselItems = [];
let N = CATEGORIES.length;
const fanStage   = document.getElementById("fanStage");
const reelView   = document.getElementById("reelView");
const reelScroll = document.getElementById("reelScroll");
const reelLabel  = document.getElementById("reelCategoryLabel");
const reelIcon   = document.getElementById("reelIcon");
const reelCount  = document.getElementById("reelCount");
const closeReel  = document.getElementById("closeReel");
const prevBtn    = document.getElementById("prevBtn");
const nextBtn    = document.getElementById("nextBtn");

let current = 3; // start with Jewellery centered, like the reference
let autoTimer = null;
let resumeTimer = null;
let cards = [];
let manualColorMode = false;

/* ---------- build the fan cards once ---------- */
function buildCards(){
  fanStage.innerHTML = "";
  const projects = getSavedProjects();
  carouselItems = [
    ...projects.map(project => ({ type:"project", project }))
  ];
  N = carouselItems.length;
  current = 0;

  const sidebarList = document.getElementById("sidebarList");
  if (sidebarList) {
    sidebarList.innerHTML = projects.length ? "" : '<p class="sidebar-empty">No created cards yet.</p>';
    projects.forEach((project, index) => {
      const button = document.createElement("button");
      button.className = "sidebar-item";
      button.type = "button";
      button.textContent = project.title;
      button.addEventListener("click", () => { goTo(index); closeCategorySidebar(); });
      sidebarList.appendChild(button);
    });
  }

  if (!N) {
    fanStage.innerHTML = '<p class="projects-empty is-visible">No categories created yet. Add your first category from the menu.</p>';
    document.documentElement.style.setProperty("--active-color", "#08033D");
    return;
  }

  cards = carouselItems.map((item, i) => {
    const isProject = item.type === "project";
    const cat = isProject ? null : item.category;
    const label = isProject ? item.project.title : cat.label;
    const mediaClass = isProject ? "project-media" : cat.wash;
    const mediaStyle = isProject ? ` style="background-image:url('${item.project.image}')"` : "";
    const icon = isProject
      ? `<path d="M5 5h14v14H5zM7.5 15l3-3 2.2 2.2 1.8-1.8 2 2" stroke-linejoin="round"/><circle cx="15.5" cy="8.5" r="1.2"/>`
      : ICONS[cat.icon];
    const el = document.createElement("div");
    el.className = `fan-card${isProject ? " is-project" : ""}`;
    el.dataset.cardColor = isProject && /^#[0-9a-f]{6}$/i.test(item.project.color || "") ? item.project.color : "#08033D";
    el.innerHTML = `
      <div class="card-media ${mediaClass}"${mediaStyle}>
        <span class="card-num">${String(i+1).padStart(2,"0")}</span>
        <span class="card-play"><svg viewBox="0 0 24 24"><path d="M8 5v14l11-7-11-7Z"/></svg></span>
        <div class="card-foot">
          <span class="card-icon"><svg viewBox="0 0 24 24" fill="none" stroke-width="1.8">${icon}</svg></span>
          <div class="card-label">${escapeHtml(label)}</div>
          <span class="card-view"><span>View Products</span><span class="card-view-arrow">&rarr;</span></span>
          ${isProject ? `<div class="card-description">${escapeHtml(item.project.description)}</div>` : ""}
          <div class="card-underline"></div>
        </div>
      </div>`;
    el.addEventListener("click", () => onCardClick(i));
    
    fanStage.appendChild(el);
    return el;
  });
  render();
}

/* shortest signed distance on a circular track of size N */
function offsetFor(i, center){
  let d = i - center;
  d = ((d % N) + N) % N;
  if (d > N / 2) d -= N;
  return d;
}

function render(){
  const viewport = Math.min(window.innerWidth, 720);
  const isMobile = viewport <= 600;
  const radius = isMobile ? Math.max(215, viewport * 0.65) : 250;
  const angleStep = isMobile ? 34 : 28;
  cards.forEach((el, i) => {
    const off = offsetFor(i, current);
    const abs = Math.abs(off);
    const angle = off * angleStep * Math.PI / 180;
    const x = Math.sin(angle) * radius;
    const z = isMobile
      ? (off === 0 ? 0 : -120 - abs * 35)
      : (Math.cos(angle) - 1) * radius;
    const rotateY = off * -angleStep;
    const scale = Math.max(0.68, 1 - abs * 0.075);
    const opacity = abs <= 4 ? Math.max(0, 1 - abs * 0.18) : 0;
    el.style.transform = `translate3d(${x}px, 0, ${z}px) rotateY(${rotateY}deg) scale(${scale})`;
    el.style.zIndex = 100 - abs;
    el.style.opacity = opacity;
    el.style.pointerEvents = abs <= 4 ? "auto" : "none";
    el.classList.toggle("is-active", off === 0);
    el.style.setProperty("--card-color", el.dataset.cardColor);
  });

  const activeDescription = document.getElementById("activeDescription");
  const activeItem = carouselItems[current];
  if (activeDescription && activeItem) {
    activeDescription.textContent = activeItem.type === "project"
      ? activeItem.project.description
      : activeItem.category.tagline;
  }
  const activeColor = activeItem?.type === "project" && /^#[0-9a-f]{6}$/i.test(activeItem.project.color || "") ? activeItem.project.color : "#08033D";
  document.documentElement.style.setProperty("--active-color", activeColor);
}

function goTo(i){
  if (!N) return;
  current = ((i % N) + N) % N;
  render();
}
function step(dir){ goTo(current + dir); }

const menuBtn = document.getElementById("menuBtn");
const categorySidebar = document.getElementById("categorySidebar");
const sidebarBackdrop = document.getElementById("sidebarBackdrop");
function openCategorySidebar(){ categorySidebar?.classList.add("is-open"); sidebarBackdrop?.classList.add("is-open"); categorySidebar?.setAttribute("aria-hidden", "false"); menuBtn?.setAttribute("aria-expanded", "true"); }
function closeCategorySidebar(){ categorySidebar?.classList.remove("is-open"); sidebarBackdrop?.classList.remove("is-open"); categorySidebar?.setAttribute("aria-hidden", "true"); menuBtn?.setAttribute("aria-expanded", "false"); }
menuBtn?.addEventListener("click", openCategorySidebar);
document.getElementById("closeSidebar")?.addEventListener("click", closeCategorySidebar);
sidebarBackdrop?.addEventListener("click", closeCategorySidebar);
document.addEventListener("pointerdown", event => {
  if (!categorySidebar?.classList.contains("is-open")) return;
  if (categorySidebar.contains(event.target) || menuBtn?.contains(event.target)) return;
  closeCategorySidebar();
});

/* ---------- autoplay ---------- */
function startAuto(){
  stopAuto();
  manualColorMode = false;
  render();
  autoTimer = setInterval(() => {
    manualColorMode = false;
    step(1);
  }, 3200);
}
function stopAuto(){
  if (autoTimer) clearInterval(autoTimer);
  autoTimer = null;
}
function pauseThenResume(){
  stopAuto();
  clearTimeout(resumeTimer);
  resumeTimer = setTimeout(startAuto, 4500);
}

/* ---------- drag / swipe ---------- */
let dragging = false, startX = 0, dragged = 0;
fanStage.addEventListener("pointerdown", (e) => {
  dragging = true; startX = e.clientX; dragged = 0;
  fanStage.setPointerCapture(e.pointerId);
  pauseThenResume();
});
fanStage.addEventListener("pointermove", (e) => {
  if (!dragging) return;
  dragged = e.clientX - startX;
});
fanStage.addEventListener("pointerup", () => {
  if (!dragging) return;
  dragging = false;
  if (dragged < -50) { manualColorMode = true; step(1); }
  else if (dragged > 50) { manualColorMode = true; step(-1); }
});
fanStage.addEventListener("pointercancel", () => dragging = false);

prevBtn?.addEventListener("click", () => { step(-1); pauseThenResume(); });
nextBtn?.addEventListener("click", () => { step(1); pauseThenResume(); });

/* ---------- click a card: bring to front, then open its reel ---------- */
function onCardClick(i){
  manualColorMode = false;
  pauseThenResume();
  if (window.innerWidth > 600) {
    const selectedItem = carouselItems[i];
    if (selectedItem?.type === "project") {
      window.location.assign(`/categories/${encodeURIComponent(selectedItem.project.id)}/products`);
    }
    return;
  }
  const wasCentered = offsetFor(i, current) === 0;
  goTo(i);
  if (wasCentered) {
    openCarouselItem(i);
  } else {
    setTimeout(() => openCarouselItem(i), 120);
  }
}

function openCarouselItem(i){
  const item = carouselItems[i];
  if (item.type === "project") {
    const card = cards[i];
    card?.classList.add("is-opening");
    stopAuto();
    setTimeout(() => {
      window.location.href = `/categories/${encodeURIComponent(item.project.id)}/products`;
    }, 130);
    return;
  }
  openReel(item.categoryIndex);
}

/* ---------- reel / video feed view ---------- */
let observer = null;

function openReel(i){
  const cat = CATEGORIES[i];
  reelLabel.textContent = cat.label;
  reelIcon.innerHTML = `<svg viewBox="0 0 24 24" fill="none" stroke-width="1.8">${ICONS[cat.icon]}</svg>`;
  reelCount.textContent = `${cat.reels.length} videos`;

  reelScroll.innerHTML = cat.reels.map((desc, idx) => `
    <div class="reel-item" data-idx="${idx}">
      <div class="reel-media ${cat.wash}"></div>
      <div class="reel-progress">
        ${cat.reels.map((_, p) => `<span class="bar ${p < idx ? "done" : ""}"></span>`).join("")}
      </div>
      <button class="reel-playbtn" aria-label="Play/pause">
        <svg viewBox="0 0 24 24"><path d="M8 5v14l11-7-11-7Z" fill="#fff"/></svg>
      </button>
      <div class="reel-side">
        <button aria-label="Like"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M12 21s-7-4.4-9.5-9A5.5 5.5 0 0 1 12 6a5.5 5.5 0 0 1 9.5 6c-2.5 4.6-9.5 9-9.5 9Z"/></svg><span>Like</span></button>
        <button aria-label="Share"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M4 12l16-7-6 16-3-6-7-3Z" stroke-linejoin="round"/></svg><span>Share</span></button>
      </div>
      <div class="reel-info">
        <span class="reel-badge">${cat.label} · Vol ${idx+1}</span>
        <h3>Seyon × ${cat.label}</h3>
        <p>${desc}</p>
      </div>
    </div>
  `).join("");

  reelView.classList.add("is-open");
  reelView.setAttribute("aria-hidden", "false");
  reelScroll.scrollTop = 0;
  document.body.style.overflow = "hidden";
  stopAuto();

  setupReelObserver();
  markActiveBar(0);
}

function setupReelObserver(){
  if (observer) observer.disconnect();
  observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting && entry.intersectionRatio > 0.6) {
        markActiveBar(Number(entry.target.dataset.idx));
      }
    });
  }, { root: reelScroll, threshold: [0, 0.6, 1] });

  reelScroll.querySelectorAll(".reel-item").forEach(item => {
    observer.observe(item);
    const playBtn = item.querySelector(".reel-playbtn");
    playBtn.addEventListener("click", () => item.classList.toggle("is-paused"));
  });
}

function markActiveBar(activeIdx){
  reelScroll.querySelectorAll(".reel-item").forEach(item => {
    const idx = Number(item.dataset.idx);
    item.querySelectorAll(".bar").forEach((bar, p) => {
      bar.classList.remove("active", "done");
      if (p < idx) bar.classList.add("done");
      if (p === idx) bar.classList.add("active");
    });
  });
}

/* ---------- content created on add.html ---------- */
function getSavedProjects(){
  return Array.isArray(window.PANDIAN_CATEGORIES) ? window.PANDIAN_CATEGORIES : [];
}

function escapeHtml(value){
  return String(value).replace(/[&<>'"]/g, character => ({
    "&":"&amp;", "<":"&lt;", ">":"&gt;", "'":"&#39;", '"':"&quot;"
  })[character]);
}

function renderSavedProjects(){
  const grid = document.getElementById("projectsGrid");
  const empty = document.getElementById("projectsEmpty");
  if (!grid || !empty) return;
  const projects = getSavedProjects();

  grid.innerHTML = "";
  empty.classList.toggle("is-visible", projects.length === 0);
  projects.forEach(project => {
    const article = document.createElement("article");
    article.className = "project-card";
    const img = document.createElement("img");
    img.src = project.image;
    img.alt = project.title;
    img.loading = "lazy";
    const body = document.createElement("div");
    body.className = "project-card-body";
    const title = document.createElement("h3");
    title.textContent = project.title;
    const description = document.createElement("p");
    description.textContent = project.description;
    body.append(title, description);
    article.append(img, body);
    grid.appendChild(article);
  });
}

closeReel.addEventListener("click", () => {
  reelView.classList.remove("is-open");
  reelView.setAttribute("aria-hidden", "true");
  document.body.style.overflow = "";
  if (observer) observer.disconnect();
  startAuto();
});

/* ---------- pause autoplay while user hovers the fan (desktop) ---------- */
fanStage.addEventListener("mouseenter", stopAuto);
fanStage.addEventListener("mouseleave", () => { if (!dragging) startAuto(); });

/* Recalculate fan geometry after rotation or browser resizing. */
let resizeTimer;
window.addEventListener("resize", () => {
  clearTimeout(resizeTimer);
  resizeTimer = setTimeout(render, 100);
}, { passive:true });

/* ---------- init ---------- */
buildCards();
renderSavedProjects();
startAuto();

/* Restore the carousel when returning from a video page via browser back. */
window.addEventListener("pageshow", () => {
  cards.forEach(card => card.classList.remove("is-opening"));
  reelView.classList.remove("is-open");
  reelView.setAttribute("aria-hidden", "true");
  document.body.style.overflow = "";
  render();
  startAuto();
});

window.addEventListener("pagehide", () => {
  stopAuto();
  clearTimeout(resumeTimer);
  cards.forEach(card => card.classList.remove("is-opening"));
});
