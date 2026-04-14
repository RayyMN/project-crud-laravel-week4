<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $students = Student::all();
        return view('student.index', ['students' => $students]);  
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('student.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nim' => 'required|unique:students,nim',
            'nama'=> 'required',
            'email' => 'required|email',
            'prodi' => 'required'
        ],[
            'nim.required' => 'NIM wajib diisi.',
            'nim.unique' => 'NIM sudah digunakan.',
            'nama.required' => 'Nama wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'prodi.required' => 'Program studi wajib diisi.'
        ]);


        $student = new Student();
        $student->nim = $validatedData['nim'];
        $student->nama = $validatedData['nama'];
        $student->email = $validatedData['email'];
        $student->prodi = $validatedData['prodi'];

        if($student->save()){
            return redirect('/student')->with([
                'notifikasi' => 'Data siswa berhasil disimpan.',
                'type' => 'success' 
            ]);
        } else {
            return redirect()->back()->with([
                'notifikasi' => 'Data siswa gagal disimpan.',
                'type' => 'error' 
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $student = Student::where(['nim' => $id]);

        if($student->count() < 1){
            return redirect('/student')->with([
                'notifikasi' => 'Data siswa tidak ditemukan.',
                'type' => 'error' 
            ]);
        } 

        return view('student.edit', ['student' => $student->first()]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validatedData = $request->validate([
            'nim' => [
                'required',
                'unique:students,nim,'.$request->old_nim.',nim'
            ],
            'nama'=> 'required',
            'email' => 'required|email',
            'prodi' => 'required'
        ],[
            'nim.required' => 'NIM wajib diisi.',
            'nim.unique' => 'NIM sudah digunakan.',
            'nama.required' => 'Nama wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'prodi.required' => 'Program studi wajib diisi.'
        ]);

        $student = Student::where(['nim' => $id])->first();
        $student->nim = $validatedData['nim'];
        $student->nama = $validatedData['nama'];
        $student->email = $validatedData['email'];
        $student->prodi = $validatedData['prodi'];

        if($student->save()){
            return redirect('/student')->with([
                'notifikasi' => 'Data siswa berhasil diperbarui.',
                'type' => 'success' 
            ]);
        } else {
            return redirect()->back()->with([
                'notifikasi' => 'Data siswa gagal diperbarui.',
                'type' => 'error' 
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $student = Student::where(['nim' => $id]);

        if($student->count() < 1){
            return redirect('/student')->with([
                'notifikasi' => 'Data siswa tidak ditemukan.',
                'type' => 'error' 
            ]);
        }

        if($student->first()->delete()){
            return redirect('/student')->with([
                'notifikasi' => 'Data siswa berhasil dihapus.',
                'type' => 'success'
            ]);
        } else {
            return redirect('/student')->with([
                'notifikasi' => 'Data siswa gagal dihapus.',
                'type' => 'error'
            ]);
        }   
    }
}
