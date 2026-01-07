# ✅ Testing Checklist - Elenagin System

## 🎨 UI/UX Enhancements

### Font Awesome Icons
- [ ] **Dashboard:** All cards show FA icons (no emojis)
  - [ ] Sales Today: `fas fa-dollar-sign`
  - [ ] Sales Week: `fas fa-chart-line`
  - [ ] Sales Month: `fas fa-chart-bar`
  - [ ] Total Sales: `fas fa-gem`
  - [ ] Total Items: `fas fa-box`
  - [ ] Inventory Value: `fas fa-money-bill-wave`
  - [ ] Low Stock: `fas fa-exclamation-triangle`
  - [ ] Out of Stock: `fas fa-ban`

- [ ] **Sidebar:** All menu items show FA icons
  - [ ] Dashboard: `fas fa-tachometer-alt`
  - [ ] Inventory: `fas fa-boxes`
  - [ ] Adjustments: `fas fa-exclamation-triangle`
  - [ ] Suppliers: `fas fa-truck`
  - [ ] Reports: `fas fa-chart-line`
  - [ ] Employees: `fas fa-users`

- [ ] **Automated Reports Page:** All report cards show FA icons
  - [ ] Daily: `fas fa-calendar-day`
  - [ ] Weekly: `fas fa-chart-bar`
  - [ ] Monthly: `fas fa-chart-line`

### Logo Visibility
- [ ] Logo fully visible in sidebar (not cut off)
- [ ] Logo maintains aspect ratio
- [ ] Logo visible on mobile devices

### Purple Gradient Theme
- [ ] Dashboard filters: Purple gradient background
- [ ] KPI card icons: Gradient backgrounds
- [ ] Buttons: Purple gradient on hover
- [ ] Page titles: Purple gradient text

### Clickable KPI Cards
- [ ] Sales Today → `/cashier/sales?period=day`
- [ ] Sales Week → `/cashier/sales?period=week`
- [ ] Sales Month → `/cashier/sales?period=month`
- [ ] Total Sales → `/cashier/sales`
- [ ] Total Items → `/inventory`
- [ ] Inventory Value → `/inventory`
- [ ] Low Stock → `/inventory?filter=low_stock`
- [ ] Out of Stock → `/inventory?filter=out_of_stock`

### Card Alignment
- [ ] Values: Right-aligned
- [ ] Labels: Left-aligned
- [ ] Icons: Positioned correctly

---

## 💰 Payment System

### Payment Methods Display
- [ ] Cash option shows amount input
- [ ] Card option hides amount input
- [ ] GCash option hides amount input
- [ ] PayMaya option hides amount input
- [ ] Bank Transfer option hides amount input

### Cash Payment
- [ ] Amount received input appears
- [ ] Change calculated in real-time
- [ ] Shows "Insufficient payment" if amount < total
- [ ] Prevents submission if insufficient
- [ ] Shows exact change amount

**Test Case:**
- Cart Total: ₱500
- Amount Received: ₱600
- Expected Change: ₱100 ✅

- Cart Total: ₱500
- Amount Received: ₱400
- Expected: Warning, cannot submit ✅

### Database Recording
- [ ] `amount_received` saved correctly
- [ ] `change_amount` saved correctly
- [ ] `payment_method` saved as string
- [ ] Transaction shows payment details

**Test:**
1. Make a cash sale with ₱1000 for ₱750 total
2. Check database: `SELECT amount_received, change_amount FROM sales ORDER BY id DESC LIMIT 1;`
3. Expected: `amount_received = 1000.00`, `change_amount = 250.00`

---

## 📊 Dashboard Filters

### Date Range Filter
- [ ] Start date field accepts date input
- [ ] End date field accepts date input
- [ ] Apply button filters all KPIs
- [ ] Charts update based on date range
- [ ] Reset button clears filters

**Test Case:**
1. Set start date: 2025-12-01
2. Set end date: 2025-12-15
3. Click Apply
4. Verify KPIs show only data from Dec 1-15

### Category Filter
- [ ] Dropdown shows all active categories
- [ ] Selecting category filters dashboard
- [ ] "All Categories" shows everything
- [ ] Works with date range filters

