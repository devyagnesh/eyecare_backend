<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreEyeExaminationRequest extends FormRequest
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
                'required',
                'integer',
                'exists:customers,id',
            ],
            'store_id' => [
                'required',
                'integer',
                'exists:stores,id',
            ],
            'exam_date' => [
                'required',
                'date',
                'before_or_equal:today',
            ],
            'chief_complaint' => [
                'nullable',
                'string',
            ],
            'old_rx_date' => [
                'nullable',
                'date',
                'before_or_equal:exam_date',
            ],
            'od_va_unaided' => [
                'nullable',
                'string',
                'max:20',
            ],
            'os_va_unaided' => [
                'nullable',
                'string',
                'max:20',
            ],
            'od_sphere' => [
                'required',
                'numeric',
                'between:-20.00,20.00',
            ],
            'od_cylinder' => [
                'required',
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
                'required',
                'numeric',
                'between:-20.00,20.00',
            ],
            'os_cylinder' => [
                'required',
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
                'min:0.00',
                'max:3.50',
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
                'max:20',
            ],
            'os_bcva' => [
                'nullable',
                'string',
                'max:20',
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
            ],
            'management_plan' => [
                'nullable',
                'string',
            ],
            'next_recall_date' => [
                'nullable',
                'date',
                'after:exam_date',
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
            'customer_id.integer' => 'Customer ID must be an integer.',
            'customer_id.exists' => 'The selected customer does not exist.',
            'store_id.required' => 'Store ID is required.',
            'store_id.integer' => 'Store ID must be an integer.',
            'store_id.exists' => 'The selected store does not exist.',
            'exam_date.required' => 'Examination date is required.',
            'exam_date.date' => 'Examination date must be a valid date.',
            'exam_date.before_or_equal' => 'Examination date cannot be in the future.',
            'old_rx_date.date' => 'Previous RX date must be a valid date.',
            'old_rx_date.before_or_equal' => 'Previous RX date cannot be after the examination date.',
            'od_sphere.required' => 'OD sphere is required.',
            'od_sphere.numeric' => 'OD sphere must be a number.',
            'od_sphere.between' => 'OD sphere must be between -20.00 and +20.00.',
            'od_cylinder.required' => 'OD cylinder is required.',
            'od_cylinder.numeric' => 'OD cylinder must be a number.',
            'od_cylinder.between' => 'OD cylinder must be between -20.00 and +20.00.',
            'od_axis.integer' => 'OD axis must be an integer.',
            'od_axis.min' => 'OD axis must be between 0 and 180 degrees.',
            'od_axis.max' => 'OD axis must be between 0 and 180 degrees.',
            'os_sphere.required' => 'OS sphere is required.',
            'os_sphere.numeric' => 'OS sphere must be a number.',
            'os_sphere.between' => 'OS sphere must be between -20.00 and +20.00.',
            'os_cylinder.required' => 'OS cylinder is required.',
            'os_cylinder.numeric' => 'OS cylinder must be a number.',
            'os_cylinder.between' => 'OS cylinder must be between -20.00 and +20.00.',
            'os_axis.integer' => 'OS axis must be an integer.',
            'os_axis.min' => 'OS axis must be between 0 and 180 degrees.',
            'os_axis.max' => 'OS axis must be between 0 and 180 degrees.',
            'add_power.numeric' => 'Add power must be a number.',
            'add_power.min' => 'Add power must be 0.00 or a positive value.',
            'add_power.max' => 'Add power must not exceed 3.50.',
            'pd_distance.numeric' => 'PD distance must be a number.',
            'pd_distance.min' => 'PD distance must be between 40 and 80 mm.',
            'pd_distance.max' => 'PD distance must be between 40 and 80 mm.',
            'pd_near.numeric' => 'PD near must be a number.',
            'pd_near.min' => 'PD near must be between 40 and 80 mm.',
            'pd_near.max' => 'PD near must be between 40 and 80 mm.',
            'od_va_unaided.string' => 'OD unaided VA must be a string.',
            'od_va_unaided.max' => 'OD unaided VA must not exceed 20 characters.',
            'os_va_unaided.string' => 'OS unaided VA must be a string.',
            'os_va_unaided.max' => 'OS unaided VA must not exceed 20 characters.',
            'od_bcva.string' => 'OD BCVA must be a string.',
            'od_bcva.max' => 'OD BCVA must not exceed 20 characters.',
            'os_bcva.string' => 'OS BCVA must be a string.',
            'os_bcva.max' => 'OS BCVA must not exceed 20 characters.',
            'iop_od.integer' => 'OD IOP must be an integer.',
            'iop_od.min' => 'OD IOP must be between 5 and 60 mmHg.',
            'iop_od.max' => 'OD IOP must be between 5 and 60 mmHg.',
            'iop_os.integer' => 'OS IOP must be an integer.',
            'iop_os.min' => 'OS IOP must be between 5 and 60 mmHg.',
            'iop_os.max' => 'OS IOP must be between 5 and 60 mmHg.',
            'next_recall_date.date' => 'Next recall date must be a valid date.',
            'next_recall_date.after' => 'Next recall date must be after the examination date.',
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
            // Axis validation: Required if cylinder is not 0.00
            if (!empty($this->od_cylinder) && (float)$this->od_cylinder != 0.00 && empty($this->od_axis)) {
                $validator->errors()->add(
                    'od_axis',
                    'OD axis is required when OD cylinder is not 0.00.'
                );
            }

            if (!empty($this->os_cylinder) && (float)$this->os_cylinder != 0.00 && empty($this->os_axis)) {
                $validator->errors()->add(
                    'os_axis',
                    'OS axis is required when OS cylinder is not 0.00.'
                );
            }

            // PD near must be less than PD distance if both are provided
            if (!empty($this->pd_distance) && !empty($this->pd_near)) {
                if ((float)$this->pd_near >= (float)$this->pd_distance) {
                    $validator->errors()->add(
                        'pd_near',
                        'PD near must be less than PD distance.'
                    );
                }
            }

            // Add power validation: Must be 0.00 or positive
            if (!empty($this->add_power) && (float)$this->add_power < 0.00) {
                $validator->errors()->add(
                    'add_power',
                    'Add power must be 0.00 or a positive value.'
                );
            }
        });
    }
}
