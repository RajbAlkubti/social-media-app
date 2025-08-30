document.addEventListener('DOMContentLoaded', function () {

  const style = document.createElement('style');
  style.textContent = `
    .nav-item.active {
      font-weight: bold;
      background-color: rgb(243 244 246);
    }

    .explore-tab.active,
    .connection-tab.active,
    .profile-tab.active,
    .settings-tab.active,
    .admin-tab.active {
      border-bottom: 2px solid #808080;
      color: #808080;
      font-weight: bold;
    }
  `;
  document.head.appendChild(style);
});
