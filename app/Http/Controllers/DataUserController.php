<?php 

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class DataUserController extends Controller
{
    public function addUpdate(Request $request)
    {

        if ($request->isUpdate === 'true') {
            $validatedData = $request->validate([
                'id' => 'required|exists:tbusers,id',
                'fullname' => 'required|string|max:255',
                'username' => 'required|string|max:255|unique:tbusers,username,' . $request->id,
                'email' => 'required|string|email|max:255|unique:tbusers,email,' . $request->id,
                'hak_akses' => 'required|string|max:255',
            ]);
    
            try {
                DB::table('tbusers')->where('id', $request->id)->update([
                    'nama_lengkap' => $validatedData['fullname'],
                    'username' => $validatedData['username'],
                    'email' => $validatedData['email'],
                    // Uncomment the next line if password can be updated
                    // 'password' => Hash::make($request->password),
                    'hak_akses' => $validatedData['hak_akses'],
                    'updated_at' => now(),
                ]);
    
                Session::flash('success', 'User updated successfully!');
            } catch (\Exception $e) {
                Session::flash('error', 'Failed to update user: ' . $e->getMessage());
            }
    
            return redirect()->route('user');
        } else {
            $validatedData = $request->validate([
                'fullname' => 'required|string|max:255',
                'username' => 'required|string|max:255|unique:tbusers,username',
                'email' => 'required|string|email|max:255|unique:tbusers,email',
                'password' => 'required|string|min:8',
                'hak_akses' => 'required|string|max:255',
            ]);
    
            try {
                DB::table('tbusers')->insert([
                    'nama_lengkap' => $validatedData['fullname'],
                    'username' => $validatedData['username'],
                    'email' => $validatedData['email'],
                    'password' => Hash::make($validatedData['password']),
                    'hak_akses' => $validatedData['hak_akses'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
    
                Session::flash('success', 'User added successfully!');
            } catch (\Exception $e) {
                Session::flash('error', 'Failed to add user: ' . $e->getMessage());
            }
    
            return redirect()->route('user');
        }

    }

    public function update(Request $request)
    {

    }

    public function delete(Request $request)
    {
        $validatedData = $request->validate([
            'id' => 'required|exists:tbusers,id',
        ]);

        try {
            DB::table('tbusers')->where('id', $validatedData['id'])->delete();

            Session::flash('success', 'User deleted successfully!');
        } catch (\Exception $e) {
            Session::flash('error', 'Failed to delete user: ' . $e->getMessage());
        }

        return redirect()->route('user');
    }
}
