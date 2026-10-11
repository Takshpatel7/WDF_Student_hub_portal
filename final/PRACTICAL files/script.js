// Dark Mode Toggle Logic
document.addEventListener('DOMContentLoaded', () => {
  const themeCheckbox = document.getElementById('theme-checkbox');
  const savedTheme = localStorage.getItem('theme');

  // Check saved theme preference or system preference
  if (savedTheme === 'dark' || (!savedTheme && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
    document.body.classList.add('dark-mode');
    document.body.classList.remove('light-mode');
    if (themeCheckbox) themeCheckbox.checked = true;
  } else {
    document.body.classList.remove('dark-mode');
    document.body.classList.add('light-mode');
    if (themeCheckbox) themeCheckbox.checked = false;
  }

  // Event listener for toggle switch
  if (themeCheckbox) {
    themeCheckbox.addEventListener('change', () => {
      if (themeCheckbox.checked) {
        document.body.classList.add('dark-mode');
        document.body.classList.remove('light-mode');
        localStorage.setItem('theme', 'dark');
      } else {
        document.body.classList.remove('dark-mode');
        document.body.classList.add('light-mode');
        localStorage.setItem('theme', 'light');
      }
    });
  }
});
