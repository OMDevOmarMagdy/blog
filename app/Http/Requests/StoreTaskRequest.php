<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreTaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => [
                'required',
                'string',
                'max:255',
            ],

            'description' => [
                'required',
                'string',
            ],

            'status' => [
                'required',
                'in:pending,in_progress,completed,cancelled',
            ],

            'priority' => [
                'required',
                'in:low,medium,high',
            ],

            'assigned_to' => [
                'nullable',
                'exists:users,id',
            ],

            'project_id' => [
                'required',
                'exists:projects,id',
            ],

            'due_date' => [
                'nullable',
                'date',
            ],
        ];
    }
}