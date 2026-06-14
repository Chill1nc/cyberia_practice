<?php
namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required_without:phone|email|nullable',
            'phone' => 'required_without:email|string|nullable',
        ]);
        $searchField = $request->has('email') && $request->input('email') ? 'email' : 'phone';
        $searchValue = $request->input($searchField);
        $user = User::firstOrCreate([$searchField => $searchValue]);

        $code = str_pad((string) rand(0, 9999), 4, '0', STR_PAD_LEFT);
        $user->update([
            'login_code' => $code,
            'login_code_expires_at' => now()->addMinutes(10),
        ]);
        Log::info("Код авторизации для {$searchValue}: {$code}");
        return response()->json([
            'message' => 'Код авторизации отправлен.',
            'debug_code' => $code
        ]);
    }
    public function verify(Request $request)
    {
        $request->validate([
            'email' => 'required_without:phone|email|nullable',
            'phone' => 'required_without:email|string|nullable',
            'code' => 'required|string|size:4',
        ]);
        $searchField = $request->has('email') && $request->input('email') ? 'email' : 'phone';
        $searchValue = $request->input($searchField);
        $user = User::where($searchField, $searchValue)->first();
        if (!$user || $user->login_code !== $request->code || now()->greaterThan($user->login_code_expires_at)) {
            return response()->json(['message' => 'Неверный или просроченный код.'], 422);
        }
        $user->update([
            'login_code' => null,
            'login_code_expires_at' => null,
        ]);
        $token = $user->createToken('auth_token')->plainTextToken;
        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }
    public function profile(Request $request)
    {
        return response()->json($request->user());
    }
    public function updateProfile(Request $request)
    {
        $user = $request->user();
        $validated = $request->validate([
            'first_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'email' => 'nullable|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|unique:users,phone,' . $user->id,
        ]);
        $user->update($validated);
        return response()->json([
            'message' => 'Профиль обновлен.',
            'user' => $user
        ]);
    }
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json([
            'message' => 'Вы успешно вышли из системы.'
        ]);
    }
}