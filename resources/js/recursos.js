 // Script para el acordeón de preguntas frecuentes
document.querySelectorAll(".accordion-header").forEach((btn) => {
  btn.addEventListener("click", () => {
    const content = btn.nextElementSibling;
    content.style.display =
      content.style.display === "block" ? "none" : "block";
  });
});