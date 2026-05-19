
const BASE_URL = document.querySelector('meta[name="base-url"]')?.content || '';
 
function showErr(id, msg) {
  const el = document.getElementById(id);
  if (el) { el.textContent = msg; el.classList.remove('hidden'); }
}
function clearErr(id) {
  const el = document.getElementById(id);
  if (el) { el.textContent = ''; el.classList.add('hidden'); }
}
function isValidEmail(email) {
  return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
}
function escHtml(s) {
  return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}


const registerForm = document.getElementById('registerForm');
if (registerForm) {
  registerForm.addEventListener('submit', function(e) {
    let valid = true;
    ['err-name','err-email','err-password','err-password-confirm'].forEach(clearErr);

    const name  = this.querySelector('[name=name]').value.trim();
    const email = this.querySelector('[name=email]').value.trim();
    const pass  = this.querySelector('[name=password]').value;
    const pass2 = this.querySelector('[name=password_confirm]').value;

    if (name.length < 2)            { showErr('err-name', 'Name must be at least 2 characters.'); valid = false; }
    if (!isValidEmail(email))       { showErr('err-email', 'Please enter a valid email.'); valid = false; }
    if (pass.length < 8)            { showErr('err-password', 'Password must be at least 8 characters.'); valid = false; }
    if (pass && pass !== pass2)     { showErr('err-password-confirm', 'Passwords do not match.'); valid = false; }

    if (!valid) e.preventDefault();
  });
}

const profileForm = document.getElementById('profileForm');
if (profileForm) {
  profileForm.addEventListener('submit', function(e) {
    let valid = true;
    const newPass  = this.querySelector('#new_password');
    const newPass2 = this.querySelector('#new_password_confirm');
    if (newPass && newPass.value && newPass.value.length < 8) {
      alert('New password must be at least 8 characters.'); valid = false;
    }
    if (newPass && newPass2 && newPass.value && newPass.value !== newPass2.value) {
      alert('New passwords do not match.'); valid = false;
    }
    if (!valid) e.preventDefault();
  });
}


const restaurantForm = document.getElementById('restaurantForm');
if (restaurantForm) {
  restaurantForm.addEventListener('submit', function(e) {
    const name     = this.querySelector('[name=name]').value.trim();
    const location = this.querySelector('[name=location]').value.trim();
    const area     = this.querySelector('[name=area]').value.trim();
    if (!name || !location || !area) {
      alert('Name, Location, and Area are required.'); e.preventDefault();
    }
  });
}


const menuItemForm = document.getElementById('menuItemForm');
if (menuItemForm) {
  menuItemForm.addEventListener('submit', function(e) {
    const name  = this.querySelector('[name=name]').value.trim();
    const price = parseFloat(this.querySelector('[name=price]').value);
    if (!name)           { alert('Item name is required.'); e.preventDefault(); return; }
    if (isNaN(price) || price <= 0) { alert('Price must be greater than 0.'); e.preventDefault(); }
  });
}

─
const feForm = document.getElementById('feForm');
if (feForm) {
  feForm.addEventListener('submit', function(e) {
    let valid = true;
    ['err-title','err-content'].forEach(clearErr);
    const title   = this.querySelector('[name=title]').value.trim();
    const content = this.querySelector('[name=content]').value.trim();
    if (!title)   { showErr('err-title', 'Title is required.'); valid = false; }
    if (!content) { showErr('err-content', 'Content is required.'); valid = false; }
    if (!valid) e.preventDefault();
  });
}


const searchBtn = document.getElementById('search-btn');
if (searchBtn) {
  let searchTimer;
  function doSearch() {
    const q        = document.getElementById('search-q').value.trim();
    const location = document.getElementById('search-location').value.trim();
    const area     = document.getElementById('search-area').value.trim();
    if (!q && !location && !area) {
      document.getElementById('search-results').classList.add('hidden');
      return;
    }
    const params = new URLSearchParams({q, location, area});
    fetch(`${window.FOOD_BASE_URL || ''}/api/search?${params}`)
      .then(r => r.json())
      .then(data => renderSearchResults(data))
      .catch(() => {});
  }

  searchBtn.addEventListener('click', doSearch);
  ['search-q','search-location','search-area'].forEach(id => {
    document.getElementById(id)?.addEventListener('input', () => {
      clearTimeout(searchTimer);
      searchTimer = setTimeout(doSearch, 400);
    });
  });

  function renderSearchResults(data) {
    const box = document.getElementById('search-results');
    box.classList.remove('hidden');
    let html = '';

    if (data.restaurants?.length) {
      html += '<h4>Restaurants</h4>';
      data.restaurants.forEach(r => {
        html += `<div class="card" style="margin-bottom:.75rem">
          <h4><a href="${escHtml(window.FOOD_BASE_URL || '')}/restaurants/${r.id}/show">${escHtml(r.name)}</a></h4>
          <p class="meta">📍 ${escHtml(r.location)} · ${escHtml(r.area)}</p>
        </div>`;
      });
    }

    if (data.items?.length) {
      html += '<h4>Menu Items</h4>';
      data.items.forEach(i => {
        html += `<div class="card" style="margin-bottom:.75rem">
          <h4><a href="${escHtml(window.FOOD_BASE_URL || '')}/menu-items/${i.id}/show">${escHtml(i.name)}</a></h4>
          <p class="meta">From: ${escHtml(i.restaurant_name)} · ৳${parseFloat(i.price).toFixed(2)}</p>
        </div>`;
      });
    }

    if (!html) html = '<p>No results found.</p>';
    box.innerHTML = html;
  }
}

window.FOOD_BASE_URL = (function() {
  const base = document.querySelector('base');
  if (base) return base.href.replace(/\/$/, '');
  return window.location.origin + '/food_blog';
})();
