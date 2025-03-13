<?php

declare(strict_types=1);

namespace App\Infrastructure\Controller;

use App\Application\UseCase\User\RegisterUserRequest;
use App\Application\UseCase\User\RegisterUserUseCase;
use App\Domain\Exception\InvalidEmailException;
use App\Domain\Exception\InvalidNameException;
use App\Domain\Exception\UserAlreadyExistsException;
use App\Domain\Exception\WeakPasswordException;

final class RegisterUserController
{
    private RegisterUserUseCase $registerUserUseCase;

    public function __construct(RegisterUserUseCase $registerUserUseCase)
    {
        $this->registerUserUseCase = $registerUserUseCase;
    }

    public function __invoke(array $requestData): array
    {
        try {
            // Validar datos de entrada
            if (!isset($requestData['name']) || !isset($requestData['email']) || !isset($requestData['password'])) {
                return $this->errorResponse('Falta alguno de los campos requeridos', 400);
            }

            $request = new RegisterUserRequest(
                $requestData['name'],
                $requestData['email'],
                $requestData['password']
            );

            $userResponse = $this->registerUserUseCase->execute($request);

            return [
                'status' => 'success',
                'data' => $userResponse->toArray(),
                'code' => 201
            ];
            
        } catch (InvalidEmailException $e) {
            return $this->errorResponse($e->getMessage(), 400);
        } catch (InvalidNameException $e) {
            return $this->errorResponse($e->getMessage(), 400);
        } catch (WeakPasswordException $e) {
            return $this->errorResponse($e->getMessage(), 400);
        } catch (UserAlreadyExistsException $e) {
            return $this->errorResponse($e->getMessage(), 409);
        } catch (\Throwable $e) {
            return $this->errorResponse('Ocurrió un error inesperado', 500);
        }
    }

    private function errorResponse(string $message, int $code): array
    {
        return [
            'status' => 'error',
            'message' => $message,
            'code' => $code
        ];
    }
} 