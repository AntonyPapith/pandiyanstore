const VIDEO_DB_NAME = "seyonVideoDatabase";
const VIDEO_STORE = "videos";
function openVideoDatabase(){
  return new Promise((resolve,reject)=>{
    const request=indexedDB.open(VIDEO_DB_NAME,1);
    request.onupgradeneeded=()=>{ const db=request.result; if(!db.objectStoreNames.contains(VIDEO_STORE)){ const store=db.createObjectStore(VIDEO_STORE,{keyPath:"id",autoIncrement:true}); store.createIndex("categoryId","categoryId"); } };
    request.onsuccess=()=>resolve(request.result); request.onerror=()=>reject(request.error);
  });
}
async function saveCategoryVideo(record){ const db=await openVideoDatabase(); return new Promise((resolve,reject)=>{ const tx=db.transaction(VIDEO_STORE,"readwrite"); tx.objectStore(VIDEO_STORE).add(record); tx.oncomplete=resolve; tx.onerror=()=>reject(tx.error); }); }
async function getCategoryVideos(categoryId){ const db=await openVideoDatabase(); return new Promise((resolve,reject)=>{ const tx=db.transaction(VIDEO_STORE,"readonly"); const request=tx.objectStore(VIDEO_STORE).index("categoryId").getAll(String(categoryId)); request.onsuccess=()=>resolve(request.result.sort((a,b)=>a.createdAt-b.createdAt)); request.onerror=()=>reject(request.error); }); }
