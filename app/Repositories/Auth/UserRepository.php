<?php

namespace App\Repositories\Auth;

use App\Interfaces\RepositoryInterface;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserRepository implements RepositoryInterface
{
    public function show($id)
    {
        return User::find($id);
    }

    public function showAll()
    {
        return User::all();
    }

    public function delete($id)
    {
        $user = $this->show($id);
        if ($user != null) {
            return $user->delete();
        } else {
            return false;
        }
    }

    public function update($id, array $data)
    {
        $user = $this->show($id);
        if ($user != null) {
            $user->update($data);
            return $user;
        } else {
            return false;
        }
    }

    public function store(array $data)
    {
        return User::create($data);
    }

    public function checkUser($email, $password)
    {
        $userMiddleware = auth()->user();
        $user = User::where('email', $email)->first();

        if ($user->email != $userMiddleware->email) {
            abort(406, "Nomor handphone tidak sama dengan yang login");
        }

        if (!$user || !Hash::check($password, $user->password)) {
            abort(406, "User tidak ada atau password salah");
        }
    }

    public function register(Request $request): User
    {
        $user = User::where('email', $request->email)->first();

        if ($user) {
            abort(406, "Nomor handphone sudah terdaftar");
        }

        $data = [
            "name" => $request->name,
            "email" => $request->email,
            "password" => Hash::make($request->password),
        ];

        $user = $this->store($data);
        $user->assignRole("pengunjung");
        return $user;
    }

    public function login(Request $request): User
    {
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            abort(406, "Nomor handphone atau password salah");
        }

        if ($request->notification_token) {
            $user->update(['notification_token' => $request->notification_token]);
        }

        return $user;
    }

    public function login_admin(Request $request): User
    {
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            abort(406, "Nomor handphone atau password salah");
        }

        if ($request->notification_token) {
            $user->update(['notification_token' => $request->notification_token]);
        }

        if (!$user->hasRole('super_admin')) {
            abort(403, "You do not have permission to access this resource");
        }

        return $user;
    }

    public function createToken(Request $request): string
    {
        $user = User::where('email', $request->email)->first();
        $token = $user->createToken($request->device_name ?? "token")->plainTextToken;
        return $token;
    }

    public function getUser(): ?User
    {
        $user = auth()->user();
        if ($user->hasRole('mitra')) {
            $user = User::where('id', $user->id)->with(['mitra', 'mitra.toko', 'mitra.wallet', 'mitra.address', 'mitra.toko.address'])->first();
            // $user->mitra = $mitra;
        }
        return $user;
    }

    public function setNotificationToken(Request $request): ?User
    {
        $user = auth()->user();
        $user->update(['notification_token' => $request->notification_token]);
        return $user;
    }

    public function deleteToken(Request $request): bool
    {
        return $request->user()->currentAccessToken()->delete();
    }

}
