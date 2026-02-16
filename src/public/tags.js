
const input  = document.getElementById("tag-input");
const chips  = document.getElementById("chips");
const hidden = document.getElementById("tags-hidden");

const selected = new Set();

function addTag(tag){
  const t = tag.trim();
  if (!t || selected.has(t)) return;

  selected.add(t);
  input.value = "";
  render();
}

function render(){
  chips.innerHTML = "";
  selected.forEach(tag => {
    const el = document.createElement("span");
    el.className = "chip";
    el.textContent = tag;
    el.onclick = () => {
      selected.delete(tag);
      render();
    };
    chips.appendChild(el);
  });
  hidden.value = Array.from(selected).join(",");
}

// When user selects from datalist
input.addEventListener("change", () => {
  addTag(input.value);
});

// When user types and presses Enter
input.addEventListener("keydown", e => {
  if (e.key === "Enter") {
    e.preventDefault();
    addTag(input.value);
  }
});

// Load initial tags
document.addEventListener("DOMContentLoaded", () => {
  const initial = hidden.value.split(",").map(t => t.trim()).filter(Boolean);
  initial.forEach(tag => selected.add(tag));
  render();
});
