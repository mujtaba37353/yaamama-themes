// Quick View Modal functionality
(function() {
  // Create and inject the modal HTML
  function createQuickViewModal() {
    const modalHTML = `
      <div class="quick-view-overlay" id="quickViewOverlay">
        <div class="quick-view-modal">
          <button class="quick-view-close" id="quickViewClose">
            <i class="fa-solid fa-xmark"></i>
          </button>
          <div class="quick-view-content">
            <div class="quick-view-image">
              <img src="../../assets/product.png" alt="منتج" class="main-image" id="quickViewMainImage">
              <div class="quick-view-thumbnails" id="quickViewThumbnails"></div>
            </div>
            <div class="quick-view-details">
              <h2 class="product-title" id="quickViewTitle">تورته أرويو - ميني</h2>
              <p class="product-code" id="quickViewCode">#786A5</p>
              <div class="quick-view-rating" id="quickViewRating"></div>
              <div class="product-price">
                <span id="quickViewPrice">12</span>
                <img src="../../assets/ryal.svg" alt="ريال">
              </div>
              <p class="product-description">كيكة شوكولاتة لذيذة مع طبقات من الكريمة والأوريو، مثالية للمناسبات الخاصة.</p>
              
              <div class="quick-view-quantity">
                <label>الكمية:</label>
                <div class="quantity-controls">
                  <button type="button" id="quickViewMinus">-</button>
                  <span class="quantity-value" id="quickViewQuantity">1</span>
                  <button type="button" id="quickViewPlus">+</button>
                </div>
              </div>
              
              <div class="quick-view-actions">
                <button class="btn-add-cart" id="quickViewAddCart">
                  <i class="fa-solid fa-bag-shopping"></i>
                  <span>إضافة إلى السلة</span>
                </button>
                <button class="btn-buy-now" id="quickViewBuyNow">
                  <i class="fa-solid fa-credit-card"></i>
                  <span>شراء الآن</span>
                </button>
                <a href="../../templates/single product/layout.html" class="btn-view-details" id="quickViewDetails">
                  <i class="fa-solid fa-eye"></i>
                  <span>عرض التفاصيل</span>
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    `;
    
    document.body.insertAdjacentHTML('beforeend', modalHTML);
    // Fix asset URLs when running in WordPress (sweetHouseQuickView.assetUri)
    if (typeof sweetHouseQuickView !== 'undefined' && sweetHouseQuickView.assetUri) {
      var ryalImg = document.querySelector('.quick-view-details .product-price img');
      if (ryalImg) {
        ryalImg.src = sweetHouseQuickView.assetUri + 'assets/ryal.svg';
      }
      // Keep "عرض التفاصيل" visible in WordPress; href is set in openModal from productData.link
    }
  }

  // Initialize the modal
  function initQuickView() {
    if (!document.body) return;
    try {
      // Create modal if it doesn't exist
      if (!document.getElementById('quickViewOverlay')) {
        createQuickViewModal();
      } else if (typeof sweetHouseQuickView !== 'undefined' && sweetHouseQuickView.assetUri) {
        var ryalImg = document.querySelector('#quickViewOverlay .quick-view-details .product-price img');
        if (ryalImg) {
          ryalImg.src = sweetHouseQuickView.assetUri + 'assets/ryal.svg';
        }
      }

      const overlay = document.getElementById('quickViewOverlay');
      if (!overlay) return;

      const closeBtn = document.getElementById('quickViewClose');
      const minusBtn = document.getElementById('quickViewMinus');
      const plusBtn = document.getElementById('quickViewPlus');
      const quantitySpan = document.getElementById('quickViewQuantity');
      const thumbnailsContainer = document.getElementById('quickViewThumbnails');
      const mainImage = document.getElementById('quickViewMainImage');
      const addCartBtn = document.getElementById('quickViewAddCart');
      const buyNowBtn = document.getElementById('quickViewBuyNow');

      // Close modal function
      function closeModal() {
        overlay.classList.remove('active');
        document.body.style.overflow = '';
      }

      // Render star rating HTML (0-5, supports half stars)
      function renderRatingStars(rating, count) {
        if (rating == null || rating === '' || parseFloat(rating) <= 0) return '';
        var r = parseFloat(rating);
        var c = parseInt(count, 10) || 0;
        var stars = '';
        for (var i = 1; i <= 5; i++) {
          if (r >= i) {
            stars += '<span class="star filled"><i class="fa-solid fa-star"></i></span>';
          } else if (r >= i - 0.5) {
            stars += '<span class="star half"><i class="fa-solid fa-star-half-stroke"></i></span>';
          } else {
            stars += '<span class="star"><i class="fa-regular fa-star"></i></span>';
          }
        }
        var countText = c > 0 ? ' <span class="rating-count">(' + c + ')</span>' : '';
        return '<div class="rating-stars">' + stars + '</div>' + countText;
      }

      // Open modal function
      function openModal(productData) {
        productData = productData || {};
        var titleEl = document.getElementById('quickViewTitle');
        var priceEl = document.getElementById('quickViewPrice');
        var ratingEl = document.getElementById('quickViewRating');
        var detailsLink = document.getElementById('quickViewDetails');
        if (titleEl) titleEl.textContent = productData.title != null ? productData.title : '';
        if (priceEl) priceEl.textContent = productData.price != null ? productData.price : '';
        if (ratingEl) {
          var ratingHtml = renderRatingStars(productData.rating, productData.ratingCount);
          ratingEl.innerHTML = ratingHtml;
          ratingEl.style.display = ratingHtml ? '' : 'none';
        }
        if (mainImage) mainImage.src = productData.image || mainImage.src;
        if (thumbnailsContainer) {
          var gallery = productData.gallery || [];
          if (gallery.length > 1) {
            thumbnailsContainer.style.display = '';
            thumbnailsContainer.innerHTML = gallery.map(function(url, i) {
              var esc = (url + '').replace(/"/g, '&quot;');
              return '<img src="' + esc + '" alt="صورة ' + (i + 1) + '"' + (i === 0 ? ' class="active"' : '') + '>';
            }).join('');
          } else {
            thumbnailsContainer.style.display = 'none';
            thumbnailsContainer.innerHTML = '';
          }
        }
        if (detailsLink) {
          detailsLink.href = productData.link || '#';
          detailsLink.style.display = productData.hideDetails ? 'none' : '';
        }
        if (productData.addToCartUrl) {
          overlay.dataset.addToCartUrl = productData.addToCartUrl;
        } else {
          delete overlay.dataset.addToCartUrl;
        }
        if (quantitySpan) quantitySpan.textContent = '1';
        overlay.classList.add('active');
        document.body.style.overflow = 'hidden';
      }

      // Close button click
      if (closeBtn) closeBtn.addEventListener('click', closeModal);

      // Click outside modal to close
      overlay.addEventListener('click', function(e) {
        if (e.target === overlay) {
          closeModal();
        }
      });

      // Escape key to close
      document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && overlay.classList.contains('active')) {
          closeModal();
        }
      });

      // Quantity controls
      var quantity = 1;
      if (minusBtn) {
        minusBtn.addEventListener('click', function() {
          if (quantity > 1) {
            quantity--;
            if (quantitySpan) quantitySpan.textContent = quantity;
          }
        });
      }
      if (plusBtn) {
        plusBtn.addEventListener('click', function() {
          quantity++;
          if (quantitySpan) quantitySpan.textContent = quantity;
        });
      }

      // Thumbnail clicks (event delegation - works for dynamically added imgs)
      if (thumbnailsContainer) {
        thumbnailsContainer.addEventListener('click', function(e) {
          var thumb = e.target.closest('img');
          if (!thumb || !thumbnailsContainer.contains(thumb)) return;
          var imgs = thumbnailsContainer.querySelectorAll('img');
          imgs.forEach(function(t) { t.classList.remove('active'); });
          thumb.classList.add('active');
          if (mainImage) mainImage.src = thumb.src;
        });
      }

      // Add to cart button (WooCommerce: redirect to add-to-cart URL when set)
      if (addCartBtn) {
        addCartBtn.addEventListener('click', function() {
          if (overlay.dataset.addToCartUrl) {
            var qty = parseInt(quantitySpan.textContent, 10) || 1;
            var url = overlay.dataset.addToCartUrl;
            if (qty > 1) {
              url = url + (url.indexOf('?') >= 0 ? '&' : '?') + 'quantity=' + qty;
            }
            window.location.href = url;
            closeModal();
            return;
          }
          var titleEl = document.getElementById('quickViewTitle');
          var qty = parseInt(quantitySpan.textContent, 10) || 1;
          alert('تمت إضافة ' + qty + ' من "' + (titleEl ? titleEl.textContent : '') + '" إلى السلة');
          closeModal();
        });
      }

      // Buy now button (WooCommerce: use cart URL from localized script when set)
      if (buyNowBtn) {
        buyNowBtn.addEventListener('click', function() {
          var cartUrl = (typeof sweetHouseQuickView !== 'undefined' && sweetHouseQuickView.cartUrl) ? sweetHouseQuickView.cartUrl : '../../templates/cart/layout.html';
          window.location.href = cartUrl;
        });
      }

      // Expose openModal globally
      window.openQuickView = openModal;

      // Attach click handlers to all quick-add buttons
      attachQuickAddHandlers();
    } catch (err) {
      console.warn('Quick view init error:', err);
    }
  }

  // Get product data from card: prefer data attributes (from WordPress), fallback to DOM
  function getProductDataFromCard(card) {
    var defaultImg = (typeof sweetHouseQuickView !== 'undefined' && sweetHouseQuickView.assetUri) ? sweetHouseQuickView.assetUri + 'assets/product.png' : '../../assets/product.png';
    var getAttr = function(name) { return (card.getAttribute && card.getAttribute(name)) || ''; };
    var permalink = getAttr('data-product-permalink');
    if (!permalink && card.querySelector('.product-card-img a')) {
      permalink = card.querySelector('.product-card-img a').href || '';
    }
    var hasValidLink = !!(permalink && permalink !== '#');
    var title = getAttr('data-product-title');
    var price = getAttr('data-product-price');
    var image = getAttr('data-product-image');
    var addToCartUrl = getAttr('data-product-add-to-cart');
    var rating = getAttr('data-product-rating');
    var ratingCount = getAttr('data-product-rating-count');
    var galleryRaw = getAttr('data-product-gallery');
    var gallery = [];
    if (galleryRaw) {
      try {
        var parsed = JSON.parse(galleryRaw);
        gallery = Array.isArray(parsed) ? parsed : [];
      } catch (e) {}
    }
    if (!title && card.querySelector('.product-card-title')) {
      title = (card.querySelector('.product-card-title').textContent || '').trim() || 'منتج';
    }
    if (!price && card.querySelector('.price-amount')) {
      price = (card.querySelector('.price-amount').textContent || '').trim() || '0';
    }
    if (!image && card.querySelector('.product-card-img img')) {
      image = card.querySelector('.product-card-img img').src || defaultImg;
    }
    if (!addToCartUrl && card.querySelector('.add-to-cart-btn')) {
      var btn = card.querySelector('.add-to-cart-btn');
      addToCartUrl = (btn.href || btn.getAttribute('data-add-to-cart-url') || '');
    }
    return {
      title: title || 'منتج',
      price: price || '0',
      image: image || defaultImg,
      link: permalink || '',
      hideDetails: !hasValidLink,
      addToCartUrl: addToCartUrl || '',
      rating: rating || '',
      ratingCount: ratingCount || '0',
      gallery: gallery
    };
  }

  // Open quick-view modal when + button or product image/title is clicked (event delegation)
  function handleQuickViewClick(e) {
    var trigger = e.target.closest('.quick-add-btn') ||
      e.target.closest('.product-card .js-open-quick-view') ||
      e.target.closest('.product-card .product-card-img a');
    if (!trigger) return;
    var card = trigger.closest('.product-card');
    if (!card) return;
    e.preventDefault();
    e.stopPropagation();
    if (typeof window.openQuickView === 'function') {
      window.openQuickView(getProductDataFromCard(card));
    }
  }

  // Attach handlers once via delegation (works for existing and dynamically added cards)
  function attachQuickAddHandlers() {
    document.removeEventListener('click', handleQuickViewClick, true);
    document.addEventListener('click', handleQuickViewClick, true);
  }

  // Initialize when DOM is ready (body must exist for modal injection)
  function runInit() {
    initQuickView();
    attachQuickAddHandlers();
  }
  if (document.readyState === 'loading' || !document.body) {
    document.addEventListener('DOMContentLoaded', runInit);
  } else {
    runInit();
  }
})();

