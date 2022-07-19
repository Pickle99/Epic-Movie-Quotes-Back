<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
	use HasFactory;

	protected $guarded = ['id'];

	public function user()
	{
		return $this->belongsTo(User::class);
	}

	public function like()
	{
		return $this->belongsTo(Like::class);
	}

	public function comment()
	{
		return $this->belongsTo(Comment::class);
	}

	public function quote()
	{
		return $this->belongsTo(Quote::class);
	}
}
