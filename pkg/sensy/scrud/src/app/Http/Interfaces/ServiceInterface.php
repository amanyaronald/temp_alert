<?php

namespace Sensy\Scrud\app\Http\Interfaces;

use Illuminate\Http\Request;

interface ServiceInterface
{
    /**
     * Retrieve all records.
     *
     * @return mixed
     */
    public function index(Request $request);

    /**
     * Retrieve a record by its ID.
     *
     * @param int|string $id
     * @return mixed
     */
    public function show(Request $request, $id);

    /**
     * Create a new record.
     *
     * @param Request $request
     * @return mixed
     */
    public function store(Request $request);

    /**
     * Update an existing record.
     *
     * @param int|string $id
     * @param Request $request
     * @return mixed
     */
    public function update(Request $request, $id);

    /**
     * Soft delete a record by its ID.
     *
     * @param int|string $id
     * @return mixed
     */
    public function delete(Request $request, $id);

    /**
     * Permanently delete a record by its ID.
     *
     * @param int|string $id
     * @return mixed
     */
    public function forceDelete(Request $request, $id);
}
