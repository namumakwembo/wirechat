<?php

namespace Namu\WireChat\Jobs;

use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\Attributes\WithoutRelations;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Namu\WireChat\Events\NotifyParticipant;
use Namu\WireChat\Facades\WireChat;
use Namu\WireChat\Models\Message;
use Namu\WireChat\Models\Participant;

class NotifyParticipants implements ShouldQueue
{
    use Batchable,Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Set a maximum time limit of 60 seconds for the job.
     * Because we don't want users getting old notifications
     */
    public int $timeout = 60;

    public int $retry_after = 65;

    public int $tries = 1;

    protected $auth;

    protected $messagesTable;

    protected $participantsTable;

    public function __construct(

        public Model $conversation,
        #[WithoutRelations]
        public Message $message)
    {
        //
        $this->onQueue(WireChat::notificationsQueue());
        //  $this->delay(now()->addSeconds(3)); // Delay
        $this->auth = $message->sendable;

        //Get table
        $this->participantsTable = (new Participant)->getTable();

        //dd($this);

    }

    /**
     * Get the middleware the job should pass through.
     */
    // public function middleware(): array
    // {

    //     return [
    //         new SkipIfOlderThanSeconds(60), // You can pass a custom max age in seconds
    //     ];
    // }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Check if the message is too old
        $messageAgeInSeconds = now()->diffInSeconds($this->message->created_at);

        //delete the job if it is greater then 60 seconds
        if ($messageAgeInSeconds > 60) {
            // Delete the job and stop further processing
            //$this->fail();
            $this->delete();

            return;
        }

        Log::info('Here');

        /**
         * Fetch participants, ordered by `last_active_at` in descending order,
         * so that the most recently active participants are notified first. */
        Participant::where('conversation_id', $this->conversation->id)
            ->where(function ($query) {
                return $query->where('participantable_id', '!=', $this->auth->id)
                    ->orWhere('participantable_type', '==', $this->auth->getMorphClass());
            })
            ->latest('last_active_at') // Prioritize active participants
            ->chunk(50, function ($participants) {
                Log::info(['participants count' => $participants->count()]);
                foreach ($participants as $key => $participant) {
                    Log::info(['participant' => ['participantable_id' => $participant->id, 'participantable_type' => $participant->participantable_type]]);

                    broadcast(new NotifyParticipant($participant, $this->message));
                }
            });

    }
}
