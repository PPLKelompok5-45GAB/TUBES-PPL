<?php

namespace App\Http\Controllers;

use App\Http\Requests\AnnouncementRequest;
use App\Models\Pengumuman;
use Illuminate\Contracts\View\View;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\RedirectResponse;

/**
 * Class AnnouncementController
 *
 * @package App\Http\Controllers
 */
class AnnouncementController extends Controller
{
    /**
     * Display a listing of the announcements.
     *
     * @return View|Factory
     */
    public function index(): View|Factory
    {
        $announcements = Pengumuman::query()->orderByDesc('created_at')->paginate(10);

        return view('vendor.argon.announcements.index', compact('announcements'));
    }

    /**
     * Show the form for creating a new announcement.
     *
     * @return View|Factory
     */
    public function create(): View|Factory
    {
        return view('vendor.argon.announcements.create');
    }

    /**
     * Store a newly created announcement in storage.
     *
     * @param  AnnouncementRequest  $request
     * @return RedirectResponse
     */
    public function store(AnnouncementRequest $request): RedirectResponse
    {
        /** @var array<string, mixed> $validated */
        $validated = (array) $request->validated();
        $validated['admin_id'] = Auth::id() ?? 1;
        $announcement = new Pengumuman();
        $announcement->fill($validated);
        $announcement->save();

        return Redirect::route('announcements.index')->with('status', 'Announcement created.');
    }

    /**
     * Display the specified announcement.
     *
     * @param  Pengumuman  $announcement
     * @return View|Factory
     */
    public function show(Pengumuman $announcement): View|Factory
    {
        return view('vendor.argon.announcements.show', compact('announcement'));
    }

    /**
     * Show the form for editing the specified announcement.
     *
     * @param  Pengumuman  $announcement
     * @return View|Factory
     */
    public function edit(Pengumuman $announcement): View|Factory
    {
        return view('vendor.argon.announcements.edit', compact('announcement'));
    }

    /**
     * Update the specified announcement in storage.
     *
     * @param  AnnouncementRequest  $request
     * @param  Pengumuman  $announcement
     * @return RedirectResponse
     */
    public function update(AnnouncementRequest $request, Pengumuman $announcement): RedirectResponse
    {
        /** @var array<string, mixed> $validated */
        $validated = (array) $request->validated();
        $validated['admin_id'] = Auth::id() ?? $announcement->admin_id;
        $announcement->fill($validated);
        $announcement->save();

        return Redirect::route('announcements.index')->with('status', 'Announcement updated.');
    }

    /**
     * Remove the specified announcement from storage.
     *
     * @param  Pengumuman  $announcement
     * @return RedirectResponse
     */
    public function destroy(Pengumuman $announcement): RedirectResponse
    {
        $announcement->delete();

        return Redirect::route('announcements.index')->with('status', 'Announcement deleted.');
    }
}
