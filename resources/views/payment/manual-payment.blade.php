<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>الدفع عبر {{ $gatewayName }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .payment-card {
            background: white;
            border-radius: 20px;
            max-width: 450px;
            width: 100%;
            padding: 30px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        }

        .header {
            text-align: center;
            margin-bottom: 25px;
        }

        .header h1 {
            color: #d4af37;
            font-size: 24px;
            margin-bottom: 10px;
        }

        .header p {
            color: #666;
            font-size: 14px;
        }

        .order-info {
            background: #f8f9fa;
            border-radius: 12px;
            padding: 15px;
            margin-bottom: 20px;
            text-align: center;
        }

        .order-info .amount {
            font-size: 32px;
            font-weight: bold;
            color: #d4af37;
        }

        .order-info .order-id {
            color: #666;
            font-size: 14px;
            margin-top: 5px;
        }

        .payment-details {
            background: #f0f7ff;
            border: 2px dashed #3b82f6;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
        }

        .payment-details h3 {
            color: #1e40af;
            margin-bottom: 15px;
            font-size: 16px;
        }

        .phone-number {
            display: flex;
            align-items: center;
            gap: 10px;
            background: white;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 15px;
        }

        .phone-number input {
            flex: 1;
            border: none;
            font-size: 22px;
            font-weight: bold;
            color: #1a1a2e;
            text-align: center;
            direction: ltr;
            background: transparent;
        }

        .copy-btn {
            background: #d4af37;
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .copy-btn:hover {
            background: #b8962f;
            transform: scale(1.05);
        }

        .copy-btn.copied {
            background: #10b981;
        }

        .account-name {
            text-align: center;
            color: #666;
            font-size: 14px;
        }

        .instructions {
            background: #fff7ed;
            border-right: 4px solid #f97316;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
        }

        .instructions h4 {
            color: #c2410c;
            margin-bottom: 10px;
        }

        .instructions ol {
            padding-right: 20px;
            color: #666;
            font-size: 14px;
            line-height: 1.8;
        }

        .confirm-btn {
            display: block;
            width: 100%;
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            padding: 15px;
            border: none;
            border-radius: 12px;
            font-size: 18px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
            text-align: center;
        }

        .confirm-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(16, 185, 129, 0.3);
        }

        .back-link {
            display: block;
            text-align: center;
            margin-top: 15px;
            color: #666;
            text-decoration: none;
        }

        .back-link:hover {
            color: #d4af37;
        }

        .qr-section {
            text-align: center;
            margin: 20px 0;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 12px;
        }

        .qr-section img {
            max-width: 150px;
            margin-bottom: 10px;
        }

        .qr-section p {
            color: #666;
            font-size: 12px;
        }
    </style>
</head>

<body>
    <div class="payment-card">
        <div class="header">
            <h1>الدفع عبر {{ $gatewayName }}</h1>
            <p>يرجى تحويل المبلغ إلى الرقم أدناه</p>
        </div>

        <div class="order-info">
            <div class="amount">{{ number_format($amount, 2) }} {{ $currency }}</div>
            <div class="order-id">رقم الطلب: #{{ $order->order_serial_no }}</div>
        </div>

        <div class="payment-details">
            <h3>📱 رقم التحويل</h3>
            <div class="phone-number">
                <input type="text" id="phoneNumber" value="{{ $phoneNumber }}" readonly>
                <button class="copy-btn" onclick="copyNumber()">
                    <span id="copyIcon">📋</span>
                    <span id="copyText">نسخ</span>
                </button>
            </div>
            @if($accountName)
            <div class="account-name">
                👤 اسم الحساب: <strong>{{ $accountName }}</strong>
            </div>
            @endif
        </div>

        <div class="qr-section">
            <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ urlencode($phoneNumber) }}" alt="QR Code">
            <p>امسح الكود من تطبيق {{ $gatewayName }}</p>
        </div>

        <div class="instructions">
            <h4>📝 خطوات الدفع:</h4>
            <ol>
                <li>انسخ الرقم أعلاه أو امسح الـ QR Code</li>
                <li>افتح تطبيق {{ $gatewayName }} على هاتفك</li>
                <li>حوّل المبلغ <strong>{{ number_format($amount, 2) }} {{ $currency }}</strong></li>
                <li>بعد التحويل، اضغط "تم الدفع" أدناه</li>
            </ol>
        </div>

        <a href="{{ route('payment.success', ['paymentGateway' => $gateway, 'order' => $order->id, 'token' => $token]) }}" class="confirm-btn">
            ✅ تم الدفع - إكمال الطلب
        </a>

        <a href="{{ url('/checkout/payment') }}" class="back-link">
            ← العودة لاختيار طريقة دفع أخرى
        </a>
    </div>

    <script>
        function copyNumber() {
            const phoneInput = document.getElementById('phoneNumber');
            phoneInput.select();
            phoneInput.setSelectionRange(0, 99999);

            navigator.clipboard.writeText(phoneInput.value).then(() => {
                const btn = document.querySelector('.copy-btn');
                const copyText = document.getElementById('copyText');
                const copyIcon = document.getElementById('copyIcon');

                btn.classList.add('copied');
                copyIcon.textContent = '✅';
                copyText.textContent = 'تم النسخ!';

                setTimeout(() => {
                    btn.classList.remove('copied');
                    copyIcon.textContent = '📋';
                    copyText.textContent = 'نسخ';
                }, 2000);
            });
        }
    </script>
</body>

</html>