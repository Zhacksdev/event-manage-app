<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class EventController extends Controller
{
    // =========================
    // Helper Response
    // =========================
    private function success($data = null, $message = 'Success', $code = 200)
    {
        return response()->json([
            'status'  => 'success',
            'code'    => $code,
            'message' => $message,
            'data'    => $data
        ], $code);
    }

    private function error($message = 'Error', $code = 500, $error = null)
    {
        return response()->json([
            'status'  => 'error',
            'code'    => $code,
            'message' => $message,
            'error'   => $error
        ], $code);
    }

    // =========================
    // GET /api/events
    // =========================
    public function index(Request $request)
    {
        try {
            $query = Event::query();

            if ($request->type) {
                $query->where('type', $request->type);
            }

            return $this->success(
                $query->get(),
                'Data event berhasil diambil'
            );

        } catch (\Exception $e) {
            return $this->error(
                'Gagal mengambil data event',
                500,
                $e->getMessage()
            );
        }
    }

    // =========================
    // POST /api/events
    // =========================
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'type' => 'required|in:seminar,lomba,workshop',
                'description' => 'required|string',
                'organizer_user_id' => 'required|integer',
                'quota' => 'required|integer|min:1',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date',
                'location' => 'required|string',
                'status' => 'required|in:open,closed,cancelled'
            ]);

            // 🔗 VALIDASI USER SERVICE
            try {
                $userResponse = Http::timeout(5)->get(
                    env('USER_SERVICE_URL', 'http://localhost:8000/api') 
                    . '/users/' . $validated['organizer_user_id']
                );

                if ($userResponse->failed()) {
                    return $this->error(
                        'Organizer tidak ditemukan di UserService',
                        404
                    );
                }

            } catch (\Illuminate\Http\Client\ConnectionException $e) {
                return $this->error(
                    'UserService tidak dapat dihubungi',
                    503,
                    $e->getMessage()
                );
            }

            // Default registered_count
            $validated['registered_count'] = 0;

            $event = Event::create($validated);

            return $this->success(
                $event,
                'Event berhasil dibuat',
                201
            );

        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->error(
                'Validation error',
                422,
                $e->errors()
            );
        } catch (\Exception $e) {
            return $this->error(
                'Gagal membuat event',
                500,
                $e->getMessage()
            );
        }
    }

    // =========================
    // GET /api/events/{id}
    // =========================
    public function show($id)
    {
        try {
            $event = Event::find($id);

            if (!$event) {
                return $this->error('Event tidak ditemukan', 404);
            }

            return $this->success(
                $event,
                'Data event ditemukan'
            );

        } catch (\Exception $e) {
            return $this->error(
                'Gagal mengambil detail event',
                500,
                $e->getMessage()
            );
        }
    }

    // =========================
    // PUT /api/events/{id}
    // =========================
    public function update(Request $request, $id)
    {
        try {
            $event = Event::find($id);

            if (!$event) {
                return $this->error('Event tidak ditemukan', 404);
            }

            $validated = $request->validate([
                'title' => 'sometimes|string|max:255',
                'type' => 'sometimes|in:seminar,lomba,workshop',
                'description' => 'sometimes|string',
                'quota' => 'sometimes|integer|min:1',
                'start_date' => 'sometimes|date',
                'end_date' => 'sometimes|date',
                'location' => 'sometimes|string',
                'status' => 'sometimes|in:open,closed,cancelled'
            ]);

            $event->update($validated);

            return $this->success(
                $event,
                'Event berhasil diupdate'
            );

        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->error('Validation error', 422, $e->errors());
        } catch (\Exception $e) {
            return $this->error('Gagal update event', 500, $e->getMessage());
        }
    }

    // =========================
    // DELETE /api/events/{id}
    // =========================
    public function destroy($id)
    {
        try {
            $event = Event::find($id);

            if (!$event) {
                return $this->error('Event tidak ditemukan', 404);
            }

            $event->delete();

            return $this->success(
                null,
                'Event berhasil dihapus'
            );

        } catch (\Exception $e) {
            return $this->error('Gagal menghapus event', 500, $e->getMessage());
        }
    }

    // =========================
    // PUT /api/events/{id}/quota
    // =========================
    public function updateQuota(Request $request, $id)
    {
        try {
            $event = Event::find($id);

            if (!$event) {
                return $this->error('Event tidak ditemukan', 404);
            }

            // ❌ Status bukan open
            if ($event->status !== 'open') {
                return $this->error(
                    'Event sudah ditutup atau dibatalkan',
                    400
                );
            }

            // ❌ Kuota penuh
            if ($event->registered_count >= $event->quota) {
                return $this->error(
                    'Kuota event sudah penuh',
                    400
                );
            }

            // ✅ Increment
            $event->registered_count += 1;

            // 🔒 Auto close
            if ($event->registered_count >= $event->quota) {
                $event->status = 'closed';
            }

            $event->save();

            return $this->success(
                $event,
                'Quota berhasil diupdate'
            );

        } catch (\Exception $e) {
            return $this->error(
                'Gagal update quota',
                500,
                $e->getMessage()
            );
        }
    }
}