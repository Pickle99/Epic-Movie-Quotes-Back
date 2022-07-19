<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
	use HasFactory;

	protected $guarded = ['id'];

	public function quote()
	{
		return $this->belongsTo(Quote::class);
	}

	public function user()
	{
		return $this->belongsTo(User::class);
	}

	public function notificiation()
	{
		return $this->hasOne(Notification::class);
	}
}
