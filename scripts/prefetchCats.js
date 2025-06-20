async function prefetchCatImages() {
  try {
    const res = await fetch('https://api.thecatapi.com/v1/images/search?limit=10&has_breeds=1', {
      headers: {
        'x-api-key': 'live_GIhaIiqRG9MlriAdZe7WZ38C3bBALOLWsT2EJjlRU6Zl4zhHQh7XLNRM2KqkfJWD'
      }
    });
    const cats = await res.json();

    // Preload images
    cats.forEach(cat => {
      const img = new Image();
      img.src = cat.url;
    });

    // Store in sessionStorage to access in gallery page
    sessionStorage.setItem('prefetchedCats', JSON.stringify(cats));
  } catch (err) {
    console.error('Prefetch failed:', err);
  }
}

prefetchCatImages()
