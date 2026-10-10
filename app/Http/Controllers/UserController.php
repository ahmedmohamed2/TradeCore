<?php

namespace App\Http\Controllers;

use App\Enums\Permission;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\Role;
use App\Models\User;
use App\Support\RealtimeSearch;
use App\Support\RoleName;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\UploadedFile;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class UserController extends Controller implements HasMiddleware
{
    /**
     * @return array<int, Middleware>
     */
    public static function middleware(): array
    {
        return [
            new Middleware('permission:'.Permission::ViewUsers->value, only: ['index']),
            new Middleware('permission:'.Permission::CreateUsers->value, only: ['create', 'store']),
            new Middleware('permission:'.Permission::UpdateUsers->value, only: ['edit', 'update']),
            new Middleware('permission:'.Permission::DeleteUsers->value, only: ['destroy']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View|Response
    {
        $search = mb_substr($request->string('search')->trim()->toString(), 0, 100);

        return RealtimeSearch::view($request, 'users.index', [
            'users' => User::query()
                ->with('roles')
                ->search($search)
                ->latest()
                ->paginate(15)
                ->withQueryString(),
            'search' => $search,
            'superAdminCount' => User::role(RoleName::SuperAdmin)->count(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request): View
    {
        return view('users.create', [
            'roles' => $this->assignableRoles($request->user()),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request): RedirectResponse
    {
        $user = User::query()->create([
            'name' => $request->validated('name'),
            'email' => $request->validated('email'),
            'password' => $request->validated('password'),
            'locale' => $request->validated('locale'),
            'profile_photo_path' => $this->storeProfilePhoto($request) ?? User::DefaultProfilePhoto,
        ]);

        $user->syncRoles($request->validated('roles') ?? []);

        return to_route('users.index')->with('status', __('users.created'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, User $user): View
    {
        $this->ensureCanManage($request->user(), $user);

        return view('users.edit', [
            'user' => $user->load('roles'),
            'roles' => $this->assignableRoles($request->user()),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        $attributes = $request->safe()->only(['name', 'email', 'locale']);

        if ($request->filled('password')) {
            $attributes['password'] = $request->validated('password');
        }

        $photo = $this->storeProfilePhoto($request, $user->profile_photo_path);

        if ($photo !== null) {
            $attributes['profile_photo_path'] = $photo;
        } elseif (blank($user->profile_photo_path)) {
            $attributes['profile_photo_path'] = User::DefaultProfilePhoto;
        }

        $user->update($attributes);
        $user->syncRoles($request->validated('roles') ?? []);

        return to_route('users.index')->with('status', __('users.updated'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, User $user): RedirectResponse
    {
        $actor = $request->user();

        if (! $actor instanceof User) {
            abort(403);
        }

        if ($user->hasRole(RoleName::SuperAdmin) && ! $actor->hasRole(RoleName::SuperAdmin)) {
            abort(403);
        }

        if ($user->hasRole(RoleName::SuperAdmin) && User::role(RoleName::SuperAdmin)->count() <= 1) {
            return back()->with('error', __('users.cannot_delete_last_super_admin'));
        }

        if ($actor->is($user)) {
            return back()->with('error', __('users.cannot_delete_self'));
        }

        $this->deleteProfilePhoto($user->profile_photo_path);

        $user->delete();

        return to_route('users.index')->with('status', __('users.deleted'));
    }

    private function storeProfilePhoto(Request $request, ?string $current = null): ?string
    {
        $photo = $request->file('profile_photo');

        if (! $photo instanceof UploadedFile) {
            return null;
        }

        File::ensureDirectoryExists(public_path('uploads/profile_images'));

        $filename = $photo->hashName();
        Storage::disk('profile_images')->putFileAs('', $photo, $filename);
        $this->deleteProfilePhoto($current);

        return $filename;
    }

    private function deleteProfilePhoto(?string $filename): void
    {
        if (blank($filename) || $filename === User::DefaultProfilePhoto) {
            return;
        }

        Storage::disk('profile_images')->delete($filename);
    }

    /**
     * @return Collection<int, Role>
     */
    private function assignableRoles(User $actor): Collection
    {
        return Role::query()
            ->where('guard_name', RoleName::Guard)
            ->orderBy('name')
            ->get()
            ->when(
                ! $actor->hasRole(RoleName::SuperAdmin),
                fn (Collection $roles): Collection => $roles
                    ->reject(fn (Role $role): bool => $role->isSuperAdmin())
                    ->values(),
            );
    }

    private function ensureCanManage(User $actor, User $subject): void
    {
        if ($subject->hasRole(RoleName::SuperAdmin) && ! $actor->hasRole(RoleName::SuperAdmin)) {
            abort(403);
        }
    }
}
