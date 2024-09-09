<table class="table table-bordered">
    <tbody>
        <tr>
            <th>Invoice Number</th>
            <td>{{ $data->invoice_number }}</td>
        </tr>
        <tr>
            <th>Invoice Price</th>
            <td>{{ $data->price }}</td>
        </tr>
        <tr>
            <th>Invoice Weight</th>
            <td>{{ $data->weight }}</td>
        </tr>
        <tr>
            <th>Product Code</th>
            <td>{{ $data->code }}</td>
        </tr>
        <tr>
            <th>Packet Weight</th>
            <td>{{ $data->product_weight }}</td>
        </tr>
        <tr>
            <th>Unit</th>
            <td>{{ $data->unit }}</td>
        </tr>
        <tr>
            <th>Production Code</th>
            <td>{{ $data->production_code }}</td>
        </tr>
        <tr>
            <th>Total Weight</th>
            <td>{{ $data->total_weight }}</td>
        </tr>
        <tr>
            <th>Stock In</th>
            <td>{{ $data->stock_in }}</td>
        </tr>
        <tr>
            <th>Stock Out</th>
            <td>{{ $data->stock_out }}</td>
        </tr>
        <tr>
            <th>Tea Packet Name</th>
            <td>{{ $data->name }}</td>
        </tr>
        <tr>
            <th>Total Weight</th>
            <td>{{ $data->packet_weight }}</td>
        </tr>
    </tbody>
</table>
