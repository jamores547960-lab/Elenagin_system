<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash; // 👈 Ensure Hash Facade is available

class EmployeeController extends Controller
{
    public function index()
    {
        $query = Employee::with('user')->orderBy('last_name');

        if ($search = request('search')) {
            $like = '%'.$search.'%';
            $query->where(function ($q) use ($like) {
                $q->where('first_name','like',$like)
                  ->orWhere('last_name','like',$like)
                  ->orWhere('contact_number','like',$like)
                  ->orWhere('sss_number','like',$like)
                  ->orWhereHas('user', fn($uq) =>
                      $uq->where('name','like',$like)
                           ->orWhere('email','like',$like)
                  );
            });
        }

        $employees = $query->paginate(10);
        return view('employees.index', compact('employees'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'             => ['required','string','max:100'],
            'email'            => ['required','email','max:150','unique:users,email'],
            'password'         => ['required','confirmed','min:6'],
            // 🚨 ADJUSTMENT 1: Validate the new 'role' field
            'role'             => ['required','string', Rule::in(['employee', 'cashier'])], 
            'first_name'       => ['required','string','max:80'],
            'last_name'        => ['required','string','max:80'],
            'address'          => ['required','string','max:255'],
            'contact_number'   => ['required','string','max:40'],
            'sss_number'       => ['required','string','max:40','unique:employees,sss_number'],
            'profile_picture'  => ['nullable','image','mimes:jpg,jpeg,png,webp','max:2048'],
        ]);

        DB::transaction(function () use ($request, $data) {

            // 🚨 ADJUSTMENT 2: Use the submitted 'role' instead of hardcoding 'employee'
            $user = User::create([
                'name'     => $data['name'],
                'email'    => $data['email'],
                'password' => Hash::make($data['password']), // 👈 Best practice to explicitly hash if model casting is not used
                'role'     => $data['role'], 
            ]);

            $profilePath = $request->hasFile('profile_picture')
                ? $request->file('profile_picture')->store('employee_profiles','public')
                : null;

            $employee = Employee::create([
                'user_id'        => $user->id,
                'first_name'     => $data['first_name'],
                'last_name'      => $data['last_name'],
                'address'        => $data['address'],
                'contact_number' => $data['contact_number'],
                'sss_number'     => $data['sss_number'],
                'profile_picture'=> $profilePath,
            ]);

            ActivityLog::record(
                'employee.created',
                $employee,
                'Employee created: '.$employee->first_name.' '.$employee->last_name,
                ['employee_id' => $employee->id, 'user_id' => $user->id]
            );
        });

        // Redirect to the index page with a specific success message including the role
        return redirect()->route('employees.index')->with('success','Employee created and assigned the role of '.ucfirst($data['role']).'.');
    }

    // --- The rest of the controller methods ---

    public function edit(Employee $employee)
    {
        $employee->load('user');
        return view('employees.edit', compact('employee'));
    }

