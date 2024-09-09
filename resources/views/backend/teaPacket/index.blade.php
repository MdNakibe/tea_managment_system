@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-3">
        <div class="col-md-6">
            <h2>Tea Packet List</h2>
        </div>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th scope="col">Invoice Number</th>
                <th scope="col">Name</th>
                <th scope="col">Weight</th>
                <th scope="col">Total Stock</th>
                <th scope="col">Stock Out</th>
            </tr>
        </thead>
        <tbody>
        <div id="error-message" class="alert alert-danger d-none"></div>
            @foreach($teapack as $item)
            <tr>
                <td>{{ $item['invoice']['invoice_number'] }}</td>
                <td>{{ $item['name'] }}</td>
                <td>{{ $item['packet_weight'] }} KG</td>
                <td>{{ $item['stock_in'] }} KG</td>
                <td>{{ $item['stock_out'] }} KG</td>
            </tr>
            @endforeach
        </tbody>
    </table>

   
</div>
@endsection

@section('customscript')
<script>
    $(document).ready(function() {
        $('.editInvoice').on('click', function() {
            var invoiceId = $(this).data('id'); 
            $.ajax({
                url: '/invoices/' + invoiceId + '/edit', // Ensure this route exists and returns JSON
                type: 'GET',
                success: function(data) {
                    $('#invoice_id').val(data.id);
                    $('#invoice_number').val(data.invoice_number);
                    $('#price').val(data.price);
                    $('#weight').val(data.weight);

                    $('#editInvoiceForm').attr('action', '/invoices/' + data.id);
                    var editModal = new bootstrap.Modal(document.getElementById('editInvoiceModal'), {
                        keyboard: false
                    });
                    editModal.show();
                },
                error: function(xhr) {
                    console.log("An error occurred while fetching the invoice data: ", xhr.responseText);
                }
            });
        });
        $('.delete-invoice-btn').on('click', function(e) {
          e.preventDefault();

          var form = $(this).closest('form'); 
          var invoiceId = form.data('id'); 

          $.ajax({
              url: form.attr('action'),
              type: 'POST',
              data: form.serialize(), 
              success: function(response) {
                  if (response.success) {
                      form.closest('tr').remove();
                      $('#error-message').addClass('d-none'); 
                  }
              },
              error: function(xhr) {
                  if (xhr.status === 400 && xhr.responseJSON.error) {
                      $('#error-message').text(xhr.responseJSON.error).removeClass('d-none');
                  }
              }
          });
      });
    });
</script>
@endsection
