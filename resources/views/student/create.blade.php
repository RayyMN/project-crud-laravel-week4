<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrinkto-fit=no">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Students Add | Laravel</title>
    <!-- Bootstrap CSS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
    <div class="container">
        <div class="container-fluid mt-4">
            <div class="card">
                <div class="card-header">
                    Tambah Siswa
                    <a href="/student" type="button" class="btn btn-danger float-right">Kembali</a>
                </div>
                <form action="/student/add" method="POST">
                    @csrf
                    <div class="card-body">
                        @if (session('notifikasi'))
                            <div class="form-group">
                                <div class="alert alert-{{ session('type') }}">
                                    {{ session('notifikasi') }}
                                </div>
                            </div>
                        @endif
                        <div class="form-group">
                            <label for="nama">NIM <b class="textdanger">*</b></label>
                            <input required placeholder="Masukkan NIM" type="text" id="nim" name="nim"
                                class="form-control @error('nim') is-invalid
@enderror" value="{{ old('nim') }}">
                            @error('nim')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="nama">Nama <b class="textdanger">*</b></label>
                            <input required placeholder="Masukkan Nama" type="text" id="nama" name="nama"
                                class="form-control @error('nama') is-invalid
@enderror" value="{{ old('nama') }}">
                            @error('nama')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="nama">E-Mail <b class="textdanger">*</b></label>
                            <input required placeholder="Masukkan E-Mail" type="email" id="email" name="email"
                                class="form-control @error('email') isinvalid @enderror" value="{{ old('email') }}">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="nama">Prodi <b class="textdanger">*</b></label>
                            <select required id="prodi" name="prodi"
                                class="form-control @error('prodi') is-invalid
@enderror" required>
                                <option value="">- Pilih Prodi -</option>
                                <option>Teknik Informatika</option>
                                <option>Teknik Rekayasa Keamanan Siber</option>
                                <option>Teknik Rekayasa Perangkat Lunak</option>
                            </select>
                            @error('prodi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="card-footer">
                        <a href="/student" class="btn btn-danger">Batal</a>
                        <button type="reset" class="btn btn-warning">Reset</button>
                        <button type="submit" class="btn btn-success">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-
    q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-
    UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-
    JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</body>

</html>
