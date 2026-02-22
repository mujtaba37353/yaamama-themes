fetch("../../components/single product/y-c-single-product.html")
  .then((response) => response.text())
  .then((data) => {
    document.querySelector('[data-y="single-product"]').innerHTML = data;
    
    // Initialize single product functionality
    initSingleProduct();
  });

function initSingleProduct() {
  // Quantity controls
  const minusBtn = document.querySelector('.qty-minus');
  const plusBtn = document.querySelector('.qty-plus');
  const quantitySpan = document.querySelector('.quantity-value');
  
  let quantity = 1;
  
  if (minusBtn && plusBtn && quantitySpan) {
    minusBtn.addEventListener('click', function() {
      if (quantity > 1) {
        quantity--;
        quantitySpan.textContent = quantity;
      }
    });
    
    plusBtn.addEventListener('click', function() {
      quantity++;
      quantitySpan.textContent = quantity;
    });
  }
  
  // Thumbnail image switching
  const thumbnails = document.querySelectorAll('.product-thumbnails img');
  const mainImage = document.getElementById('mainProductImage');
  
  if (thumbnails.length && mainImage) {
    thumbnails.forEach(function(thumb) {
      thumb.addEventListener('click', function() {
        // Remove active class from all thumbnails
        thumbnails.forEach(t => t.classList.remove('active'));
        // Add active class to clicked thumbnail
        this.classList.add('active');
        // Update main image
        mainImage.src = this.src;
      });
    });
  }
  
  // Add to cart button
  const addToCartBtn = document.querySelector('.single-product-details .btn-add-cart');
  if (addToCartBtn) {
    addToCartBtn.addEventListener('click', function() {
      const title = document.querySelector('.single-product-details .product-title')?.textContent || 'المنتج';
      const qty = parseInt(quantitySpan?.textContent || '1');
      alert(`تمت إضافة ${qty} من "${title}" إلى السلة`);
    });
  }
  
  // Buy now button
  const buyNowBtn = document.querySelector('.single-product-details .btn-buy-now');
  if (buyNowBtn) {
    buyNowBtn.addEventListener('click', function() {
      window.location.href = '../../templates/cart/layout.html';
    });
  }
}
