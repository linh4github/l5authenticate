<?php

namespace Modules\Authenticate\Repositories\Contracts;

interface ActivationRepositoryInterface
{
    /**
     * Creates a code
     */
    public function create(array $data);

    /**
     * Complete activate code
     */
    public function complete($user_id, $code);

    /**
     * @param $user_id
     * @return bool
     */
    public function isActivated($user_id);

    /**
     * @param $user_id
     * @param $code
     * @return integer
     */
    public function checkLinkStatus($user_id, $code);
}
