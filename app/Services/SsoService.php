<?php

namespace App\Services;

use App\Models\SsoLog;
use App\Models\User;
use Illuminate\Http\Request;

/**
 * Class SsoService
 *
 * @package App\Services
 *
 * Sso::checkUser($user);
 * Sso::createPhone($phone);
 * Sso::exist($phone);
 * Sso::destroy($phone);
 */
class SsoService
{
    /**
     * App\Http\Middleware\SingleSignOn
     *
     * @param  User  $user
     *
     * @return bool
     */
    public function checkUser(User $user): bool
    {
        if (!$user->mobile) {
            return true;
        }

        $log = SsoLog::where('phone', $user->phone)->first();

        if (!$log) {
            $data = [
                'phone' => $user->phone,
                'uuid'  => request()->header('uuid'),
            ];

            SsoLog::create($data);

            return true;
        }

        if ($log->uuid == request()->header('uuid')) {
            return true;
        }

        return false;
    }

    /**
     * @param $phone
     *
     * @return bool
     */
    public function checkPhone($phone): bool
    {
        $log = SsoLog::where('phone', $phone)->first();

        if (!$log) {
            return true;
        }

        if ($log->uuid == request()->header('uuid')) {
            return true;
        }

        return false;
    }

    public function exist($phone)
    {
        return SsoLog::where('phone', $phone)->exist();
    }

    public function destroy($phone)
    {
        SsoLog::where('phone', $phone)->delete();
    }
}
