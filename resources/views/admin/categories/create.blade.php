@extends('layouts.admin')

@section('title', 'Create Category')
@section('breadcrumb', 'Add a new product category')
@section('cat_open', 'true')

@section('content')

<!-- Page header -->
<div class="page-header">
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.categories.index') }}"
           class="w-9 h-9 rounded-xl border border-gray-200 flex items-center justify-center hover:bg-gray-50 transition text-gray-500">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </a>
        <div>
            <h2 class="text-xl font-bold text-gray-800">Create Category</h2>
            <p class="text-sm text-gray-500 mt-0.5">Fill in the details to add a new category</p>
        </div>
    </div>
    <a href="{{ route('admin.categories.index') }}" class="btn-ghost flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
        All Categories
    </a>
</div>

<div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

    <!-- Main form -->
    <div class="xl:col-span-2">
        <div class="bg-white rounded-2xl p-6" style="box-shadow:0 2px 16px rgba(0,0,0,.06)">
            <form action="{{ route('admin.categories.store') }}" method="POST" id="categoryForm">
                @csrf

                <!-- Name -->
                <div class="mb-5">
                    <label class="form-label">
                        Category Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" id="catName"
                           value="{{ old('name') }}"
                           class="form-input @error('name') border-red-400 @enderror"
                           placeholder="e.g. Electronics, Fashion, Home & Garden"
                           oninput="generateSlug(this.value)"
                           required>
                    @error('name')
                        <p class="text-red-500 text-xs mt-1.5 flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Slug -->
                <div class="mb-5">
                    <label class="form-label">
                        Slug
                        <span class="text-gray-400 font-normal text-xs ml-1">(auto-generated from name, must be unique)</span>
                    </label>
                    <div class="relative">
                        <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-sm font-medium select-none">/</span>
                        <input type="text" name="slug" id="catSlug"
                               value="{{ old('slug') }}"
                               class="form-input pl-6 @error('slug') border-red-400 @enderror"
                               placeholder="auto-generated">
                    </div>
                    @error('slug')
                        <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>
                    @enderror
                    <p class="text-gray-400 text-xs mt-1.5">Leave empty to auto-generate from name. Only lowercase letters, numbers, and hyphens.</p>
                </div>

                <!-- Parent Category -->
                <div class="mb-5">
                    <label class="form-label">Parent Category
                        <span class="text-gray-400 font-normal text-xs ml-1">(optional)</span>
                    </label>
                    <select name="parent_id" class="form-input @error('parent_id') border-red-400 @enderror">
                        <option value="">— None (Top-level category) —</option>
                        @foreach($parents as $parent)
                            <option value="{{ $parent->id }}" {{ old('parent_id') == $parent->id ? 'selected' : '' }}>
                                {{ $parent->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('parent_id')
                        <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div class="mb-5">
                    <label class="form-label">Description
                        <span class="text-gray-400 font-normal text-xs ml-1">(optional)</span>
                    </label>
                    <textarea name="description" rows="4"
                              class="form-input resize-none @error('description') border-red-400 @enderror"
                              placeholder="Briefly describe what products belong in this category...">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Form actions -->
                <div class="flex items-center gap-3 pt-2">
                    <button type="submit" class="btn-primary flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Create Category
                    </button>
                    <a href="{{ route('admin.categories.index') }}" class="btn-ghost">Cancel</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Sidebar: Status + Tips -->
    <div class="space-y-5">

        <!-- Status -->
        <div class="bg-white rounded-2xl p-5" style="box-shadow:0 2px 16px rgba(0,0,0,.06)">
            <h3 class="font-semibold text-gray-700 mb-4">Status</h3>
            <div class="space-y-2">
                <label class="flex items-center gap-3 p-3 rounded-xl border-2 cursor-pointer transition"
                       :class="selectedStatus === 'active' ? 'border-indigo-500 bg-indigo-50' : 'border-gray-200 hover:border-gray-300'"
                       x-data="{ selectedStatus: '{{ old('status', 'active') }}' }"
                       x-init="$root.__x = $data">
                    <input type="radio" name="status" value="active"
                           {{ old('status', 'active') === 'active' ? 'checked' : '' }}
                           class="text-indigo-600 w-4 h-4"
                           @change="selectedStatus='active'">
                    <div>
                        <div class="text-sm font-semibold text-gray-700">Active</div>
                        <div class="text-xs text-gray-400">Visible in the storefront</div>
                    </div>
                    <span class="ml-auto badge-active">Active</span>
                </label>
                <label class="flex items-center gap-3 p-3 rounded-xl border-2 cursor-pointer transition border-gray-200 hover:border-gray-300">
                    <input type="radio" name="status" value="inactive"
                           {{ old('status') === 'inactive' ? 'checked' : '' }}
                           class="text-indigo-600 w-4 h-4">
                    <div>
                        <div class="text-sm font-semibold text-gray-700">Inactive</div>
                        <div class="text-xs text-gray-400">Hidden from customers</div>
                    </div>
                    <span class="ml-auto badge-inactive">Inactive</span>
                </label>
            </div>
            @error('status')
                <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
            @enderror
        </div>

        <!-- Tips -->
        <div class="rounded-2xl p-5" style="background:linear-gradient(135deg,rgba(99,102,241,.06),rgba(139,92,246,.06));border:1px solid rgba(99,102,241,.15)">
            <div class="flex items-center gap-2 mb-3">
                <svg class="w-5 h-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <h3 class="font-semibold text-indigo-700">Tips</h3>
            </div>
            <ul class="space-y-2 text-xs text-indigo-600">
                <li class="flex items-start gap-2">
                    <span class="mt-0.5 w-1.5 h-1.5 rounded-full bg-indigo-400 flex-shrink-0"></span>
                    Use clear, descriptive names for easy browsing.
                </li>
                <li class="flex items-start gap-2">
                    <span class="mt-0.5 w-1.5 h-1.5 rounded-full bg-indigo-400 flex-shrink-0"></span>
                    Slugs are used in URLs — keep them short & readable.
                </li>
                <li class="flex items-start gap-2">
                    <span class="mt-0.5 w-1.5 h-1.5 rounded-full bg-indigo-400 flex-shrink-0"></span>
                    Assign a parent to create sub-categories (e.g. "Laptops" under "Electronics").
                </li>
                <li class="flex items-start gap-2">
                    <span class="mt-0.5 w-1.5 h-1.5 rounded-full bg-indigo-400 flex-shrink-0"></span>
                    Set Inactive to hide a category without deleting it.
                </li>
            </ul>
        </div>
    </div>
</div>

@push('scripts')
<script>
function generateSlug(val) {
    const slugField = document.getElementById('catSlug');
    // Only auto-generate if user hasn't manually typed a slug
    if (!slugField.dataset.manual) {
        slugField.value = val
            .toLowerCase()
            .replace(/[^a-z0-9\s-]/g, '')
            .trim()
            .replace(/[\s]+/g, '-')
            .replace(/-+/g, '-');
    }
}
document.getElementById('catSlug').addEventListener('input', function() {
    this.dataset.manual = 'true';
    // Clean what user types
    this.value = this.value
        .toLowerCase()
        .replace(/[^a-z0-9-]/g, '')
        .replace(/-+/g, '-');
});
</script>
@endpush

@endsection
