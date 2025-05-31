// Basic interactivity example: a simple alert on clicking a product card

document.addEventListener('DOMContentLoaded', () => {
    const productCards = document.querySelectorAll('.product-card');

    productCards.forEach(card => {
        card.addEventListener('click', () => {
            const productName = card.querySelector('.product-name').textContent;
            alert(`You clicked on: ${productName}`);
        });
    });
});
