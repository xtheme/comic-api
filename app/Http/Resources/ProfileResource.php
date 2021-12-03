<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProfileResource extends JsonResource
{
    protected $token = null;

    public function withToken($value)
    {
        $this->token = $value;

        return $this;
    }

    protected $password = null;

    public function withPassword($value)
    {
        $this->password = $value;

        return $this;
    }

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'area' => $this->area,
            'mobile' => $this->mobile,
            'wallet' => $this->wallet,
            'subscribed_until' => optional($this->subscribed_until)->format('Y-m-d H:i:s'),
            'logged_at' => optional($this->logged_at)->format('Y-m-d H:i:s'),
            'token' => $this->when(!is_null($this->token), $this->token),
            'password' => $this->when(!is_null($this->password), $this->password),
        ];
    }
}
