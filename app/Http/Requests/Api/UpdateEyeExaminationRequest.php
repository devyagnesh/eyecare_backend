<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEyeExaminationRequest extends FormRequest
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
            'customer_id' => [
                'sometimes',
                'required',
                'integer',
                'exists:customers,id',
            ],
            'exam_date' => [
                'sometimes',
                'required',
                'date',
                'before_or_equal:today',
            ],
            'chief_complaint' => [
                'nullable',
                'string',
                'max:500',
            ],
            'old_rx_date' => [
                'nullable',
                'date',
            ],
            'od_va_unaided' => [
                'nullable',
                'string',
                'max:255',
            ],
            'os_va_unaided' => [
                'nullable',
                'string',
                'max:255',
            ],
            'od_sphere' => [
                'nullable',
                'numeric',
                'between:-20.00,20.00',
            ],
            'od_cylinder' => [
                'nullable',
                'numeric',
                'between:-20.00,20.00',
            ],
            'od_axis' => [
                'nullable',
                'integer',
                'min:0',
                'max:180',
            ],
            'os_sphere' => [
                'nullable',
                'numeric',
                'between:-20.00,20.00',
            ],
            'os_cylinder' => [
                'nullable',
                'numeric',
                'between:-20.00,20.00',
            ],
            'os_axis' => [
                'nullable',
                'integer',
                'min:0',
                'max:180',
            ],
            'add_power' => [
                'nullable',
                'numeric',
                'between:0.00,4.00',
            ],
            'pd_distance' => [
                'nullable',
                'numeric',
                'min:40',
                'max:80',
            ],
            'pd_near' => [
                'nullable',
                'numeric',
                'min:40',
                'max:80',
            ],
            'od_bcva' => [
                'nullable',
                'string',
                'max:255',
            ],
            'os_bcva' => [
                'nullable',
                'string',
                'max:255',
            ],
            'iop_od' => [
                'nullable',
                'integer',
                'min:5',
                'max:60',
            ],
            'iop_os' => [
                'nullable',
                'integer',
                'min:5',
                'max:60',
            ],
            'fundus_notes' => [
                'nullable',
                'string',
            ],
            'diagnosis' => [
                'nullable',
                'string',
                'max:500',
            ],
            'management_plan' => [
                'nullable',
                'string',
            ],
            'next_recall_date' => [
                'nullable',
                'date',
            ],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'customer_id.required' => 'Customer ID is required.',
            'customer_id.exists' => 'The selected customer does not exist.',
            'exam_date.required' => 'Examination date is required.',
            'exam_date.date' => 'Examination date must be a valid date.',
            'exam_date.before_or_equal' => 'Examination date cannot be in the future.',
            'chief_complaint.string' => 'Chief complaint must be a string.',
            'chief_complaint.max' => 'Chief complaint must not exceed 500 characters.',
            'old_rx_date.date' => 'Previous RX date must be a valid date.',
            'od_sphere.between' => 'OD sphere must be between -20.00 and +20.00.',
            'os_sphere.between' => 'OS sphere must be between -20.00 and +20.00.',
            'od_cylinder.between' => 'OD cylinder must be between -20.00 and +20.00.',
            'os_cylinder.between' => 'OS cylinder must be between -20.00 and +20.00.',
            'od_axis.min' => 'OD axis must be between 0 and 180 degrees.',
            'od_axis.max' => 'OD axis must be between 0 and 180 degrees.',
            'os_axis.min' => 'OS axis must be between 0 and 180 degrees.',
            'os_axis.max' => 'OS axis must be between 0 and 180 degrees.',
            'add_power.between' => 'Add power must be between 0.00 and 4.00.',
            'pd_distance.min' => 'PD distance must be between 40 and 80 mm.',
            'pd_distance.max' => 'PD distance must be between 40 and 80 mm.',
            'pd_near.min' => 'PD near must be between 40 and 80 mm.',
            'pd_near.max' => 'PD near must be between 40 and 80 mm.',
            'iop_od.min' => 'OD IOP must be between 5 and 60 mmHg.',
            'iop_od.max' => 'OD IOP must be between 5 and 60 mmHg.',
            'iop_os.min' => 'OS IOP must be between 5 and 60 mmHg.',
            'iop_os.max' => 'OS IOP must be between 5 and 60 mmHg.',
            'diagnosis.string' => 'Diagnosis must be a string.',
            'diagnosis.max' => 'Diagnosis must not exceed 500 characters.',
            'next_recall_date.date' => 'Next recall date must be a valid date.',
        ];
    }

    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            // Custom validation: If updating chief_complaint or diagnosis, ensure they are not empty
            if ($this->has('chief_complaint') && empty(trim($this->chief_complaint))) {
                $validator->errors()->add(
                    'chief_complaint',
                    'Chief complaint cannot be empty.'
                );
            }

            if ($this->has('diagnosis') && empty(trim($this->diagnosis))) {
                $validator->errors()->add(
                    'diagnosis',
                    'Diagnosis cannot be empty.'
                );
            }

            // Validate old_rx_date is before exam_date if both are provided
            if ($this->has('old_rx_date') && $this->has('exam_date')) {
                $oldRxDate = $this->old_rx_date;
                $examDate = $this->exam_date;
                
                if ($oldRxDate && $examDate && strtotime($oldRxDate) > strtotime($examDate)) {
                    $validator->errors()->add(
                        'old_rx_date',
                        'Previous RX date cannot be after the examination date.'
                    );
                }
            }

            // Validate next_recall_date is after exam_date if both are provided
            if ($this->has('next_recall_date') && $this->has('exam_date')) {
                $nextRecallDate = $this->next_recall_date;
                $examDate = $this->exam_date;
                
                if ($nextRecallDate && $examDate && strtotime($nextRecallDate) <= strtotime($examDate)) {
                    $validator->errors()->add(
                        'next_recall_date',
                        'Next recall date must be after the examination date.'
                    );
                }
            }
        });
    }
}

