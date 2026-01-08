<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Pesanan - Admin</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <style>
        .orders-container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 20px;
        }
        
        .order-card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            border-left: 4px solid #b6895b;
        }
        
        .order-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
        }
        
        .order-number {
            font-weight: bold;
            color: #b6895b;
            font-size: 18px;
        }
        
        .order-table {
            color: #666;
            font-size: 14px;
        }
        
        .order-items {
            margin: 15px 0;
        }
        
        .order-item {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px dashed #eee;
        }
        
        .order-item:last-child {
            border-bottom: none;
        }
        
        .item-name {
            flex: 2;
        }
        
        .item-qty {
            flex: 1;
            text-align: center;
        }
        
        .item-price {
            flex: 1;
            text-align: right;
        }
        
        .order-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 15px;
            padding-top: 15px;
            border-top: 2px solid #eee;
        }
        
        .order-total {
            font-weight: bold;
            font-size: 18px;
            color: #b6895b;
        }
        
        .status-select {
            padding: 8px 15px;
            border-radius: 5px;
            border: 1px solid #ddd;
            background: white;
            cursor: pointer;
        }
        
        .status-select:focus {
            outline: none;
            border-color: #b6895b;
        }
        
        .update-btn {
            background: #28a745;
            color: white;
            border: none;
            padding: 8px 20px;
            border-radius: 5px;
            cursor: pointer;
            margin-left: 10px;
        }
        
        .update-btn:hover {
            background: #218838;
        }
        
        .no-orders {
            text-align: center;
            padding: 40px;
            color: #666;
            font-style: italic;
        }
        
        .refresh-btn {
            background: #b6895b;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            margin-bottom: 20px;
        }
        
        .special-instruction {
            font-size: 12px;
            color: #666;
            background: #f8f9fa;
            padding: 3px 8px;
            border-radius: 3px;
            margin-top: 3px;
            display: inline-block;
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <a href="/admin" class="back"><- Kembali ke Dashboard</a>
        <div class="navbar-nav">
            <a href="/admin">Menu</a>
            <a href="/meja">Meja</a>
            <a href="/admin/orders" class="active">Pesanan</a>
        </div>
    </nav>
    
    <div class="orders-container">
        <h2>Kelola Pesanan</h2>
        <button class="refresh-btn" onclick="location.reload()">🔄 Refresh Pesanan</button>
        
        @if($orders->isEmpty())
            <div class="no-orders">
                <h3>Belum ada pesanan</h3>
                <p>Tidak ada pesanan yang perlu diproses</p>
            </div>
        @else
            @foreach($orders as $orderNumber => $orderItems)
                @php
                    $totalAmount = $orderItems->sum('total_amount');
                    $totalItems = $orderItems->sum('quantity');
                    $firstItem = $orderItems->first();
                @endphp
                
                <div class="order-card" id="order-{{ $orderNumber }}">
                    <div class="order-header">
                        <div>
                            <div class="order-number">{{ $orderNumber }}</div>
                            <div class="order-table">Meja: {{ $firstItem->user->name }}</div>
                        </div>
                        <div>
                            <span>Status saat ini: </span>
                            <strong style="color: 
                                @if($firstItem->status == 'pending') #856404
                                @elseif($firstItem->status == 'processing') #004085
                                @elseif($firstItem->status == 'ready') #155724
                                @elseif($firstItem->status == 'served') #0c5460
                                @else #383d41 @endif">
                                @if($firstItem->status == 'pending')
                                    Menunggu
                                @elseif($firstItem->status == 'processing')
                                    Diproses
                                @elseif($firstItem->status == 'ready')
                                    Siap
                                @elseif($firstItem->status == 'served')
                                    Disajikan
                                @else
                                    Dibayar
                                @endif
                            </strong>
                        </div>
                    </div>
                    
                    <div class="order-items">
                        @foreach($orderItems as $item)
                            <div class="order-item">
                                <div class="item-name">
                                    {{ $item->menu->nama }}
                                    @if($item->special_instructions)
                                        <br><span class="special-instruction">{{ $item->special_instructions }}</span>
                                    @endif
                                </div>
                                <div class="item-qty">{{ $item->quantity }}x</div>
                                <div class="item-price">Rp {{ number_format($item->menu->harga, 0, ',', '.') }}</div>
                            </div>
                        @endforeach
                    </div>
                    
                    <div class="order-footer">
                        <div class="order-total">
                            Total: Rp {{ number_format($totalAmount, 0, ',', '.') }} ({{ $totalItems }} item)
                        </div>
                        <div>
                            <select class="status-select" id="status-{{ $orderNumber }}">
                                <option value="pending" {{ $firstItem->status == 'pending' ? 'selected' : '' }}>Menunggu</option>
                                <option value="processing" {{ $firstItem->status == 'processing' ? 'selected' : '' }}>Diproses</option>
                                <option value="ready" {{ $firstItem->status == 'ready' ? 'selected' : '' }}>Siap</option>
                                <option value="served" {{ $firstItem->status == 'served' ? 'selected' : '' }}>Disajikan</option>
                                <option value="paid" {{ $firstItem->status == 'paid' ? 'selected' : '' }}>Dibayar</option>
                            </select>
                            <button class="update-btn" onclick="updateOrderStatus('{{ $orderNumber }}')">Update</button>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
    </div>
    
    <script>
        async function updateOrderStatus(orderNumber) {
            const select = document.getElementById(`status-${orderNumber}`);
            const newStatus = select.value;
            
            try {
                const response = await fetch(`/admin/orders/${orderNumber}/status`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ status: newStatus })
                });
                
                const result = await response.json();
                
                if (response.ok) {
                    alert(`Status pesanan ${orderNumber} berhasil diubah menjadi ${getStatusText(newStatus)}`);
                    location.reload();
                } else {
                    throw new Error(result.message || 'Gagal mengubah status');
                }
            } catch (error) {
                alert('Error: ' + error.message);
            }
        }
        
        function getStatusText(status) {
            const statusMap = {
                'pending': 'Menunggu',
                'processing': 'Diproses',
                'ready': 'Siap',
                'served': 'Disajikan',
                'paid': 'Dibayar'
            };
            return statusMap[status] || status;
        }
        
        // Auto refresh every 30 seconds
        setInterval(() => {
            location.reload();
        }, 30000);
    </script>
</body>
</html>