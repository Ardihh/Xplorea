document.addEventListener("DOMContentLoaded", function () {
  // ===== LOGIN TOGGLE =====
  const loginToggle = document.getElementById("loginToggle");
  const loginExpand = document.getElementById("loginExpand");

  if (loginToggle && loginExpand) {
    loginToggle.addEventListener("click", function (e) {
      e.preventDefault();
      loginExpand.classList.toggle("hidden");
    });

    document.addEventListener("click", function (e) {
      if (!loginToggle.contains(e.target) && !loginExpand.contains(e.target)) {
        loginExpand.classList.add("hidden");
      }
    });
  }

  // ===== CART TOGGLE =====
  const cartToggle = document.getElementById("cartToggle");
  const cartExpand = document.getElementById("cartExpand");
  const closeCart = document.getElementById("closeCart");
  const overlay = document.getElementById("overlay");

  if (cartToggle && cartExpand && closeCart && overlay) {
    cartToggle.addEventListener("click", function (e) {
      e.preventDefault();
      openCartSidebar();
    });

    closeCart.addEventListener("click", closeCartSidebar);
    overlay.addEventListener("click", closeCartSidebar);
  }

  // ===== ADD TO CART =====
  const addToCartButtons = document.querySelectorAll(".add-to-cart");
  const cartBottom = document.querySelector(".cart-expand-bottom");

  addToCartButtons.forEach((button) => {
    button.addEventListener("click", function (e) {
      e.preventDefault();

      const productId = this.getAttribute("product-id");
      const quantity = 1;
      const sizeId = this.getAttribute("data-size-id") ?? null;
      const frameId = this.getAttribute("data-frame-id") ?? null;

      const formData = new FormData();
      formData.append("quantity", quantity);
      formData.append("size_id", sizeId);
      formData.append("frame_id", frameId);

      fetch(`/cart/add/${productId}`, {
        method: "POST",
        body: formData,
        headers: {
          "X-Requested-With": "XMLHttpRequest",
        },
      })
        .then((response) => response.json())
        .then((data) => {
          console.log("Added to cart", data);
          updateCartCount(data.cart_count);
          if (cartBottom) cartBottom.style.display = "flex";
          openCartSidebar();
          setTimeout(refreshCartContent, 100);
        })
        .catch((error) => {
          console.error("Error:", error);
          alert("Failed to add item to cart. Please try again.");
        });
    });
  });

  // ===== ARTIST DROPDOWN =====
  const artistToggle = document.getElementById("artistToggle");
  const artistExpand = document.getElementById("artistExpand");
  const artistNavLink = document.querySelector(".navbar-nav .nav-link#artistToggle");

  if (artistToggle && artistExpand) {
    artistToggle.addEventListener("click", function (e) {
      e.preventDefault();
      artistExpand.classList.toggle("hidden");
      this.classList.add("active");
    });

    document.addEventListener("click", function (e) {
      if (!artistToggle.contains(e.target) && !artistExpand.contains(e.target)) {
        artistExpand.classList.add("hidden");
        if (artistNavLink) artistNavLink.classList.remove("active");
      }
    });
  }

  // ===== FORM VALIDATION =====
  const agreeTermsCheckbox = document.getElementById("agreeTerms");
  const submitBtn = document.getElementById("submitBtn");

  if (agreeTermsCheckbox && submitBtn) {
    agreeTermsCheckbox.addEventListener("change", function () {
      submitBtn.disabled = !this.checked;
    });
  }

  // ===== SIZE FORM BUTTONS =====
  const sizeFormButtons = document.querySelectorAll("#sizeForm button");
  sizeFormButtons.forEach((btn) => {
    btn.addEventListener("click", function () {
      console.log("Size selected:", this.textContent);
    });
  });

  // ===== QUANTITY CONTROL BUTTONS =====
  // Event listener untuk tombol quantity control di navbar
  document.addEventListener('click', function(e) {
    // Tombol decrease
    if (e.target.classList.contains('btn-decrease')) {
      e.preventDefault();
      const productId = e.target.getAttribute('data-product-id');
      const sizeId = e.target.getAttribute('data-size-id');
      const frameId = e.target.getAttribute('data-frame-id');
      updateQuantity(productId, -1, sizeId, frameId);
    }
    
    // Tombol increase
    if (e.target.classList.contains('btn-increase')) {
      e.preventDefault();
      const productId = e.target.getAttribute('data-product-id');
      const sizeId = e.target.getAttribute('data-size-id');
      const frameId = e.target.getAttribute('data-frame-id');
      updateQuantity(productId, 1, sizeId, frameId);
    }
  });
});

// ====== CART SIDEBAR FUNCTIONS ======
function openCartSidebar() {
  const cartExpand = document.getElementById("cartExpand");
  const overlay = document.getElementById("overlay");

  if (cartExpand) {
    cartExpand.classList.remove("hidden");
    cartExpand.style.transform = "translateX(0)";
  }

  if (overlay) {
    overlay.classList.remove("hidden");
  }

  document.body.style.overflow = "hidden";
}

function closeCartSidebar() {
  const cartExpand = document.getElementById("cartExpand");
  const overlay = document.getElementById("overlay");

  if (cartExpand) {
    cartExpand.classList.add("hidden");
    cartExpand.style.transform = "translateX(100%)";
  }

  if (overlay) {
    overlay.classList.add("hidden");
  }

  document.body.style.overflow = "auto";
}

