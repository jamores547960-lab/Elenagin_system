<?php $__env->startSection('title','Stock-In - TITLE'); ?>

<?php $__env->startSection('head'); ?>
    <link href="<?php echo e(asset('css/pages.css')); ?>" rel="stylesheet">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div style="position: relative; margin-bottom: 24px;">
    <h2 class="text-accent" style="font-size: 1.75rem; font-weight: 700; margin: 0; background: linear-gradient(135deg, #667eea, #764ba2); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
        <i class="fas fa-arrow-down"></i> Stock In
    </h2>
    <button type="button"
            class="btn btn-primary"
            style="position: absolute; top: 0; right: 0; display: inline-flex; align-items: center; gap: 6px; background: linear-gradient(135deg, #667eea, #764ba2); color: #fff; border: none; padding: 10px 20px; border-radius: 8px; font-weight: 600; font-size: 0.9rem; box-shadow: 0 2px 8px rgba(102, 126, 234, 0.2); transition: all 0.2s ease; white-space: nowrap;"
            onmouseover="this.style.transform='translateY(-1px)';this.style.boxShadow='0 4px 12px rgba(102,126,234,0.3)'"
            onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='0 2px 8px rgba(102,126,234,0.2)'"
            data-action="register-stock-in">
        <i class="fas fa-plus"></i> Add Stock In
    </button>
</div>

