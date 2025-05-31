
function addToCart(name, price) {
  const cartItems = document.getElementById('cart-items');
  const emptyMsg = cartItems.querySelector('p');

  if (emptyMsg) cartItems.removeChild(emptyMsg);

  const item = document.createElement('div');
  item.className = 'item';
  item.innerHTML = `<span class="name">${name}</span> <span class="price">${price}€</span>`;
  cartItems.appendChild(item);
}

document.addEventListener('DOMContentLoaded', function () {
  const cartIcon = document.getElementById('cart-icon');
  const cartDropdown = document.getElementById('cart-dropdown');

  if (cartIcon && cartDropdown) {
    cartIcon.addEventListener('click', function (e) {
      e.preventDefault();
      cartDropdown.classList.toggle('show');
    });

    document.addEventListener('click', function (e) {
      if (!cartDropdown.contains(e.target) && !cartIcon.contains(e.target)) {
        cartDropdown.classList.remove('show');
      }
    });
  }
});
