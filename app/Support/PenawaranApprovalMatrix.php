<?php

namespace App\Support;

use App\Models\Penawaran;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class PenawaranApprovalMatrix
{
    public const APPROVAL_PERMISSION = 'admin.penawaran.approval';
    public const SINGLE_APPROVER_PENILAI_ID = 1;
    public const SINGLE_APPROVER_USER_ID = 1;

    public static function approverCandidates()
    {
        return User::query()
            ->where(function ($query) {
                self::applyApprovalPermissionConstraint($query);
            })
            ->orderBy('name')
            ->get(['id', 'name', 'email']);
    }

    public static function sanitizeApproverIds(array $ids): array
    {
        if (empty($ids)) {
            return [];
        }

        $candidates = self::approverCandidates();

        return $candidates->whereIn('id', $ids)->pluck('id')->all();
    }

    public static function roleApproverIds(int $roleId): array
    {
        return DB::table('role_penawaran_approver')
            ->where('role_id', $roleId)
            ->pluck('user_id')
            ->all();
    }

    public static function syncRoleApprovers(int $roleId, array $userIds): void
    {
        DB::table('role_penawaran_approver')->where('role_id', $roleId)->delete();

        if (empty($userIds)) {
            return;
        }

        $now = now();

        $payload = array_map(fn ($userId) => [
            'role_id' => $roleId,
            'user_id' => $userId,
            'created_at' => $now,
            'updated_at' => $now,
        ], $userIds);

        DB::table('role_penawaran_approver')->insert($payload);
    }

    public static function approverRoleIdsForUser(User $user): Collection
    {
        if (!self::userHasApprovalPermission($user)) {
            return collect();
        }

        return DB::table('role_penawaran_approver')
            ->where('user_id', $user->id)
            ->pluck('role_id');
    }

    public static function resolveOwnerRoleId(User $user): ?int
    {
        return $user->roles()
            ->where('status', true)
            ->orderBy('id')
            ->value('id');
    }

    public static function canViewAllPenawaran(User $user): bool
    {
        return $user->hasRole('admin');
    }

    public static function applyVisibilityScope(Builder $query, User $user): Builder
    {
        if (self::canViewAllPenawaran($user)) {
            return $query;
        }

        $approverRoleIds = self::approverRoleIdsForUser($user);

        return $query->where(function ($builder) use ($user, $approverRoleIds) {
            $builder->where('user_id', $user->id);

            if (self::userHasApprovalPermission($user)) {
                if ($user->id === self::SINGLE_APPROVER_USER_ID) {
                    $builder->orWhere('penanggung_jawab_penanggung_penilai_id', self::SINGLE_APPROVER_PENILAI_ID);
                }

                if ($approverRoleIds->isNotEmpty()) {
                    $builder->orWhere(function ($roleScoped) use ($approverRoleIds, $user) {
                        $roleScoped->whereIn('owner_role_id', $approverRoleIds)
                            ->when($user->id !== self::SINGLE_APPROVER_USER_ID, function ($nonSpecial) {
                                $nonSpecial->where(function ($sub) {
                                    $sub->whereNull('penanggung_jawab_penanggung_penilai_id')
                                        ->orWhere('penanggung_jawab_penanggung_penilai_id', '!=', self::SINGLE_APPROVER_PENILAI_ID);
                                });
                            })
                            ->whereDoesntHave('owner', function ($ownerQuery) {
                                self::applyApprovalPermissionConstraint($ownerQuery);
                            });
                    });
                }

                $builder->orWhere(function ($fallback) use ($user) {
                    $fallback->where(function ($sub) {
                        $sub->whereNull('owner_role_id')
                            ->orWhereNotExists(function ($subquery) {
                                $subquery->select(DB::raw(1))
                                    ->from('role_penawaran_approver as rpa')
                                    ->whereColumn('rpa.role_id', 'penawarans.owner_role_id');
                            });
                    })->whereDoesntHave('owner', function ($ownerQuery) {
                        self::applyApprovalPermissionConstraint($ownerQuery);
                    });

                    if ($user->id !== self::SINGLE_APPROVER_USER_ID) {
                        $fallback->where(function ($nonSpecial) {
                            $nonSpecial->whereNull('penanggung_jawab_penanggung_penilai_id')
                                ->orWhere('penanggung_jawab_penanggung_penilai_id', '!=', self::SINGLE_APPROVER_PENILAI_ID);
                        });
                    }
                });
            }
        });
    }

    public static function userCanApprovePenawaran(User $user, Penawaran $penawaran): bool
    {
        if (self::canViewAllPenawaran($user)) {
            return true;
        }

        if (self::requiresSingleApprover($penawaran->penanggung_jawab_penanggung_penilai_id)) {
            return $user->id === self::SINGLE_APPROVER_USER_ID;
        }

        if ($penawaran->user_id === $user->id && self::userHasApprovalPermission($user)) {
            return true;
        }

        if (!self::userHasApprovalPermission($user)) {
            return false;
        }

        $owner = $penawaran->relationLoaded('owner') ? $penawaran->owner : $penawaran->owner()->first();

        if ($owner && self::userHasApprovalPermission($owner)) {
            return false;
        }

        if (!$penawaran->owner_role_id || !self::roleHasApprovers($penawaran->owner_role_id)) {
            return true;
        }

        return self::approverRoleIdsForUser($user)->contains($penawaran->owner_role_id);
    }

    public static function approverUserIdsForRole(?int $roleId): array
    {
        if (!$roleId) {
            return [];
        }

        return DB::table('role_penawaran_approver')
            ->where('role_id', $roleId)
            ->pluck('user_id')
            ->all();
    }

    public static function roleHasApprovers(?int $roleId): bool
    {
        if (!$roleId) {
            return false;
        }

        return DB::table('role_penawaran_approver')
            ->where('role_id', $roleId)
            ->exists();
    }

    protected static function applyApprovalPermissionConstraint($query): void
    {
        $query->where(function ($permissionQuery) {
            $permissionQuery->whereHas('permissions', function ($permQuery) {
                $permQuery->where('name', self::APPROVAL_PERMISSION);
            })->orWhereHas('roles.permissions', function ($rolePermQuery) {
                $rolePermQuery->where('name', self::APPROVAL_PERMISSION);
            });
        });
    }

    protected static function userHasApprovalPermission(User $user): bool
    {
        return $user->can(self::APPROVAL_PERMISSION);
    }

    protected static function requiresSingleApprover(?int $penanggungPenilaiId): bool
    {
        return $penanggungPenilaiId === self::SINGLE_APPROVER_PENILAI_ID;
    }
}
