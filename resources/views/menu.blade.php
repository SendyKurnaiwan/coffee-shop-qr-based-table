<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Menu Kami</title>
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="css/stylew.css">
  <!-- font -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link
    href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,300;0,400;0,700;1,700&display=swap"
    rel="stylesheet"
  />
  <!-- feather icons -->
  <script src="https://unpkg.com/feather-icons"></script>
  <!-- SweetAlert for notifications -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
  
  <nav class="navbar">
    <a href="/" class="back"><-Back</a>
    <div class="navbar-nav">
        <a href="/">Home</a>
        <a href="/#about">Tentang Kami</a>
        <a href="/menu">Menu</a>
        <a href="/#contact">kontak</a>
    </div>
    
    <div class="navbar-extra">
      <a href="#" id="hamburger-menu"><i data-feather="menu"></i></a>
    </div>
  </nav>

  <section id="menu" class="menu">
    <h2><span>Menu</span> Kami</h2>
    <h4>
      Silakan pilih menu yang anda inginkan
    </h4>
    
    <div class="row">
      @foreach ($data as $row)
        <?php
          $imagePath = 'fotomenu/'.$row->foto;
          $extension = strtolower(pathinfo($row->foto, PATHINFO_EXTENSION));
          $isJpg = in_array($extension, ['jpg', 'jpeg', 'jfif']);
          $isPng = in_array($extension, ['png']);
          
          $boxStyle = '';
          
          if ($isJpg) {
            $boxStyle = "style=\"background-image: url('" . asset($imagePath) . "');
            
            border-bottom: 0px;
            background-position: center center;\"";
          }
        ?>

        <div class="box" <?php echo $boxStyle; ?>>
          <div class="menu-card"<?php if ($isJpg): echo "style=\"background: linear-gradient(to top, rgba(0,0,0,0.9), transparent); border-radius: 0 0 20px 20px;\""; endif; ?>>
            <div>
              <?php if ($isPng): ?>
                <img src="{{ asset('fotomenu/'.$row->foto) }}" alt="{{$row->nama}}" class="menu-card-img" />
              <?php endif; ?>
              <h3 <?php if ($isJpg): echo "class=\"has-jpg-bg-title\""; else :
                echo "class=\"menu-card-title\""; endif; ?>>{{$row->nama}}</h3>
              <p <?php if ($isJpg): echo "class=\"has-jpg-bg-price\""; else :
                echo "class=\"menu-card-price\""; endif; ?>>{{ frupiah($row->harga)}}</p>
                <a href="#" 
                   class="cta beli-btn" 
                   data-id="{{ $row->id }}"
                   data-name="{{ $row->nama }}"
                   data-price="{{ $row->harga }}"
                   onclick="addToCart(event, this)">Beli</a>
            </div>
          </div>
        </div>
      @endforeach 
    </div>
    
    <!-- Cart Summary Footer -->
    <div id="cartSummary" class="footer" style="display:none">
      <div class="cart-info">
        <p><span id="totalItems">0</span> item</p>
        <p>Rp <span id="totalPrice">0</span> </p>
      </div>
      <button id="checkoutBtn" class="checkout-btn" onclick="checkout()">Checkout</button>
    </div>
  </section>

  <!-- Cart Modal -->
  <div id="cartModal" class="modal" style="display:none">
    <div class="modal-content">
      <span class="close" onclick="closeModal()">&times;</span>
      <h3>Keranjang Pesanan</h3>
      <div id="cartItems"></div>
      <div class="cart-total">
        <p>Total: Rp <span id="modalTotalPrice">0</span> </p>
        <p>Items: <span id="modalTotalItems">0</span></p>
      </div>
      <button id="confirmOrderBtn" class="confirm-btn" onclick="confirmOrder()">Konfirmasi Pesanan</button>
    </div>
  </div>

  <script>
feather.replace();

let cart = [];
let tableUserId = {{ $tableUserId ?? 'null' }};
let redirectTimer = null;

// Get grouped cart items
function getGroupedCart() {
    const grouped = {};
    
    cart.forEach(item => {
        const key = `${item.id}_${item.specialInstructions || ''}`;
        if (!grouped[key]) {
            grouped[key] = {
                id: item.id,
                name: item.name,
                price: item.price,
                quantity: 0,
                specialInstructions: item.specialInstructions || ''
            };
        }
        grouped[key].quantity += item.quantity;
    });
    
    return Object.values(grouped);
}

