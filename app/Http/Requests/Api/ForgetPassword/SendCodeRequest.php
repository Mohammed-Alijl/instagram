<?php

namespace App\Http\Requests\Api\ForgetPassword;

use App\Http\Controllers\Api\Traits\Api_Response;
use App\Mail\SendCodeResetPassword;
use App\Models\ResetCodePassword;
use App\Models\User;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Exception;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Mail;

class SendCodeRequest extends FormRequest
{
    use Api_Response;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    public function run()
    {
        try {
            $user = User::firstWhere('email', $this->email);
            if (!$user)
                return $this->apiResponse(['send' => false], 422, __('messages.forgetPassword.email.exists'));
            ResetCodePassword::where('email', $this->email)->delete();
            $code = mt_rand(100000, 999999);
            $codeData = new ResetCodePassword;
            $codeData->email = $this->email;
            $codeData->code = $code;
            if ($codeData->save()) {
                Mail::to($this->email)->send(new SendCodeResetPassword($code,$user->name));
                return $this->apiResponse(['send' => true], 200, __('messages.forgetPassword.message.sent'));
            }
            return $this->apiResponse(['send' => false], 500, __('messages.failed'));
        } catch (Exception $ex) {
            return $this->apiResponse(['send' => false], 500, $ex->getMessage());
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'email' => 'required|email',
        ];
    }
    public function messages()
    {
        return[
          'email.required'=>__('messages.forgetPassword.email.required'),
          'email.email'=>__('messages.forgetPassword.email.email'),
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException($this->apiResponse(null, 422, $validator->errors()));
    }
}
