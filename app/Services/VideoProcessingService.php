<?php

namespace App\Services;

use App\Models\Video;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use RuntimeException;
use Symfony\Component\Process\Process;

class VideoProcessingService
{
    public function process(Video $video): void
    {
        $disk = Storage::disk(config('video.disk'));

        if (!$video->video_path) {
            throw new RuntimeException(
                'Video source path is missing.'
            );
        }

        if (!$disk->exists($video->video_path)) {
            throw new RuntimeException(
                'Video source file does not exist: ' .
                $video->video_path
            );
        }

        $video->update([
            'status' => 'processing',
        ]);

        $sourcePath = $disk->path(
            $video->video_path
        );

        $processedDirectory = config(
            'video.processed_directory'
        );

        $thumbnailDirectory = config(
            'video.thumbnail_directory'
        );

        $processedPath =
            $processedDirectory .
            '/' .
            $video->id .
            '.mp4';

        $thumbnailPath =
            $thumbnailDirectory .
            '/' .
            $video->id .
            '.jpg';

        $disk->makeDirectory(
            $processedDirectory
        );

        $disk->makeDirectory(
            $thumbnailDirectory
        );

        $processedAbsolutePath = $disk->path(
            $processedPath
        );

        $thumbnailAbsolutePath = $disk->path(
            $thumbnailPath
        );

        $duration = $this->getDuration(
            $sourcePath
        );

        $this->createProcessedVideo(
            $sourcePath,
            $processedAbsolutePath
        );

        $this->createThumbnail(
            $sourcePath,
            $thumbnailAbsolutePath
        );

        if (!$disk->exists($processedPath)) {
            throw new RuntimeException(
                'Processed video was not created.'
            );
        }

        if (!$disk->exists($thumbnailPath)) {
            throw new RuntimeException(
                'Video thumbnail was not created.'
            );
        }

        $video->update([
            'video_path' => $processedPath,
            'thumbnail_path' => $thumbnailPath,
            'duration' => $duration,
            'status' => 'published',
            'published_at' => $video->published_at ?? now(),
        ]);

        Log::info(
            'STEP 24: Video processing completed',
            [
                'video_id' => $video->id,
                'duration' => $duration,
                'video_path' => $processedPath,
                'thumbnail_path' => $thumbnailPath,
            ]
        );
    }

    private function getDuration(
        string $sourcePath
    ): int {
        $process = new Process([
            config('video.ffprobe'),
            '-v',
            'error',
            '-show_entries',
            'format=duration',
            '-of',
            'default=noprint_wrappers=1:nokey=1',
            $sourcePath,
        ]);

        $process->setTimeout(120);

        $process->run();

        if (!$process->isSuccessful()) {
            throw new RuntimeException(
                'FFprobe failed: ' .
                trim($process->getErrorOutput())
            );
        }

        $duration = (float) trim(
            $process->getOutput()
        );

        if ($duration <= 0) {
            throw new RuntimeException(
                'Invalid video duration returned by FFprobe.'
            );
        }

        return (int) round($duration);
    }

    private function createProcessedVideo(
        string $sourcePath,
        string $outputPath
    ): void {
        $process = new Process([
            config('video.ffmpeg'),
            '-y',
            '-i',
            $sourcePath,
            '-vf',
            'scale=' .
                config('video.video_width') .
                ':' .
                config('video.video_height') .
                ':force_original_aspect_ratio=decrease,' .
                'pad=' .
                config('video.video_width') .
                ':' .
                config('video.video_height') .
                ':(ow-iw)/2:(oh-ih)/2',
            '-c:v',
            'libx264',
            '-preset',
            'medium',
            '-crf',
            '23',
            '-c:a',
            'aac',
            '-b:a',
            '128k',
            '-movflags',
            '+faststart',
            $outputPath,
        ]);

        $process->setTimeout(3600);

        $process->run();

        if (!$process->isSuccessful()) {
            throw new RuntimeException(
                'FFmpeg video processing failed: ' .
                trim($process->getErrorOutput())
            );
        }
    }

    private function createThumbnail(
        string $sourcePath,
        string $thumbnailPath
    ): void {
        $process = new Process([
            config('video.ffmpeg'),
            '-y',
            '-ss',
            (string) config(
                'video.thumbnail_time'
            ),
            '-i',
            $sourcePath,
            '-frames:v',
            '1',
            '-q:v',
            '2',
            $thumbnailPath,
        ]);

        $process->setTimeout(120);

        $process->run();

        if (!$process->isSuccessful()) {
            throw new RuntimeException(
                'FFmpeg thumbnail generation failed: ' .
                trim($process->getErrorOutput())
            );
        }
    }
}