<?php

namespace App\Http\Controllers;

use App\Repositories\ReportProcessesRepository;
use Illuminate\View\View;

class ReportProcessesController extends Controller
{
    private ReportProcessesRepository $repository;

    public function __construct(ReportProcessesRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $processes = $this->repository->getAllWithStatus();
        
        return view('report_processes.index', compact('processes'));
    }
}