**Test:**
1. Select a specific category
2. Click Apply
3. Verify KPIs reflect only that category

---

## 📋 Automated Reports

### Access Control
- [ ] Reports page accessible by admin only
- [ ] Cashier redirected if accessing `/reports`
- [ ] Employee redirected if accessing `/reports`

### Report Generation
- [ ] **Daily Report:**
  - [ ] Button click triggers AJAX
  - [ ] Loading spinner shows
  - [ ] Success message appears
  - [ ] Statistics increment
  - [ ] Activity log created

- [ ] **Weekly Report:**
  - [ ] Button click triggers AJAX
  - [ ] Returns last 7 days data
  - [ ] Activity log created

- [ ] **Monthly Report:**
  - [ ] Button click triggers AJAX
  - [ ] Returns current month data
  - [ ] Activity log created

**Test:**
1. Click "Generate Daily Report"
2. Check `activity_logs` table:
   ```sql
   SELECT * FROM activity_logs WHERE event_type = 'report.daily' ORDER BY id DESC LIMIT 1;
   ```
3. Verify `description` and `meta` fields contain correct data

---

## 🗑️ Inventory Adjustments

### Page Access
- [ ] `/inventory/adjustments` loads correctly
- [ ] Sidebar link highlights when active
- [ ] Statistics cards display correct data
- [ ] Table shows all adjustments

### Recording Adjustments

#### Form Validation
- [ ] Item dropdown shows all items with stock levels
- [ ] Type dropdown has all 7 types
- [ ] Quantity must be > 0
- [ ] Quantity cannot exceed available stock
- [ ] Reason field is required
- [ ] Cost impact auto-calculates

**Test Case 1: Valid Adjustment**
1. Select item with 50 units in stock
2. Select type: Spoilage
3. Enter quantity: 5
4. Enter reason: "Testing spoilage tracking"
5. Verify cost impact = 5 × unit_price
6. Submit
7. Expected: Success message, inventory reduced by 5

**Test Case 2: Exceeds Stock**
1. Select item with 10 units in stock
2. Enter quantity: 15
3. Submit
4. Expected: Error "Adjustment quantity exceeds available stock!"

**Test Case 3: Empty Reason**
1. Fill all fields except reason
2. Submit
3. Expected: Browser validation error

#### Confirmation Dialog
- [ ] Confirmation shows correct quantity
- [ ] Confirmation shows correct item name
- [ ] Cancel button aborts submission
- [ ] OK button proceeds

### Adjustment Types & Colors
- [ ] Spoilage: Red badge
- [ ] Wastage: Orange badge
- [ ] Damage: Yellow badge
- [ ] Expired: Purple badge
- [ ] Theft: Dark badge
- [ ] Correction: Blue badge
- [ ] Return: Green badge

### Filters
- [ ] Filter by type works
- [ ] Filter by status works
- [ ] Filter by date range works
- [ ] Multiple filters combine correctly
- [ ] Filter icon button submits form

**Test:**
1. Filter: Type = Spoilage, Status = Approved
2. Expected: Only approved spoilage adjustments shown

### Admin Approval
- [ ] Approve button only visible to admins
- [ ] Approve button only on pending adjustments
- [ ] Clicking approve shows confirmation
- [ ] Approval updates status
- [ ] Approval deducts inventory
- [ ] Activity log created

**Test (as Admin):**
1. Create adjustment with status "pending"
2. Refresh page
3. Click green checkmark
4. Confirm
5. Verify:
   - Status changed to "approved"
   - Inventory quantity reduced
   - Activity log entry created

### View Modal
- [ ] Eye icon opens modal
- [ ] Modal shows all adjustment details
- [ ] Modal shows approver name if approved
- [ ] Close button works

---

## 🚨 Real-Time Monitoring

### Dashboard Alerts

#### Low Stock Alert
- [ ] Shows when items ≤ 10 units
- [ ] Displays correct count
- [ ] Red gradient background
- [ ] Link to inventory with filter

