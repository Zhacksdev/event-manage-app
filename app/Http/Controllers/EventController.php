<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $query = Event::query();

        if ($request->type) {
            $query->where('type', $request->type);
        }

        return response()->json([
            'message' => 'Data event berhasil diambil',
            'data' => $query->get()
        ], 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'type' => 'required|in:seminar,lomba,workshop',
            'description' => 'required',
            'organizer_user_id' => 'required|integer',
            'quota' => 'required|integer',
            'start_date' => 'required',
            'end_date' => 'required',
            'location' => 'required',
            'status' => 'required|in:open,closed,cancelled'
        ]);

        $user = Http::get(
            env('USER_SERVICE_URL') . '/users/' . $request->organizer_user_id
        );

        if ($user->failed()) {
            return response()->json([
                'message' => 'Organizer tidak ditemukan'
            ], 404);
        }

        $event = Event::create($request->all());

        return response()->json([
            'message' => 'Event berhasil dibuat',
            'data' => $event
        ], 201);
    }

    public function show($id)
    {
        $event = Event::find($id);

        if (!$event) {
            return response()->json([
                'message' => 'Event tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'message' => 'Data event ditemukan',
            'data' => $event
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $event = Event::find($id);

        if (!$event) {
            return response()->json([
                'message' => 'Event tidak ditemukan'
            ], 404);
        }

        $event->update($request->all());

        return response()->json([
            'message' => 'Event berhasil diupdate',
            'data' => $event
        ], 200);
    }

    public function destroy($id)
    {
        $event = Event::find($id);

        if (!$event) {
            return response()->json([
                'message' => 'Event tidak ditemukan'
            ], 404);
        }

        $event->delete();

        return response()->json([
            'message' => 'Event berhasil dihapus'
        ], 200);
    }

    public function updateQuota(Request $request, $id)
    {
        $event = Event::find($id);

        if (!$event) {
            return response()->json([
                'message' => 'Event tidak ditemukan'
            ], 404);
        }

        $event->quota = $request->quota;
        $event->save();

        return response()->json([
            'message' => 'Quota berhasil diupdate',
            'data' => $event
        ], 200);
    }
}
