<?php

namespace App\Http\Requests;

use App\Models\Project;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class StoreTaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        $project = Project::find($this->integer('project_id'));

        return $project && $this->user()->can('create', [\App\Models\Task::class, new \App\Models\Task(['project_id' => $project->id])]);
    }

    public function rules(): array
    {
        return [
            'project_id' => ['required', 'exists:projects,id'],
            'assigned_to' => ['nullable', 'exists:users,id'],
            'title' => ['required', 'string', 'max:200'],
            'description' => ['nullable', 'string'],
            'priority' => ['required', 'in:low,medium,high,urgent'],
            'status' => ['required', 'in:todo,in_progress,review,completed'],
            'deadline' => ['nullable', 'date'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            if (! $this->filled('assigned_to') || ! $this->filled('project_id')) {
                return;
            }

            $isEmployeeMember = Project::find($this->integer('project_id'))?->members()
                ->whereKey($this->integer('assigned_to'))
                ->where('role', 'employee')
                ->exists();
            if (! $isEmployeeMember) {
                $validator->errors()->add('assigned_to', 'The assignee must be a member of the selected project.');
            }
        });
    }
}