**Test:**
1. Reduce an item to 5 units
2. Refresh dashboard
3. Expected: "X item(s) are running low on stock"

#### Pending Adjustments Alert
- [ ] Shows when pending adjustments exist
- [ ] Yellow gradient background
- [ ] Displays correct count
- [ ] Link to adjustments page with filter

**Test:**
1. Create adjustment with status "pending"
2. Refresh dashboard
3. Expected: "X adjustment(s) require admin approval"

#### Monthly Spoilage Alert
- [ ] Shows when spoilage occurred this month
- [ ] Red gradient background
- [ ] Displays total quantity
- [ ] Displays total cost impact
- [ ] Link to spoilage adjustments

**Test:**
1. Record spoilage adjustment
2. Refresh dashboard
3. Expected: "X units spoiled this month with ₱X.XX impact"

### Alert Links
- [ ] "Review now" → `/inventory/adjustments?status=pending`
- [ ] "View details" → `/inventory/adjustments?type=spoilage`

---

## 🔒 Role-Based Access

### Admin Role
- [ ] Can access `/reports`
- [ ] Can access `/reports/automated`
- [ ] Can approve adjustments
- [ ] Can access `/employees`
- [ ] Can access `/cashier`
- [ ] Can see all dashboard features

### Cashier Role
- [ ] Can access `/cashier`
- [ ] Cannot access `/reports`
- [ ] Cannot access `/inventory/adjustments`
- [ ] Cannot access `/employees`

### Employee Role
- [ ] Can access `/inventory`
- [ ] Can access `/inventory/adjustments`
- [ ] Can record adjustments
- [ ] Cannot approve adjustments
- [ ] Cannot access `/reports`
- [ ] Cannot access `/cashier`

**Test for Each Role:**
1. Log in as [role]
2. Try accessing protected routes
3. Verify redirect/403 error

---

## 📝 Activity Logging

### Check Logs
- [ ] Sales create activity logs
- [ ] Report generation creates logs
- [ ] Adjustment recording creates logs
- [ ] Adjustment approval creates logs

**SQL Test:**
```sql
SELECT event_type, description, occurred_at 
FROM activity_logs 
ORDER BY id DESC 
LIMIT 10;
```

Expected events:
- `sale.created`
- `report.daily`
- `report.weekly`
- `report.monthly`
- `inventory.adjustment`
- `inventory.adjustment.approved`

### Metadata
- [ ] `meta` field contains JSON
- [ ] JSON parseable and readable
- [ ] Contains relevant details

**Test:**
```sql
SELECT meta FROM activity_logs WHERE event_type = 'inventory.adjustment' LIMIT 1;
```

Expected JSON structure:
```json
{
  "adjustment_id": "ADJ-20251220...",
  "item_id": "...",
  "type": "spoilage",
  "quantity": 5,
  "cost_impact": 250.00
}
```

---

## ✅ Validation & Error Prevention

### Double-Confirmation
- [ ] Inventory item deletion asks confirmation
- [ ] Category deletion asks confirmation
- [ ] Adjustment recording asks confirmation
- [ ] Adjustment approval asks confirmation

### Form Validation
- [ ] Required fields marked with red asterisk
- [ ] Browser validation triggers on submit
- [ ] Server-side validation returns errors
- [ ] Error messages displayed clearly

### Stock Protection
- [ ] Cannot delete items with foreign key references
- [ ] Cannot deduct more than available stock
- [ ] Soft deletes maintain data integrity

---

## 📱 Responsive Design

### Desktop (1920px)
- [ ] Dashboard: 3 columns of KPI cards
- [ ] Charts: 2 per row
- [ ] Sidebar: Fully expanded
- [ ] Tables: All columns visible

### Tablet (768px)
- [ ] Dashboard: 2 columns of KPI cards
- [ ] Charts: 1 per row
- [ ] Sidebar: Collapsible
- [ ] Tables: Horizontal scroll

### Mobile (375px)
- [ ] Dashboard: 1 column of KPI cards
- [ ] Charts: Scaled down
- [ ] Sidebar: Hamburger menu
- [ ] Tables: Card layout or scroll

---

## 🔧 Database Integrity

