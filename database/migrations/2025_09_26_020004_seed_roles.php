<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    const NEW_ROLES = [
        [
            'name' => 'operator',
            'description' => 'Receives calls and creates orders',
        ],
        [
            'name' => 'manager',
            'description' => 'Views created orders list',
        ],
    ];

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        foreach (self::NEW_ROLES as $role) {
            DB::table('roles')->insert([
                ...$role,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('roles')->whereIn('name', array_column(self::NEW_ROLES, 'name'))->delete();
    }
};
