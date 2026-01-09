@extends('system')

@section('title','Inventory - TITLE')

@section('head')
    <link href="{{ asset('css/pages.css') }}" rel="stylesheet">
    <style>
        .inventory-pagination nav ul { justify-content: center; }
        .inventory-pagination nav { display:flex; justify-content:center; }
    </style>
@endsection

@section('content')
<div style="position: relative; margin-bottom: 24px;">
    <h2 class="text-accent" style="font-size: 1.75rem; font-weight: 700; margin: 0; background: linear-gradient(135deg, #667eea, #764ba2); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
        <i class="fas fa-box"></i> Inventory
    </h2>
    <div style="position: absolute; top: 0; right: 0; display: flex; gap: 10px;">
        <button type="button"
           class="btn btn-secondary"
           id="openCategoriesBtn"
           style="display: inline-flex; align-items: center; gap: 6px; background: #ffffff; border: 2px solid rgba(102, 126, 234, 0.2); color: #667eea; padding: 10px 18px; border-radius: 8px; font-weight: 600; font-size: 0.9rem; transition: all 0.2s ease; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04); white-space: nowrap;"
           onmouseover="this.style.borderColor='#667eea';this.style.boxShadow='0 4px 12px rgba(102,126,234,0.15)'"
           onmouseout="this.style.borderColor='rgba(102,126,234,0.2)';this.style.boxShadow='0 2px 8px rgba(0,0,0,0.04)'">
            <i class="fas fa-folder-open"></i> Categories
        </button>
        <button type="button"
                class="btn btn-primary"
                data-action="register-item"
                style="display: inline-flex; align-items: center; gap: 6px; background: linear-gradient(135deg, #667eea, #764ba2); color: #fff; border: none; padding: 10px 20px; border-radius: 8px; font-weight: 600; font-size: 0.9rem; box-shadow: 0 2px 8px rgba(102, 126, 234, 0.2); transition: all 0.2s ease; white-space: nowrap;"
                onmouseover="this.style.transform='translateY(-1px)';this.style.boxShadow='0 4px 12px rgba(102,126,234,0.3)'"
                onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='0 2px 8px rgba(102,126,234,0.2)'">
            <i class="fas fa-plus"></i> Add Item
        </button>
    </div>
</div>

