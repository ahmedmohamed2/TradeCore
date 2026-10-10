<?php

namespace App\Http\Controllers;

use App\Enums\Permission;
use App\Http\Requests\StoreTreasuryRequest;
use App\Http\Requests\UpdateTreasuryRequest;
use App\Models\Treasury;
use App\Support\RealtimeSearch;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\View\View;

class TreasuryController extends Controller implements HasMiddleware
{
    /**
     * @return array<int, Middleware>
     */
    public static function middleware(): array
    {
        return [
            new Middleware('permission:'.Permission::ViewTreasuries->value, only: ['index']),
            new Middleware('permission:'.Permission::CreateTreasuries->value, only: ['create', 'store']),
            new Middleware('permission:'.Permission::UpdateTreasuries->value, only: ['edit', 'update']),
            new Middleware('permission:'.Permission::DeleteTreasuries->value, only: ['destroy']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View|Response
    {
        $search = mb_substr($request->string('search')->trim()->toString(), 0, 100);

        return RealtimeSearch::view($request, 'treasuries.index', [
            'treasuries' => Treasury::query()
                ->main()
                ->with([
                    'createdBy:id,name',
                    'updatedBy:id,name',
                    'children',
                ])
                ->when($search !== '', function (Builder $query) use ($search): void {
                    $query->where(function (Builder $query) use ($search): void {
                        $query->search($search)
                            ->orWhereHas('children', function (Builder $children) use ($search): void {
                                $children->search($search);
                            });
                    });
                })
                ->latest()
                ->paginate(15)
                ->withQueryString(),
            'search' => $search,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request): View
    {
        $mainTreasuries = $this->mainTreasuries();
        $requestedParent = $request->string('parent')->trim()->toString();
        $selectedParent = ctype_digit($requestedParent)
            ? $mainTreasuries->firstWhere('id', (int) $requestedParent)
            : null;

        return view('treasuries.create', [
            'mainTreasuries' => $mainTreasuries,
            'selectedParentId' => $selectedParent?->id,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTreasuryRequest $request): RedirectResponse
    {
        Treasury::query()->create([
            ...$request->validated(),
            'created_by' => $request->user()->id,
            'updated_by' => $request->user()->id,
        ]);

        return to_route('treasuries.index')->with('status', __('treasuries.created'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Treasury $treasury): View
    {
        $treasury->load(['createdBy', 'updatedBy'])->loadCount('children');

        return view('treasuries.edit', [
            'treasury' => $treasury,
            'mainTreasuries' => $treasury->children_count > 0 ? new Collection : $this->mainTreasuries($treasury),
            'selectedParentId' => $treasury->parent_id,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTreasuryRequest $request, Treasury $treasury): RedirectResponse
    {
        $treasury->update([
            ...$request->validated(),
            'updated_by' => $request->user()->id,
        ]);

        return to_route('treasuries.index')->with('status', __('treasuries.updated'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Treasury $treasury): RedirectResponse
    {
        if ($treasury->children()->exists()) {
            return to_route('treasuries.index')->with('error', __('treasuries.delete_blocked'));
        }

        $treasury->delete();

        return to_route('treasuries.index')->with('status', __('treasuries.deleted'));
    }

    /**
     * @return Collection<int, Treasury>
     */
    private function mainTreasuries(?Treasury $except = null): Collection
    {
        return Treasury::query()
            ->main()
            ->when(
                $except instanceof Treasury,
                fn (Builder $query): Builder => $query->whereKeyNot($except->getKey()),
            )
            ->orderBy('name')
            ->get(['id', 'code', 'name']);
    }
}