<div class="glass-card glass-card-wide" style="height: calc(100vh - 250px); display: flex; flex-direction: column;">

    <?php if(session('success')): ?>
        <div class="alert alert-success mb-3"><?php echo e(session('success')); ?></div>
    <?php endif; ?>
    <?php if($errors->any() && old('_from')!=='createStockIn'): ?>
        <div class="alert alert-danger mb-3">
            <ul class="m-0 ps-3" style="font-size:.7rem;">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $e): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><li><?php echo e($e); ?></li><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    <?php endif; ?>

    <div class="toolbar-top d-flex flex-wrap justify-content-end gap-3 mb-3">
        <div class="search-bar-wrapper">
            <form method="GET" action="<?php echo e(route('stock_in.index')); ?>" class="search-bar" autocomplete="off">
                <span class="search-icon"><i class="fas fa-search"></i></span>
                <input type="text"
                       name="search"
                       value="<?php echo e(request('search')); ?>"
                       class="search-input"
                       placeholder="Search stock-in ID, item, supplier...">
                <?php if(request('search')): ?>
                    <button type="button"
                            class="search-clear"
                            onclick="window.location='<?php echo e(route('stock_in.index')); ?>'">
                        <i class="fas fa-times"></i>
                    </button>
                <?php endif; ?>
                <button class="btn btn-primary btn-search-main" type="submit">Search</button>
            </form>
            <div class="search-meta">
                <?php $total = $stockIns->count(); ?>
                <span class="result-count">
                    <?php echo e($total); ?> <?php echo e(\Illuminate\Support\Str::plural('result',$total)); ?>

                    <?php if(request('search')): ?> for "<strong><?php echo e(e(request('search'))); ?></strong>" <?php endif; ?>
                </span>
                <?php if(request('search')): ?>
                    <span class="active-filter-chip"><i class="fas fa-filter"></i> Filter active</span>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="table-responsive" style="flex: 1; overflow-y: auto;">
        <table class="table align-middle">
            <thead>
                <tr>
                    <th>Stock-In ID</th>
                    <th>Item</th>
                    <th>Supplier</th>
                    <th class="text-end">Qty</th>
                    <th class="text-end">Unit Price</th>
                    <th class="text-end">Total</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $stockIns; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td><?php echo e($row->stockin_id); ?></td>
                    <td><?php echo e($row->item?->name); ?></td>
                    <td><?php echo e($row->supplier?->name); ?></td>
                    <td class="text-end"><?php echo e($row->quantity); ?></td>
                    <td class="text-end">₱<?php echo e(number_format($row->price,2)); ?></td>
                    <td class="text-end">₱<?php echo e(number_format($row->total_price,2)); ?></td>
                    <td><?php echo e(\Carbon\Carbon::parse($row->stockin_date)->format('Y-m-d')); ?></td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="7" class="empty-row text-center">No stock-in records found.</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="modal hidden"
     id="createStockInModal"
     data-modal
     <?php if($errors->any() && old('_from')==='createStockIn'): ?> data-auto-open="true" <?php endif; ?>>
    <div class="modal-content service-modal-wide" style="max-width:840px;">
        <h2 style="margin-bottom:14px;">Add Stock-In</h2>

        <?php if($errors->any() && old('_from')==='createStockIn'): ?>
            <div class="alert alert-danger mb-3">
                <ul class="m-0 ps-3" style="font-size:.7rem;">
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $e): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($e); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
        <?php endif; ?>

        <form action="<?php echo e(route('stock_in.store')); ?>" method="POST" id="stockInForm" autocomplete="off">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="_from" value="createStockIn">

            <div class="form-row" style="display:flex;gap:14px;flex-wrap:wrap;">
                <div class="form-group" style="flex:0 0 200px;">
                    <label>Date</label>
                    <input type="date"
                           name="stockin_date"
                           class="form-input"
                           value="<?php echo e(old('stockin_date', now()->format('Y-m-d'))); ?>"
                           required>
                </div>
                <div class="form-group" style="flex:1 1 280px;">
                    <label>Supplier</label>
                    <select name="supplier_id" class="form-input" required>
                        <option value="">-- supplier --</option>
                        <?php $__currentLoopData = $suppliers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sup): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($sup->supplier_id); ?>" <?php if(old('supplier_id')==$sup->supplier_id): echo 'selected'; endif; ?>>
                                <?php echo e($sup->name); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
            </div>

            <div class="line-items-block" style="margin-top:14px;">
                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:6px;">
                    <h4 style="font-size:.7rem;letter-spacing:1px;text-transform:uppercase;margin:0;">Stock Lines</h4>
                    <button type="button" class="btn btn-secondary btn-sm" id="addStockLine">
                        <i class="fas fa-plus"></i> Add Line
                    </button>
                </div>
                <div class="table-responsive">
                    <table class="table compact" id="stockLinesTable">
                        <thead>
                        <tr>
                            <th style="width:54%;">Item</th>
                            <th style="width:10%;" class="text-end">Qty</th>
                            <th style="width:16%;" class="text-end">Unit</th>
                            <th style="width:14%;" class="text-end">Line Total</th>
                            <th style="width:6%;"></th>
                        </tr>
                        </thead>
                        <tbody></tbody>
                        <tfoot>
                        <tr>
                            <td colspan="3" class="text-end" style="font-weight:600;">Grand Total</td>
                            <td class="text-end"><span id="stockGrandTotal">0.00</span></td>
                            <td></td>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <div class="note" style="font-size:.63rem;margin-top:6px;opacity:.7;">
                Unit price auto-fills from selected item.
            </div>

            <div class="button-row" style="margin-top:18px;display:flex;gap:10px;justify-content:flex-end;">
                <button type="button" class="btn-secondary" data-close>Cancel</button>
                <button type="submit" class="btn-primary">Save</button>
            </div>
        </form>
    </div>
</div>

<template id="stockLineTemplate">
    <tr class="stock-line-row">
        <td>
            <select data-name="item_id" class="form-input stock-item-select" required>
                <option value="">-- item --</option>
                <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $it): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($it->item_id); ?>" data-price="<?php echo e($it->unit_price ?? 0); ?>">
                        <?php echo e($it->name); ?>

                    </option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </td>
        <td>
            <input type="number"
                   data-name="quantity"
                   class="form-input stock-qty-input text-end"
                   min="1"
                   value="1"
                   required>
        </td>
        <td>
            <input type="number"
                   step="0.01"
                   min="0"
                   class="form-input stock-unit-input text-end"
                   data-unit
                   readonly>
        </td>
        <td class="stock-line-total text-end">0.00</td>
        <td>
            <button type="button" class="btn btn-delete btn-sm remove-stock-line">
                <i class="fas fa-times"></i>
            </button>
        </td>
    </tr>
