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

    <!-- Display success message -->
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
</div>
        <thead>
            <tr>
                <th scope="col">Production Code</th>
                <th scope="col">Total Weight</th>
                <th scope="col">Stock In</th>
                <th scope="col">Stock Out</th>
            </tr>
        </thead>
        <tbody>
        <div id="error-message" class="alert alert-danger d-none"></div>
        @if($production)    
            @foreach($production as $item)
            <tr>
                <td>{{ $item['production_code'] }}</td>
                <td>{{ $item['total_weight'] }}</td>
                <td>{{ $item['stock_in'] }} KG</td>
                <td>{{ $item['stock_out'] }} KG</td>
            </tr>
            @endforeach
            @else
            <tr>
            <td colspan="6" class="text-center"><h6>Production Not Found</h6></td>
            </tr>
            @endif
        </tbody>
    </table>
    <!-- Modal for Creating Production -->
      <div class="modal fade modal-lg" id="createProductionModal" tabindex="-1" aria-labelledby="createProductionModalLabel" aria-hidden="true">
          <div class="modal-dialog">
              <div class="modal-content">
                  <div class="modal-header">
                      <h5 class="modal-title" id="createProductionModalLabel">Create Production</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                      <form id="createProductionForm" method="POST" action="{{ route('production.store') }}">
                          @csrf

                          <!-- Error messages -->
                          <div id="error-messages" class="alert alert-danger" style="display: none;"></div>
                          <div id="productionRowContainer">
                              <div class="row mb-3 production-row">
                                  <div class="col-md-6">
                                      <label for="tea_packet">Tea Packet:</label>
                                      <select name="tea_packets[]" class="form-control tea_packet_select" required>
                                          <option value="">Select a Tea Packet</option>
                                          @foreach ($teaPackets as $teaPacket)
                                              <option value="{{ $teaPacket->id }}" data-stock="{{ $teaPacket->stock_in}}">
                                                  {{ $teaPacket->name }} ({{ $teaPacket->stock_in }} KG)
                                              </option>
                                          @endforeach
                                      </select>
                                  </div>
                                  <div class="col-md-4">
                                      <label for="weight">Weight (kg):</label>
                                      <input type="number" name="weights[]" class="form-control weight-input" step="0.01" required>
                                  </div>
                              </div>
                          </div>
                          <button type="button" class="btn btn-success mb-2" id="addRowBtn">+ Add More</button>

                          <div class="modal-footer">
                              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                              <button type="submit" class="btn btn-primary">Create Production</button>
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
    $('#addRowBtn').on('click', function() {
        var newRow = `
            <div class="row mb-3 production-row">
                <div class="col-md-6">
                    <label for="tea_packet">Tea Packet:</label>
                    <select name="tea_packets[]" class="form-control tea_packet_select" required>
                        <option value="">Select a Tea Packet</option>
                        @foreach ($teaPackets as $teaPacket)
                            <option value="{{ $teaPacket->id }}" data-stock="{{ $teaPacket->stock_in - $teaPacket->stock_out }}">
                                {{ $teaPacket->name }} ({{ $teaPacket->stock_in }} KG)
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="weight">Weight (kg):</label>
                    <input type="number" name="weights[]" class="form-control weight-input" step="0.01" required>
                </div>
                <div class="col-md-2 mt-4">
                    <button type="button" class="btn btn-danger remove-row-btn">Remove</button>
                </div>
            </div>`;
        $('#productionRowContainer').append(newRow);
        updateDropdownOptions(); 
    });

    $(document).on('click', '.remove-row-btn', function() {
        $(this).closest('.production-row').remove();
        updateDropdownOptions(); 
    });

    $(document).on('change', '.weight-input', function() {
        var weight = parseFloat($(this).val());
        var stock = parseFloat($(this).closest('.production-row').find('.tea_packet_select option:selected').data('stock'));

        if (weight > stock) {
            alert("Weight exceeds available stock.");
            $(this).val('');
        }
    });

    function updateDropdownOptions() {
        var selectedValues = [];
        $('.tea_packet_select').each(function() {
            var selectedValue = $(this).val();
            if (selectedValue) {
                selectedValues.push(selectedValue); 
            }
        });

        $('.tea_packet_select').each(function() {
            var $dropdown = $(this);
            var currentValue = $dropdown.val();

            $dropdown.find('option').each(function() {
                $(this).prop('disabled', false);
            });

            $dropdown.find('option').each(function() {
                if (selectedValues.includes($(this).val()) && $(this).val() !== currentValue) {
                    $(this).prop('disabled', true);
                }
            });
        });
    }
    updateDropdownOptions();
    
    $(document).on('change', '.tea_packet_select', function() {
        updateDropdownOptions();
    });
});

</script>
@endsection
