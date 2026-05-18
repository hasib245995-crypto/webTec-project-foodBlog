var BASE_URL = document.querySelector('meta[name="base-url"]')?.content || '';

// Restaurant Form Validation
const restaurantForm = document.getElementById('restaurantForm');

if (restaurantForm) {
  restaurantForm.addEventListener('submit', function (e) {
    const name = this.querySelector('[name=name]').value.trim();
    const location = this.querySelector('[name=location]').value.trim();
    const area = this.querySelector('[name=area]').value.trim();
    const background = this.querySelector('[name=short_background]').value.trim();
    const goals = this.querySelector('[name=goals]').value.trim();

    if (!name || !location || !area || !background || !goals) {
      alert('All fields are required.');
      e.preventDefault();
      return;
    }
  });
}

//Menu Item Form Validation
const menuItemForm = document.getElementById('menuItemForm');

if (menuItemForm) {
  menuItemForm.addEventListener('submit', function (e) {
    const name = this.querySelector('[name=name]').value.trim();
    const description = this.querySelector('[name=description]').value.trim();
    const price = parseFloat(this.querySelector('[name=price]').value);
    const image = this.querySelector('[name=image]').files[0];

    if (!name) {
      alert('Item name is required.');
      e.preventDefault();
      return;
    }

    if (!description) {
      alert('Description is required.');
      e.preventDefault();
      return;
    }

    if (isNaN(price) || price <= 0) {
      alert('Price must be greater than 0.');
      e.preventDefault();
      return;
    }

    if (image) {
      const allowedTypes = ['image/jpeg', 'image/png'];
      if (!allowedTypes.includes(image.type)) {
        alert('Only JPEG and PNG images are allowed.');
        e.preventDefault();
        return;
      }

      if (image.size > 2 * 1024 * 1024) {
        alert('Image size must be less than 2MB.');
        e.preventDefault();
        return;
      }
    }
  });
}

//AJAX Delete Menu Item
function deleteMenuItem(id, button) {
  if (!confirm('Delete this menu item?')) return;

  const formData = new FormData();
  formData.append('id', id);

  fetch(BASE_URL + '/api/menu-items/delete', {
    method: 'POST',
    body: formData
  })
    .then(res => res.json())
    .then(data => {
      alert(data.message);
      if (data.success) {
        const card = button.closest('.card');
        if (card) card.remove();
      }
    })
    .catch(() => {
      alert('AJAX delete failed.');
    });
}

//AJAX Delete Restaurant
function deleteRestaurant(id, button) {
  if (!confirm('Delete this restaurant and all menu items?')) return;

  const formData = new FormData();
  formData.append('id', id);

  fetch(BASE_URL + '/api/restaurants/delete', {
    method: 'POST',
    body: formData
  })
    .then(res => res.json())
    .then(data => {
      alert(data.message);
      if (data.success) {
        const card = button.closest('.card');
        if (card) card.remove();
      }
    })
    .catch(() => {
      alert('AJAX delete failed.');
    });
}