<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\ServiceCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ServiceCategoryController extends Controller
{
    /**
     * Admin: list all service categories with booking counts.
     */
    public function index()
    {
        $categories = ServiceCategory::withCount('bookings')
            ->orderBy('name')
            ->get();

        return view('admin.categories', compact('categories'));
    }

    /**
     * Store a new service category (AJAX).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:service_categories,name',
            'icon' => 'nullable|string|max:100',
            'description' => 'nullable|string|max:1000',
            'estimated_price' => 'nullable|numeric|min:0',
        ]);

        $category = ServiceCategory::create([
            'name' => $validated['name'],
            'icon' => $validated['icon'] ?: 'fa-tools',
            'description' => $validated['description'] ?? null,
            'estimated_price' => $validated['estimated_price'] ?? null,
        ]);

        ActivityLog::record(Auth::id(), 'Category Created', "Created service category: {$category->name}");

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Service category created successfully!',
                'category' => $category,
            ]);
        }

        return redirect()->route('admin.categories')->with('success', 'Service category created successfully!');
    }

    /**
     * Update an existing service category (AJAX).
     */
    public function update(Request $request, $id)
    {
        $category = ServiceCategory::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:service_categories,name,' . $category->id,
            'icon' => 'nullable|string|max:100',
            'description' => 'nullable|string|max:1000',
            'estimated_price' => 'nullable|numeric|min:0',
        ]);

        $category->update([
            'name' => $validated['name'],
            'icon' => $validated['icon'] ?: 'fa-tools',
            'description' => $validated['description'] ?? null,
            'estimated_price' => $validated['estimated_price'] ?? null,
        ]);

        ActivityLog::record(Auth::id(), 'Category Updated', "Updated service category: {$category->name}");

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Service category updated successfully!',
                'category' => $category,
            ]);
        }

        return redirect()->route('admin.categories')->with('success', 'Service category updated successfully!');
    }

    /**
     * Delete a service category (AJAX) — blocked if bookings reference it.
     */
    public function destroy(Request $request, $id)
    {
        $category = ServiceCategory::findOrFail($id);

        // Guard: the bookings FK cascades on delete, so removing a category with
        // existing bookings would silently wipe real booking history. Refuse.
        if ($category->bookings()->exists()) {
            $message = 'Cannot delete "' . $category->name . '" because it has existing bookings.';
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => $message], 409);
            }
            return back()->with('error', $message);
        }

        $name = $category->name;
        $category->delete();

        ActivityLog::record(Auth::id(), 'Category Deleted', "Deleted service category: {$name}");

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Service category deleted successfully!',
            ]);
        }

        return redirect()->route('admin.categories')->with('success', 'Service category deleted successfully!');
    }
}
