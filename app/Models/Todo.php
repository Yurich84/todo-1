<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Todo extends Model
{
    use HasFactory;

    const COLUMN_ID = 'id';
    const COLUMN_NAME = 'name';

    protected $guarded = [self::COLUMN_ID];
}
