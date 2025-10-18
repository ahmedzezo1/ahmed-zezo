<?php
namespace App\Controllers;

declare(strict_types=1);

use App\Core\Controller;

class HomeController extends Controller
{
    public function index(): string
    {
        return $this->view('home/index', [
            'title' => 'إدارة الأصول التقنية',
        ]);
    }
}