<div class="glass-card glass-card-wide" style="height: calc(100vh - 250px); display: flex; flex-direction: column;">

    @if(session('success'))
        <div class="alert alert-success mb-3" style="background: linear-gradient(135deg, #48bb78, #38a169); border-left: 4px solid #22543d; border-radius: 10px; padding: 16px 20px; color: #ffffff; font-weight: 600; border: 2px solid #48bb78; box-shadow: 0 4px 12px rgba(72, 187, 120, 0.3);">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif
    @if($errors->any() && old('_from') === 'createItem')
        <div class="alert alert-danger mb-3" style="background: linear-gradient(135deg, rgba(245, 101, 101, 0.1), rgba(245, 101, 101, 0.05)); border-left: 4px solid #f56565; border-radius: 10px; padding: 14px 18px; color: #c53030; font-weight: 500; border: 1px solid rgba(245, 101, 101, 0.2);">
            <i class="fas fa-exclamation-triangle"></i> Please fix the following errors:
            <ul class="m-0 ps-3 mt-2" style="font-size:.85rem;">
                @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
            </ul>
        </div>
    @endif

    <div class="toolbar-modern mb-4">
        <div class="search-bar-wrapper" style="flex:1 1 400px;max-width:600px;">
            <form method="GET" action="{{ route('inventory.index') }}" class="search-bar-modern" autocomplete="off">
                <span class="search-icon" style="color:#667eea;"><i class="fas fa-search"></i></span>
                <input type="text"
                       name="search"
                       value="{{ request('search') }}"
                       style="flex:1;border:none;outline:none;font-size:0.95rem;background:transparent;"
                       placeholder="Search item name or category...">
                @if(request('search'))
                    <button type="button"
                            style="background:none;border:none;color:#667eea;cursor:pointer;padding:4px 8px;"
                            onclick="window.location='{{ route('inventory.index') }}'">
                        <i class="fas fa-times"></i>
                    </button>
                @endif
                <button style="background:linear-gradient(135deg,#667eea,#764ba2);color:#fff;border:none;padding:8px 20px;border-radius:8px;font-weight:600;cursor:pointer;transition:all 0.2s ease;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'"><i class="fas fa-search"></i> Search</button>
            </form>
            <div class="search-meta" style="margin-top:12px;display:flex;align-items:center;gap:12px;font-size:0.85rem;color:#718096;">
                @php $total = $items->total(); @endphp
                <span style="font-weight:500;">
                    <i class="fas fa-list"></i> {{ $total }} {{ \Illuminate\Support\Str::plural('result',$total) }}
                    @if(request('search')) for <strong style="color:#667eea;">"{{ e(request('search')) }}"</strong> @endif
                </span>
                @if(request('search') || request('category_filter'))
                    <span class="badge-modern badge-info" style="font-size:0.7rem;"><i class="fas fa-filter"></i> Filter active</span>
                @endif
            </div>
        </div>

        <form method="GET" action="{{ route('inventory.index') }}" class="filter-group" style="display:flex;gap:10px;align-items:center;">
            @if(request('search'))
                <input type="hidden" name="search" value="{{ request('search') }}">
            @endif
            <select name="category_filter" class="form-select-modern" style="min-width:200px;">
                <option value=""><i class="fas fa-filter"></i> All Categories</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->itemctgry_id }}"
                        @selected(request('category_filter') == $cat->itemctgry_id)>
                        {{ $cat->name }}
                    </option>
                @endforeach
            </select>
            <button style="background:linear-gradient(135deg,#667eea,#764ba2);color:#fff;border:none;padding:10px 20px;border-radius:8px;font-weight:600;cursor:pointer;transition:all 0.2s ease;" onmouseover="this.style.transform='translateY(-2px)';this.style.boxShadow='0 4px 12px rgba(102,126,234,0.3)'" onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='none'">Apply Filter</button>
            @if(request('category_filter'))
                <a href="{{ route('inventory.index', array_filter(['search'=>request('search')])) }}"
                   style="background:#fff;color:#667eea;border:2px solid rgba(102,126,234,0.2);padding:10px 20px;border-radius:8px;font-weight:600;text-decoration:none;transition:all 0.2s ease;"
                   onmouseover="this.style.borderColor='#667eea';this.style.background='rgba(102,126,234,0.05)'"
                   onmouseout="this.style.borderColor='rgba(102,126,234,0.2)';this.style.background='#fff'"><i class="fas fa-times-circle"></i> Clear</a>
            @endif
        </form>
    </div>

    <div class="table-responsive" style="flex: 1; overflow-y: auto; border-radius:12px;overflow:hidden;box-shadow:0 4px 12px rgba(0,0,0,0.05);">
        <table class="table table-modern align-middle" style="margin:0;">
            <thead>
            <tr>
                <th><i class="fas fa-hashtag"></i> Item ID</th>
                <th><i class="fas fa-cube"></i> Name</th>
                <th><i class="fas fa-tag"></i> Category</th>
                <th class="text-end"><i class="fas fa-sort-numeric-down"></i> Qty</th>
                <th><i class="fas fa-ruler"></i> Unit</th>
                <th class="text-end"><i class="fas fa-dollar-sign"></i> Unit Price</th>
                <th style="width:160px;text-align:center;"><i class="fas fa-cog"></i> Actions</th>
            </tr>
            </thead>
            <tbody>
            @forelse($items as $item)
                <tr>
                    <td style="font-weight:600;color:#667eea;">#{{ $item->item_id }}</td>
                    <td style="font-weight:600;color:#2d3748;">{{ $item->name }}</td>
                    <td>
                        <span class="badge-modern badge-info" style="font-size:0.75rem;">
                            {{ $item->category?->name ?? '—' }}
                        </span>
                    </td>
                    <td class="text-end" style="font-weight:600;color:#2d3748;">{{ $item->quantity }}</td>
                    <td style="color:#718096;">{{ $item->unit ?? '—' }}</td>
                    <td class="text-end" style="font-weight:600;color:#48bb78;">₱{{ number_format($item->unit_price,2) }}</td>
                    <td>
                        <div class="d-flex gap-2 justify-content-center">
                            <a href="{{ route('inventory.edit', $item->item_id) }}"
                               class="btn-action btn-action-edit" title="Edit Item">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('inventory.destroy', $item->item_id) }}"
                                  method="POST"
                                  onsubmit="return confirm('Are you sure you want to delete this item?');">
                                @csrf
                                @method('DELETE')
                                <button class="btn-action btn-action-delete" title="Delete Item" type="submit">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="7" class="text-center py-5">
                    <div class="empty-state">
                        <div class="empty-state-icon">
                            <i class="fas fa-box"></i>
                        </div>
                        <div class="empty-state-title">No Items Found</div>
                        <div class="empty-state-description">
                            @if(request('search') || request('category_filter'))
                                No items match your current filters. Try adjusting your search criteria.
                            @else
                                Your inventory is empty. Click "Add New Item" to get started.
                            @endif
                        </div>
                    </div>
                </td></tr>
            @endforelse
            </tbody>
        </table>
    </div>

    @if($items->hasPages())
        <div class="mt-2 inventory-pagination" style="display:flex;flex-direction:column;align-items:center;gap:4px;">
            <div style="font-size:.65rem;opacity:.75;">
                Page {{ $items->currentPage() }} of {{ $items->lastPage() }}
                | Showing {{ $items->firstItem() }}–{{ $items->lastItem() }} of {{ $items->total() }}
            </div>
            <div style="width:100%;display:flex;justify-content:center;">
                {{ $items->onEachSide(1)->links() }}
            </div>
        </div>
    @endif