// Add item to cart
function addToCart(event, element) {
    event.preventDefault();
    
    const itemId = element.getAttribute('data-id');
    const itemName = element.getAttribute('data-name');
    const itemPrice = parseInt(element.getAttribute('data-price'));
    
    // Prompt for quantity
    Swal.fire({
        title: 'Jumlah Pesanan',
        input: 'number',
        inputLabel: `Masukkan jumlah untuk ${itemName}`,
        inputValue: 1,
        inputAttributes: {
            min: 1,
            max: 20,
            step: 1
        },
        showCancelButton: true,
        confirmButtonText: 'Lanjut',
        cancelButtonText: 'Batal',
        showLoaderOnConfirm: true,
        preConfirm: (quantity) => {
            if (!quantity || quantity < 1) {
                Swal.showValidationMessage('Jumlah minimal 1');
            }
            return { quantity: parseInt(quantity) };
        }
    }).then((result) => {
        if (result.isConfirmed) {
            const quantity = result.value.quantity;
            
            // Prompt for special instructions
            Swal.fire({
                title: 'Catatan Khusus',
                input: 'textarea',
                inputLabel: `Catatan untuk ${itemName} (opsional)`,
                inputPlaceholder: 'Contoh: Tanpa cabe, kurang asin, dll...',
                showCancelButton: true,
                confirmButtonText: 'Tambahkan',
                cancelButtonText: 'Tanpa Catatan',
                showLoaderOnConfirm: true,
                allowOutsideClick: () => !Swal.isLoading()
            }).then((instructionResult) => {
                if (instructionResult.isConfirmed || instructionResult.dismiss === Swal.DismissReason.cancel) {
                    const specialInstructions = instructionResult.isConfirmed ? 
                        instructionResult.value : '';
                    
                    // Check if item already exists in cart with same instructions
                    const existingItemIndex = cart.findIndex(item => 
                        item.id == itemId && 
                        item.specialInstructions === specialInstructions
                    );
                    
                    if (existingItemIndex > -1) {
                        cart[existingItemIndex].quantity += quantity;
                    } else {
                        cart.push({
                            id: itemId,
                            name: itemName,
                            price: itemPrice,
                            quantity: quantity,
                            specialInstructions: specialInstructions
                        });
                    }
                    
                    updateCartDisplay();
                    saveCartToLocalStorage();
                    
                    Swal.fire({
                        icon: 'success',
                        title: 'Ditambahkan!',
                        text: `${quantity}x ${itemName} telah ditambahkan ke keranjang`,
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 1500
                    });
                }
            });
        }
    });
}

// Update cart display
function updateCartDisplay() {
    const groupedCart = getGroupedCart();
    const totalItems = groupedCart.reduce((sum, item) => sum + item.quantity, 0);
    const totalPrice = groupedCart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
    
    document.getElementById('totalItems').textContent = totalItems;
    document.getElementById('totalPrice').textContent = totalPrice.toLocaleString('id-ID');
    
    const cartSummary = document.getElementById('cartSummary');
    if (totalItems > 0) {
        cartSummary.style.display = 'flex';
    } else {
        cartSummary.style.display = 'none';
    }
    
    updateCartModal();
}

// Update cart modal
function updateCartModal() {
    const cartItemsDiv = document.getElementById('cartItems');
    cartItemsDiv.innerHTML = '';
    
    const groupedCart = getGroupedCart();
    
    if (groupedCart.length === 0) {
        cartItemsDiv.innerHTML = '<p class="empty-cart">Keranjang kosong</p>';
        return;
    }
    
    groupedCart.forEach(item => {
        const itemDiv = document.createElement('div');
        itemDiv.className = 'cart-item';
        
        const instructionText = item.specialInstructions ? 
            `<p class="special-instruction"><small>Catatan: ${item.specialInstructions}</small></p>` : '';
        
        itemDiv.innerHTML = `
            <div class="cart-item-info">
                <h4>${item.name}</h4>
                ${instructionText}
                <p>Rp ${item.price.toLocaleString('id-ID')} x ${item.quantity}</p>
                <p>Subtotal: Rp ${(item.price * item.quantity).toLocaleString('id-ID')}</p>
            </div>
            <div class="cart-item-actions">
                <button onclick="updateCartItemQuantity('${item.id}', '${item.specialInstructions}', -1)">-</button>
                <span>${item.quantity}</span>
                <button onclick="updateCartItemQuantity('${item.id}', '${item.specialInstructions}', 1)">+</button>
                <button onclick="removeCartItem('${item.id}', '${item.specialInstructions}')" class="remove-btn">Hapus</button>
            </div>
        `;
        cartItemsDiv.appendChild(itemDiv);
    });
    
    const totalItems = groupedCart.reduce((sum, item) => sum + item.quantity, 0);
    const totalPrice = groupedCart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
    
    document.getElementById('modalTotalItems').textContent = totalItems;
    document.getElementById('modalTotalPrice').textContent = totalPrice.toLocaleString('id-ID');
}

