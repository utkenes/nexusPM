<?php

namespace App\Http\Controllers;

use App\Actions\Organization\CreateOrganizationAction;
use App\Http\Requests\StoreOrganizationRequest;
use App\Models\Organization;
use App\Services\Organization\OrganizationMembershipService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrganizationController extends Controller
{
    /**
     * Display a listing of the user's organizations.
     */
    public function index(Request $request): View
    {
        $organizations = $request->user()->organizations()->withPivot('role')->get();

        return view('organizations.index', compact('organizations'));
    }

    /**
     * Show the form for creating a new organization.
     */
    public function create(): View
    {
        return view('organizations.create');
    }

    /**
     * Store a newly created organization in storage.
     */
    public function store(StoreOrganizationRequest $request, CreateOrganizationAction $action): RedirectResponse
    {
        $organization = $action->execute(
            $request->user(),
            $request->validated()
        );

        return redirect()->route('dashboard')
            ->with('success', "Organization {$organization->name} created successfully!");
    }

    /**
     * Switch user's active organization context.
     */
    public function switch(Request $request, Organization $organization, OrganizationMembershipService $service): RedirectResponse
    {
        try {
            $service->switchActiveOrganization($request->user(), $organization);

            return redirect()->route('dashboard')
                ->with('success', "Switched to {$organization->name} active workspace.");
        } catch (\InvalidArgumentException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
