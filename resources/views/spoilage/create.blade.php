@extends('system')

@section('title', 'Record Spoilage - TITLE')

@section('head')
    <link href="{{ asset('css/pages.css') }}" rel="stylesheet">
    <style>
        .form-card {
            background: #fff;
            border-radius: 12px;
            padding: 32px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08);
            max-width: 100%;
            margin: 0;
        }
        .form-section {
            margin-bottom: 28px;
        }
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 24px;
        }
        .form-row.full-width {
            grid-template-columns: 1fr;
        }
        .form-section h3 {
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 16px;
            color: #2d3748;
            border-bottom: 2px solid #f56565;
            padding-bottom: 8px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            font-weight: 600;
            margin-bottom: 8px;
            color: #4a5568;
            font-size: 0.9rem;
        }
        .form-control {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            font-size: 0.95rem;
            transition: all 0.2s ease;
        }
        .form-control:focus {
            outline: none;
            border-color: #f56565;
            box-shadow: 0 0 0 3px rgba(245, 101, 101, 0.1);
        }
        .btn-group {
            display: flex;
            gap: 12px;
            justify-content: flex-end;
            margin-top: 32px;
        }
        .stock-info {
            background: #f7fafc;
            padding: 12px 16px;
            border-radius: 8px;
            border-left: 4px solid #3182ce;
            margin-top: 8px;
        }
        .stock-info strong {
            color: #2c5282;
        }
    </style>
@endsection

@section('content')
<div style="margin-bottom: 24px;">
    <h2 class="text-accent" style="font-size: 1.75rem; font-weight: 700; margin: 0; background: linear-gradient(135deg, #f56565, #ed8936); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
        <i class="fas fa-exclamation-triangle"></i> Record Spoilage
    </h2>
    <p style="color: #718096; margin-top: 8px;">Record damaged, expired, or contaminated items to adjust inventory</p>
</div>

<div class="form-card">
    @if($errors->any())
        <div class="alert alert-danger mb-3" style="background: linear-gradient(135deg, rgba(245, 101, 101, 0.1), rgba(245, 101, 101, 0.05)); border-left: 4px solid #f56565; border-radius: 10px; padding: 14px 18px; color: #c53030; font-weight: 500; border: 1px solid rgba(245, 101, 101, 0.2);">
            <i class="fas fa-exclamation-triangle"></i> Please fix the following errors:
            <ul class="m-0 ps-3 mt-2" style="font-size:.85rem;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('spoilage.store') }}" method="POST" id="spoilageForm">
        @csrf

        <div class="form-section">
            <h3><i class="fas fa-box"></i> Item Information</h3>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="item_id">Select Item <span style="color: #f56565;">*</span></label>
                    <select name="item_id" id="item_id" class="form-control" required>
                        <option value="">-- Choose an item --</option>
                        @foreach($items as $item)
                            <option value="{{ $item->item_id }}" 
                                    data-stock="{{ $item->quantity }}"
                                    data-name="{{ $item->name }}"
                                    {{ old('item_id') == $item->item_id ? 'selected' : '' }}>
                                {{ $item->name }} (Stock: {{ $item->quantity }})
                            </option>
                        @endforeach
                    </select>
                    <div id="stockInfo" class="stock-info" style="display: none;">
                        <strong>Available Stock:</strong> <span id="availableStock">0</span>
                    </div>
                </div>

                <div class="form-group">
                    <label for="quantity">Quantity <span style="color: #f56565;">*</span></label>
                    <input type="number" 
                           name="quantity" 
                           id="quantity" 
                           class="form-control" 
                           min="1" 
                           value="{{ old('quantity') }}" 
                           placeholder="Enter quantity to remove"
                           required>
                </div>
            </div>
        </div>

        <div class="form-section">
            <h3><i class="fas fa-clipboard-list"></i> Spoilage Details</h3>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="reason">Reason for Spoilage <span style="color: #f56565;">*</span></label>
                    <select name="reason" id="reason" class="form-control" required>
                        <option value="">-- Select a reason --</option>
                    <option value="expired" {{ old('reason') == 'expired' ? 'selected' : '' }}>
                        <i class="fas fa-calendar-times"></i> Expired
                    </option>
                    <option value="damaged" {{ old('reason') == 'damaged' ? 'selected' : '' }}>
                        <i class="fas fa-hammer"></i> Damaged
                    </option>
                    <option value="contaminated" {{ old('reason') == 'contaminated' ? 'selected' : '' }}>
                        <i class="fas fa-biohazard"></i> Contaminated
                    </option>
                    <option value="other" {{ old('reason') == 'other' ? 'selected' : '' }}>
                        <i class="fas fa-question-circle"></i> Other
                    </option>
                </select>
                </div>

                <div class="form-group">
                    <label for="stockout_date">Date <span style="color: #f56565;">*</span></label>
                    <input type="date" 
                           name="stockout_date" 
                           id="stockout_date" 
                           class="form-control" 
                           value="{{ old('stockout_date', date('Y-m-d')) }}" 
                           max="{{ date('Y-m-d') }}"
                           required>
                </div>
            </div>

            <div class="form-row full-width">
                <div class="form-group">
                    <label for="notes">Additional Notes (Optional)</label>
                    <textarea name="notes" 
                              id="notes" 
                              class="form-control" 
                              rows="4" 
                              maxlength="500" 
                              placeholder="Add any additional details about the spoilage...">{{ old('notes') }}</textarea>
                    <small style="color: #a0aec0; font-size: 0.85rem;">Maximum 500 characters</small>
                </div>
            </div>
        </div>

        <div class="btn-group">
            <a href="{{ route('spoilage.index') }}" 
               class="btn btn-secondary"
               style="padding: 12px 24px; background: #e2e8f0; color: #4a5568; border: none; border-radius: 8px; font-weight: 600; text-decoration: none; transition: all 0.2s ease;">
                <i class="fas fa-times"></i> Cancel
            </a>
            <button type="submit" 
                    class="btn btn-primary"
                    style="padding: 12px 32px; background: linear-gradient(135deg, #f56565, #ed8936); color: #fff; border: none; border-radius: 8px; font-weight: 600; box-shadow: 0 2px 8px rgba(245, 101, 101, 0.2); transition: all 0.2s ease;">
                <i class="fas fa-save"></i> Record Spoilage
            </button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const itemSelect = document.getElementById('item_id');
    const stockInfo = document.getElementById('stockInfo');
    const availableStock = document.getElementById('availableStock');
    const quantityInput = document.getElementById('quantity');

    itemSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        if (selectedOption.value) {
            const stock = selectedOption.getAttribute('data-stock');
            availableStock.textContent = stock;
            stockInfo.style.display = 'block';
            quantityInput.max = stock;
        } else {
            stockInfo.style.display = 'none';
            quantityInput.max = '';
        }
    });

    // Trigger change event if item is already selected (for old() values)
    if (itemSelect.value) {
        itemSelect.dispatchEvent(new Event('change'));
    }

    // Form validation
    document.getElementById('spoilageForm').addEventListener('submit', function(e) {
        const selectedOption = itemSelect.options[itemSelect.selectedIndex];
        if (selectedOption.value) {
            const stock = parseInt(selectedOption.getAttribute('data-stock'));
            const quantity = parseInt(quantityInput.value);
            
            if (quantity > stock) {
                e.preventDefault();
                alert('Quantity cannot exceed available stock (' + stock + ')');
                return false;
            }
        }
    });
});
</script>
@endsection
