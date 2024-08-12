<?php

namespace App\Http\Requests;

use App\Models\Project;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Gate;

class StoreProjectRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize()
    {
        return Gate::authorize('create', Project::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'min:3'],
            'slug' => ['required', Rule::unique('projects', 'slug')],
            'excerpt' => ['required'],
            'body' => ['required'],
            'thumbnail' => ['required', 'image']
        ];
    }

    public function validated($key = null, $default = null)
    {
        if (request()->isMethod('post')) {
            return array_merge(parent::validated(), ['thumbnail' => $this->file('thumbnail')->store('thumbnails'), 'user_id' => auth()->user()->id]);
        }

        return parent::validated();
    }
}
