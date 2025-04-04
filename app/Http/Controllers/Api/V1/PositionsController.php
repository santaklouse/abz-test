<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\PositionsApiResource;
use App\Models\Position;

class PositionsController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            return new PositionsApiResource(Position::all());
        } catch (\Exception $e) {
            return $this->jsonResponse(
                message: "Positions not found",
                code: 404
            );
        }
    }

}
