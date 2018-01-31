<?php

namespace App\Http\Controllers\Admin;


class HomeController extends CommonController
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home')->with('success', '这是这个');
    }

    public function icon()
    {
        return view('icon');
    }
}
