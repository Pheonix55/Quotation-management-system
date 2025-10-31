{{-- <table class="table">
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
        @endphp
        {{ $index = 0 }}

        <tr>
            @foreach ($products as $index => $item)
            @php
            // dd($item);
            $product = $item['product'];
            $totalAmount = $item['total'];
            // dd($product, $total);
            $index = $index + 1;

            $quantity = $product->pivot->quantity ?? 1;

            //
            $quotation_price = json_decode($quotation->price, true);
            $quotation_qty = json_decode($quotation->quantity, true);

            // If nested arrays exist, flatten them
            if (isset($quotation_price[0]) && is_array($quotation_price[0])) {
            $quotation_price = $quotation_price[0];
            }

            if (isset($quotation_qty[0]) && is_array($quotation_qty[0])) {
            $quotation_qty = $quotation_qty[0];
            }

            @endphp

            <td>{{ $index + 1 }}</td>
            <td>{{ $product->name }}</td>
            <td class="text-right">{{ number_format($product->sale_price, 2) }}</td>
            <td class="text-right">{{ number_format($product->gst, 2) }}%</td>
            <td class="text-right">{{ $quotation_qty[$index] }}</td>
            <td class="text-right">
                {{ $quotation_qty[$index] * $quotation_price[$index] }}</td>
            @endforeach
        </tr>
    </tbody>
</table> --}}