</div>

<!-- Create Item Modal -->
<div class="modal hidden"
     id="createItemModal"
     data-modal
     @if($errors->any() && old('_from') === 'createItem') data-auto-open="true" @endif>
    <div class="modal-content" style="max-width:640px;">
        <h2 style="margin-bottom:14px;">Add Item</h2>
        <form action="{{ route('inventory.store') }}" method="POST">
            @csrf
            <input type="hidden" name="_from" value="createItem">
            <div class="form-row">
                <div class="form-group" style="flex:1 0 60%;">
                    <label>Name</label>
                    <input name="name" class="form-input" required value="{{ old('name') }}">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Unit Price</label>
                    <input name="unit_price" type="number" step="0.01" min="0" class="form-input" required value="{{ old('unit_price',0) }}">
                </div>
                <div class="form-group">
                    <label>Category</label>
                    <select name="itemctgry_id" class="form-input" style="width:100%;" required>
                        <option value="">-- select --</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->itemctgry_id }}" @selected(old('itemctgry_id')==$cat->itemctgry_id)>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Unit</label>
                    <input name="unit" class="form-input" value="{{ old('unit') }}" placeholder="pcs / box">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group" style="flex:1 0 100%;">
                    <label>Description</label>
                    <textarea name="description" rows="3" class="form-input" style="resize:vertical; width:100%;">{{ old('description') }}</textarea>
                </div>
            </div>

            <div class="button-row" style="margin-top:18px;display:flex;gap:10px;justify-content:flex-end;">
                <button type="button" class="btn-secondary" data-close>Cancel</button>
                <button type="submit" class="btn-primary">Save</button>
            </div>
        </form>
    </div>
</div>

