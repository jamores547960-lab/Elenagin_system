# 🚀 Developer Documentation - Inventory Adjustments Module

## Module Overview

The Inventory Adjustments module provides comprehensive tracking for inventory shrinkage, including spoilage, wastage, damage, expiration, theft, corrections, and returns. It integrates seamlessly with the existing Elenagin POS system.

---

## Architecture

### MVC Pattern

```
┌─────────────────┐
│   Route Layer   │ → web.php (defines endpoints)
└────────┬────────┘
         │
┌────────▼────────┐
│   Controller    │ → InventoryAdjustmentController.php
│  (Business Logic)│
└────────┬────────┘
         │
┌────────▼────────┐
│     Model       │ → InventoryAdjustment.php
│  (Data Layer)   │
└────────┬────────┘
         │
┌────────▼────────┐
│    Database     │ → inventory_adjustments table
└─────────────────┘
```

### Data Flow

```
User Action (Blade View)
    ↓
HTTP Request (POST /inventory/adjustments)
    ↓
Route → Controller (InventoryAdjustmentController@store)
    ↓
Validation → Business Logic
    ↓
Database Transaction (BEGIN)
    ├→ Create Adjustment Record
    ├→ Update Item Quantity
    ├→ Log Activity
    └→ COMMIT
    ↓
Response (Redirect with Success Message)
```

---

## Database Schema

### `inventory_adjustments` Table

```sql
CREATE TABLE `inventory_adjustments` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `adjustment_id` varchar(255) UNIQUE NOT NULL,
  `item_id` varchar(255) NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `adjustment_type` enum('spoilage','wastage','damage','expired','theft','correction','return') NOT NULL,
  `quantity` int NOT NULL,
  `reason` text,
  `cost_impact` decimal(10,2) NOT NULL,
  `adjustment_date` date NOT NULL,
  `approved_by` varchar(255),
  `status` enum('pending','approved','rejected') DEFAULT 'approved',
  `created_at` timestamp NULL,
  `updated_at` timestamp NULL,
  `deleted_at` timestamp NULL,
  PRIMARY KEY (`id`),
  KEY `inventory_adjustments_item_id_foreign` (`item_id`),
  KEY `inventory_adjustments_user_id_foreign` (`user_id`),
  CONSTRAINT `inventory_adjustments_item_id_foreign` 
    FOREIGN KEY (`item_id`) REFERENCES `items` (`item_id`) ON DELETE CASCADE,
  CONSTRAINT `inventory_adjustments_user_id_foreign` 
    FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
);
```

### Indexes

- **Primary Key:** `id`
- **Unique:** `adjustment_id`
- **Foreign Keys:** `item_id`, `user_id`
- **Recommended Additional Indexes:**
  ```sql
  CREATE INDEX idx_adjustment_type ON inventory_adjustments(adjustment_type);
  CREATE INDEX idx_adjustment_date ON inventory_adjustments(adjustment_date);
  CREATE INDEX idx_status ON inventory_adjustments(status);
  ```

---

## Model: `InventoryAdjustment.php`

### Key Features

```php
namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class InventoryAdjustment extends Model
{
    use SoftDeletes;
    
    protected $fillable = [
        'adjustment_id', 'item_id', 'user_id', 'adjustment_type',
        'quantity', 'reason', 'cost_impact', 'adjustment_date',
        'approved_by', 'status'
    ];
    
    protected $casts = [
        'adjustment_date' => 'date',
        'cost_impact' => 'decimal:2',
        'quantity' => 'integer'
    ];
}
```

### Relationships

```php
// Belongs to Item
public function item()
{
    return $this->belongsTo(Item::class, 'item_id', 'item_id');
}

// Belongs to User (who recorded it)
public function user()
{
    return $this->belongsTo(User::class);
}

// Belongs to User (who approved it)
public function approver()
{
    return $this->belongsTo(User::class, 'approved_by', 'id');
}
```

### Query Scopes

```php
// Get pending adjustments
$pending = InventoryAdjustment::pending()->get();

// Get approved adjustments
$approved = InventoryAdjustment::approved()->get();

// Implementation
public function scopePending($query)
{
    return $query->where('status', 'pending');
}

public function scopeApproved($query)
{
    return $query->where('status', 'approved');
}
```

### Accessors

```php
// Get badge color for adjustment type
$adjustment->type_label; // Returns ['color' => '#dc2626', 'label' => 'Spoilage']
```

---

