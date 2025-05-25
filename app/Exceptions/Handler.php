<?php

namespace App\Exceptions;

use App\Traits\ApiResponseTrait;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Exception\MethodNotAllowedException;
use Throwable;
use Illuminate\Support\Str;

class Handler extends ExceptionHandler
{
    use ApiResponseTrait;
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $e)
    {
        if ($request->expectsJson() || Str::contains($request->path(), 'api')) {
            if ($e instanceof PinNotSetException) {
                return $this->apiResponse([
                    'success'    => false,
                    'message'    => $e->getMessage(),
                    'error_code' => Response::HTTP_BAD_REQUEST,
                    'exception'  => $e,
                ], Response::HTTP_BAD_REQUEST);
            }
            if ($e instanceof InvalidPinLengthException) {
                return $this->apiResponse([
                    'success'    => false,
                    'message'    => $e->getMessage(),
                    'error_code' => Response::HTTP_BAD_REQUEST,
                    'exception'  => $e,
                ], Response::HTTP_BAD_REQUEST);
            }
            if ($e instanceof AmountToLowException) {
                return $this->apiResponse([
                    'success'    => false,
                    'message'    => $e->getMessage(),
                    'error_code' => Response::HTTP_BAD_REQUEST,
                    'exception'  => $e,
                ], Response::HTTP_BAD_REQUEST);
            }
            if ($e instanceof InvalidAccountNumberException) {
                return $this->apiResponse([
                    'success'    => false,
                    'message'    => $e->getMessage(),
                    'error_code' => Response::HTTP_BAD_REQUEST,
                    'exception'  => $e,
                ], Response::HTTP_BAD_REQUEST);
            }
            if ($e instanceof InvalidPinException) {
                return $this->apiResponse([
                    'success'    => false,
                    'message'    => $e->getMessage(),
                    'error_code' => Response::HTTP_BAD_REQUEST,
                    'exception'  => $e,
                ], Response::HTTP_BAD_REQUEST);
            }
            if ($e instanceof MethodNotAllowedHttpException) {
                return $this->apiResponse([
                    'success'    => false,
                    'message'    => $e->getMessage(),
                    'error_code' => Response::HTTP_METHOD_NOT_ALLOWED,
                    'exception'  => $e,
                ], Response::HTTP_METHOD_NOT_ALLOWED);
            }
            if ($e instanceof AuthenticationException) {
                return $this->apiResponse([
                    'success'    => false,
                    'message'    => 'Unauthenticated or Token Expired, please try to log in again',
                    'error_code' => Response::HTTP_UNAUTHORIZED,
                    'exception'  => $e,
                ], Response::HTTP_UNAUTHORIZED);
            }
            if ($e instanceof NotFoundHttpException) {
                return $this->apiResponse([
                    'success'    => false,
                    'message'    => $e->getMessage(),
                    'error_code' => $e->getStatusCode(),
                    'exception'  => $e,
                ], $e->getStatusCode());
            }
            if ($e instanceof ValidationException) {
                return $this->apiResponse([
                    'success'    => false,
                    'message'    => 'Validated false',
                    'error_code' => Response::HTTP_NOT_FOUND,
                    'exception'  => $e,
                    'errors'     => $e->errors(),
                ], Response::HTTP_NOT_FOUND);
            }
            if ($e instanceof ModelNotFoundException) {
                return $this->apiResponse([
                    'success'    => false,
                    'message'    => 'Resource not be found',
                    'error_code' => Response::HTTP_NOT_FOUND,
                    'exception'  => $e,
                ], Response::HTTP_NOT_FOUND);
            }

            if ($e instanceof QueryException) {
                return $this->apiResponse([
                    'success'    => false,
                    'message'    => 'Could not excute query',
                    'error_code' => Response::HTTP_INTERNAL_SERVER_ERROR,
                    'exception'  => $e,
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
            }

            if ($e instanceof AccountNumberExistsException) {
                return $this->apiResponse([
                    'message' => 'Account Number has already been generate',
                    'success' => false,
                    'exception' => $e,
                    'error_code' => Response::HTTP_INTERNAL_SERVER_ERROR,
                ]);
            }

            if ($e instanceof \Exception) {
                return $this->apiResponse([
                    'message' => 'We could not handle your request, please try again later',
                    'success' => false,
                    'exception' => $e,
                    'error_code' => Response::HTTP_INTERNAL_SERVER_ERROR,
                ]);
            }

            if ($e instanceof \Error) {
                return $this->apiResponse([
                    'message' => 'We could not handle your request, please try again later',
                    'success' => false,
                    'exception' => $e,
                    'error_code' => Response::HTTP_INTERNAL_SERVER_ERROR,
                ]);
            }
        }

        return parent::render($request, $e);
    }
}
