async function renderCats() {
  const gallery = document.getElementById('catGallery');
  const cats = JSON.parse(sessionStorage.getItem('prefetchedCats') || '[]');

  if (cats.length === 0) return;

  const toShow = hasInfo ? cats : cats.slice(0, 3);

  toShow.forEach(cat => {
    const breed = cat.breeds[0];
    const card = document.createElement('div');
    card.className = 'cat-card';
    card.innerHTML = `
      <img src="${cat.url}" alt="Cat Image">
      <div class="desc">
        <h3>${breed?.name || 'Unknown Breed'}</h3>
        <p>${breed?.description || 'No description available.'}</p>
      </div>
    `;
    gallery.appendChild(card);
  });

}

renderCats()