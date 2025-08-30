document.querySelectorAll('.post.clickable').forEach(post => {
  post.addEventListener('click', () => {
    window.location = post.dataset.url;
  });
});