</template>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
(function(){
    const openBtn = document.querySelector('[data-action="register-stock-in"]');
    const modal   = document.getElementById('createStockInModal');

    function openModal(){
        if(!modal) return;
        modal.classList.remove('hidden');
        modal.classList.add('show');
        document.body.style.overflow = 'hidden';
        ensureInit();
    }
    function closeModal(){
        if(!modal) return;
        modal.classList.remove('show');
        setTimeout(()=>{
            modal.classList.add('hidden');
            if(!document.querySelector('.modal.show')) document.body.style.overflow='';
        },180);
    }

    document.addEventListener('click', e=>{
        if(e.target.matches('[data-close]')){
            closeModal();
        }
        if(!e.target.closest('.modal-content') && e.target.classList.contains('modal') ){
            closeModal();
        }
    });
    document.addEventListener('keydown', e=>{
        if(e.key==='Escape' && modal && modal.classList.contains('show')) closeModal();
    });

    openBtn?.addEventListener('click', openModal);

    if(modal && modal.dataset.autoOpen === 'true'){
        openModal();
    }

    function ensureInit(){
        if(modal.dataset.multiInit === '1') return;
        modal.dataset.multiInit = '1';
        initMultiStock();
    }

    function initMultiStock(){
        const tableBody = modal.querySelector('#stockLinesTable tbody');
        const tmpl      = document.getElementById('stockLineTemplate');
        const addBtn    = document.getElementById('addStockLine');
        const grandOut  = document.getElementById('stockGrandTotal');
        const form      = document.getElementById('stockInForm');

        function addLine(){
            const index = tableBody.querySelectorAll('tr').length;
            const row = tmpl.content.firstElementChild.cloneNode(true);
            row.querySelectorAll('[data-name]').forEach(el=>{
                const key = el.getAttribute('data-name');
                el.name = `lines[${index}][${key}]`;
            });
            tableBody.appendChild(row);
            bindRow(row);
            updateTotals();
        }

        function reindex(){
            tableBody.querySelectorAll('tr').forEach((tr,i)=>{
                tr.querySelectorAll('[data-name]').forEach(el=>{
                    const key = el.getAttribute('data-name');
                    el.name = `lines[${i}][${key}]`;
                });
            });
        }

        function bindRow(row){
            const itemSel = row.querySelector('.stock-item-select');
            const qtyInp  = row.querySelector('.stock-qty-input');
            const unitInp = row.querySelector('[data-unit]');
            const remBtn  = row.querySelector('.remove-stock-line');

            function fillUnit(){
                const opt = itemSel.options[itemSel.selectedIndex];
                const price = opt ? parseFloat(opt.dataset.price||'0') : 0;
                if(!unitInp.value || unitInp.readOnly){
                    unitInp.value = price.toFixed(2);
                }
                updateTotals();
            }

            itemSel.addEventListener('change', fillUnit);
            qtyInp.addEventListener('input', updateTotals);
            unitInp.addEventListener('input', updateTotals);
            remBtn.addEventListener('click', ()=>{
                row.remove();
                reindex();
                updateTotals();
            });

            fillUnit();
        }

        function updateTotals(){
            let grand = 0;
            tableBody.querySelectorAll('tr').forEach(tr=>{
                const qty  = parseFloat(tr.querySelector('.stock-qty-input')?.value || 0);
                const unit = parseFloat(tr.querySelector('[data-unit]')?.value || 0);
                const line = qty * unit;
                tr.querySelector('.stock-line-total').textContent = line.toFixed(2);
                grand += line;
            });
            if(grandOut) grandOut.textContent = grand.toFixed(2);
        }

        addBtn?.addEventListener('click', addLine);

        if(tableBody.children.length === 0) addLine();

        form?.addEventListener('submit', e=>{
            if(tableBody.children.length === 0){
                e.preventDefault();
                alert('Add at least one stock line.');
                return;
            }
            let ok = true;
            tableBody.querySelectorAll('tr').forEach(tr=>{
                const item = tr.querySelector('.stock-item-select')?.value;
                const qty  = tr.querySelector('.stock-qty-input')?.value;
                if(!item || !qty) ok = false;
            });
            if(!ok){
                e.preventDefault();
                alert('Complete all line fields.');
            }
        });
    }
})();
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('system', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Sydney Jagape\kerk\resources\views/stock_in/index.blade.php ENDPATH**/ ?>