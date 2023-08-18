<?php

namespace App\Console\Commands;

use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Console\Command;

class DeleteExpiredEvents extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     *
     */
    protected $signature = 'events:delete-expired';
    protected $description = 'Delete expired events';


    public function __construct()
    {
        parent::__construct();
    }


    /**
     * Execute the console command.
     */
    public function handle()
    {
        $expiredEvents = Event::where('end_date', '<=', Carbon::now())->get();

        foreach ($expiredEvents as $event) {
            $this->deleteEvent($event);
        }

        $this->info('Expired events deleted successfully.');
    }

    private function deleteEvent(Event $event)
    {
        // Delete the associated image file if it exists
        $imagePath = public_path("images/events/{$event->image}");
        if (file_exists($imagePath)) {
            unlink($imagePath);
        }

        // Delete the event from the database
        $event->delete();
    }
}

