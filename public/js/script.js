function toggleForm() {
  const form = document.getElementById("themeForm");
  form.classList.toggle("active");
  if (form.classList.contains("active")) {
    form.scrollIntoView({ behavior: "smooth", block: "start" });
  }
}

function updateColorPreview() {
  const colorInput = document.getElementById("themeColor");
  const colorPreview = document.getElementById("colorPreview");
  colorPreview.textContent = colorInput.value.toUpperCase();
  colorPreview.style.background = colorInput.value + "20";
  colorPreview.style.border = "2px solid " + colorInput.value;
  console.log(colorPreview);
  console.log(colorInput);
}

document.addEventListener("DOMContentLoaded", function () {
  updateColorPreview();
});

function checkUsername() {}
let useredit=document.getElementById("edit");
document.addEventListener("DOMContentLoaded",()=>{
useredit.addEventListener("click",()=>{
  console.log(useredit);
  const form = document.getElementById("themeForm");
  form.classList.toggle("active");
});
});
