<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreNoteRequest;
use App\Models\Note;
use App\Facades\Tenants as FacadesTenants;
use App\Services\NoteService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NoteController extends Controller
{
    private NoteService $noteService;
    public function __construct(NoteService $noteService)
    {
        $this->noteService = $noteService;
    }

    public function index(Request $request)
    {
        $notes = $this->noteService->getAllNotes();

        return $this->successResponse(
            ['notes' => $notes],
            'Get notes successfully',
        );
    }

    public function store(StoreNoteRequest $request)
    {
        $data = $request->validated();

        DB::beginTransaction();
        try {
            $note = Note::create($data);

            DB::commit();
            return $this->successResponse(
                ['note' => $note],
                'Create note successfully',
            );
        } catch (\Exception $e) {
            DB::rollback();
            return $this->errorResponse('Failed to create note', 500);
        }
    }
}