// Update cart item quantity
function updateCartItemQuantity(itemId, specialInstructions, change) {
    const itemIndex = cart.findIndex(item => 
        item.id == itemId && 
        item.specialInstructions === specialInstructions
    );
    
    if (itemIndex > -1) {
        cart[itemIndex].quantity += change;
        
        if (cart[itemIndex].quantity <= 0) {
            cart.splice(itemIndex, 1);
        }
        
        updateCartDisplay();
        saveCartToLocalStorage();
    }
}

// Remove item from cart
function removeCartItem(itemId, specialInstructions) {
    Swal.fire({
        title: 'Hapus item?',
        text: "Item akan dihapus dari keranjang",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            cart = cart.filter(item => 
                !(item.id == itemId && item.specialInstructions === specialInstructions)
            );
            updateCartDisplay();
            saveCartToLocalStorage();
            
            Swal.fire({
                icon: 'success',
                title: 'Dihapus!',
                text: 'Item telah dihapus dari keranjang',
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 1500
            });
        }
    });
}

// Save cart to localStorage
function saveCartToLocalStorage() {
    localStorage.setItem('restaurantCart', JSON.stringify(cart));
}

// Show checkout modal
function checkout() {
    if (cart.length === 0) {
        Swal.fire({
            icon: 'warning',
            title: 'Keranjang Kosong',
            text: 'Tambahkan item terlebih dahulu!'
        });
        return;
    }
    
    document.getElementById('cartModal').style.display = 'block';
    updateCartModal();
}

// Close modal
function closeModal() {
    document.getElementById('cartModal').style.display = 'none';
}

// Clear any existing redirect timer
function clearRedirectTimer() {
    if (redirectTimer) {
        clearTimeout(redirectTimer);
        redirectTimer = null;
    }
}

