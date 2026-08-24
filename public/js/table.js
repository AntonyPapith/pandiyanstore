const tableBody = document.getElementById("storiesTableBody");

function getProjects(){
  try { return JSON.parse(localStorage.getItem("seyonProjects")) || []; }
  catch (_) { return []; }
}

function renderTable(){
  const projects = getProjects();
  tableBody.innerHTML = "";
  if (!projects.length) {
    const row = document.createElement("tr");
    row.innerHTML = '<td class="table-empty" colspan="3">No stories have been added yet.</td>';
    tableBody.appendChild(row);
    return;
  }
  projects.forEach((project, index) => {
    const row = document.createElement("tr");
    const serial = document.createElement("td");
    serial.textContent = index + 1;
    const name = document.createElement("td");
    name.textContent = project.title;
    const actions = document.createElement("td");
    actions.className = "row-actions";
    const edit = document.createElement("a");
    edit.className = "table-btn";
    edit.href = `/stories/create?edit=${encodeURIComponent(project.id)}`;
    edit.textContent = "Edit";
    const remove = document.createElement("button");
    remove.className = "table-btn delete";
    remove.type = "button";
    remove.textContent = "Delete";
    remove.addEventListener("click", () => {
      if (!window.confirm(`Delete “${project.title}”?`)) return;
      const updated = getProjects().filter(item => Number(item.id) !== Number(project.id));
      localStorage.setItem("seyonProjects", JSON.stringify(updated));
      renderTable();
    });
    actions.append(edit, remove);
    row.append(serial, name, actions);
    tableBody.appendChild(row);
  });
}

renderTable();
