<?php

namespace App\Services;

use App\Models\Note;
use Illuminate\Support\Facades\Log;

class NoteService
{
    public function storeNote(array $data): ?Note
    {
        try {
            $note = Note::create($data);
        } catch (\Exception $e) {
            Log::error('Failed to create note', ['error' => $e->getMessage()]);
            return null;
        }

        return $note;
    }

    public function getAllNotes()
    {
        return Note::all();
    }
}