// Confirm order
async function confirmOrder() {
    const confirmBtn = document.getElementById('confirmOrderBtn');
    confirmBtn.disabled = true;
    confirmBtn.textContent = 'Memproses...';
    
    // Clear any existing timer
    clearRedirectTimer();
    
    try {
        // Get the table user ID
        const user = {!! auth()->check() ? json_encode(auth()->user()) : 'null' !!};
        
        if (!user || user.role !== 'meja') {
            throw new Error('Anda harus login sebagai meja terlebih dahulu!');
        }
        
        if (cart.length === 0) {
            throw new Error('Keranjang kosong!');
        }
        
        // Get CSRF token
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
        
        // Prepare orders data
        const ordersData = {
            user_id: user.id,
            orders: cart.map(item => ({
                crud_id: parseInt(item.id),
                quantity: item.quantity,
                special_instructions: item.specialInstructions || null
            })),
        };
        
        console.log('Sending order data:', ordersData);
        
        // Send order to backend
        const response = await fetch('/orders', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify(ordersData)
        });
        
        const result = await response.json();
        console.log('Server response:', result);
        
        if (response.ok && result.success) {
            // Store the order number for redirection
            const orderNumber = result.order_number;
            
            // Show order confirmation with countdown
            let timeLeft = 5;
            
            const swalResult = await Swal.fire({
                icon: 'success',
                title: 'Pesanan Berhasil!',
                html: `
                    <div style="text-align: left; margin: 15px 0;">
                        <p><strong>Nomor Pesanan:</strong> ${orderNumber}</p>
                        <p><strong>Jumlah Item:</strong> ${result.orders ? result.orders.length : result.order.items.length}</p>
                        <p><strong>Meja:</strong> ${user.name}</p>
                        <p><strong>Status:</strong> Diterima</p>
                        <hr style="margin: 15px 0;">
                        <p id="countdown" style="font-size: 14px; color: #666;">
                            Anda akan diarahkan ke halaman detail pesanan dalam <b>${timeLeft}</b> detik...
                        </p>
                        <p style="font-size: 12px; color: #999; margin-top: 10px;">
                            Klik "OK" untuk langsung menuju ke halaman detail pesanan.
                        </p>
                    </div>
                `,
                showCancelButton: true,
                confirmButtonText: 'OK',
                cancelButtonText: 'Tunggu',
                timer: 5000, // 5 seconds
                timerProgressBar: true,
                didOpen: () => {
                    // Start countdown timer
                    const countdownElement = document.getElementById('countdown');
                    redirectTimer = setInterval(() => {
                        timeLeft--;
                        if (countdownElement) {
                            countdownElement.innerHTML = `Anda akan diarahkan ke halaman detail pesanan dalam <b>${timeLeft}</b> detik...`;
                        }
                        
                        if (timeLeft <= 0) {
                            clearRedirectTimer();
                        }
                    }, 1000);
                },
                willClose: () => {
                    clearRedirectTimer();
                }
            });
            
            // Clear cart and close modal regardless of user action
            cart = [];
            updateCartDisplay();
            localStorage.removeItem('restaurantCart');
            closeModal();
            
            // Redirect based on user action
            if (swalResult.isConfirmed || swalResult.dismiss === Swal.DismissReason.timer) {
                // User clicked OK or timer expired - redirect to order detail
                window.location.href = `/order/${orderNumber}`;
            }
            // If user clicked "Tunggu", they can stay on the current page
            
        } else {
            let errorMessage = 'Terjadi kesalahan saat memproses pesanan.';
            
            if (result.errors) {
                const errors = Object.values(result.errors).flat();
                errorMessage = errors.join(', ');
            } else if (result.message) {
                errorMessage = result.message;
            }
            
            throw new Error(errorMessage);
        }
        
    } catch (error) {
        console.error('Order error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Gagal',
            text: error.message || 'Terjadi kesalahan saat memproses pesanan. Silakan coba lagi.',
            confirmButtonText: 'OK'
        });
    } finally {
        confirmBtn.disabled = false;
        confirmBtn.textContent = 'Konfirmasi Pesanan';
    }
}

// Close modal when clicking outside
window.onclick = function(event) {
    const modal = document.getElementById('cartModal');
    if (event.target == modal) {
        closeModal();
    }
}

