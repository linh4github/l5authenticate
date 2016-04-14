<?php

namespace Modules\Authenticate\Repositories\Contracts;

interface ReminderRepositoryInterface {

    /**
     * Create
     * @param $user_id
     * @return mixed
     */
    public function create($user_id);

    /**
     * Complete activate code
     */
    public function complete($user_id, $code);

    /**
     * @param $user_id
     * @param $code
     * @return integer
     */
    public function checkLinkStatus($user_id, $code);
}