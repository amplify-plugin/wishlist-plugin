<?php

namespace Amplify\Wishlist\Jobs;

use Amplify\System\Backend\Models\Contact;
use Amplify\System\Backend\Models\Customer;
use Amplify\System\Backend\Models\CustomerOrder;
use Amplify\System\Backend\Models\Product;
use Amplify\System\Backend\Traits\NotificationEventTrait;
use Amplify\Wishlist\Models\Wishlist;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use function Adminer\where;

class WishlistProductRestockedJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, NotificationEventTrait, Queueable, SerializesModels;

    public array $stockEntry;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($event_code, $args)
    {
        $this->eventCode = $event_code;
        $this->stockEntry = $args;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $product = Product::whereProductCode($this->stockEntry['product_code'])->first();

        $this->getNecessaryItems();

        Wishlist::whereProductId($product->id)
            ->whereNotify(true)
            ->where(function ($query) {
                return $query->whereNull('last_notified_at')
                    ->orWhere('last_notified_at', '<=', now()->subDays('wishlist.notify_interval'));
            })
            ->get()
            ->each(function (Wishlist $wishlist) {
                foreach ($this->eventInfo->eventActions as $eventAction) {
                    if ($eventAction->eventTemplate->notification_type == 'emailable') {
                        $this->emailService->sendWishlistProductRestockedEmail($eventAction, $wishlist);
                    }
                }
            });
    }
}