function updateCartCount(count) {
  const cartBadge = document.querySelector("#cartToggle .badge");
  if (cartBadge) {
    cartBadge.textContent = count;
    cartBadge.style.display = count > 0 ? "inline" : "none";
  } else if (count > 0) {
    const cartButton = document.getElementById("cartToggle");
    if (cartButton) {
      const badge = document.createElement("span");
      badge.className = "position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger small";
      badge.textContent = count;
      cartButton.appendChild(badge);
    }
  }
}

function refreshCartContent() {
  fetch("/cart/getCartData", {
    method: "GET",
    headers: {
      "X-Requested-With": "XMLHttpRequest",
    },
  })
    .then((response) => response.json())
    .then((data) => {
      console.log("Cart Data:", data);
      updateCartSidebarContent(data);
    })
    .catch((error) => {
      console.error("Error refreshing cart:", error);
      window.location.reload();
    });
}

function updateCartSidebarContent(cartData) {
  const cartSidebar = document.getElementById("cart-sidebar");
  if (!cartSidebar) return;

  cartSidebar.innerHTML = "";

  cartData.forEach((item) => {
    const itemDiv = document.createElement("div");
    itemDiv.classList.add("cart-item");
    itemDiv.dataset.productId = item.product_id;
    itemDiv.dataset.sizeId = item.size_id;
    itemDiv.dataset.frameId = item.frame_id;

    // Isi innerHTML DULU
    itemDiv.innerHTML = `
      <div class="d-flex align-items-center mb-2 border-bottom pb-2">
        <img src="${item.image_url}" class="me-2" style="width: 50px; height: 50px; object-fit: cover;">
        <div>
          <div><strong>${item.name}</strong></div>
          <div>${item.description ?? ""}</div>
          <div class="d-flex align-items-center mt-1">
            <button class="btn btn-sm btn-outline-secondary btn-decrease"
              data-product-id="${item.product_id}"
              data-size-id="${item.size_id ?? ""}"
              data-frame-id="${item.frame_id ?? ""}">-</button>
            <span class="mx-2">${item.quantity}</span>
            <button class="btn btn-sm btn-outline-secondary btn-increase"
              data-product-id="${item.product_id}"
              data-size-id="${item.size_id ?? ""}"
              data-frame-id="${item.frame_id ?? ""}">+</button>
          </div>
          <div class="text-muted mt-1">Rp ${item.price.toLocaleString("id-ID")}</div>
        </div>
      </div>
    `;

    // Setelah innerHTML diisi, baru bisa ambil tombolnya
    const btnInc = itemDiv.querySelector(".btn-increase");
    const btnDec = itemDiv.querySelector(".btn-decrease");

    btnInc?.addEventListener("click", function () {
      const pid = this.getAttribute("data-product-id");
      const sid = this.getAttribute("data-size-id");
      const fid = this.getAttribute("data-frame-id");
      updateQuantity(pid, 1, sid, fid);
    });

    btnDec?.addEventListener("click", function () {
      const pid = this.getAttribute("data-product-id");
      const sid = this.getAttribute("data-size-id");
      const fid = this.getAttribute("data-frame-id");
      updateQuantity(pid, -1, sid, fid);
    });

    cartSidebar.appendChild(itemDiv);
  });
}

function updateQuantity(productId, delta, sizeId = null, frameId = null) {
  const formData = new FormData();
  formData.append("delta", delta);
  formData.append("size_id", sizeId);
  formData.append("frame_id", frameId);

  fetch(`/cart/update/${productId}`, {
    method: "POST",
    body: formData,
    headers: {
      "X-Requested-With": "XMLHttpRequest",
    },
  })
    .then((response) => response.json())
    .then((data) => {
      // Refresh cart content dan update cart count
      refreshCartContent();
      updateCartCount(data.cart_count);
      
      // Update quantity display di navbar jika ada
      updateQuantityDisplay(productId, sizeId, frameId, data.new_quantity);
      
      console.log("Update qty:", { productId, delta, sizeId, frameId });
    })
    .catch((error) => {
      console.error("Error updating quantity:", error);
      alert("Failed to update quantity. Please try again.");
    });
}

// Fungsi untuk update tampilan quantity di navbar
function updateQuantityDisplay(productId, sizeId, frameId, newQuantity) {
  // Cari tombol quantity control yang sesuai
  const quantityControls = document.querySelectorAll('.quantity-control');
  
  quantityControls.forEach(control => {
    const btnDecrease = control.querySelector('.btn-decrease');
    const btnIncrease = control.querySelector('.btn-increase');
    const quantityDisplay = control.querySelector('.px-3');
    
    if (btnDecrease && btnIncrease && quantityDisplay) {
      const controlProductId = btnDecrease.getAttribute('data-product-id');
      const controlSizeId = btnDecrease.getAttribute('data-size-id');
      const controlFrameId = btnDecrease.getAttribute('data-frame-id');
      
      // Jika ini adalah item yang sama, update quantity-nya
      if (controlProductId === productId && 
          controlSizeId === sizeId && 
          controlFrameId === frameId) {
        quantityDisplay.textContent = newQuantity;
      }
    }
  });
}

// Manual dropdown untuk user profile
document.addEventListener('DOMContentLoaded', function() {
    const userDropdown = document.getElementById('userDropdown');
    const dropdownMenu = userDropdown?.nextElementSibling;
    
    if (userDropdown && dropdownMenu) {
        userDropdown.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            // Toggle dropdown
            if (dropdownMenu.classList.contains('show')) {
                dropdownMenu.classList.remove('show');
            } else {
                dropdownMenu.classList.add('show');
            }
        });
        
        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            if (!userDropdown.contains(e.target) && !dropdownMenu.contains(e.target)) {
                dropdownMenu.classList.remove('show');
            }
        });
    }
});
