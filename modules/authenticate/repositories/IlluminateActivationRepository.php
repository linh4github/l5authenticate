<?php

namespace Modules\Authenticate\Repositories;

use Carbon\Carbon;
use Modules\Authenticate\Models\Activation;
use Modules\Authenticate\Repositories\Contracts\ActivationRepositoryInterface;

class IlluminateActivationRepository implements ActivationRepositoryInterface
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
     * @param $user_id
     * @return bool|Activation
     */
    public function create(array $data)
    {
        $activation = new Activation();

        $activation->user_id = $data['user_id'];
        $activation->code = $data['code'];

        if ($activation->save()) return $activation;

        return false;
    }

    /**
     * Complete activate code
     * @param $user_id
     * @param $code
     * @return bool
     */
    public function complete($user_id, $code)
    {
        $activation = Activation::where('user_id', $user_id)
                                ->where('code', $code)
                                ->first();
        $activation->completed = true;
        $activation->completed_at = Carbon::now();

        $activation->save();

        return $activation ?: false;
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

    /**
     * @param $user_id
     * @return bool
     */
    public function isActivated($user_id)
    {
        $activation = Activation::where('user_id', $user_id)
            ->where('completed', true)
            ->first();

        return $activation ?: false;
    }

    /**
     * @param $user_id
     * @param $code
     * @return int
     */
    public function checkLinkStatus($user_id, $code){
        $expires = $this->expires();

        $activation = Activation::where('user_id', $user_id)
            ->where('code', $code)->get()->first();

        if (is_null($activation)) return [false, 'link not valid'];

        //if ($activation->completed === true) return self::LINK_COMPLETED;

        $created_at = $activation->created_at;
        if ($created_at->lt($expires)) return [false, 'link is expired'];

        return [true, $activation];
    }
}
