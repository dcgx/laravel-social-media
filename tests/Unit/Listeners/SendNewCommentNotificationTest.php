<?php

namespace Tests\Unit\Listeners;

use App\Models\Status;
use App\Models\Comment;
use App\Events\CommentCreated;
use App\Notifications\NewCommentNotification;

use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Support\Facades\Notification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SendNewCommentNotificationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_notification_is_sent_when_a_status_receive_a_new_comment()
    {
        Notification::fake();

        $status = Status::factory()->create();
        $comment = Comment::factory()->create(['status_id' => $status]);

        CommentCreated::dispatch($comment);

        Notification::assertSentTo(
            $status->user,
            NewCommentNotification::class,
            function($notification, $channels) use ($comment, $status) {
                $this->assertContains('database', $channels);
                $this->assertContains('broadcast', $channels);
                $this->assertTrue($notification->comment->is($comment));
                $this->assertInstanceOf(BroadcastMessage::class, $notification->toBroadcast($status->user));
                return true;
            }
        );
    }
}
