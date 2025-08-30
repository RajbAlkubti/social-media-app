const style = document.createElement('style');
style.textContent = `
.tab-button.active {
  border-bottom: 2px solid #808080;
  color: #808080;
  font-weight: bold;
}
`;
document.head.appendChild(style);

let currentFeedTab = 'following';

function switchFeedTab(tab) {
  currentFeedTab = tab;

  document.querySelectorAll('.tab-button').forEach(button => {
    button.classList.remove('active');
    if (button.dataset.tab === tab) {
      button.classList.add('active');
    }
  });

  document.querySelectorAll('.tab-content').forEach(section => {
    section.classList.add('hidden');
  });

  const activeContent = document.getElementById(`${tab}TabContent`);
  if (activeContent) activeContent.classList.remove('hidden');

}

document.addEventListener('DOMContentLoaded', () => {
  switchFeedTab(currentFeedTab);
});
