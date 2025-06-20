document.getElementById('logoutBtn')?.addEventListener('click', async () => {
  try {
    const res = await fetch('./utils/logout.php', {
      method: 'POST'
    });

    const result = await res.json();

    document.getElementById('logoutResponse').textContent = result.message;
    document.getElementById('logoutResponse').style.color = 'green';

    // Redirect after a short delay
    setTimeout(() => {
      window.location.href = './index.html';
    }, 800);
  } catch (err) {
    document.getElementById('logoutResponse').textContent = 'Logout failed.';
    document.getElementById('logoutResponse').style.color = 'firebrick';
  }
});
