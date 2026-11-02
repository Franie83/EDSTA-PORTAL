<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use Illuminate\Http\Request;

class AdminContactMessageController extends Controller
{
    public function index(Request $request)
    {
        $query = ContactMessage::query();

        if ($request->filled('q')) {
            $term = trim($request->input('q'));

            $query->where(function ($builder) use ($term) {
                $builder->where('name', 'like', '%' . $term . '%')
                    ->orWhere('email', 'like', '%' . $term . '%')
                    ->orWhere('subject', 'like', '%' . $term . '%')
                    ->orWhere('message', 'like', '%' . $term . '%');
            });
        }

        $allowedStatuses = ['new', 'read', 'replied', 'resolved'];

        if ($request->filled('status') && in_array($request->status, $allowedStatuses, true)) {
            $query->where('status', $request->status);
        }

        $messages = $query->orderByRaw(
            "CASE WHEN status = 'new' THEN 0 ELSE 1 END"
        )->orderByDesc('created_at')->paginate(15)->withQueryString();

        $counts = [
            'all' => ContactMessage::count(),
            'new' => ContactMessage::where('status', 'new')->count(),
            'read' => ContactMessage::where('status', 'read')->count(),
            'replied' => ContactMessage::where('status', 'replied')->count(),
            'resolved' => ContactMessage::where('status', 'resolved')->count(),
        ];

        return view('admin.contact-messages.index', compact('messages', 'counts'));
    }

    public function show(int $id)
    {
        $contactMessage = ContactMessage::findOrFail($id);

        if ($contactMessage->status === 'new') {
            $contactMessage->update(['status' => 'read']);
        }

        return view('admin.contact-messages.show', compact('contactMessage'));
    }

    public function updateStatus(Request $request, int $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:new,read,replied,resolved',
        ]);

        $contactMessage = ContactMessage::findOrFail($id);
        $contactMessage->update(['status' => $validated['status']]);

        return back()->with('success', 'Contact message status updated.');
    }

    public function destroy(int $id)
    {
        $contactMessage = ContactMessage::findOrFail($id);
        $contactMessage->delete();

        return redirect()
            ->route('admin.contact-messages.index')
            ->with('success', 'Contact message deleted.');
    }
}