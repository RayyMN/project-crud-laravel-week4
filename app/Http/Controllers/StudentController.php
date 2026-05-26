<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $students = Student::orderBy('nim', 'asc')->get();
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
            'nama' => 'required',
            'email' => 'required|email',
            'prodi' => 'required',
            'foto' => 'required'
        ], [
            'nim.required' => 'NIM wajib diisi.',
            'nim.unique' => 'NIM sudah digunakan.',
            'nama.required' => 'Nama wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'prodi.required' => 'Program studi wajib diisi.',
            'foto.required' => 'Foto wajib diunggah.'
        ]);

        if ($request->hasFile('foto')) {
            $foto = $request->file('foto')->store('foto', 'public');
            $foto = basename($foto);
        } else {
            $foto = null;
        }

        $student = new Student();
        $student->nim = $validatedData['nim'];
        $student->nama = $validatedData['nama'];
        $student->email = $validatedData['email'];
        $student->prodi = $validatedData['prodi'];
        $student->foto = $foto ? 'foto/' . $foto : null;

        if ($student->save()) {
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
    public function show(string $id) {}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $student = Student::where(['nim' => $id]);

        if ($student->count() < 1) {
            return redirect('/student')->with([
                'notifikasi' => 'Data siswa tidak ditemukan.',
                'type' => 'error'
            ]);
        }

        return view('student.edit', ['student' => $student->first()]);
    }

    public function download(string $id)
    {
        $student = Student::where('nim', $id)->firstOrFail();

        // cek apakah foto kosong
        if (empty($student->foto)) {
            return redirect('/student')->with([
                'notifikasi' => 'Foto siswa tidak tersedia.',
                'type' => 'error'
            ]);
        }

        $filePath = public_path('storage/' . $student->foto);

        // cek apakah file ada
        if (!file_exists($filePath)) {
            return redirect('/student')->with([
                'notifikasi' => 'File foto tidak ditemukan.',
                'type' => 'error'
            ]);
        }

        $extension = pathinfo($filePath, PATHINFO_EXTENSION);

        $fileName = 'foto_' . $student->nim . '.' . $extension;

        return response()->download($filePath, $fileName);
    }

    public function preview(string $id)
    {
        $student = Student::where('nim', $id)->firstOrFail();

        // cek foto kosong
        if (empty($student->foto)) {
            return redirect('/student')->with([
                'notifikasi' => 'Foto siswa tidak tersedia.',
                'type' => 'error'
            ]);
        }

        $filePath = public_path('storage/' . $student->foto);

        // cek file ada
        if (!file_exists($filePath)) {
            return redirect('/student')->with([
                'notifikasi' => 'File foto tidak ditemukan.',
                'type' => 'error'
            ]);
        }

        return response()->file($filePath);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $student = Student::where('nim', $id)->firstOrFail();

        $validatedData = $request->validate([
            'nim' => [
                'required',
                'unique:students,nim,' . $request->old_nim . ',nim'
            ],
            'nama' => 'required',
            'email' => 'required|email',
            'prodi' => 'required'
        ], [
            'nim.required' => 'NIM wajib diisi.',
            'nim.unique' => 'NIM sudah digunakan.',
            'nama.required' => 'Nama wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'prodi.required' => 'Program studi wajib diisi.'
        ]);

        // default foto lama
        $foto = $student->foto;

        // jika ganti foto
        if ($request->ganti_foto == 1) {

            $request->validate([
                'foto' => 'required|image|mimes:jpg,jpeg,png|max:2048'
            ], [
                'foto.required' => 'Foto wajib diunggah.',
                'foto.image' => 'File harus berupa gambar.',
                'foto.mimes' => 'Format foto harus JPG, JPEG, atau PNG.',
                'foto.max' => 'Ukuran foto maksimal 2 MB.'
            ]);

            if ($request->hasFile('foto')) {

                // hapus foto lama
                if (!empty($student->foto) && Storage::disk('public')->exists($student->foto)) {
                    Storage::disk('public')->delete($student->foto);
                }

                // upload foto baru
                $foto = $request->file('foto')->store('foto', 'public');
            }
        }

        // update data
        $student->nim = $validatedData['nim'];
        $student->nama = $validatedData['nama'];
        $student->email = $validatedData['email'];
        $student->prodi = $validatedData['prodi'];
        $student->foto = $foto;

        if ($student->save()) {
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
    public function destroy($id)
    {
        $student = Student::where('nim', $id)->firstOrFail();

        // hapus file foto jika ada
        if ($student->foto && Storage::disk('public')->exists($student->foto)) {
            Storage::disk('public')->delete($student->foto);
        }

        // hapus data
        if ($student->delete()) {
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
