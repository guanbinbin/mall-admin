<?php

namespace App\Http\Controllers\Admin;

use App\Exceptions\ValidatorException;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class CommonController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * 描述：字段检测
     * User：Jack Yang
     *
     * @param $data
     * @param $rule
     * @param $message
     */
    public function makeVali($data, $rule, $message)
    {
        $validator = Validator::make($data, $rule, $message);

        throw_if($validator->fails(), ValidatorException::class, $validator->errors()->first());
    }
}