    public function update(Request $request, Employee $employee)
    {
        $employee->load('user');

        $data = $request->validate([
            'name'             => ['required','string','max:100'],
            'email'            => ['required','email','max:150', Rule::unique('users','email')->ignore($employee->user_id)],
            'password'         => ['nullable','confirmed','min:6'],
            // 🚨 ADJUSTMENT 3: Add 'role' to validation (optional for update, but necessary if you want to change it)
            'role'             => ['required', 'string', Rule::in(['cashier', 'employee'])], // 'employee' kept for existing users
            'first_name'       => ['required','string','max:80'],
            'last_name'        => ['required','string','max:80'],
            'address'          => ['required','string','max:255'],
            'contact_number'   => ['required','string','max:40'],
            'sss_number'       => ['required','string','max:40', Rule::unique('employees','sss_number')->ignore($employee->id)],
            'profile_picture'  => ['nullable','image','mimes:jpg,jpeg,png,webp','max:2048'],
        ]);

        DB::transaction(function () use ($request, $data, $employee) {

            $employee->user->name  = $data['name'];
            $employee->user->email = $data['email'];
            // 🚨 ADJUSTMENT 4: Update the user's role if the field was submitted
            $employee->user->role  = $data['role']; 
            
            if (!empty($data['password'])) {
                // Best practice to explicitly hash if not relying on model casting
                $employee->user->password = Hash::make($data['password']);
            }
            $employee->user->save();

            if ($request->hasFile('profile_picture')) {
                if ($employee->profile_picture && Storage::disk('public')->exists($employee->profile_picture)) {
                    Storage::disk('public')->delete($employee->profile_picture);
                }
                $employee->profile_picture = $request->file('profile_picture')->store('employee_profiles','public');
            }

            $employee->update([
                'first_name'     => $data['first_name'],
                'last_name'      => $data['last_name'],
                'address'        => $data['address'],
                'contact_number' => $data['contact_number'],
                'sss_number'     => $data['sss_number'],
            ]);

            ActivityLog::record(
                'employee.updated',
                $employee,
                'Employee updated: '.$employee->first_name.' '.$employee->last_name,
                ['employee_id' => $employee->id]
            );
        });

        return redirect()->route('employees.edit',$employee->id)->with('success','Employee updated.');
    }

    public function destroy(Employee $employee)
    {
        $employee->load('user');

        if ($employee->profile_picture && Storage::disk('public')->exists($employee->profile_picture)) {
            Storage::disk('public')->delete($employee->profile_picture);
        }

        $user = $employee->user;

        DB::transaction(function () use ($employee, $user) {
            $employee->delete();
            // 🚨 NOTE: Assuming the role 'employee' is equivalent to 'cashier' for deletion.
            // If the user's role is NOT admin (meaning they are a regular worker), delete the user account.
            if ($user && $user->role !== 'admin') { 
                $user->delete();
            }

            ActivityLog::record(
                'employee.archived',
                $employee,
                'Employee archived: '.$employee->first_name.' '.$employee->last_name,
                ['employee_id' => $employee->id, 'user_id' => $user?->id]
            );
        });

        return redirect()->route('employees.index')->with('success','Employee deleted.');
    }

    // restore a soft-deleted employee (and its user if applicable)
    public function restore($id)
    {
        $employee = Employee::withTrashed()->where('id', $id)->firstOrFail();
        $user = $employee->user()->withTrashed()->first();

        DB::transaction(function () use ($employee, $user) {
            if ($employee->trashed()) $employee->restore();
            if ($user && method_exists($user, 'restore') && $user->trashed()) $user->restore();

            ActivityLog::record(
                'employee.restored',
                $employee,
                'Employee restored: '.$employee->first_name.' '.$employee->last_name,
                ['employee_id' => $employee->id, 'user_id' => $user?->id]
            );
        });

        return redirect()->route('employees.index')->with('success','Employee restored.');
    }

    // permanently delete an employee and its user
    public function forceDelete($id)
    {
        $employee = Employee::withTrashed()->where('id', $id)->firstOrFail();
        $user = $employee->user()->withTrashed()->first();

        if ($employee->profile_picture && Storage::disk('public')->exists($employee->profile_picture)) {
            Storage::disk('public')->delete($employee->profile_picture);
        }

        DB::transaction(function () use ($employee, $user) {
            $name = $employee->first_name.' '.$employee->last_name;
            // 🚨 NOTE: Checking if the role is NOT admin before permanent deletion of the user account.
            if ($user && $user->role !== 'admin' && method_exists($user, 'forceDelete')) { 
                $user->forceDelete();
            }
            $employee->forceDelete();

            ActivityLog::record(
                'employee.permanently_deleted',
                null,
                'Employee permanently deleted: '.$name,
                ['employee_id' => $employee->id]
            );
        });

        return redirect()->route('employees.index')->with('success','Employee permanently deleted.');
    }
}