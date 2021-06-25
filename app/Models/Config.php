<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;

class Config extends BaseModel
{
    protected $fillable = [
        'group',
        'name',
        'type',
        'code',
        'content',
    ];

    public function scopeGroup(Builder $query, string $group = null)
    {
        return $query->when($group, function (Builder $query, $group) {
            return $query->where('group', $group);
        });
    }

    public function scopeCode(Builder $query, string $code = null)
    {
        return $query->when($code, function (Builder $query, $code) {
            return $query->where('code', $code);
        });
    }

    public function scopeKeyword(Builder $query, string $keyword = null)
    {
        return $query->when($keyword, function (Builder $query, $keyword) {
            return $query->where('code', 'like', '%' . $keyword . '%')->orWhere('name', 'like', '%' . $keyword . '%');
        });
    }

    public function getValueAttribute()
    {
        switch ($this->type) {
            case 'switch':
                $value = $this->content == 1;
                break;
            case 'text':
                $value = nl2br($this->content);
                break;
            case 'array':
                $value = preg_split('/\r\n|\r|\n/', $this->content);
                break;
            case 'image':
                if (getConfig('app', 'encrypt_img')) {
                    $value = getConfig('app', 'encode_img_url') . $this->content;
                } else {
                    $value = getConfig('app', 'img_url') . $this->content;
                }
                break;
            default:
                $value = $this->content ?? '';
                break;
        }

        return $value;
    }
}
