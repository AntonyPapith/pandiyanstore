const categorySelect=document.getElementById("videoCategory");
const videoForm=document.getElementById("videoForm");
const videoMessage=document.getElementById("videoMessage");
let projects=[];
try{ projects=JSON.parse(localStorage.getItem("seyonProjects"))||[]; }catch(_){}
categorySelect.innerHTML=projects.length?'<option value="">Select category</option>':'<option value="">No created categories</option>';
projects.forEach(project=>{ const option=document.createElement("option"); option.value=project.id; option.textContent=project.title; categorySelect.appendChild(option); });
videoForm.addEventListener("submit",async event=>{ event.preventDefault(); const project=projects.find(item=>String(item.id)===categorySelect.value); const file=document.getElementById("videoFile").files[0]; if(!project||!file){ videoMessage.textContent="Select a category and video."; return; } videoMessage.textContent="Saving video…"; try{ await saveCategoryVideo({categoryId:String(project.id),categoryName:project.title,title:document.getElementById("videoTitle").value.trim(),blob:file,createdAt:Date.now()}); window.location.href=`/videos?category=${encodeURIComponent(project.id)}`; }catch(_){ videoMessage.textContent="The video could not be saved. Check browser storage space."; } });
