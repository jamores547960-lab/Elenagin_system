# 🚀 Quick Start Guide - Inventory Adjustments

## What is Inventory Adjustments?

Track and manage inventory losses due to spoilage, wastage, damage, expiration, theft, and corrections. This feature helps you:

✅ Monitor inventory shrinkage  
✅ Calculate cost impact  
✅ Maintain accurate stock levels  
✅ Generate loss reports  
✅ Require admin approval for high-value adjustments  

---

## 📍 How to Access

**Sidebar Navigation:**
1. Log in to the system
2. Look for **"Adjustments"** in the left sidebar
3. Click to open Inventory Adjustments page

**Direct URL:** `/inventory/adjustments`

---

## ➕ Recording an Adjustment

### Step 1: Click "Record Adjustment" Button
- Located at the top-right of the page
- Opens a modal form

### Step 2: Fill in the Form

**Required Fields:**

1. **Item** (Dropdown)
   - Shows all available items
   - Displays current stock: `Product Name (Stock: 50 @ ₱100.00)`
   - Available stock shown below quantity field

2. **Adjustment Type** (Dropdown)
   - **Spoilage** - Product deteriorated/rotted
   - **Wastage** - Operational waste
   - **Damage** - Physically damaged goods
   - **Expired** - Past expiration date
   - **Theft** - Stolen items
   - **Correction** - Inventory count corrections
   - **Return** - Customer returns

3. **Quantity** (Number)
   - How many units to deduct
   - Must be ≤ available stock
   - Minimum: 1 unit

4. **Reason** (Text)
   - Explain why adjustment is needed
   - Example: "Found 5 bottles with broken seals during inventory check"
   - Maximum: 500 characters

**Auto-Calculated:**
- **Cost Impact** - Automatically calculated as: Quantity × Unit Price
- Example: 10 units × ₱50.00 = ₱500.00

### Step 3: Submit
1. Click **"Record Adjustment"** button
2. Confirmation dialog appears:
   > "Are you sure you want to deduct 10 units of 'Product Name' from inventory?"
3. Click **OK** to confirm
4. Inventory automatically updated
5. Success message appears

---

## 📊 Viewing Adjustments

### Statistics Cards (Top of Page)

1. **Total Spoilage (This Month)**
   - Total units lost to spoilage
   - Resets monthly

2. **Total Wastage (This Month)**
   - Total units wasted
   - Resets monthly

3. **Cost Impact (This Month)**
   - Total monetary loss
   - Sum of all approved adjustments

4. **Pending Approvals**
   - Adjustments awaiting admin review
   - Admin-only feature

### Adjustments Table

**Columns:**
- Adjustment ID (e.g., ADJ-20251220153045-123)
- Item Name
- Type (color-coded badge)
- Quantity (red, negative)
- Cost Impact (₱ amount)
- Reason (truncated)
- Date
- Recorded By (username)
- Status (Pending/Approved/Rejected)
- Actions (View/Approve buttons)

---

## 🔍 Filtering Adjustments

### Filter Options (Above Table)

1. **Adjustment Type**
   - All Types
   - Spoilage
   - Wastage
   - Damage
   - Expired
   - Theft
   - Correction
   - Return

2. **Status**
   - All Status
   - Pending
   - Approved
   - Rejected

3. **Date Range**
   - Date From
   - Date To

**Apply:** Click filter icon button  
**Reset:** Refresh page or clear all fields

---

## ✅ Admin: Approving Adjustments

**Note:** Only visible if status = "pending" AND user role = "admin"

### Steps:
1. Find pending adjustment in table
2. Click green checkmark ✓ button
3. Confirmation appears:
   > "Are you sure you want to approve this adjustment? This will deduct X units from [Item Name]."
4. Click **OK**
5. Inventory deducted
6. Status changed to "Approved"
7. Activity logged

---

## 🚨 Dashboard Alerts

### Pending Adjustments Alert (Yellow Banner)
- **Shows when:** There are pending adjustments
- **Message:** "X adjustment(s) require admin approval. Review now"
- **Action:** Click "Review now" → Goes to pending adjustments

### Monthly Spoilage Alert (Red Banner)
- **Shows when:** Spoilage occurred this month
- **Message:** "X units spoiled this month with ₱X.XX impact. View details"
- **Action:** Click "View details" → Shows spoilage-only view

---

## 💡 Pro Tips

### ✅ DO:
- Always provide detailed reasons
- Check available stock before submitting
- Review cost impact before confirming
- Use correct adjustment type
- Approve pending adjustments promptly (admins)

### ❌ DON'T:
- Record more quantity than available
- Leave reason field empty or vague
- Use wrong adjustment type
- Delete items with adjustments (soft-deleted)

---

## 🎨 Badge Colors by Type

| Type | Color |
|------|-------|
| Spoilage | 🔴 Red (`#dc2626`) |
| Wastage | 🟠 Orange (`#f97316`) |
| Damage | 🟡 Yellow (`#eab308`) |
| Expired | 🟣 Purple (`#9333ea`) |
| Theft | ⚫ Dark (`#1f2937`) |
| Correction | 🔵 Blue (`#3b82f6`) |
| Return | 🟢 Green (`#10b981`) |

---

## 📱 Responsive Design

- **Desktop:** Full table with all columns
- **Tablet:** Scrollable table
- **Mobile:** Optimized card layout

---

## 🔒 Permissions

| Action | Admin | Cashier | Employee |
|--------|-------|---------|----------|
| View Adjustments | ✅ | ❌ | ✅ |
| Record Adjustment | ✅ | ❌ | ✅ |
| Approve Adjustment | ✅ | ❌ | ❌ |
| View Dashboard Alerts | ✅ | ❌ | ❌ |

---

## 🆘 Troubleshooting

### Error: "Adjustment quantity exceeds available stock!"
- **Cause:** Trying to deduct more than in stock
- **Solution:** Reduce quantity or check item stock level

### Adjustment Not Saving
- **Check:**
  1. All required fields filled?
  2. Quantity > 0?
  3. Reason provided?
  4. Item selected?

### Can't See Approve Button
- **Cause:** Either status is not "pending" OR you're not an admin
- **Solution:** Ask admin to approve, or check your user role

### Cost Impact Shows ₱0.00
- **Cause:** Item not selected yet
- **Solution:** Select an item from dropdown first

---

## 📞 Support

For technical issues or questions:
- **Admin Dashboard:** Check activity logs
- **System Logs:** `storage/logs/laravel.log`
- **Database:** `inventory_adjustments` table

---

## 🎯 Example Workflow

**Scenario:** Found 5 expired bottles of shampoo

1. Navigate to **Adjustments** page
2. Click **"Record Adjustment"**
3. Select **Item:** "Shampoo 500ml"
4. Select **Type:** "Expired"
5. Enter **Quantity:** 5
6. Enter **Reason:** "Found 5 bottles past expiration date (12/15/2025) during weekly inventory check"
7. Review **Cost Impact:** ₱250.00 (auto-calculated)
8. Click **"Record Adjustment"**
9. Confirm in dialog
10. ✅ Done! Inventory reduced by 5 units

---

*Last Updated: December 20, 2025*  
*Feature Version: 1.0*
