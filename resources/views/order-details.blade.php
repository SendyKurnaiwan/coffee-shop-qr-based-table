<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order #{{ $order->order_number }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/qrcode@1.5.3/build/qrcode.min.js"></script>
    <style>
        :root {
            --primary-color: #FF6B35;
            --secondary-color: #FFA500;
            --light-bg: #FFF9F5;
            --card-bg: #ffffff;
            --text-dark: #333333;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            border-radius: 20px;
            padding: 10px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid var(--light-bg);
        }

        .header h1 {
            color: var(--primary-color);
            margin-bottom: 10px;
        }

        .order-info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin: 30px 0;
        }

        .info-card {
            background: var(--light-bg);
            padding: 20px;
            border-radius: 15px;
            border-left: 4px solid var(--primary-color);
        }

        .qr-section {
            text-align: center;
            padding: 30px;
            background: var(--light-bg);
            border-radius: 15px;
            margin: 30px 0;
            position: relative;
        }

        .qr-code-container {
            display: inline-block;
            margin: 20px 0;
            padding: 20px;
            background: white;
            border-radius: 10px;
            position: relative;
        }

        #qrcode {
            display: inline-block;
            margin: 0 auto;
        }

        #qrcode canvas {
            border-radius: 5px;
        }

        .action-buttons {
            display: flex;
            gap: 15px;
            justify-content: center;
            margin-top: 30px;
            flex-wrap: wrap;
        }

        .btn {
            padding: 12px 25px;
            border-radius: 10px;
            border: none;
            font-weight: 600;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .btn-primary {
            background: var(--primary-color);
            color: white;
        }

        .btn-secondary {
            background: white;
            color: var(--text-dark);
            border: 2px solid var(--light-bg);
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .status-badge {
            display: inline-block;
            padding: 8px 15px;
            border-radius: 20px;
            font-weight: 600;
            margin-left: 10px;
        }

        .status-completed { background: #4CAF50; color: white; }
        .status-processing { background: #2196F3; color: white; }
        .status-pending { background: #FFC107; color: black; }

        @media print {
            .no-print {
                display: none;
            }
        }
        
        .hidden-canvas {
            display: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            
            <h1 style="font-size: 40px"> {{ $order->user->name }}</h1>
            <h1 style="color: #FFC107"><i class="fas fa-receipt"></i> {{ $order->order_number }}</h1>
            <p>Order placed on {{ $order->created_at->format('F d, Y \a\t h:i A') }}</p>
            <span class="status-badge status-{{ $order->status }}">
                {{ ucfirst($order->status) }}
            </span>
        </div>

        <div class="order-info-grid">

        </div>

        @if($order->notes)
            <div class="info-card">
                <h3><i class="fas fa-sticky-note"></i> Special Notes</h3>
                <p>{{ $order->notes }}</p>
            </div>
        @endif

        <!-- QR Code Section -->
        <div class="qr-section">
            <h3><i class="fas fa-qrcode"></i> Order QR Code</h3>
            <p>Scan this code to verify order details</p>
            
            <!-- Display QR Code using Laravel QR Code Generator -->
            <div class="qr-code-container">
                {!! QrCode::size(200)
                    ->backgroundColor(255, 255, 255)
                    ->color(255, 107, 53)
                    ->margin(1)
                    ->generate(url('/order/' . $order->order_number)) !!}
            </div>
            
            <!-- Hidden canvas for downloading QR code -->
            <canvas id="qrCanvas" class="hidden-canvas" width="300" height="300"></canvas>
            
            <p class="small-text">Scan with any QR code scanner</p>
            <p>
                <small>
                    URL: <code>{{ url('/order/' . $order->order_number) }}</code>
                </small>
            </p>
        </div>

        <!-- Order Items -->
        <div class="info-card">
            <h3><i class="fas fa-list-ul"></i> Order Items</h3>
            @foreach($order->items as $item)
                <div style="display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid #eee;">
                    <div>
                        <strong>{{ $item->menu->nama }}</strong>
                        <p style="color: #666; font-size: 0.9em; margin: 5px 0;">
                            {{ $item->quantity }} × Rp {{ number_format($item->unit_price, 0, ',', '.') }}
                        </p>
                        @if($item->special_instructions)
                            <p style="font-size: 0.85em; color: #4CAF50;">
                                <i class="fas fa-info-circle"></i> {{ $item->special_instructions }}
                            </p>
                        @endif
                    </div>
                    <div>
                        Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                    </div>
                </div>
            @endforeach
            
            <div style="text-align: right; margin-top: 20px; padding-top: 15px; border-top: 2px solid var(--primary-color);">
                <h3>Total: Rp {{ number_format($order->total_amount, 0, ',', '.') }}</h3>
            </div>
        </div>

        <div class="action-buttons">
            <a href="/orders" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Orders
            </a>
        </div>
    </div>

    <script>
        function printOrder() {
            window.print();
        }

        function downloadQRCode() {
            // Get the SVG element (Laravel QR code generates SVG by default)
            const svgElement = document.querySelector('.qr-code-container svg');
            const orderNumber = "{{ $order->order_number }}";
            
            if (svgElement) {
                // Convert SVG to canvas, then to PNG
                const canvas = document.getElementById('qrCanvas');
                const ctx = canvas.getContext('2d');
                
                // Create an image from SVG
                const svgData = new XMLSerializer().serializeToString(svgElement);
                const svgBlob = new Blob([svgData], {type: 'image/svg+xml;charset=utf-8'});
                const url = URL.createObjectURL(svgBlob);
                
                const img = new Image();
                img.onload = function() {
                    // Draw image on canvas
                    ctx.clearRect(0, 0, canvas.width, canvas.height);
                    ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
                    
                    // Convert canvas to PNG and download
                    const pngUrl = canvas.toDataURL('image/png');
                    const downloadLink = document.createElement('a');
                    downloadLink.href = pngUrl;
                    downloadLink.download = `order-${orderNumber}-qrcode.png`;
                    document.body.appendChild(downloadLink);
                    downloadLink.click();
                    document.body.removeChild(downloadLink);
                    
                    // Clean up
                    URL.revokeObjectURL(url);
                };
                
                img.src = url;
            } else {
                // Fallback: Use JavaScript QR code generator if SVG not found
                const orderUrl = "{{ url('/order/' . $order->order_number) }}";
                const canvas = document.getElementById('qrCanvas');
                
                QRCode.toCanvas(canvas, orderUrl, {
                    width: 300,
                    margin: 1,
                    color: {
                        dark: '#FF6B35',
                        light: '#FFFFFF'
                    }
                }, function(error) {
                    if (!error) {
                        const pngUrl = canvas.toDataURL('image/png');
                        const downloadLink = document.createElement('a');
                        downloadLink.href = pngUrl;
                        downloadLink.download = `order-${orderNumber}-qrcode.png`;
                        document.body.appendChild(downloadLink);
                        downloadLink.click();
                        document.body.removeChild(downloadLink);
                    } else {
                        alert('Error generating QR code for download');
                    }
                });
            }
        }
        
        // Make QR code clickable to open the order URL
        document.addEventListener('DOMContentLoaded', function() {
            const qrContainer = document.querySelector('.qr-code-container');
            const orderUrl = "{{ url('/order/' . $order->order_number) }}";
            
            if (qrContainer) {
                qrContainer.style.cursor = 'pointer';
                qrContainer.title = 'Click to open order URL: ' + orderUrl;
                qrContainer.addEventListener('click', function() {
                    window.open(orderUrl, '_blank');
                });
                
                // Also make the SVG inside clickable
                const svgElement = qrContainer.querySelector('svg');
                if (svgElement) {
                    svgElement.style.cursor = 'pointer';
                }
            }
        });
    </script>
</body>
</html>