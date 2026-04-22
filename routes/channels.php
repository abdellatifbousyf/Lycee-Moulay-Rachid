<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Broadcast;
use App\Models\User;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

// ✅ القناة الشخصية للمستخدم (للإشعارات المباشرة)
Broadcast::channel('users.{id}', function (User $user, int $id): bool {
    return $user->id === $id;
});

// ✅ قناة خاصة بالأدمن (للمشرفين فقط)
Broadcast::channel('admin', function (User $user): bool {
    return $user->role?->type === 'admin' || $user->role?->type === 'superadmin';
});

// ✅ قناة خاصة بالأستاذ (لإشعارات الغياب الفورية)
Broadcast::channel('prof.{id_ens}', function (User $user, int $id_ens): bool {
    return $user->id === $id_ens || $user->role?->type === 'admin';
});

// ✅ قناة عامة للإشعارات (لجميع المستخدمين المسجلين)
Broadcast::channel('notifications', function (User $user): bool {
    return true; // أي مستخدم مسجل يقدر يسمع
});

// ✅ قناة خاصة بشعبة معينة (مثلاً: لإشعارات غياب جماعي)
Broadcast::channel('filiere.{id_filiere}', function (User $user, int $id_filiere): bool {
    // التأكد أن المستخدم مرتبط بهاد الشعبة (طالب أو أستاذ)
    return $user->etudiant?->id_filiere === $id_filiere
        || $user->enseignant?->matieres->contains('id_filiere', $id_filiere)
        || $user->role?->type === 'admin';
});
