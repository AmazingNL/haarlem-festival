<?php

declare(strict_types=1);

namespace App\Controllers;
use App\Core\BaseController;
use App\Core\Middleware;
use App\Services\IUserService;
use App\Models\User;
final class HomeController extends BaseController
{
    public function index(): void
    {
        $this->view('home/index');
    } 
}

