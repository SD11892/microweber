<?php

namespace MicroweberPackages\User\Listeners;

class RecordFailedLoginAttemptListener
{
    protected $success = 0;

    use LoginListenerTrait;

}
