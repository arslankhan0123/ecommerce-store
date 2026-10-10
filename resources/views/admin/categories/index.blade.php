@extends('layouts.admin')

@section('title', 'Categories')
@section('breadcrumb', 'Manage your product categories')
@section('cat_open', 'true')

@section('content')

<!-- Page header -->
<div class="page-header">
    <div>
        <h2 class="text-xl font-bold text-gray-800">All Categories</h2>
        <p class="text-sm text-gray-500 mt-0.5">{{ $categories->total() }} categories found</p>
    </div>
    <a href="{{ route('admin.categories.create') }}" class="btn-primary flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
        Add Category
    </a>
</div>

<!-- Table card -->
<div class="bg-white rounded-2xl overflow-hidden" style="box-shadow:0 2px 16px rgba(0,0,0,.06)">

    @if($categories->count())
    <div class="overflow-x-auto">
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width:50px">#</th>
                    <th>Name</th>
                    <th>Slug</th>
                    <th>Parent</th>
                    <th>Description</th>
                    <th>Status</th>
                    <th>Created</th>
                    <th style="width:130px">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($categories as $i => $cat)
                <tr>
                    <td class="text-gray-400 font-mono text-xs">{{ ($categories->currentPage()-1)*10 + $i + 1 }}</td>
                    <td>
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0 text-white text-sm font-bold"
                                 style="background:linear-gradient(135deg,#6366f1,#8b5cf6);">
                                {{ strtoupper(substr($cat->name,0,1)) }}
                            </div>
                            <div>
                                <div class="font-semibold text-gray-800">{{ $cat->name }}</div>
                                @if($cat->children_count ?? 0)
                                    <div class="text-xs text-gray-400">{{ $cat->children_count }} sub-categories</div>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td>
                        <code class="bg-gray-100 text-gray-600 px-2 py-1 rounded-lg text-xs font-mono">{{ $cat->slug }}</code>
                    </td>
                    <td>
                        @if($cat->parent)
                            <span class="flex items-center gap-1.5 text-sm">
                                <svg class="w-3.5 h-3.5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                {{ $cat->parent->name }}
                            </span>
                        @else
                            <span class="text-gray-300 text-sm">—</span>
                        @endif
                    </td>
                    <td class="max-w-xs">
                        <span class="text-gray-500 text-sm line-clamp-1">
                            {{ Str::limit($cat->description, 40) ?: '—' }}
                        </span>
                    </td>
                    <td>
                        <span class="{{ $cat->status === 'active' ? 'badge-active' : 'badge-inactive' }}">
                            {{ ucfirst($cat->status) }}
                        </span>
                    </td>
                    <td class="text-gray-400 text-xs">{{ $cat->created_at->format('M d, Y') }}</td>
                    <td>
                        <div class="flex items-center gap-2">
                            <a href="{{ route('admin.categories.edit', $cat) }}"
                               class="w-8 h-8 rounded-lg bg-indigo-50 hover:bg-indigo-100 flex items-center justify-center text-indigo-600 transition"
                               title="Edit">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </a>
                            <form action="{{ route('admin.categories.destroy', $cat) }}" method="POST"
                                  onsubmit="return confirm('Delete \'{{ $cat->name }}\'? This cannot be undone.')">
                                @csrf @method('DELETE')
                                <button type="submit"
                                        class="w-8 h-8 rounded-lg bg-red-50 hover:bg-red-100 flex items-center justify-center text-red-500 transition"
                                        title="Delete">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($categories->hasPages())
    <div class="px-6 py-4 border-t border-gray-100">
        {{ $categories->links() }}
    </div>
    @endif

    @else
    <!-- Empty state -->
    <div class="py-20 text-center">
        <div class="w-20 h-20 rounded-2xl mx-auto mb-5 flex items-center justify-center"
             style="background:linear-gradient(135deg,rgba(99,102,241,.1),rgba(139,92,246,.1))">
            <svg class="w-10 h-10 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
            </svg>
        </div>
        <h3 class="text-lg font-semibold text-gray-700 mb-2">No categories yet</h3>
        <p class="text-gray-400 text-sm mb-6">Start organizing your products by adding your first category.</p>
        <a href="{{ route('admin.categories.create') }}" class="btn-primary">
            + Create First Category
        </a>
    </div>
    @endif
</div>

@endsection
