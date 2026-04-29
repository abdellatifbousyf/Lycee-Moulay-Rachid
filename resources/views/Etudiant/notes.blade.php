@extends('layouts.app') {{-- admin.blade.php حسب  كتخدم --}}

@section('content')
<div class="container py-4">
    <h3><i class="fas fa-list me-2"></i>غياباتي</h3>

    @if($absences->isEmpty())
        <div class="alert alert-info">لا توجد غيابات مسجلة ✅</div>
    @else
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>المادة</th>
                    <th>التاريخ</th>
                    <th>الحالة</th>
                </tr>
            </thead>
            <tbody>
                @foreach($absences as $absence)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $absence->seance->matiere->nom_matiere ?? '-' }}</td>
                    <td>{{ $absence->created_at->format('d/m/Y') }}</td>
                    <td>
                        <span class="badge bg-danger">غائب</span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        {{ $absences->links() }}
    @endif
</div>
@endsection
