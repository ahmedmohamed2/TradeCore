<?php

namespace App\Http\Controllers;

use App\Enums\Permission;
use App\Http\Requests\StoreTreasuryRequest;
use App\Http\Requests\UpdateTreasuryRequest;
use App\Models\Treasury;
use App\Support\RealtimeSearch;
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
                ->search($search)
                ->latest()
                ->paginate(15)
                ->withQueryString(),
            'search' => $search,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('treasuries.create');
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
        return view('treasuries.edit', [
            'treasury' => $treasury->load(['createdBy', 'updatedBy']),
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
        $treasury->delete();

        return to_route('treasuries.index')->with('status', __('treasuries.deleted'));
    }
}
