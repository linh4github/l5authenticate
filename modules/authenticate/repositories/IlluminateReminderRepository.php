<?php

namespace Modules\Authenticate\Repositories;

use Carbon\Carbon;
use Modules\Authenticate\Models\Reminder;
use Modules\Authenticate\Repositories\Contracts\ReminderRepositoryInterface;

class IlluminateReminderRepository implements ReminderRepositoryInterface
{
    use ReuseTrait;

    protected $expires;
    const LINK_NOT_VALID = 0;
    const LINK_COMPLETED = 1;
    const LINK_EXPIRED   = 2;
    const LINK_READY     = 3;

    /**
     * IlluminateActivationRepository constructor.
     * @param int $expires
     */
    public function __construct($expires)
    {
        $this->expires = $expires;
    }


    /**
     * Creates a code
     */
    public function create($user_id)
    {
        $reminder = new Reminder();

        $reminder->user_id = $user_id;
        $reminder->code = $this->generateCode();

        if ($reminder->save()) return $reminder;

        return false;
    }

    /**
     * Complete activate code
     */
    public function complete($user_id, $code)
    {
        $reminder = Reminder::where('user_id', $user_id)
                            ->where('code', $code)
                            ->first();
        $reminder->completed = true;
        $reminder->completed_at = Carbon::now();

        $reminder->save();

        return $reminder ?: false;
    }

    /**
     * Returns the expiration date.
     *
     * @return \Carbon\Carbon
     */
    public function expires()
    {
        return Carbon::now()->subSeconds($this->expires);
    }

    public function isCompleted($user_id)
    {
        $reminder = Reminder::where('user_id', $user_id)
                            ->where('completed', true)
                            ->first();

        return $reminder ?: false;
    }

    public function checkLinkStatus($user_id, $code){
        $expires = $this->expires();

        $reminder = Reminder::where('user_id', $user_id)
                            ->where('code', $code)
                            ->first();

        if (is_null($reminder)) return self::LINK_NOT_VALID;

        if ($reminder->completed === true) return self::LINK_COMPLETED;

        $created_at = $reminder->created_at;
        if ($created_at->lt($expires)) return self::LINK_EXPIRED;

        return self::LINK_READY;
    }
}
