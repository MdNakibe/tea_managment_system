@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-3">
        <div class="col-md-6">
            <h2>Invoice List</h2>
        </div>
        <div class="col-md-6 text-end">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createProductionModal">
                Create Production
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
                <th scope="col">Product Code</th>
                <th scope="col">Total Weight</th>
                <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody>
        <div id="error-message" class="alert alert-danger d-none"></div>
        @if($peakting)    
            @foreach($peakting as $item)
            <tr>
                <td>{{ $item['code'] }}</td>
                <td>{{ $item['packet_weight'] }} {{$item['unit']}}</td>
                <td>
                <button type="button" class="btn btn-info view-details" data-id="{{ $item['id'] }}" data-bs-toggle="modal" data-bs-target="#infoModal">
                    View Details
                </button>
                </td>
            </tr>
            @endforeach
            @else
            <tr>
            <td colspan="6" class="text-center"><h6>Product Not Found</h6></td>
            </tr>
            @endif
        </tbody>
    </table>
    <div class="modal fade modal-lg" id="createProductionModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="createProductionModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createProductionModalLabel">Create Invoice</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('productpackets.store') }}" method="POST">
                        @csrf
                        <div class="row mb-5">
                        <div class="col-md-6">
                            <label for="tea_packet">Tea Packet:</label>
                            <select name="production_id" class="form-control tea_packet_select" required>
                                <option value="">Select a Production Peak</option>
                                @foreach ($production as $teaPacket)
                                    <option value="{{ $teaPacket->id }}" data-stock="{{ $teaPacket->stock_in - $teaPacket->stock_out }}">
                                        {{ $teaPacket->production_code }} ({{ $teaPacket->stock_in }} KG)
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="weight">Weight:</label>
                            <input type="number" name="weights" class="form-control weight-input" step="0.01" required>
                        </div>
                        <div class="col-md-2">
                            <label for="unit">Unit:</label>
                            <select name="units" class="form-control unit-select">
                                <option value="kg">KG</option>
                                <option value="gm">GM</option>
                            </select>
                        </div>
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

      <div class="modal fade modal-lg" id="infoModal" tabindex="-1" aria-labelledby="createProductionModalLabel" aria-hidden="true">
          <div class="modal-dialog">
              <div class="modal-content">
                  <div class="modal-header">
                      <h5 class="modal-title" id="infoModalLabel">Create Production</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body" id="modalContent">
                      
                  </div>
              </div>
          </div>
      </div>
</div>
@endsection

@section('customscript')
<script>
    $(document).ready(function() {
        var totalWeight = 0;
        var maxWeight = 0;

        $(document).on('change', '.tea_packet_select', function() {
            var selectedStock = parseFloat($(this).find('option:selected').data('stock'));
            maxWeight = selectedStock; 
            totalWeight = 0; 
            $('.weight-input').val(''); 
            updateTotalWeight(); 
        });
        $(document).on('input change', '.weight-input, .unit-select', function() {
            updateTotalWeight();
        });

        function updateTotalWeight() {
            totalWeight = 0;
            var weight = parseFloat($('.weight-input').val()) || 0;
            var unit = $('.unit-select').val();

            if (unit === 'gm') {
                weight = weight / 1000; 
            }

            totalWeight += weight;

            
            if (totalWeight > maxWeight) {
                alert('Total weight exceeds available production weight.');
                $('.weight-input').val(''); 
                return;
            }

        }
    });
    $(document).ready(function() {
    $(document).on('click', '.view-details', function() {
        var itemId = $(this).data('id');

        $.ajax({
            url: '/product-history/' + itemId,
            method: 'GET',
            success: function(response) {
                console.log(response);
                $('#modalContent').html(response);
            },
            error: function(error) {
                $('#modalContent').html(error);
            }
        });
    });
});
</script>
@endsection