### Foreign Keys
- [ ] `inventory_adjustments.item_id` → `items.item_id`
- [ ] `inventory_adjustments.user_id` → `users.id`
- [ ] `sales.user_id` → `users.id`
- [ ] `activity_logs.user_id` → `users.id`

**Test:**
```sql
SELECT 
  TABLE_NAME, 
  CONSTRAINT_NAME, 
  REFERENCED_TABLE_NAME 
FROM 
  information_schema.KEY_COLUMN_USAGE 
WHERE 
  TABLE_SCHEMA = 'your_database_name' 
  AND REFERENCED_TABLE_NAME IS NOT NULL;
```

### Soft Deletes
- [ ] Deleted adjustments have `deleted_at` timestamp
- [ ] Deleted adjustments hidden from default queries
- [ ] Can restore soft-deleted records

**Test:**
```sql
-- Check soft deletes exist
SELECT * FROM inventory_adjustments WHERE deleted_at IS NOT NULL;
```

---

## 🎯 End-to-End Scenarios

### Scenario 1: Complete Spoilage Workflow
1. [ ] Dashboard shows 0 pending adjustments
2. [ ] Employee records spoilage (10 units, ₱500 impact)
3. [ ] Dashboard shows 1 pending adjustment (yellow alert)
4. [ ] Admin clicks "Review now"
5. [ ] Admin approves adjustment
6. [ ] Dashboard clears pending alert
7. [ ] Dashboard shows spoilage alert (10 units, ₱500)
8. [ ] Inventory reduced by 10 units
9. [ ] Activity logs show 2 entries (record + approve)

### Scenario 2: Cash Sale with Change
1. [ ] Cashier adds items totaling ₱850
2. [ ] Selects "Cash" payment
3. [ ] Enters ₱1000 amount received
4. [ ] Change shows ₱150
5. [ ] Submits transaction
6. [ ] Database records:
   - `amount_received = 1000.00`
   - `change_amount = 150.00`
   - `payment_method = 'cash'`
7. [ ] Receipt displays change amount

### Scenario 3: Monthly Report Generation
1. [ ] Admin navigates to `/reports/automated`
2. [ ] Clicks "Generate Monthly Report"
3. [ ] Loading spinner appears
4. [ ] Success message: "Monthly report generated successfully!"
5. [ ] Statistics counter increments
6. [ ] Activity log created with event_type = 'report.monthly'
7. [ ] Meta contains sales data for current month

---

## 🐛 Known Issues / Edge Cases

### To Test:
- [ ] What happens if item deleted after adjustment?
- [ ] Can adjust items with 0 stock? (should be prevented)
- [ ] Can create adjustment for non-existent item? (validation)
- [ ] Concurrent approval by two admins (race condition)
- [ ] Very long reasons (500+ characters)
- [ ] Special characters in reason field
- [ ] Large quantity numbers (integer overflow)
- [ ] Negative quantities (should be prevented)

---

## 📊 Performance

### Page Load Times
- [ ] Dashboard: < 2 seconds
- [ ] Adjustments page: < 2 seconds
- [ ] Reports page: < 1 second
- [ ] Cashier POS: < 1 second

### AJAX Requests
- [ ] Report generation: < 1 second
- [ ] Form submissions: < 500ms

### Database Queries
- [ ] Dashboard: Optimized with indexes
- [ ] Adjustments table: Pagination working
- [ ] No N+1 query problems

**Check with:**
```php
\DB::enableQueryLog();
// ... execute page load ...
dd(\DB::getQueryLog());
```

---

## ✅ Sign-Off

**Tested By:** ___________________  
**Date:** ___________________  
**Environment:** ☐ Development ☐ Staging ☐ Production  
**Browser:** ☐ Chrome ☐ Firefox ☐ Safari ☐ Edge  

**Overall Status:**  
☐ All Tests Passed  
☐ Minor Issues Found (documented below)  
☐ Major Issues Found (requires fixes)  

**Notes:**
```
[Space for tester notes]
```

---

*Testing Checklist Version: 1.0*  
*Last Updated: December 20, 2025*
