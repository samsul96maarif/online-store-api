<?php
/*
 * Autrhor: Samsul Ma'arif <samsulma828@gmail.com>
 * Copyright (c) 2021.
 */

namespace App\Repositories\Contracts;


use Illuminate\Http\Request;

interface RepositoryContract
{
    public function fetch(Request $request);

    public function list();

    public function byId($id);

    public function store(Request $request);

    public function update($id, Request $request);

    public function remove($id);

    public function getModel();
}
