<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Arr;

class UsersApiPagination extends ResourceCollection
{
    public $collects = UserResource::class;

    /**
     * Customize the pagination information for the resource.
     *
     * @param  Request  $request
     * @param  array $paginated
     * @param  array $default
     * @return array
     */
    public function paginationInformation(Request $request, array $paginated, array $default): array
    {
        return Arr::get($default, 'data', []);
    }

    /**
     * Transform the resource collection into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'success' => true,
            'page' => $this->currentPage(),
            'total_pages' => $this->lastPage(),
            'total_users' => $this->total(),
            'count' => $this->count(),
            'users' => $this->collection,
            'links' => [
                'next_url' => $this->nextPageUrl(),
                'prev_url' => $this->previousPageUrl()
            ],
        ];
    }
}
