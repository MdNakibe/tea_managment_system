@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-3">
        <div class="col-md-6">
            <h2>Invoice List</h2>
        </div>
        <div class="col-md-6 text-end">
            <!-- Button trigger Create Invoice modal -->
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createInvoiceModal">
                Create Invoice
            </button>
        </div>
    </div>

    <table class="table">
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
</div>
        <thead>
            <tr>
                <th scope="col">Invoice Number</th>
                <th scope="col">Price</th>
                <th scope="col">Weight</th>
                <th scope="col">Total Stock</th>
                <th scope="col">Stock Out</th>
                <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody>
        <div id="error-message" class="alert alert-danger d-none"></div>
        @if($invoice)    
            @foreach($invoice as $item)
            <tr>
                <td>{{ $item['invoice_number'] }}</td>
                <td>{{ $item['price'] }}</td>
                <td>{{ $item['weight'] }} KG</td>
                <td>{{ $item['stock_in'] }} KG</td>
                <td>{{ $item['stock_out'] }} KG</td>
                <td>
                    @if(!isset($item['tea_packets']))
                    <button type="button" class="btn btn-primary editInvoice" data-id="{{ $item['id'] }}">Edit</button>
                    <form action="{{ route('invoices.destroy', $item['id']) }}" method="POST" class="d-inline delete-invoice-form" data-id="{{ $item['id'] }}">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="btn btn-danger delete-invoice-btn">Delete</button>
                    </form>
                    
                    @endif
                    @if($item['stock_in'] > 0)
                    <button type="button" class="btn btn-success createTeaPacket" data-id="{{ $item['id'] }}">Add Tea Packet</button>
                    @else
                        <h6>Out of Stock</h6>
                    @endif

                </td>
            </tr>
            @endforeach
            @else
            <tr>
                <td colspan="6" class="text-center"><h6>Invoice Not Found</h6></td>
            </tr>
            @endif
        </tbody>
    </table>
    <div class="modal fade" id="createTeaPacketModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="createTeaPacketModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createTeaPacketModalLabel">Create Tea Packets</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="createTeaPacketForm" method="POST">
                        @csrf
                        <input type="hidden" name="invoice_id" id="invoice_id">
                        <div id="error-messages" class="alert alert-danger" style="display: none;"></div>
                        <div id="packetWeightContainer">
                            <div class="form-group packet-weight-row">
                                <label for="packet_weight">Packet Weights (kg):</label>
                                <input type="number" name="packet_weight[]" class="form-control mb-2" step="0.01" required placeholder="Enter weight">
                            </div>
                            <div class="form-group packet-weight-row">
                                <label for="name">Name:</label>
                                <input type="text" name="name[]" class="form-control mb-2" required placeholder="Enter name">
                            </div>
                        </div>
                        <button type="button" class="btn btn-success mb-2" id="addPacketRowBtn">+ Add More Packets</button>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Create Packets</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Create Invoice Modal -->
    <div class="modal fade" id="createInvoiceModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="createInvoiceModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createInvoiceModalLabel">Create Invoice</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('invoices.store') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="invoice_number">Invoice Number:</label>
                            <input type="text" name="invoice_number" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="price">Price:</label>
                            <input type="text" name="price" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="weight">Weight (kg):</label>
                            <input type="number" name="weight" class="form-control" step="0.01" required>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Invoice Modal -->
    <div class="modal fade" id="editInvoiceModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="editInvoiceModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editInvoiceModalLabel">Edit Invoice</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editInvoiceForm" method="POST">
                        @csrf
                        @method('PUT') 
                        <input type="hidden" name="id" id="invoice_id">
                        <div class="form-group">
                            <label for="invoice_number">Invoice Number:</label>
                            <input type="text" name="invoice_number" id="invoice_number" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="price">Price:</label>
                            <input type="text" name="price" id="price" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="weight">Weight (kg):</label>
                            <input type="number" name="weight" id="weight" class="form-control" step="0.01" required>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('customscript')
<script>
    $(document).ready(function() {
        $('#addPacketRowBtn').on('click', function() {
            var newRow = `
                <div class="form-group packet-weight-row">
                    <label for="packet_weight">Packet Weights (kg):</label>
                    <input type="number" name="packet_weight[]" class="form-control mb-2" step="0.01" required placeholder="Enter weight">
                </div>
                <div class="form-group packet-weight-row">
                    <label for="name">Name:</label>
                    <input type="text" name="name[]" class="form-control mb-2" required placeholder="Enter name">
                </div>
            `;
            $('#packetWeightContainer').append(newRow);
        });
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
                      $('#error-message').addClass('d-none'); // Hide any previous error messages
                  }
              },
              error: function(xhr) {
                  if (xhr.status === 400 && xhr.responseJSON.error) {
                      $('#error-message').text(xhr.responseJSON.error).removeClass('d-none');
                  }
              }
          });
      });
      $('.createTeaPacket').on('click', function() {
        var invoiceId = $(this).data('id');
        $('#invoice_id').val(invoiceId);
        $('#createTeaPacketModal').modal('show');
    });
    $('#createTeaPacketForm').on('submit', function(event) {
            var allFieldsFilled = true;
            var errorMessages = [];

            $('#packetWeightContainer input[name="packet_weight[]"]').each(function() {
                if ($(this).val() === '') {
                    allFieldsFilled = false;
                    errorMessages.push('Please fill in all packet weight fields.');
                }
            });

            $('#packetWeightContainer input[name="name[]"]').each(function() {
                if ($(this).val() === '') {
                    allFieldsFilled = false;
                    errorMessages.push('Please fill in all name fields.');
                }
            });
            if (!allFieldsFilled) {
                $('#error-messages').html(errorMessages.join('<br>')).show();
                event.preventDefault(); 
            }else{
                event.preventDefault();
            
            var formData = $(this).serialize(); 
            $.ajax({
                url: '/teapackets',
                type: 'POST',
                data: formData,
                success: function(response) {
                    $('#createTeaPacketModal').modal('hide');
                    location.reload(); 
                },
                error: function(xhr) {
                    var response = JSON.parse(xhr.responseText);
                    if (response.error) {
                        var errorMessage = '<ul><li>' + response.error + '</li></ul>';
                        $('#error-messages').html(errorMessage).show();
                    }
                }
            });
            }
        });
    });
</script>
@endsection
