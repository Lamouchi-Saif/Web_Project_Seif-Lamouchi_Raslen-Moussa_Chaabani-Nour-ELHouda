document.addEventListener('DOMContentLoaded', () => {
  const cartToggleBtn = document.getElementById('cart-toggle');
  const cartDropdown = document.getElementById('cart-dropdown');
  const cartCountElem = document.querySelector('#cart-toggle .badge');
  const cartTotalElem = document.getElementById('cart-total');
  const cartItemsList = document.getElementById('cart-items-list');

  // Toggle cart dropdown visibility
  cartToggleBtn.addEventListener('click', (e) => {
    e.stopPropagation();
    cartDropdown.style.display = cartDropdown.style.display === 'block' ? 'none' : 'block';
  });

  // Hide dropdown when clicking outside
  document.addEventListener('click', (e) => {
    if (!cartDropdown.contains(e.target) && !cartToggleBtn.contains(e.target)) {
      cartDropdown.style.display = 'none';
    }
  });

  // Add to cart (called from buttons)
  window.addToCart = async function (productId) {
    try {
        const response = await fetch(`/cart/add/${productId}`, { method: 'POST' });
        const data = await response.json();
        if (data.success) {
            updateCart(); // Call to update the cart UI
            alert(`Produit ajouté. Quantité : ${data.quantity}`);
        } else {
            alert('Erreur lors de l’ajout');
        }
    } catch (err) {
        console.error(err);
        alert('!! Cannot add to cart unless you are logged in');
    }
};


  // Remove one item from cart
  document.addEventListener('submit', async (e) => {
    if (e.target.classList.contains('remove-one-form')) {
      e.preventDefault();
      const form = e.target;
      const action = form.getAttribute('action');

      try {
        const response = await fetch(action, { method: 'POST' });
        const data = await response.json();
        if (data.success) {
          updateCart();
        } else {
          alert('Erreur lors de la suppression.');
        }
      } catch (err) {
        console.error(err);
        alert('Erreur réseau');
      }
    }
  });

  // Refresh cart UI (re-fetch from backend and update DOM)
  async function updateCart() {
    try {
        const response = await fetch('/cart/items');
        const data = await response.json();
        console.log('Cart items fetched:', data); // Log the fetched data

        // Update badge
        const totalQuantity = data.reduce((sum, item) => sum + item.quantity, 0);
        cartCountElem.textContent = totalQuantity;

        if (cartItemsList) {
               // Clear previous items
               cartItemsList.innerHTML = '';
               let total = 0;

              if (data.length === 0) {
                  cartItemsList.innerHTML = '<div class="text-muted">Votre panier est vide.</div>';
                  cartTotalElem.innerHTML = 'Total : $0.00'; // Reset total when cart is empty
              } else {
                    data.forEach((item) => {
                        total += item.quantity * item.price;
                        const itemHtml = `
                            <div class="d-flex align-items-center mb-2">
                                <img src="${item.imageUrl}" alt="${item.name}" style="width: 50px; height: 50px; object-fit: cover;" class="me-2">
                                <div class="flex-grow-1">
                                    <div>${item.name}</div>
                                    <div>${item.quantity} × $${item.price}</div>
                                    <form action="/cart/remove/${item.productId}" method="post" class="remove-one-form">
                                        <button type="submit" class="btn btn-sm btn-outline-danger">−</button>
                                    </form>
                                </div>
                            </div>
                            <hr>
                        `;
                        cartItemsList.insertAdjacentHTML('beforeend', itemHtml);
                    });

                    // Update the total
                    cartTotalElem.innerHTML = `Total : $${total.toFixed(2)}`;
                  }
                }else {
               console.error('cartItemsList element not found');
              }
    } catch (e) {
        console.error(e);
    }
}

  updateCart(); // Initial load
});
