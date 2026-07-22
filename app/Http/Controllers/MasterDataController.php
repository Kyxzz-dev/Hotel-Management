<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Position;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MasterDataController extends Controller
{
    private array $allowedPositions = ['HRD', 'Head Department', 'General Manager', 'Staff'];

    public function index()
    {
        $departments = Department::orderBy('name')->get();
        $positions = Position::orderBy('name')->get();

        return view('admin.master-data.index', compact('departments', 'positions'));
    }

    public function storeDepartment(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:departments,name'],
        ]);

        Department::create(['name' => $data['name'], 'is_active' => true]);

        return back()->with('success', 'Departemen baru berhasil ditambahkan.');
    }

    public function updateDepartment(Request $request, Department $department)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('departments', 'name')->ignore($department->id)],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $department->update([
            'name' => $data['name'],
            'is_active' => $request->boolean('is_active'),
        ]);

        return back()->with('success', 'Departemen berhasil diperbarui.');
    }

    public function destroyDepartment(Department $department)
    {
        $department->delete();
        return back()->with('success', 'Departemen berhasil dihapus.');
    }

    public function storePosition(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::in($this->allowedPositions), 'unique:positions,name'],
        ]);

        Position::create(['name' => $data['name'], 'is_active' => true]);

        return back()->with('success', 'Posisi berhasil ditambahkan.');
    }

    public function updatePosition(Request $request, Position $position)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::in($this->allowedPositions), Rule::unique('positions', 'name')->ignore($position->id)],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $position->update([
            'name' => $data['name'],
            'is_active' => $request->boolean('is_active'),
        ]);

        return back()->with('success', 'Posisi berhasil diperbarui.');
    }

    public function destroyPosition(Position $position)
    {
        $position->delete();
        return back()->with('success', 'Posisi berhasil dihapus.');
    }
}