// Initialize cart from localStorage
document.addEventListener('DOMContentLoaded', function() {
    const savedCart = localStorage.getItem('restaurantCart');
    if (savedCart) {
        try {
            cart = JSON.parse(savedCart);
            updateCartDisplay();
        } catch (e) {
            console.error('Error loading cart:', e);
            localStorage.removeItem('restaurantCart');
            cart = [];
        }
    }
    
    // Save cart before page unload
    window.addEventListener('beforeunload', saveCartToLocalStorage);
    
    // Check if table is properly set
    if (!tableUserId || tableUserId === 'null') {
        console.warn('Table user ID not found. User might not be logged in as meja.');
    }
});
  </script>
  
  <style>
    /* Cart Summary Styles */
    .footer {
      position: fixed;
      bottom: 0;
      left: 0;
      right: 0;
      background: white;
      padding: 15px 20px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      box-shadow: 0 -2px 10px rgba(0,0,0,0.1);
      z-index: 1000;
    }
    
    .cart-info {
      display: flex;
      flex-direction: column;
      gap: 5px;
    }
    
    .cart-info p {
      margin: 0;
      font-weight: bold;
    }
    
    .cart-info p:first-child {
      color: #666;
      font-size: 14px;
    }
    
    .cart-info p:last-child {
      color: #b6895b;
      font-size: 18px;
    }
    
    .checkout-btn {
      background: #b6895b;
      color: white;
      border: none;
      padding: 12px 30px;
      border-radius: 8px;
      cursor: pointer;
      font-weight: bold;
      font-size: 16px;
      transition: background 0.3s;
    }
    
    .checkout-btn:hover {
      background: #9c744e;
    }
    
    /* Modal Styles */
    .modal {
      display: none;
      position: fixed;
      z-index: 1001;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0,0,0,0.5);
      animation: fadeIn 0.3s;
    }
    
    @keyframes fadeIn {
      from { opacity: 0; }
      to { opacity: 1; }
    }
    
    .modal-content {
      background-color: white;
      margin: 5% auto;
      padding: 25px;
      border-radius: 15px;
      width: 90%;
      max-width: 500px;
      max-height: 85vh;
      overflow-y: auto;
      animation: slideIn 0.3s;
    }
    
    @keyframes slideIn {
      from { transform: translateY(-50px); opacity: 0; }
      to { transform: translateY(0); opacity: 1; }
    }
    
    .close {
      float: right;
      font-size: 28px;
      font-weight: bold;
      cursor: pointer;
      color: #666;
      transition: color 0.3s;
    }
    
    .close:hover {
      color: #000;
    }
    
    .modal-content h3 {
      margin-top: 0;
      color: #b6895b;
      text-align: center;
      margin-bottom: 20px;
    }
    
    .cart-item {
      display: flex;
      justify-content: space-between;
      align-items: flex-start;
      padding: 15px 0;
      border-bottom: 1px solid #eee;
    }
    
    .cart-item-info h4 {
      margin: 0 0 5px 0;
      color: #333;
    }
    
    .cart-item-info p {
      margin: 2px 0;
      color: #666;
      font-size: 14px;
    }
    
    .cart-item-actions {
      display: flex;
      align-items: center;
      gap: 10px;
    }
    
    .cart-item-actions button {
      padding: 5px 10px;
      border: 1px solid #ddd;
      background: white;
      cursor: pointer;
      border-radius: 4px;
      font-weight: bold;
      transition: all 0.2s;
    }
    
    .cart-item-actions button:not(.remove-btn):hover {
      background: #f0f0f0;
    }
    
    .cart-item-actions span {
      min-width: 30px;
      text-align: center;
      font-weight: bold;
    }
    
    .remove-btn {
      background: #ff4444 !important;
      color: white;
      border: none !important;
      margin-left: 10px;
      padding: 5px 15px !important;
    }
    
    .remove-btn:hover {
      background: #cc0000 !important;
    }
    
    .cart-total {
      margin-top: 20px;
      padding-top: 20px;
      border-top: 2px solid #b6895b;
      display: flex;
      justify-content: space-between;
      font-weight: bold;
      font-size: 18px;
      color: #333;
    }
    
    .confirm-btn {
      width: 100%;
      padding: 15px;
      background: #28a745;
      color: white;
      border: none;
      border-radius: 8px;
      font-size: 16px;
      font-weight: bold;
      cursor: pointer;
      margin-top: 20px;
      transition: background 0.3s;
    }
    
    .confirm-btn:hover {
      background: #218838;
    }
    
    .confirm-btn:disabled {
      background: #cccccc;
      cursor: not-allowed;
    }
    
    /* Additional styles */
    .empty-cart {
      text-align: center;
      color: #666;
      font-style: italic;
      padding: 20px;
    }
    
    .special-instruction {
      color: #666;
      font-size: 12px;
      margin: 5px 0;
      background: #f8f9fa;
      padding: 5px;
      border-radius: 4px;
      border-left: 3px solid #b6895b;
      word-break: break-word;
    }
    
    /* Responsive adjustments */
    @media (max-width: 768px) {
      .footer {
        padding: 12px 15px;
      }
      
      .checkout-btn {
        padding: 10px 20px;
        font-size: 14px;
      }
      
      .modal-content {
        margin: 10% auto;
        padding: 20px;
        width: 95%;
      }
      
      .cart-item {
        flex-direction: column;
        align-items: flex-start;
        gap: 10px;
      }
      
      .cart-item-actions {
        width: 100%;
        justify-content: flex-end;
      }
    }
    
    /* Countdown animation */
    @keyframes pulse {
      0% { opacity: 1; }
      50% { opacity: 0.7; }
      100% { opacity: 1; }
    }
    
    #countdown b {
      color: #28a745;
      animation: pulse 1s infinite;
    }
  </style>
</body>
</html>