## Controller: `InventoryAdjustmentController.php`

### Methods

#### `index(Request $request)`

**Purpose:** Display all adjustments with filters

**Parameters:**
- `type` (optional) - Filter by adjustment type
- `status` (optional) - Filter by status
- `date_from` (optional) - Start date
- `date_to` (optional) - End date

**Returns:** View with paginated adjustments and statistics

**Example:**
```php
GET /inventory/adjustments?type=spoilage&status=approved
```

#### `store(Request $request)`

**Purpose:** Create new adjustment

**Validation:**
```php
[
    'item_id' => 'required|exists:items,item_id',
    'adjustment_type' => 'required|in:spoilage,wastage,damage,expired,theft,correction,return',
    'quantity' => 'required|integer|min:1',
    'reason' => 'required|string|max:500'
]
```

**Process Flow:**
1. Validate input
2. Begin database transaction
3. Generate unique `adjustment_id` (format: ADJ-YYYYMMDDHHMMSS-###)
4. Calculate cost impact (quantity × unit_price)
5. Create adjustment record
6. Deduct from inventory
7. Log activity
8. Commit transaction
9. Return success message

**Error Handling:**
- Catches exceptions
- Rolls back transaction on error
- Returns error message to user

#### `approve($id)`

**Purpose:** Approve pending adjustment (admin only)

**Authorization:** Requires admin role (enforced by middleware)

**Process Flow:**
1. Find adjustment by ID
2. Check status is "pending"
3. Begin transaction
4. Update status to "approved"
5. Set `approved_by` to current user
6. Deduct inventory
7. Log activity
8. Commit transaction
9. Return success message

---

## Routes

```php
// Display adjustments page
Route::get('/inventory/adjustments', [InventoryAdjustmentController::class, 'index'])
    ->name('inventory.adjustments');

// Store new adjustment
Route::post('/inventory/adjustments', [InventoryAdjustmentController::class, 'store'])
    ->name('inventory.adjustments.store');

// Approve adjustment (admin only)
Route::post('/inventory/adjustments/{id}/approve', [InventoryAdjustmentController::class, 'approve'])
    ->name('inventory.adjustments.approve')
    ->middleware('role:admin');
```

### Route Names
- `inventory.adjustments` - List page
- `inventory.adjustments.store` - Create adjustment
- `inventory.adjustments.approve` - Approve pending

---

## Views

### `resources/views/inventory/adjustments.blade.php`

#### Sections

1. **Page Header**
   - Title with icon
   - "Record Adjustment" button

2. **Statistics Cards**
   - Total Spoilage (This Month)
   - Total Wastage (This Month)
   - Cost Impact (This Month)
   - Pending Approvals

3. **Filters Form**
   - Adjustment Type dropdown
   - Status dropdown
   - Date From input
   - Date To input
   - Filter button

4. **Adjustments Table**
   - Adjustment ID
   - Item Name
   - Type (badge)
   - Quantity (negative, red)
   - Cost Impact
   - Reason (truncated)
   - Date
   - Recorded By
   - Status (badge)
   - Actions (view/approve)

5. **Record Modal**
   - Item selection (shows stock)
   - Type selection
   - Quantity input
   - Reason textarea
   - Cost impact display (auto-calculated)

6. **View Modals**
   - Detailed adjustment information
   - Read-only display

#### JavaScript Functions

```javascript
// Update available stock when item selected
document.getElementById('adjustmentItem').addEventListener('change', function() {
    const stock = this.options[this.selectedIndex].getAttribute('data-quantity');
    document.getElementById('availableStock').textContent = stock + ' units';
});

// Calculate cost impact in real-time
function calculateCostImpact() {
    const quantity = parseInt(document.getElementById('adjustmentQuantity').value);
    const unitPrice = parseFloat(selectedOption.getAttribute('data-price'));
    const costImpact = quantity * unitPrice;
    document.getElementById('costImpactDisplay').value = '₱' + costImpact.toFixed(2);
}

// Confirm before submitting
function confirmAdjustment() {
    const quantity = document.getElementById('adjustmentQuantity').value;
    const itemName = itemSelect.options[itemSelect.selectedIndex].text;
    return confirm(`Are you sure you want to deduct ${quantity} units of "${itemName}"?`);
}
```

---

## Frontend Integration

### Dashboard Alerts

**Location:** `resources/views/dashboard/index.blade.php`

#### Pending Adjustments Alert

```blade
@php
    $pendingAdjustments = \App\Models\InventoryAdjustment::pending()->count();
@endphp

@if($pendingAdjustments > 0)
<div class="alert-banner" style="background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);">
    <h4><i class="fas fa-clock"></i> Pending Inventory Adjustments</h4>
    <p>{{ $pendingAdjustments }} adjustment(s) require approval. 
       <a href="{{ route('inventory.adjustments') }}?status=pending">Review now</a>
    </p>
</div>
@endif
```

#### Monthly Spoilage Alert

```blade
@php
    $thisMonthSpoilage = \App\Models\InventoryAdjustment::where('adjustment_type', 'spoilage')
        ->whereMonth('adjustment_date', now()->month)->sum('quantity');
    $thisMonthCostImpact = \App\Models\InventoryAdjustment::approved()
        ->whereMonth('adjustment_date', now()->month)->sum('cost_impact');
@endphp

@if($thisMonthSpoilage > 0)
<div class="alert-banner" style="background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);">
    <h4><i class="fas fa-trash-alt"></i> Spoilage Alert</h4>
    <p>{{ number_format($thisMonthSpoilage) }} units spoiled with ₱{{ number_format($thisMonthCostImpact, 2) }} impact. 
       <a href="{{ route('inventory.adjustments') }}?type=spoilage">View details</a>
    </p>
</div>
@endif
```

### Sidebar Link

**Location:** `resources/views/system.blade.php`

```blade
<li class="nav-item">
    <a href="{{ route('inventory.adjustments') }}" 
       class="nav-link {{ request()->routeIs('inventory.adjustments*') ? 'active' : '' }}">
        <i class="fas fa-exclamation-triangle"></i>
        <span>Adjustments</span>
    </a>
</li>
```

---

## Security

### Authorization

**Middleware:** `CheckRole`

```php
// Only admins can approve
Route::post('/inventory/adjustments/{id}/approve', ...)
    ->middleware('role:admin');

// All authenticated users can view/create
Route::get('/inventory/adjustments', ...)
    ->middleware('auth');
```

### CSRF Protection

All forms include CSRF token:
```blade
@csrf
```

### SQL Injection Prevention

- Using Eloquent ORM (parameterized queries)
- Validation on all inputs
- Foreign key constraints

### XSS Prevention

- Blade templates auto-escape output: `{{ $variable }}`
- Use `{!! !!}` only for trusted HTML

---

## Testing

### Unit Tests (Suggested)

```php
// tests/Unit/InventoryAdjustmentTest.php

public function test_adjustment_id_is_unique()
{
    $adj1 = InventoryAdjustment::factory()->create();
    $adj2 = InventoryAdjustment::factory()->create();
    
    $this->assertNotEquals($adj1->adjustment_id, $adj2->adjustment_id);
}

public function test_cost_impact_calculated_correctly()
{
    $item = Item::factory()->create(['unit_price' => 100]);
    $adjustment = InventoryAdjustment::factory()->create([
        'item_id' => $item->item_id,
        'quantity' => 5
    ]);
    
    $this->assertEquals(500, $adjustment->cost_impact);
}

public function test_inventory_deducted_on_approval()
{
    $item = Item::factory()->create(['quantity' => 50]);
    $adjustment = InventoryAdjustment::factory()->create([
        'item_id' => $item->item_id,
        'quantity' => 10,
        'status' => 'pending'
    ]);
    
    $controller = new InventoryAdjustmentController();
    $controller->approve($adjustment->id);
    
    $item->refresh();
    $this->assertEquals(40, $item->quantity);
}
```

### Feature Tests (Suggested)

```php
// tests/Feature/AdjustmentWorkflowTest.php

public function test_admin_can_create_adjustment()
{
    $admin = User::factory()->create(['role' => 'admin']);
    $item = Item::factory()->create(['quantity' => 100]);
    
    $response = $this->actingAs($admin)->post('/inventory/adjustments', [
        'item_id' => $item->item_id,
        'adjustment_type' => 'spoilage',
        'quantity' => 10,
        'reason' => 'Test reason'
    ]);
    
    $response->assertRedirect();
    $this->assertDatabaseHas('inventory_adjustments', [
        'item_id' => $item->item_id,
        'quantity' => 10
    ]);
}

public function test_cashier_cannot_approve_adjustments()
{
    $cashier = User::factory()->create(['role' => 'cashier']);
    $adjustment = InventoryAdjustment::factory()->create(['status' => 'pending']);
    
    $response = $this->actingAs($cashier)
        ->post("/inventory/adjustments/{$adjustment->id}/approve");
    
    $response->assertStatus(403); // Forbidden
}
```

---

## Performance Optimization

### Eager Loading

Prevent N+1 queries:

```php
// Bad (N+1 query problem)
$adjustments = InventoryAdjustment::all();
foreach ($adjustments as $adj) {
    echo $adj->item->name; // Separate query for each item
}

// Good (Eager loading)
$adjustments = InventoryAdjustment::with(['item', 'user', 'approver'])->get();
foreach ($adjustments as $adj) {
    echo $adj->item->name; // No additional queries
}
```

### Pagination

Always paginate large datasets:

```php
$adjustments = InventoryAdjustment::paginate(20); // 20 per page
```

### Caching (Optional)

Cache monthly statistics:

```php
$totalSpoilage = Cache::remember('monthly_spoilage', 3600, function () {
    return InventoryAdjustment::where('adjustment_type', 'spoilage')
        ->whereMonth('adjustment_date', now()->month)
        ->sum('quantity');
});
```

---

## Extending the Module

### Adding New Adjustment Type

1. **Update Migration:**
   ```php
   $table->enum('adjustment_type', [
       'spoilage', 'wastage', 'damage', 'expired', 
       'theft', 'correction', 'return', 'NEW_TYPE'
   ]);
   ```

2. **Update Validation:**
   ```php
   'adjustment_type' => 'required|in:spoilage,...,NEW_TYPE'
   ```

3. **Update Model Accessor:**
   ```php
   case 'NEW_TYPE':
       return ['color' => '#hexcode', 'label' => 'Display Name'];
   ```

4. **Update View:**
   ```blade
   <option value="NEW_TYPE">Display Name</option>
   ```

### Adding Approval Comments

1. **Migration:**
   ```php
   $table->text('approval_comment')->nullable();
   ```

2. **Controller:**
   ```php
   $adjustment->approval_comment = $request->comment;
   ```

3. **View:**
   ```blade
   <textarea name="comment" placeholder="Approval notes..."></textarea>
   ```

### Email Notifications

1. **Create Notification:**
   ```bash
   php artisan make:notification AdjustmentApproved
   ```

2. **Send in Controller:**
   ```php
   $adjustment->user->notify(new AdjustmentApproved($adjustment));
   ```

---

## Troubleshooting

### Issue: "SQLSTATE[23000]: Integrity constraint violation"

**Cause:** Trying to delete item that has adjustments

**Solution:** Use soft deletes on items table or cascade delete

### Issue: Inventory goes negative

**Cause:** Adjustment quantity exceeds stock

**Solution:** Add validation:
```php
$validator->after(function ($validator) use ($request, $item) {
    if ($request->quantity > $item->quantity) {
        $validator->errors()->add('quantity', 'Exceeds available stock');
    }
});
```

### Issue: Slow query on adjustments page

**Cause:** Missing indexes

**Solution:** Add indexes:
```sql
CREATE INDEX idx_composite ON inventory_adjustments(adjustment_date, status, adjustment_type);
```

---

## API Documentation (Future)

If REST API needed:

```php
// routes/api.php
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/adjustments', [AdjustmentApiController::class, 'index']);
    Route::post('/adjustments', [AdjustmentApiController::class, 'store']);
    Route::post('/adjustments/{id}/approve', [AdjustmentApiController::class, 'approve']);
});
```

**Response Format:**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "adjustment_id": "ADJ-20251220153045-123",
    "item_id": "ITEM-001",
    "quantity": 10,
    "cost_impact": "500.00",
    "status": "approved"
  },
  "message": "Adjustment created successfully"
}
```

---

## Changelog

### Version 1.0 (December 20, 2025)
- Initial release
- Support for 7 adjustment types
- Admin approval workflow
- Real-time dashboard alerts
- Activity logging
- Cost impact calculation

---

## Contributing

When contributing to this module:

1. Follow Laravel coding standards (PSR-12)
2. Write tests for new features
3. Update this documentation
4. Use meaningful commit messages
5. Create migration for database changes

---

## Support

For technical questions:
- **Email:** support@elenagin.com
- **Documentation:** `/docs`
- **Issue Tracker:** GitHub Issues

---

*Developer Documentation Version: 1.0*  
*Last Updated: December 20, 2025*  
*Maintained by: Development Team*
