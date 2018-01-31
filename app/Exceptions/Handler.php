<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Exception $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        if ($exception instanceof ValidatorException) {
            if ($request->ajax()) {
                return ajaxReturn('error', $exception->getMessage());
            } else {
                flsh($exception->getMessage(), 'error');
                return back();
            }
        }

        // 关于数据操作的异常拦截
        if ($exception instanceof QueryException) {
            if ($exception->getCode() === 'HY000') {
                if ($request->ajax()) {
                    return ajaxReturn('error', '该数据现已被操作,请稍后再执行对该数据的操作');
                } else {
                    flsh($exception->getMessage(), 'error');

                    return back();
                }
            }
        }

        return parent::render($request, $exception);
    }
}
