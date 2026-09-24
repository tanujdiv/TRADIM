<?php

namespace App\Services;

use App\Models\Channel;
use App\Models\Comment;
use App\Models\Report;
use App\Models\User;
use App\Models\Video;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ReportingService
{
    public function create(
        User $user,
        array $data
    ): Report {
        $target = $this->findTarget(
            $data['target_type'],
            (int) $data['target_id']
        );

        if (!$target) {
            throw ValidationException::withMessages([
                'target_id' => 'Reported content was not found.',
            ]);
        }

        $ownerId = $this->ownerId(
            $data['target_type'],
            $target
        );

        if ($ownerId === $user->id) {
            throw ValidationException::withMessages([
                'target_id' => 'You cannot report your own content.',
            ]);
        }

        return DB::transaction(function () use ($user, $data, $target) {
            $existing = Report::query()
                ->where('user_id', $user->id)
                ->where('target_type', $data['target_type'])
                ->where('target_id', $data['target_id'])
                ->whereIn('status', [
                    'pending',
                    'reviewing',
                ])
                ->lockForUpdate()
                ->first();

            if ($existing) {
                throw ValidationException::withMessages([
                    'target_id' => 'You already have an open report for this content.',
                ]);
            }

            return Report::create([
                'user_id' => $user->id,
                'target_type' => $data['target_type'],
                'target_id' => $data['target_id'],
                'target_title' => $this->title(
                    $data['target_type'],
                    $target
                ),
                'reason' => $data['reason'],
                'description' => $data['description'] ?? null,
                'status' => 'pending',
                'action_taken' => 'none',
            ]);
        });
    }

    public function findTarget(
        string $type,
        int $id
    ): Video|Channel|Comment|null {
        return match ($type) {
            'video' => Video::query()
                ->where('status', 'published')
                ->where('visibility', 'public')
                ->find($id),

            'channel' => Channel::find($id),

            'comment' => Comment::query()
                ->whereHas('video', function ($query) {
                        $query->where('status', 'published')
                        ->where('visibility', 'public');
                    })
                ->find($id),

            default => null,
        };
    }

    private function ownerId(
        string $type,
        Video|Channel|Comment $target
    ): int {
        return match ($type) {
            'video', 'channel', 'comment' =>
                (int) $target->user_id,
        };
    }

    private function title(
        string $type,
        Video|Channel|Comment $target
    ): string {
        return match ($type) {
            'video' => $target->title,
            'channel' => $target->name,
            'comment' => mb_substr(
                $target->comment ?: $target->body,
                0,
                255
            ),
        };
    }
}