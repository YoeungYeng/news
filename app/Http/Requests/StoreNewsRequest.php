<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreNewsRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'description' => 'required|string|max:1500',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'status' => 'required|in:active,inactive',
        ];
    }
    // validation messages
    public function messages(): array
    {
        return [
            'title.required' => 'The title is required.',
            'content.required' => 'The content is required.',
            'category_id.required' => 'The category is required.',
            'description.required' => 'The description is required.',
            'image.image' => 'The image must be an image file.',
            'status.required' => 'The status is required.',
        ];
    }
    // custom attributes
    public function attributes(): array
    {
        return [
            'title' => 'news title',
            'content' => 'news content',
            'category_id' => 'category',
            'description' => 'news description',
            'image' => 'news image',
            'status' => 'news status',
        ];
    }
    // custom response
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'status' => 'error',
            'message' => 'Validation failed',
            'errors' => $validator->errors(),
        ], 422));
    }

    // upload image
    public function uploadImage($image)
    {
        if ($image) {
            $imagePath = $image->store('images', 'public'); // Store in storage/app/public/images
            return asset('storage/' . $imagePath); // Convert to URL
        }
        return null; // Return null if no image is provided
    }

    // handle the request and upload image

}
