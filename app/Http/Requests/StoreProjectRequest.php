<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', \App\Models\Project::class);
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:150'],
            'description' => ['nullable', 'string'],
            'start_date' => ['required', 'date'],
            'deadline' => ['required', 'date', 'after_or_equal:start_date'],
            'status' => ['required', 'in:planning,active,on_hold,completed,cancelled'],
            'manager_id' => [$this->user()->isAdmin() ? 'required' : 'nullable', 'exists:users,id'],
            'members' => ['nullable', 'array'],
            'members.*' => ['integer', 'exists:users,id'],
        ];
    }

    protected function prepareForValidation(): void
    {
        if ($this->user()->isManager()) {
            $this->merge(['manager_id' => $this->user()->id]);
        }
    }

    public function withValidator(\Illuminate\Validation\Validator $validator): void
    {
        $validator->after(function (\Illuminate\Validation\Validator $validator): void {
            if ($this->filled('manager_id') && ! \App\Models\User::whereKey($this->integer('manager_id'))->where('role', 'manager')->exists()) {
                $validator->errors()->add('manager_id', 'The project manager must have a manager role.');
            }

            if ($this->filled('members')) {
                $employeeCount = \App\Models\User::whereIn('id', $this->input('members', []))->where('role', 'employee')->count();
                if ($employeeCount !== count(array_unique($this->input('members', [])))) {
                    $validator->errors()->add('members', 'Only employees can be added to a project.');
                }
            }
        });
    }
}
