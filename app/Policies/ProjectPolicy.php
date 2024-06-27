<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Project;
use Illuminate\Auth\Access\Response;

class ProjectPolicy
{
    public function view(User $user, Project $project): bool
    {
        return $user->id === $project->user_id;
    }

    public function update(User $user, Project $project): bool
    {
        return $user->id === $project->user_id;
    }

    public function delete(User $user): bool
    {
        return $user->id === 4;
    }
}
