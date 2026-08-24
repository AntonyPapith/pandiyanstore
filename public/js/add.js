const form = document.getElementById("contentForm");
const imageInput = document.getElementById("contentImage");
const preview = document.getElementById("imagePreview");
const message = document.getElementById("formMessage");
const colorInput = document.getElementById("contentColor");
const colorHexInput = document.getElementById("contentColorHex");
let optimizedImage = "";
const editId = Number(new URLSearchParams(window.location.search).get("edit"));
let editingProject = null;

colorInput.addEventListener("input", () => { colorHexInput.value = colorInput.value.toUpperCase(); });
colorHexInput.addEventListener("input", () => {
  const value = colorHexInput.value.trim();
  if (/^#[0-9a-f]{6}$/i.test(value)) colorInput.value = value;
});

if (editId) {
  let projects = [];
  try { projects = JSON.parse(localStorage.getItem("seyonProjects")) || []; } catch (_) {}
  editingProject = projects.find(project => Number(project.id) === editId) || null;
  if (editingProject) {
    document.getElementById("formHeading").textContent = "Edit Story";
    document.getElementById("formSubmit").textContent = "Update story";
    form.elements.title.value = editingProject.title;
    form.elements.description.value = editingProject.description;
    const savedColor = /^#[0-9a-f]{6}$/i.test(editingProject.color || "") ? editingProject.color : "#CD0000";
    colorInput.value = savedColor;
    colorHexInput.value = savedColor.toUpperCase();
    optimizedImage = editingProject.image;
    preview.src = optimizedImage;
    preview.classList.add("is-visible");
    imageInput.required = false;
  }
}

function optimizeImage(file){
  return new Promise((resolve, reject) => {
    const reader = new FileReader();
    reader.onerror = () => reject(new Error("The image could not be read."));
    reader.onload = () => {
      const image = new Image();
      image.onerror = () => reject(new Error("Please choose a valid image."));
      image.onload = () => {
        const maxSize = 900;
        const scale = Math.min(1, maxSize / Math.max(image.width, image.height));
        const canvas = document.createElement("canvas");
        canvas.width = Math.round(image.width * scale);
        canvas.height = Math.round(image.height * scale);
        canvas.getContext("2d").drawImage(image, 0, 0, canvas.width, canvas.height);
        resolve(canvas.toDataURL("image/jpeg", .76));
      };
      image.src = reader.result;
    };
    reader.readAsDataURL(file);
  });
}

imageInput.addEventListener("change", async () => {
  optimizedImage = "";
  preview.classList.remove("is-visible");
  message.textContent = "";
  const file = imageInput.files[0];
  if (!file) return;
  try {
    optimizedImage = await optimizeImage(file);
    preview.src = optimizedImage;
    preview.classList.add("is-visible");
  } catch (error) {
    imageInput.value = "";
    message.textContent = error.message;
  }
});

form.addEventListener("submit", event => {
  event.preventDefault();
  if (!optimizedImage) {
    message.textContent = "Please select an image first.";
    return;
  }

  let projects = [];
  try { projects = JSON.parse(localStorage.getItem("seyonProjects")) || []; }
  catch (_) { projects = []; }

  const newProject = {
    id: editingProject ? editingProject.id : Date.now(),
    image: optimizedImage,
    title: form.elements.title.value.trim(),
    description: form.elements.description.value.trim(),
    color: /^#[0-9a-f]{6}$/i.test(colorHexInput.value.trim()) ? colorHexInput.value.trim().toUpperCase() : "#CD0000"
  };
  if (editingProject) projects = projects.map(project => Number(project.id) === editId ? newProject : project);
  else projects.unshift(newProject);

  try {
    localStorage.setItem("seyonProjects", JSON.stringify(projects));
    if (!editingProject) window.name = `seyonProject:${JSON.stringify(newProject)}`;
    window.location.href = editingProject ? "/stories" : "/#projectsSection";
  } catch (_) {
    /* The handoff still works even when file-page storage is unavailable/full. */
    if (!editingProject) window.name = `seyonProject:${JSON.stringify(newProject)}`;
    window.location.href = editingProject ? "/stories" : "/#projectsSection";
  }
});
