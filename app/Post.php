<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    //table name
    protected $table = "products";
    //primary key
    public $primaryKey = "ID";

    protected $fillable = ['title', 'description', 'price', 'image'];
}
