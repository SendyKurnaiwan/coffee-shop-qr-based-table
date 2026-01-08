<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order History with QR Codes</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/qrcode@1.5.3/build/qrcode.min.js"></script>
    <style>
        /* Color Palette */
        :root {
            --primary-color: #FF6B35;
            --secondary-color: #FFA500;
            --success-color: #4CAF50;
            --processing-color: #2196F3;
            --pending-color: #FFC107;
            --light-bg: #FFF9F5;
            --card-bg: #ffffff;
            --text-dark: #333333;
            --text-light: #666666;
            --border-color: #F0F0F0;
            --shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .header h1 {
            color: var(--text-dark);
            font-size: 2.5rem;
            margin-bottom: 10px;
        }

        .header p {
            color: var(--text-light);
            font-size: 1.1rem;
        }

        /* QR Button in Order Card */
        .btn-qr {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 10px;
            font-weight: 600;
            font-size: 0.95rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .btn-qr:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
        }

        .order-actions {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 12px;
            margin-top: 20px;
        }

        /* QR Modal Styles */
        .qr-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.8);
            z-index: 1000;
            justify-content: center;
            align-items: center;
            animation: fadeIn 0.3s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .qr-modal-content {
            background: white;
            padding: 30px;
            border-radius: 20px;
            max-width: 400px;
            width: 90%;
            text-align: center;
            position: relative;
            animation: slideUp 0.3s ease;
        }

        @keyframes slideUp {
            from { transform: translateY(30px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        .close-modal {
            position: absolute;
            top: 15px;
            right: 20px;
            font-size: 1.5rem;
            cursor: pointer;
            color: var(--text-light);
            transition: color 0.3s;
        }

        .close-modal:hover {
            color: var(--primary-color);
        }

        #qr-code-container {
            margin: 20px 0;
            padding: 20px;
            background: white;
            border-radius: 10px;
            display: inline-block;
        }

        .qr-info {
            margin-top: 20px;
            padding: 15px;
            background: var(--light-bg);
            border-radius: 10px;
        }

        .qr-info p {
            margin: 5px 0;
            color: var(--text-dark);
        }

        .qr-order-id {
            font-weight: 700;
            color: var(--primary-color);
            font-size: 1.2rem;
        }

        /* Keep all your existing styles from previous code */
        .order-card {
            background: var(--card-bg);
            border-radius: 16px;
            margin-bottom: 20px;
            overflow: hidden;
            border: 1px solid var(--border-color);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .order-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
        }

        .order-header {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 18px 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .order-header-left {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }
        
        .order-number {
            font-size: 1.2rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .order-date {
            font-size: 0.9rem;
            opacity: 0.9;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .order-status-badge {
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
        }
        
        .status-completed { background: var(--success-color); }
        .status-processing { background: var(--processing-color); }
        .status-pending { background: var(--pending-color); }
        .status-delivered { background: var(--success-color); }
        .status-cancelled { background: #F44336; }

        .order-notes.note-bubble {
            background: var(--light-bg);
            margin: 16px;
            padding: 12px 16px;
            border-radius: 12px;
            border-left: 4px solid var(--primary-color);
            display: flex;
            align-items: flex-start;
            gap: 12px;
        }
        
        .note-icon {
            color: var(--primary-color);
            margin-top: 2px;
        }
        
        .note-content {
            flex: 1;
            font-size: 0.95rem;
            color: var(--text-dark);
        }

        .order-items-container {
            padding: 0 24px;
        }
        
        .section-title {
            color: var(--text-dark);
            font-size: 1.1rem;
            margin: 20px 0 16px 0;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .order-item.food-item-card {
            display: flex;
            gap: 16px;
            padding: 16px 0;
            border-bottom: 1px solid var(--border-color);
            align-items: flex-start;
        }
        
        .order-item.food-item-card:last-child {
            border-bottom: none;
        }
        
        .food-item-image {
            width: 80px;
            height: 80px;
            flex-shrink: 0;
        }
        
        .food-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 12px;
        }
        
        .food-img-placeholder {
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #f5f5f5, #e0e0e0);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-light);
            font-size: 1.5rem;
        }
        
        .food-item-details {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }
        
        .food-item-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }
        
        .food-name {
            margin: 0;
            font-size: 1rem;
            font-weight: 600;
            color: var(--text-dark);
        }
        
        .food-price {
            font-weight: 700;
            color: var(--primary-color);
            font-size: 1rem;
        }
        
        .special-instruction-tag {
            background: #E8F5E9;
            color: #2E7D32;
            padding: 6px 12px;
            border-radius: 8px;
            font-size: 0.85rem;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            max-width: fit-content;
        }
        
        .food-item-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 4px;
        }
        
        .quantity-badge {
            background: var(--light-bg);
            padding: 4px 12px;
            border-radius: 6px;
            display: flex;
            align-items: center;
            gap: 4px;
            font-weight: 600;
            color: var(--text-dark);
        }
        
        .item-subtotal {
            font-weight: 700;
            color: var(--text-dark);
            font-size: 1rem;
        }

        .order-summary.card-summary {
            background: var(--light-bg);
            padding: 20px 24px;
            margin-top: 16px;
            border-top: 1px solid var(--border-color);
        }
        
        .summary-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
        }
        
        .summary-label {
            display: flex;
            align-items: center;
            gap: 10px;
            color: var(--text-light);
        }
        
        .summary-value {
            font-weight: 600;
            color: var(--text-dark);
        }
        
        .total-row {
            border-top: 2px dashed var(--border-color);
            margin-top: 8px;
            padding-top: 16px;
        }
        
        .total-amount {
            font-size: 1.3rem;
            font-weight: 700;
            color: var(--primary-color);
        }

        .btn-reorder {
            background: var(--primary-color);
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 10px;
            font-weight: 600;
            font-size: 0.95rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: all 0.3s ease;
            text-decoration: none;
        }
        
        .btn-reorder:hover {
            background: #E55A2E;
            transform: translateY(-2px);
        }
        
        .btn-details {
            background: white;
            color: var(--text-dark);
            border: 2px solid var(--border-color);
            padding: 12px 20px;
            border-radius: 10px;
            font-weight: 600;
            font-size: 0.95rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: all 0.3s ease;
            text-decoration: none;
        }
        
        .btn-details:hover {
            border-color: var(--primary-color);
            color: var(--primary-color);
            transform: translateY(-2px);
        }

        @media (max-width: 768px) {
            .order-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 12px;
            }
            
            .order-header-right {
                align-self: flex-end;
            }
            
            .order-items-container,
            .order-summary.card-summary {
                padding: 0 16px;
            }
            
            .order-actions {
                grid-template-columns: 1fr;
            }
            
            .food-item-image {
                width: 70px;
                height: 70px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1><i class="fas fa-history"></i> Order History</h1>
            <p>View and manage all your orders with QR code access</p>
        </div>

        @foreach($orders as $order)
            <div class="order-card card-shadow">
                <!-- Order Header -->
                <div class="order-header bg-gradient-primary">
                    <div class="order-header-left">
                        <div class="order-number">
                            <i class="fas fa-receipt"></i> #{{ $order->order_number }}
                        </div>
                        <div class="order-date">
                            <i class="far fa-calendar"></i> {{ $order->created_at->format('d M Y, H:i') }}
                        </div>
                    </div>
                    <div class="order-header-right">
                        <div class="order-status-badge status-{{ $order->status }}">
                            <i class="status-icon {{ $order->status === 'completed' ? 'fas fa-check-circle' : ($order->status === 'processing' ? 'fas fa-clock' : 'fas fa-utensils') }}"></i>
                            <span>{{ ucfirst($order->status) }}</span>
                        </div>
                    </div>
                </div>
                
                <!-- Order Notes -->
                @if($order->notes)
                    <div class="order-notes note-bubble">
                        <i class="fas fa-sticky-note note-icon"></i>
                        <div class="note-content">
                            <strong>Catatan Khusus:</strong> {{ $order->notes }}
                        </div>
                    </div>
                @endif
                
                <!-- Order Items -->
                <div class="order-items-container">
                    <h4 class="section-title">
                        <i class="fas fa-list-ul"></i> Items Ordered
                    </h4>
                    @foreach($order->items as $item)
                        <div class="order-item food-item-card">
                            <div class="food-item-image">
                                @if($item->menu->foto)
                                    <img src="{{ asset('fotomenu/' . $item->menu->foto) }}" alt="{{ $item->menu->nama }}" class="food-img">
                                @else
                                    <div class="food-img-placeholder">
                                        <i class="fas fa-utensils"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="food-item-details">
                                <div class="food-item-header">
                                    <h5 class="food-name">{{ $item->menu->nama }}</h5>
                                    <span class="food-price">Rp {{ number_format($item->unit_price, 0, ',', '.') }}</span>
                                </div>
                                
                                @if($item->special_instructions)
                                    <div class="special-instruction-tag">
                                        <i class="fas fa-info-circle"></i>
                                        <span>{{ $item->special_instructions }}</span>
                                    </div>
                                @endif
                                
                                <div class="food-item-footer">
                                    <div class="quantity-badge">
                                        <i class="fas fa-times qty-icon"></i>
                                        <span>{{ $item->quantity }}</span>
                                    </div>
                                    <div class="item-subtotal">
                                        Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <!-- Order Summary -->
                <div class="order-summary card-summary">
                    <div class="summary-row">
                        <div class="summary-label">
                            <i class="fas fa-box"></i>
                            <span>Total Items</span>
                        </div>
                        <div class="summary-value">
                            {{ $order->items->sum('quantity') }} items
                        </div>
                    </div>
                    
                    <div class="summary-row total-row">
                        <div class="summary-label">
                            <i class="fas fa-wallet"></i>
                            <span>Total Amount</span>
                        </div>
                        <div class="summary-value total-amount">
                            Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                        </div>
                    </div>
                    
                    <div class="order-actions">
                        <button class="btn-action btn-reorder" onclick="reorder('{{ $order->id }}')">
                            <i class="fas fa-redo"></i> Order Again
                        </button>
                        <button class="btn-action btn-details" onclick="viewDetails('{{ $order->order_number }}')">
                            <i class="fas fa-eye"></i> View Details
                        </button>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- QR Code Modal -->
    <div id="qrModal" class="qr-modal">
        <div class="qr-modal-content">
            <span class="close-modal" onclick="closeQRModal()">&times;</span>
            <h3><i class="fas fa-qrcode"></i> Order QR Code</h3>
            <p>Scan this QR code to access order details</p>
            
            <div id="qr-code-container"></div>
            
            <div class="qr-info">
                <p>Order ID: <span id="qr-order-id" class="qr-order-id"></span></p>
                <p>Generated: <span id="qr-generated-time"></span></p>
                <p class="small-text">QR content includes encrypted order data</p>
            </div>
            
            <div style="margin-top: 20px;">
                <button class="btn-action btn-details" onclick="downloadQR()">
                    <i class="fas fa-download"></i> Download QR
                </button>
            </div>
        </div>
    </div>

    <script>
        // Generate dynamic QR code for order
        function generateQRCode(orderId, orderNumber) {
            // Create unique URL for this order
            const orderUrl = `${window.location.origin}/order/${orderNumber}`;
            
            // Create QR code content (you can customize what data to include)
            const qrData = JSON.stringify({
                order_id: orderId,
                order_number: orderNumber,
                url: orderUrl,
                timestamp: new Date().toISOString()
            });
            
            // Clear previous QR code
            document.getElementById('qr-code-container').innerHTML = '';
            
            // Generate new QR code
            QRCode.toCanvas(document.getElementById('qr-code-container'), qrData, {
                width: 200,
                margin: 1,
                color: {
                    dark: '#FF6B35',
                    light: '#ffffff'
                }
            }, function(error) {
                if (error) console.error(error);
            });
            
            // Update modal info
            document.getElementById('qr-order-id').textContent = orderNumber;
            document.getElementById('qr-generated-time').textContent = new Date().toLocaleString();
            
            // Show modal
            document.getElementById('qrModal').style.display = 'flex';
        }

        function closeQRModal() {
            document.getElementById('qrModal').style.display = 'none';
        }

        function downloadQR() {
            const canvas = document.querySelector('#qr-code-container canvas');
            if (canvas) {
                const link = document.createElement('a');
                link.download = `order-qr-${document.getElementById('qr-order-id').textContent}.png`;
                link.href = canvas.toDataURL('image/png');
                link.click();
            }
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('qrModal');
            if (event.target === modal) {
                closeQRModal();
            }
        }

        function reorder(orderId) {
            alert(`Reordering order: ${orderId}`);
            // Implement reorder logic
        }

        function viewDetails(orderNumber) {
            // Navigate to order details page
            window.location.href = `/order/${orderNumber}`;
        }

        // Generate unique URL for each order on page load
        document.addEventListener('DOMContentLoaded', function() {
            // You can generate QR codes for all orders initially if needed
            console.log('Order QR system ready');
        });
    </script>
</body>
</html>