<!-- Categories Modal -->
<div class="modal hidden" id="categoriesModal" data-modal>
    <div class="modal-content" style="max-width:820px;">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:10px;">
            <h2 style="margin:0;font-size:1rem;">Item Categories</h2>
            <button type="button" class="btn btn-secondary" data-close style="padding:6px 14px;">Close</button>
        </div>
        <div style="display:flex;flex-direction:column;gap:18px;align-items:stretch;">
            <div class="glass-card" style="background:#181818;border:1px solid #272727;padding:14px;border-radius:14px;">
                <form id="catForm" method="POST" action="{{ route('inventory.itemctgry.store') }}">
                    @csrf
                    <input type="hidden" id="catMethod" name="_method" value="POST">
                    <input type="hidden" id="catId">
                    <input type="hidden" name="_categoryForm" value="1">
                    <h3 id="catFormTitle" style="margin:0 0 10px;font-size:.85rem;">Add Category</h3>
                    <div style="display:flex;flex-direction:column;gap:10px;">
                        <div>
                            <label class="filter-label">Name</label>
                            <input name="name" id="catName" class="form-input" style="width:100%;" required>
                        </div>
                    </div>
                    <div style="display:flex;gap:8px;margin-top:14px;">
                        <button class="btn btn-primary" id="catSubmit" type="submit"
                            style="width:100%;">Save</button>
                        <button type="button" class="btn btn-secondary" id="catCancelEdit"
                            style="display:none;width:100%;">Cancel Edit</button>
                    </div>
                    @if($errors->any() && ! (old('_from') === 'createItem'))
                        <div class="alert alert-danger mt-2" style="font-size:.6rem;">
                            <ul class="m-0 ps-3">
                                @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
                            </ul>
                        </div>
                    @endif
                </form>
            </div>
            <div class="glass-card" style="background:#181818;border:1px solid #272727;padding:14px;border-radius:14px;max-height:440px;overflow:auto;">
                <table class="table" style="width:100%;font-size:.65rem;">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th style="width:110px;text-align:right;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($categories as $cat)
                            <tr data-cat-row data-id="{{ $cat->itemctgry_id }}" data-name="{{ $cat->name }}">
                                <td>{{ $cat->name }}</td>
                                <td style="text-align:right;">
                                    <button type="button"
                                            class="btn btn-edit"
                                            data-edit
                                            style="padding:4px 8px;font-size:.55rem;">
                                        Edit
                                    </button>
                                    <form method="POST"
                                          action="{{ route('inventory.itemctgry.destroy',$cat->itemctgry_id) }}"
                                          style="display:inline-block;"
                                          onsubmit="return confirm('Delete this category?');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-delete" style="padding:4px 8px;font-size:.55rem;">Del</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="3" style="text-align:center;opacity:.6;">No categories.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
(function(){
    const openBtn  = document.getElementById('openCategoriesBtn');
    const modal    = document.getElementById('categoriesModal');
    const catForm  = document.getElementById('catForm');
    const catMethod= document.getElementById('catMethod');
    const catId    = document.getElementById('catId');
    const catName  = document.getElementById('catName');
    const catTitle = document.getElementById('catFormTitle');
    const catSubmit= document.getElementById('catSubmit');
    const catCancel= document.getElementById('catCancelEdit');

    function showModal() {
        modal.classList.remove('hidden');
        requestAnimationFrame(()=> modal.classList.add('show'));
        document.body.style.overflow = 'hidden';
    }
    function hideModal() {
        modal.classList.remove('show');
        setTimeout(()=>{
            modal.classList.add('hidden');
            if (!document.querySelector('.modal.show')) document.body.style.overflow = '';
        },180);
        setCreateMode();
    }

    function setCreateMode(){
        catForm.action = "{{ route('inventory.itemctgry.store') }}";
        catMethod.value = 'POST';
        catId.value = '';
        catName.value = '';
        catTitle.textContent = 'Add Category';
        catSubmit.textContent = 'Save';
        catCancel.style.display = 'none';
        catSubmit.style.width = '100%';
        catCancel.style.width = '100%';
    }
    function setEditMode(id, name){
        catForm.action = "{{ route('inventory.itemctgry.update','__ID__') }}".replace('__ID__', id);
        catMethod.value = 'PUT';
        catId.value = id;
        catName.value = name;
        catTitle.textContent = 'Edit Category #' + id;
        catSubmit.textContent = 'Update';
        catCancel.style.display = 'inline-flex';
        catSubmit.style.width = '50%';
        catCancel.style.width = '50%';
    }

    openBtn?.addEventListener('click', showModal);

    modal.addEventListener('click', e=>{
        if (e.target.matches('[data-close]') || (!e.target.closest('.modal-content'))) {
            hideModal();
        }
        const editBtn = e.target.closest('[data-edit]');
        if (editBtn) {
            const row = editBtn.closest('[data-cat-row]');
            if (row) {
                setEditMode(row.dataset.id, row.dataset.name);
            }
        }
    });

    catCancel.addEventListener('click', e=>{
        e.preventDefault();
        setCreateMode();
    });

    document.addEventListener('keydown', e=>{
        if (e.key === 'Escape' && !modal.classList.contains('hidden')) hideModal();
    });
    @if(session('showCategoriesModal') || ( $errors->any() && ! (old('_from') === 'createItem') ))
        showModal();
        @if(old('name') && session('showCategoriesModal') && old('itemctgry_id'))
            setEditMode("{{ old('itemctgry_id') }}", "{{ old('name') }}");
        @endif
    @endif

    setCreateMode();
})();
</script>
@endsection