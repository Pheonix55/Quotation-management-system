<?php

namespace App\Livewire\Customer;

use App\QuotationStatus;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\QuoteRequest;

class QuoteRequestsTable extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = '';
    public $sortField = 'created_at';
    public $sortDirection = 'desc';
    public $perPage = 10;

    protected $paginationTheme = 'bootstrap';

    protected $queryString = [
        'search' => ['except' => ''],
        'statusFilter' => ['except' => ''],
        'sortField' => ['except' => 'created_at'],
        'sortDirection' => ['except' => 'desc'],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function render()
    {
        $query = QuoteRequest::query()
            ->with('products')
            ->where('user_id', auth()->id());

        // 🔍 Search filter
        if (!empty($this->search)) {
            $query->where(function ($q) {
                // If your "status" column is an enum (Laravel 9+), cast it to string first
                $q->whereRaw("CAST(status AS CHAR) LIKE ?", ['%' . $this->search . '%'])
                    ->orWhereDate('created_at', 'like', '%' . $this->search . '%');
            });
        }

        if (!empty($this->statusFilter)) {
            $query->where('status', $this->statusFilter);
        }

        // ↕ Sorting
        $query->orderBy($this->sortField, $this->sortDirection);

        // 📄 Pagination
        $quoteRequests = $query->paginate($this->perPage);

        return view('livewire.customer.quote-requests-table', [
            'quoteRequests' => $quoteRequests,
        ]);
    }
}
