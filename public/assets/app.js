// Basic enhancements (auto-hide alerts)
document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('.alert').forEach(el => {
    setTimeout(() => { el.style.display = 'none'; }, 4000);
  });
});

