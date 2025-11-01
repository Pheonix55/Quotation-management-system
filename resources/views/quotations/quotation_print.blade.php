<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quotation #{{ $quotation->id }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
        }

        .company-info {
            margin-bottom: 20px;
        }

        .quotation-title {
            font-size: 24px;
            font-weight: bold;
            margin: 10px 0;
        }

        .section {
            margin-bottom: 25px;
        }

        .section-title {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 10px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
        }

        .row {
            display: flex;
            margin-bottom: 8px;
        }

        .col-6 {
            flex: 0 0 50%;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        .table th {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            padding: 10px;
            text-align: left;
            font-weight: bold;
        }

        .table td {
            border: 1px solid #dee2e6;
            padding: 10px;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .total-section {
            margin-top: 20px;
            border-top: 2px solid #333;
            padding-top: 10px;
        }

        .footer {
            margin-top: 50px;
            border-top: 1px solid #ddd;
            padding-top: 20px;
            font-size: 10px;
            text-align: center;
        }

        .notes {
            margin-top: 30px;
            padding: 15px;
            background-color: #f8f9fa;
            border-radius: 5px;
        }
    </style>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" />
</head>

<body>
    <div class="card p-4">

        <div class="header">
            <div class="company-info">
                <h1>Quotation Management System</h1>
                <p>G13/1- Islamabad</p>
                <p>Phone: (123) 456-7890 • Email: info@company.com • Website: www.company.com</p>
            </div>
            <div class="quotation-title">QUOTATION</div>
        </div>

        <div class="section">
            <div class="row">
                <div class="col-6">
                    <strong>Quotation Number:</strong> Q-{{ str_pad($quotation->id, 6, '0', STR_PAD_LEFT) }}<br>
                    <strong>Quotation Date:</strong>
                    {{ \Carbon\Carbon::parse($quotation->quotation_date)->format('F d, Y') }}<br>
                    <strong>Quotation Time:</strong> {{ $quotation->quotation_time }}<br>
                    <strong>Valid Until:</strong>
                    {{ \Carbon\Carbon::parse($quotation->validity_date)->format('F d, Y') }}
                </div>
                <div class="col-6">
                    @if ($quotation->customer)
                        <strong>Bill To:</strong><br>
                        {{ $quotation->customer->name }}<br>
                        @if ($quotation->customer->email)
                            {{ $quotation->customer->email }}<br>
                        @endif
                        @if ($quotation->customer->phone)
                            {{ $quotation->customer->phone }}<br>
                        @endif
                        @if ($quotation->customer->address)
                            {{ $quotation->customer->address }}
                        @endif
                    @endif
                </div>
            </div>
        </div>

        <div class="section">
            <div class="section-title">PRODUCTS & SERVICES</div>
            <table class="table">
                <thead>
                    <tr>
                        <th width="5%">#</th>
                        <th width="45%">Product Description</th>
                        <th width="15%" class="text-right">Unit Price (Rs.)</th>
                        <th width="15%" class="text-right">GST %</th>
                        <th width="10%" class="text-right">Quantity</th>
                        <th width="15%" class="text-right">Amount (Rs.)</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $subtotal = 0;
                        $totalGst = 0;

                        $quotation_price = [];
                        $quotation_qty = [];
                        foreach ($quotation->products as $prod) {
                            $quotation_price[] = $prod->sale_price;
                            $quotation_qty[] = $prod->pivot->quantity;
                        }
                    @endphp

                    @foreach ($quotation->products as $index => $product)
                        @php

                            $price = $quotation_price[$index] ?? 0;
                            $qty = $quotation_qty[$index] ?? 0;
                            $amount = $price * $qty;

                            $subtotal += $amount;
                            $gstAmount = ($product->gst / 100) * $amount;
                            $totalGst += $gstAmount;
                        @endphp
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $product->name }}</td>
                            <td class="text-right">{{ number_format($price, 2) }}</td>
                            <td class="text-right">{{ number_format($product->gst, 2) }}%</td>
                            <td class="text-right">{{ $qty }}</td>
                            <td class="text-right">{{ number_format($amount, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="total-section">
            <div style="width: 300px; margin-left: auto;">
                <div class="row">
                    <div class="col-6"><strong>Subtotal:</strong></div>
                    <div class="col-6 text-right">Rs. {{ number_format($subtotal, 2) }}</div>
                </div>
                <div class="row">
                    <div class="col-6"><strong>Total GST:</strong></div>
                    <div class="col-6 text-right">Rs. {{ number_format($totalGst, 2) }}</div>
                </div>
                <div class="row">
                    <div class="col-6"><strong>Grand Total:</strong></div>
                    <div class="col-6 text-right">
                        <strong>Rs. {{ number_format($subtotal + $totalGst, 2) }}</strong>
                    </div>
                </div>
            </div>
        </div>


        @if ($quotation->notes)
            <div class="notes">
                <strong>Notes:</strong><br>
                {{ $quotation->notes }}
            </div>
        @endif
        <div class="notes">
            <strong>Terms:</strong><br>
            <ul>
                @foreach ($quotationTerms as $term)
                    @if ($term->custom_text == null)
                        <li>{{ $term->statements }}</li>
                    @else
                        <li>{{ $term->custom_text }}</li>
                    @endif
                @endforeach
            </ul>
        </div>

        <div class="footer" style="width: max-content">
            <p>Thank you for your business! This quotation is valid until
                {{ \Carbon\Carbon::parse($quotation->validity_date)->format('F d, Y') }}</p>
            <p>For any queries, please contact us at info@company.com or call (123) 456-7890</p>
            <p><em>This is a computer-generated document. No signature is required.</em></p>
        </div>
</body>

</html>
