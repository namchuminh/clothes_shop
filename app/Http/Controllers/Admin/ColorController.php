<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ColorController extends Controller
{
    public function index(){
        return view('admin.color.index');
    }

    public function create(){
        return view('admin.color.create');
    }

    public function update(){
        return view('admin.color.update');
    }
}
